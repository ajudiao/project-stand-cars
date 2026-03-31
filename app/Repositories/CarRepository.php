<?php

namespace App\Repositories;

use App\Core\Database;
use App\Models\Car;
use PDO;

class CarRepository
{
    private PDO $conn;

    public function __construct()
    {
        // Usa o singleton do Database
        $this->conn = Database::getInstance();
    }

    public function getAllWithImages(): array
    {
        // Pega todos os veículos com LEFT JOIN para imagens
        $sql = "SELECT v.*, vi.url_imagem
            FROM veiculos v
            LEFT JOIN veiculo_imagens vi ON vi.id_veiculo = v.id
            ORDER BY v.id DESC, vi.created_at ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Agrupa as imagens por veículo
        $cars = [];
        foreach ($rows as $row) {
            $id = $row['id'];

            if (!isset($cars[$id])) {
                $cars[$id] = new Car($row);
                $cars[$id]->imagens = [];
            }

            if (!empty($row['url_imagem'])) {
                $cars[$id]->imagens[] = $row['url_imagem'];
            }
        }

        // Define a primeira imagem como foto principal
        foreach ($cars as $car) {
            $car->foto = $car->imagens[0] ?? null;
        }

        return array_values($cars); // resetar chaves numéricas
    }

    public function findById(int $id): ?Car
    {
        $stmt = $this->conn->prepare("SELECT * FROM veiculos WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ? new Car($data) : null;
    }

    public function getByIdWithImages(int $id): ?Car
    {
        $sql = "SELECT 
                v.*, 
                vi.url_imagem,
                c.nome AS categoria_nome,
                m.nome AS marca_nome
            FROM veiculos v
            LEFT JOIN veiculo_imagens vi 
                ON vi.id_veiculo = v.id
            LEFT JOIN categorias c 
                ON c.id = v.id_categoria
            LEFT JOIN marcas m 
                ON m.id = v.id_marca
            WHERE v.id = :id
            ORDER BY vi.created_at ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($rows)) {
            return null;
        }

        // Cria o veículo com a primeira linha
        $car = new Car($rows[0]);

        // adiciona propriedades extras
        $car->categoria_nome = $rows[0]['categoria_nome'] ?? null;
        $car->marca_nome     = $rows[0]['marca_nome'] ?? null;

        $car->imagens = [];

        foreach ($rows as $row) {
            if (!empty($row['url_imagem'])) {
                $car->imagens[] = $row['url_imagem'];
            }
        }

        return $car;
    }

    public function create(Car $car): int
    {
        $sql = "INSERT INTO veiculos (
                id_marca, id_categoria, modelo, ano, cor, 
                preco, quilometragem, combustivel, transmissao, 
                status, descricao, created_at
            ) VALUES (
                :id_marca, :id_categoria, :modelo, :ano, :cor, 
                :preco, :quilometragem, :combustivel, :transmissao, 
                :status, :descricao, :created_at
            )";

        $stmt = $this->conn->prepare($sql);

        $success = $stmt->execute([
            'id_marca'      => $car->id_marca,
            'id_categoria'  => $car->id_categoria,
            'modelo'        => $car->modelo,
            'ano'           => $car->ano,
            'cor'           => $car->cor,
            'preco'         => $car->preco,
            'quilometragem' => $car->quilometragem,
            'combustivel'   => $car->combustivel,
            'transmissao'   => $car->transmissao,
            'status'        => $car->status,
            'descricao'     => $car->descricao,
            // Se o banco não gerar o timestamp sozinho, enviamos agora:
            'created_at'    => $car->created_at ?? date('Y-m-d H:i:s')
        ]);

