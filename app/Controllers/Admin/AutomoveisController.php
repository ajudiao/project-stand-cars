<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Repositories\CarRepository;
use App\Models\Car;

class AutomoveisController extends Controller
{
    private CarRepository $carRepo;

    public function __construct()
    {
        $this->carRepo = new CarRepository();
    }

    // Listar veículos com todas as imagens
    public function index()
    {
        ini_set('memory_limit', '4096M');

        $veiculos = $this->carRepo->getAllWithImages(); // já traz imagens e foto principal
        $marcas = (new \App\Repositories\MarcaRepository())->getAll();
        $categorias = (new \App\Repositories\CategoriaRepository())->getAll();
        
        $this->view('dashboard/automoveis', [
            'veiculos'   => $veiculos,   // Veículos com todas as imagens
            'marcas'     => $marcas,
            'categorias' => $categorias,
            'title'      => 'Automóveis - ' . APP_NAME
        ]);
    }

    public function store()
    {
        $data = $_POST;

        // --------------------------
        // VALIDAÇÃO
        // --------------------------
        if (empty($data['modelo']) || empty($data['preco'])) {
            \App\Helpers\Helpers::setFlash('error', 'Modelo e preço são obrigatórios.');
            header('Location: /admin/automoveis');
            exit;
        }

        if (!is_numeric($data['ano']) || $data['ano'] < 1900 || $data['ano'] > date('Y') + 1) {
            \App\Helpers\Helpers::setFlash('error', 'Ano inválido.');
            header('Location: /admin/automoveis');
            exit;
        }

        // --------------------------
        // CRIAR VEÍCULO
        // --------------------------
        $data['destaque'] = isset($data['destaque']) ? $data['destaque'] : 0;
        $car = new Car($data);
        $carId = $this->carRepo->create($car);

        if (!$carId) {
            \App\Helpers\Helpers::setFlash('error', 'Erro ao criar veículo.');
            header('Location: /admin/automoveis');
            exit;
        }

        // --------------------------
        // Mensagem flash
        // --------------------------
        \App\Helpers\Helpers::setFlash('success', 'Veículo adicionado com sucesso.');

        // --------------------------
        // UPLOAD DAS IMAGENS
        // --------------------------
        if (!empty($_FILES['fotos']['name'][0])) {
            $this->uploadImages($_FILES['fotos'], $carId);
        }

        // --------------------------
        // REDIRECT
        // --------------------------
        header('Location: /admin/automoveis');
        exit;
    }

    private function uploadImages($images, $carId)
    {
        $uploadDir = __DIR__ . '/../../../public/uploads/cars/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        $maxSize = 2 * 1024 * 1024; // 2MB

        $total = count($images['name']);
        for ($i = 0; $i < min($total, 5); $i++) {

            if ($images['error'][$i] !== 0) continue;

            $tmpName = $images['tmp_name'][$i];
            $originalName = $images['name'][$i];
            $size = $images['size'][$i];

            $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

            // validações
            if (!in_array($ext, $allowed)) continue;
            if ($size > $maxSize) continue;

            // nome único
            $fileName = uniqid('car_') . '.' . $ext;
            $destination = $uploadDir . $fileName;

            if (move_uploaded_file($tmpName, $destination)) {

                // salva no banco
                $this->carRepo->saveImage($carId, $fileName);
            }
        }
    }

    public function show($id)
    {
        if (!is_numeric($id)) {
            \App\Helpers\Helpers::setFlash('error', 'Parâmetro inválido.');
            header('Location: /admin/automoveis');
            exit;
        }
        $veiculo = $this->carRepo->getByIdWithImages($id);
        if (!$veiculo) {
            \App\Helpers\Helpers::setFlash('error', 'Veículo não encontrado.');
            header('Location: /admin/automoveis');
            exit;
        }
        $marcas = (new \App\Repositories\MarcaRepository())->getAll();
        $categorias = (new \App\Repositories\CategoriaRepository())->getAll();

        $this->view('dashboard/detalhes-veiculo', [
            'veiculo' => $veiculo,
            'marcas' => $marcas,
            'categorias' => $categorias,
            'title' => 'Detalhes do Veículo - ' . APP_NAME
        ]);
    }

    public function delete($id)
    {
        if (!is_numeric($id)) {
            \App\Helpers\Helpers::setFlash('error', 'Parâmetro inválido.');
            header('Location: /admin/automoveis');
            exit;
        }
        if ($this->carRepo->delete($id)) {
            \App\Helpers\Helpers::setFlash('success', 'Veículo excluído com sucesso.');
            header('Location: /admin/automoveis');
            exit;
        } else {
            \App\Helpers\Helpers::setFlash('error', 'Veículo não encontrado ou erro ao excluir.');
            header('Location: /admin/automoveis');
            exit;
        }
    }

    public function buscar()
    {
        // --------------------------
        // CAPTURAR FILTROS (GET)
        // --------------------------
        $nome     = $_GET['nome'] ?? null;
        $status   = $_GET['status'] ?? null;
        $idMarca  = $_GET['id_marca'] ?? null;

        // limpar valores vazios
        $nome    = !empty($nome) ? trim($nome) : null;
        $status  = !empty($status) ? $status : null;
        $idMarca = !empty($idMarca) ? (int)$idMarca : null;

        $veiculos = $this->carRepo->buscarVeiculos($nome, $status, $idMarca);
        $marcas = (new \App\Repositories\MarcaRepository())->getAll();
        $categorias = (new \App\Repositories\CategoriaRepository())->getAll();

        // --------------------------
        // RETORNAR VIEW
        // --------------------------
        $this->view('dashboard/automoveis', [
            'veiculos'   => $veiculos,
            'marcas'     => $marcas,
            'categorias' => $categorias,
            'filtros'    => [
                'nome'     => $nome,
                'status'   => $status,
                'id_marca' => $idMarca
            ],
            'title'      => 'Veículos - ' . APP_NAME
        ]);
    }

    public function update($id)
    {
        $data = $_POST;
        // --------------------------
        // VALIDAÇÃO
        // --------------------------
        if (empty($data['modelo']) || empty($data['preco'])) {
            \App\Helpers\Helpers::setFlash('error', 'Modelo e preço são obrigatórios.');
            header('Location: /admin/automoveis');
            exit;
        }
        if (!is_numeric($data['ano']) || $data['ano'] < 1900 || $data['ano'] > date('Y') + 1) {
            \App\Helpers\Helpers::setFlash('error', 'Ano inválido.');
            header('Location: /admin/automoveis');
            exit;
        }
        $data['destaque'] = isset($data['destaque']) && $data['destaque'] == '1' ? 1 : 0;

        // --------------------------
        // ATUALIZAR VEÍCULO
        // --------------------------
        $car = new Car($data);
        $car->id = $id;

        if (!$this->carRepo->update($car)) {
            \App\Helpers\Helpers::setFlash('error', 'Erro ao atualizar veículo.');
            header('Location: /admin/automoveis');
            exit;
        }

        // --------------------------
        // REDIRECT
        // --------------------------
        \App\Helpers\Helpers::setFlash('success', 'Veículo atualizado com sucesso.');
        header('Location: /admin/automoveis');
        exit;
    }
}