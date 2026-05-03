<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Repositories\CarRepository;
use App\Repositories\ClienteRepository;
use App\Repositories\VendaRepository;
use App\Services\PdfService;
use DateTime;

class RelatoriosController extends Controller
{
    public function index()
    {
        $this->view('dashboard/relatorios', [
            'title' => 'Relatórios - ' . APP_NAME
        ]);
    }

    public function generate()
    {
        ini_set('memory_limit', '4096M');

        $type = $_GET['type'] ?? 'sales';
        $startDate = $_GET['start_date'] ?? (new DateTime('first day of this month'))->format('Y-m-d');
        $endDate = $_GET['end_date'] ?? (new DateTime('last day of this month'))->format('Y-m-d');

        try {
            $start = new DateTime($startDate);
            $end = new DateTime($endDate);
        } catch (\Exception $exception) {
            echo 'Período inválido';
            return;
        }

        $pdfService = new PdfService();
        $logoDataUri = $pdfService->getImageDataUri(PUBLIC_PATH . '/assets/site/images/logo.png')
            ?: 'https://thumbs.dreamstime.com/z/o-projeto-do-logotipo-do-carro-da-auto-loja-com-conceito-ostenta-silhueta-do-ve%C3%ADculo-86246431.jpg?ct=jpeg';

        $reportData = [
            'company' => [
                'name' => APP_NAME,
                'address' => defined('COMPANY_ADDRESS') ? COMPANY_ADDRESS : '',
                'phone' => defined('COMPANY_PHONE') ? COMPANY_PHONE : '',
                'email' => defined('COMPANY_EMAIL') ? COMPANY_EMAIL : '',
                'logo' => $logoDataUri,
            ],
            'period' => $start->format('d/m/Y') . ' - ' . $end->format('d/m/Y'),
            'currency' => 'AOA',
        ];

        $fileName = 'relatorio-' . $type . '-' . $start->format('Ymd') . '-' . $end->format('Ymd') . '.pdf';

        switch ($type) {
            case 'sales':
                $salesRepo = new VendaRepository();
                $reportData['title'] = 'Relatório de Vendas';
                $reportData['summary'] = [
                    'Total de vendas' => $salesRepo->getSalesCount($start->format('Y-m-d'), $end->format('Y-m-d')),
                    'Receita total' => number_format($salesRepo->getRevenue($start->format('Y-m-d'), $end->format('Y-m-d')), 2, ',', '.'),
                    'Ticket médio' => number_format($salesRepo->getAverageSale($start->format('Y-m-d'), $end->format('Y-m-d')), 2, ',', '.'),
                ];
                $reportData['columns'] = [
                    ['label' => 'ID', 'key' => 'id'],
                    ['label' => 'Cliente', 'key' => 'cliente'],
                    ['label' => 'Automóvel', 'key' => 'produto'],
                    ['label' => 'Valor', 'key' => 'valor_pago'],
                    ['label' => 'Data', 'key' => 'data_venda'],
                    ['label' => 'Status', 'key' => 'status'],
                ];
                $reportData['rows'] = $salesRepo->getSalesForReport($start->format('Y-m-d'), $end->format('Y-m-d'));
                break;

            case 'inventory':
                $carRepo = new CarRepository();
                $reportData['title'] = 'Relatório de Inventário';
                $reportData['summary'] = [
                    'Total de veículos' => count($carRepo->getInventoryReport()),
                ];
                $reportData['columns'] = [
                    ['label' => 'ID', 'key' => 'id'],
                    ['label' => 'Marca', 'key' => 'marca'],
                    ['label' => 'Modelo', 'key' => 'modelo'],
                    ['label' => 'Ano', 'key' => 'ano'],
                    ['label' => 'Preço', 'key' => 'preco'],
                    ['label' => 'Status', 'key' => 'status'],
                ];
                $reportData['rows'] = $carRepo->getInventoryReport();
                break;

            case 'financial':
                $salesRepo = new VendaRepository();
                $reportData['title'] = 'Relatório Financeiro';
                $financial = $salesRepo->getFinancialReport($start->format('Y-m-d'), $end->format('Y-m-d'));
                $reportData['summary'] = [
                    'Total de vendas' => $financial['total_vendas'],
                    'Receita líquida' => number_format($financial['receita'], 2, ',', '.'),
                    'Ticket médio' => number_format($financial['media'], 2, ',', '.'),
                ];
                $reportData['columns'] = [
                    ['label' => 'ID', 'key' => 'id'],
                    ['label' => 'Cliente', 'key' => 'cliente'],
                    ['label' => 'Valor', 'key' => 'valor_pago'],
                    ['label' => 'Data', 'key' => 'data_venda'],
                    ['label' => 'Status', 'key' => 'status'],
                ];
                $reportData['rows'] = $financial['rows'];
                break;

            case 'clients':
                $clientRepo = new ClienteRepository();
                $reportData['title'] = 'Relatório de Clientes';
                $reportData['summary'] = [
                    'Total de clientes' => count($clientRepo->getAllForReport()),
                ];
                $reportData['columns'] = [
                    ['label' => 'ID', 'key' => 'id'],
                    ['label' => 'Nome', 'key' => 'nome_completo'],
                    ['label' => 'Email', 'key' => 'email'],
                    ['label' => 'Telefone', 'key' => 'telefone'],
                    ['label' => 'Cidade', 'key' => 'cidade'],
                    ['label' => 'Registrado em', 'key' => 'created_at'],
                ];
                $reportData['rows'] = $clientRepo->getAllForReport();
                break;

            case 'performance':
                $salesRepo = new VendaRepository();
                $reportData['title'] = 'Relatório de Performance';
                $summary = $salesRepo->getPerformanceSummary($start->format('Y-m-d'), $end->format('Y-m-d'));
                $reportData['summary'] = [
                    'Total de vendas' => $summary['total_vendas'],
                    'Receita total' => number_format($summary['receita_total'], 2, ',', '.'),
                    'Ticket médio' => number_format($summary['ticket_medio'], 2, ',', '.'),
                ];
                $reportData['columns'] = [
                    ['label' => 'Marca', 'key' => 'marca'],
                    ['label' => 'Vendas', 'key' => 'quantidade'],
                    ['label' => 'Receita', 'key' => 'total'],
                ];
                $reportData['rows'] = array_map(function ($brand) {
                    return [
                        'marca' => $brand['marca'],
                        'quantidade' => $brand['quantidade'],
                        'total' => number_format($brand['total'], 2, ',', '.'),
                    ];
                }, $salesRepo->getTopBrands(5));
                break;

            default:
                echo 'Tipo de relatório inválido';
                return;
        }

        if (isset($reportData['rows']) && empty($reportData['rows'])) {
            echo '<!DOCTYPE html><html><head><title>Sem Dados</title><style>body{font-family:Arial,Helvetica,sans-serif;background:#f7f7f7;color:#333;text-align:center;padding:40px;} .card{background:#fff;border-radius:12px;box-shadow:0 8px 24px rgba(0,0,0,.08);display:inline-block;padding:30px;max-width:540px;margin:auto;} h1{margin-bottom:16px;} p{line-height:1.6;margin-bottom:24px;} button{background:#1d4ed8;color:#fff;border:none;padding:12px 24px;border-radius:8px;cursor:pointer;}</style></head><body><div class="card"><h1>Sem dados para este relatório</h1><p>Não existem registros para o período escolhido (' . $start->format('d/m/Y') . ' - ' . $end->format('d/m/Y') . ').</p><p>Verifique se há vendas ou clientes cadastrados e tente novamente.</p><button onclick="window.history.back()">Voltar</button></div></body></html>';
            return;
        }

        $pdfService->renderToPdf('dashboard/pdf/report', $reportData, $fileName);
    }

