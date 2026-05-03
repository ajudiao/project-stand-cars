<?php

namespace App\Repositories;

use App\Core\Database;
use DateTime;
use PDO;

class NotificationRepository
{
    private PDO $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance();
    }

    /**
     * Retorna notificações mescladas: vendas, clientes e veículos recentes.
     * Usa UMA query por tipo com LIMIT para evitar consumo excessivo de memória.
     */
    public function getNotifications(int $limit = 5): array
    {
        try {
            $notifications = array_merge(
                $this->getRecentSales($limit),
                $this->getRecentClients($limit),
                $this->getRecentVehicles($limit)
            );

            usort($notifications, fn($a, $b) =>
                strtotime($b['created_at']) <=> strtotime($a['created_at'])
            );

            return array_slice($notifications, 0, $limit);
        } catch (\Throwable $e) {
            error_log('[stand-cars] NotificationRepository::getNotifications: ' . $e->getMessage());
            return [];
        }
    }

    public function getWeeklySummary(): array
    {
        try {
            $salesCount = $this->conn->query(
                "SELECT COUNT(*) FROM vendas WHERE status = 'Concluido' AND data_venda >= DATE_SUB(NOW(), INTERVAL 7 DAY)"
            )->fetchColumn();

            $clientsCount = $this->conn->query(
                "SELECT COUNT(*) FROM clientes WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)"
            )->fetchColumn();

            $vehiclesCount = $this->conn->query(
                "SELECT COUNT(*) FROM veiculos WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)"
            )->fetchColumn();

            return [
                'sales'    => (int) $salesCount,
                'clients'  => (int) $clientsCount,
                'vehicles' => (int) $vehiclesCount,
            ];
        } catch (\Throwable $e) {
            return ['sales' => 0, 'clients' => 0, 'vehicles' => 0];
        }
    }

    private function getRecentSales(int $limit): array
    {
        $stmt = $this->conn->prepare(
            "SELECT ven.id, ven.data_venda, ven.valor_pago, ve.modelo, cl.nome_completo AS cliente
             FROM vendas ven
             LEFT JOIN clientes cl ON ven.id_cliente = cl.id
             LEFT JOIN veiculos ve ON ven.id_veiculo = ve.id
             WHERE ven.status = 'Concluido'
             ORDER BY ven.data_venda DESC
             LIMIT :limit"
        );
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        $out = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $out[] = [
                'type'       => 'sale',
                'title'      => 'Venda concluída',
                'message'    => sprintf('%s comprou %s por Kz %s',
                    $row['cliente'] ?? 'Cliente',
                    $row['modelo']  ?? 'Veículo',
                    number_format((float)$row['valor_pago'], 2, ',', '.')
                ),
                'created_at' => $row['data_venda'],
                'timeAgo'    => $this->formatTimeAgo($row['data_venda']),
                'url'        => '/admin/vendas/show/' . $row['id'],
                'icon'       => 'bi bi-check-circle',
                'badge'      => 'success',
            ];
        }
        return $out;
    }

    private function getRecentClients(int $limit): array
    {
        $stmt = $this->conn->prepare(
            "SELECT id, nome_completo, created_at FROM clientes ORDER BY created_at DESC LIMIT :limit"
        );
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        $out = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $out[] = [
                'type'       => 'client',
                'title'      => 'Novo cliente cadastrado',
                'message'    => $row['nome_completo'],
                'created_at' => $row['created_at'],
                'timeAgo'    => $this->formatTimeAgo($row['created_at']),
                'url'        => '/admin/clientes/show/' . $row['id'],
                'icon'       => 'bi bi-people-fill',
                'badge'      => 'info',
            ];
        }
        return $out;
    }

    private function getRecentVehicles(int $limit): array
    {
        $stmt = $this->conn->prepare(
            "SELECT ve.id, ve.modelo, ve.ano, m.nome AS marca, ve.created_at
             FROM veiculos ve
             LEFT JOIN marcas m ON ve.id_marca = m.id
             ORDER BY ve.created_at DESC
             LIMIT :limit"
        );
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        $out = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $out[] = [
                'type'       => 'vehicle',
                'title'      => 'Novo automóvel adicionado',
                'message'    => sprintf('%s %s (%s)',
                    $row['marca']  ?? 'Marca',
                    $row['modelo'] ?? 'Modelo',
                    $row['ano']    ?? ''
                ),
                'created_at' => $row['created_at'],
                'timeAgo'    => $this->formatTimeAgo($row['created_at']),
                'url'        => '/admin/automoveis/show/' . $row['id'],
                'icon'       => 'bi bi-car-front',
                'badge'      => 'primary',
            ];
        }
        return $out;
    }

    private function formatTimeAgo(string $datetime): string
    {
        try {
            $diff = (new DateTime($datetime))->diff(new DateTime());
            if ($diff->d > 0) return 'há ' . $diff->d . ' dia'  . ($diff->d > 1 ? 's' : '');
            if ($diff->h > 0) return 'há ' . $diff->h . ' hora' . ($diff->h > 1 ? 's' : '');
            if ($diff->i > 0) return 'há ' . $diff->i . ' minuto' . ($diff->i > 1 ? 's' : '');
            return 'há poucos segundos';
        } catch (\Exception $e) {
            return 'agora mesmo';
        }
    }
}
