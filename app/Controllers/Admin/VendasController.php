<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Repositories\VendaRepository;
use App\Models\Venda;
use App\Services\PdfService;

class VendasController extends Controller
{
    private VendaRepository $vendasRep;

    public function __construct()
    {
        $this->vendasRep = new VendaRepository();
    }

    public function index()
    {
        $vendas = $this->vendasRep->findAllWithClient(); // já traz nome do cliente, veículo e vendedor
        $total_concluidas = array_filter($vendas, fn($v) => $v['status'] === 'Concluido');
        $total_pendetes = array_filter($vendas, fn($v) => $v['status'] === 'Pendente');
        $currentMonth = date('Y-m');
        $salesThisMonth = count(array_filter($vendas, fn($v) => strpos($v['data_venda'], $currentMonth) === 0));
        $totalFaturamento = array_reduce($vendas, fn($carry, $v) => $carry + ((float)($v['valor_pago'] ?? 0)), 0.0);
        $clientes = new \App\Repositories\ClienteRepository();
        $veiculos = new \App\Repositories\CarRepository();

        $this->view('dashboard/vendas', [
            'vendas' => $vendas,
            'total_concluidas' => count($total_concluidas),
            'total_pendetes' => count($total_pendetes),
            'sales_this_month' => $salesThisMonth,
            'total_faturamento' => $totalFaturamento,
            'clientes' => $clientes->getAll(),
            'veiculos' => $veiculos->getAllWithImages(),
            'title' => 'Vendas - ' . APP_NAME
        ]);
    }

    public function store()
    {
        $data = $_POST;

        // Validação básica
        if (!isset($data['id_veiculo'])) {
            \App\Helpers\Helpers::setFlash('error', 'Carro não informado.');
            header('Location: /admin/vendas');
            exit;
        }

        $carroId = (int) $data['id_veiculo'];

        // Verifica se o carro já pertence a alguém
        if ($this->vendasRep->carroJaVendido($carroId)) {
            \App\Helpers\Helpers::setFlash('error', 'Este carro já está associado a um cliente.');
            header('Location: /admin/vendas');
            exit;
        }

        // Definir data e hora atual para a venda
        $data['data_venda'] = date('Y-m-d H:i:s');

        $venda = new Venda($data);

        try {
            $vendaId = $this->vendasRep->create($venda);

            if ($vendaId) {
                \App\Helpers\Helpers::setFlash('success', 'Venda registrada com sucesso.');
                header('Location: /admin/vendas');
                exit;
            } else {
                \App\Helpers\Helpers::setFlash('error', 'Erro ao criar venda.');
                header('Location: /admin/vendas');
                exit;
            }
        } catch (\PDOException $e) {
            error_log($e->getMessage());
            \App\Helpers\Helpers::setFlash('error', 'Não foi possível criar a venda. Verifique os dados e tente novamente.');
            header('Location: /admin/vendas');
            exit;
        }
    }

    public function buscar()
    {
        $filtros = [
            'nome'   => $_GET['nome'] ?? '',
            'status' => $_GET['status'] ?? ''
        ];

        try {
            $vendas = $this->vendasRep->search($filtros);
        } catch (\PDOException $e) {
            \App\Helpers\Helpers::setFlash('error', 'Erro ao buscar vendas. Tente novamente.');
            error_log($e->getMessage());
            header('Location: /admin/vendas');
            exit;
        }
        $this->view('dashboard/vendas', [
            'vendas'  => $vendas,
            'filtros' => $filtros,
            'title'   => 'Vendas - ' . APP_NAME
        ]);
    }

    public function show($id)
    {
        if (!is_numeric($id)) {
            \App\Helpers\Helpers::setFlash('error', 'Parâmetro inválido.');
            header('Location: /admin/vendas');
            exit;
        }

        $venda = $this->vendasRep->findVendaById((int)$id);
        if (!$venda) {
            \App\Helpers\Helpers::setFlash('error', 'Venda não encontrada.');
            header('Location: /admin/vendas');
            exit;
        }

        $this->view('dashboard/detalhes-venda', [
            'venda' => $venda,
            'title' => 'Detalhes da Venda - ' . APP_NAME
        ]);
    }

    public function receipt($id)
    {
        if (!is_numeric($id)) {
            \App\Helpers\Helpers::setFlash('error', 'Parâmetro inválido.');
            header('Location: /admin/vendas');
            exit;
        }

        $venda = $this->vendasRep->findVendaById((int)$id);
        if (!$venda) {
            \App\Helpers\Helpers::setFlash('error', 'Venda não encontrada.');
            header('Location: /admin/vendas');
            exit;
        }

        $venda['taxas'] = 500.00;
        $venda['valor_desconto'] = round($venda['valor_veiculo'] * ($venda['desconto'] / 100), 2);
        $venda['valor_total'] = round($venda['valor_veiculo'] - $venda['valor_desconto'] + $venda['taxas'], 2);
        $venda['emitido_em'] = date('d/m/Y H:i');

        $pdfService = new PdfService();
        $logoDataUri = $pdfService->getImageDataUri(PUBLIC_PATH . '/assets/site/images/logo.png');

        $pdfService->renderToPdf('dashboard/recibo-venda', [
            'company' => [
                'name' => APP_NAME,
                'address' => COMPANY_ADDRESS,
                'phone' => COMPANY_PHONE,
                'email' => COMPANY_EMAIL,
                'logo' => $logoDataUri,
            ],
            'seller' => [
                'nome' => $venda['vendedor'],
                'email' => $venda['vendedor_email'] ?? '',
                'telefone' => $venda['vendedor_telefone'] ?? '',
            ],
            'venda' => $venda
        ], sprintf('recibo-venda-%s.pdf', $venda['id']));
    }
}