    public function customReport()
    {
        $name = trim($_POST['report_name'] ?? 'Relatório Personalizado');
        $startDate = $_POST['start_date'] ?? (new DateTime('first day of this month'))->format('Y-m-d');
        $endDate = $_POST['end_date'] ?? (new DateTime('last day of this month'))->format('Y-m-d');
        $includeSales = isset($_POST['include_sales']);
        $includeClients = isset($_POST['include_clients']);
        $includeInventory = isset($_POST['include_inventory']);
        $includeFinancial = isset($_POST['include_financial']);

        try {
            $start = new DateTime($startDate);
            $end = new DateTime($endDate);
        } catch (\Exception $exception) {
            echo 'Período inválido';
            return;
        }

        if (!($includeSales || $includeClients || $includeInventory || $includeFinancial)) {
            echo 'Selecione ao menos um tipo de dado para o relatório.';
            return;
        }

        $clientRepo = new ClienteRepository();
        $carRepo = new CarRepository();
        $salesRepo = new VendaRepository();
        $pdfService = new PdfService();
        $logoDataUri = $pdfService->getImageDataUri(PUBLIC_PATH . '/assets/site/images/logo.png')
            ?: 'https://thumbs.dreamstime.com/z/o-projeto-do-logotipo-do-carro-da-auto-loja-com-conceito-ostenta-silhueta-do-ve%C3%ADculo-86246431.jpg?ct=jpeg';

        $sections = [];

        if ($includeSales) {
            $sections[] = [
                'title' => 'Vendas',
                'columns' => [
                    ['label' => 'ID', 'key' => 'id'],
                    ['label' => 'Cliente', 'key' => 'cliente'],
                    ['label' => 'Produto', 'key' => 'produto'],
                    ['label' => 'Valor', 'key' => 'valor_pago'],
                    ['label' => 'Data', 'key' => 'data_venda'],
                ],
                'rows' => $salesRepo->getSalesForReport($start->format('Y-m-d'), $end->format('Y-m-d')),
            ];
        }

        if ($includeClients) {
            $sections[] = [
                'title' => 'Clientes',
                'columns' => [
                    ['label' => 'ID', 'key' => 'id'],
                    ['label' => 'Nome', 'key' => 'nome_completo'],
                    ['label' => 'Email', 'key' => 'email'],
                    ['label' => 'Telefone', 'key' => 'telefone'],
                ],
                'rows' => $clientRepo->getAllForReport(),
            ];
        }

        if ($includeInventory) {
            $sections[] = [
                'title' => 'Inventário',
                'columns' => [
                    ['label' => 'ID', 'key' => 'id'],
                    ['label' => 'Marca', 'key' => 'marca'],
                    ['label' => 'Modelo', 'key' => 'modelo'],
                    ['label' => 'Ano', 'key' => 'ano'],
                    ['label' => 'Preço', 'key' => 'preco'],
                ],
                'rows' => $carRepo->getInventoryReport(),
            ];
        }

        if ($includeFinancial) {
            $financial = $salesRepo->getFinancialReport($start->format('Y-m-d'), $end->format('Y-m-d'));
            $sections[] = [
                'title' => 'Financeiro',
                'summary' => [
                    'Total de vendas' => $financial['total_vendas'],
                    'Receita' => number_format($financial['receita'], 2, ',', '.'),
                    'Ticket médio' => number_format($financial['media'], 2, ',', '.'),
                ],
                'columns' => [],
                'rows' => [],
            ];
        }

        // Verificar se há dados em pelo menos uma seção
        $hasData = false;
        foreach ($sections as $section) {
            if (!empty($section['rows'])) {
                $hasData = true;
                break;
            }
        }

        if (!$hasData) {
            echo '<!DOCTYPE html><html><head><title>Sem Dados</title><style>body{font-family:Arial;text-align:center;margin:50px;}</style></head><body><h1>Nenhum dado encontrado</h1><p>Não foram encontrados dados para o período selecionado (' . $start->format('d/m/Y') . ' - ' . $end->format('d/m/Y') . '). Tente ajustar o período ou verifique se há registros no sistema.</p><p><button onclick="window.close()">Fechar</button></p></body></html>';
            return;
        }

        $pdfService->renderToPdf('dashboard/pdf/custom-report', [
            'company' => [
                'name' => APP_NAME,
                'address' => defined('COMPANY_ADDRESS') ? COMPANY_ADDRESS : '',
                'phone' => defined('COMPANY_PHONE') ? COMPANY_PHONE : '',
                'email' => defined('COMPANY_EMAIL') ? COMPANY_EMAIL : '',
                'logo' => $logoDataUri,
            ],
            'title' => $name,
            'period' => $start->format('d/m/Y') . ' - ' . $end->format('d/m/Y'),
            'sections' => $sections,
            'currency' => 'AOA'
        ], 'relatorio-personalizado-' . date('YmdHis') . '.pdf');
    }
}