        if ($success)
            return (int) $this->conn->lastInsertId(); // retorna o id do carro inserido
        return 0;
    }

    public function saveImage($carId, $fileName)
    {
        $sql = "INSERT INTO veiculo_imagens (id_veiculo, url_imagem, created_at)
            VALUES (:id_veiculo, :url_imagem, NOW())";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            'id_veiculo' => $carId,
            'url_imagem' => $fileName
        ]);
    }

    public function getImages(int $carId): array
    {
        $sql = "SELECT url_imagem 
            FROM veiculo_imagens 
            WHERE id_veiculo = :id_veiculo
            ORDER BY created_at ASC"; // mantém a ordem de upload

        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id_veiculo' => $carId]);

        return $stmt->fetchAll(PDO::FETCH_COLUMN); // retorna array simples de nomes de arquivos
    }

    public function getMainImage(int $carId): ?string
    {
        $sql = "SELECT url_imagem 
            FROM veiculo_imagens 
            WHERE id_veiculo = :id_veiculo 
            ORDER BY created_at ASC 
            LIMIT 1"; // pega a primeira imagem cadastrada

        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id_veiculo' => $carId]);

        $image = $stmt->fetchColumn();

        return $image ?: null;
    }

    public function update(Car $car): bool
    {
        $sql = "UPDATE veiculos SET 
                id_marca = :id_marca, 
                id_categoria = :id_categoria, 
                modelo = :modelo, 
                ano = :ano, 
                cor = :cor, 
                preco = :preco, 
                quilometragem = :quilometragem, 
                combustivel = :combustivel, 
                transmissao = :transmissao, 
                status = :status, 
                descricao = :descricao
            WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            'id_marca'      => (int) $car->id_marca,
            'id_categoria'  => (int) $car->id_categoria,
            'modelo'        => $car->modelo,
            'ano'           => (int) $car->ano,
            'cor'           => $car->cor,
            'preco'         => (float) $car->preco,
            'quilometragem' => (int) $car->quilometragem,
            'combustivel'   => $car->combustivel,
            'transmissao'   => $car->transmissao,
            'status'        => $car->status,
            'descricao'     => $car->descricao,
            'id'            => (int) $car->id
        ]);

        return $stmt->rowCount() > 0;
    }

    public function getTheNumberOfVehicles(): int
    {
        $stmt = $this->conn->query("SELECT COUNT(*) FROM veiculos");
        return (int) $stmt->fetchColumn();
    }


    public function delete(int $id): bool
    {
        $stmt = $this->conn->prepare("UPDATE veiculos SET status = 'Indisponível' WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function buscarVeiculos(?string $modelo, ?string $status, ?int $idMarca): array
    {
        $sql = "SELECT 
                v.*, 
                vi.url_imagem,
                c.nome AS categoria_nome,
                m.nome AS marca_nome
            FROM veiculos v
            LEFT JOIN veiculo_imagens vi 
                ON vi.id_veiculo = v.id
            LEFT JOIN categorias c 
                ON c.id = v.id_categoria
            LEFT JOIN marcas m 
                ON m.id = v.id_marca
            WHERE 1=1";

        $params = [];

        // --------------------------
        // FILTRO NOME (modelo)
        // --------------------------
        if (!empty($modelo)) {
            $sql .= " AND v.modelo LIKE :modelo";
            $params[':modelo'] = '%' . $modelo . '%';
        }

        // --------------------------
        // FILTRO STATUS
        // --------------------------
        if (!empty($status)) {
            $sql .= " AND v.status = :status";
            $params[':status'] = $status;
        }

        // --------------------------
        // FILTRO MARCA
        // --------------------------
        if (!empty($idMarca)) {
            $sql .= " AND v.id_marca = :marca";
            $params[':marca'] = $idMarca;
        }

        $sql .= " ORDER BY v.id DESC, vi.created_at ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($rows)) {
            return [];
        }

        // --------------------------
        // AGRUPAR VEÍCULOS
        // --------------------------
        $veiculos = [];

        foreach ($rows as $row) {
            $id = $row['id'];

            // se ainda não existe, cria
            if (!isset($veiculos[$id])) {
                $car = new Car($row);

                $car->categoria_nome = $row['categoria_nome'] ?? null;
                $car->marca_nome     = $row['marca_nome'] ?? null;
                $car->imagens        = [];

                $veiculos[$id] = $car;
            }

            // adiciona imagens
            if (!empty($row['url_imagem'])) {
                $veiculos[$id]->imagens[] = $row['url_imagem'];
            }
        }

        // resetar índices (importante para view)
        return array_values($veiculos);
    }
}
