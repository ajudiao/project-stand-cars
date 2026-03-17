<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Repositories\CarRepository;
use App\Models\Car;

class VeiculosController extends Controller
{
    private CarRepository $carRepo;

    public function __construct()
    {
        $this->carRepo = new CarRepository();
    }

    // Listar veículos
    public function index()
    {
        $veiculos = $this->carRepo->getAll();
        $marcas = (new \App\Repositories\MarcaRepository())->getAll(); // Para exibir as marcas na view
        $categorias = (new \App\Repositories\CategoriaRepository())->getAll(); // Para exibir as categorias na view
        $this->view('deashboad/veiculos', [
            'veiculos' => $veiculos, // Aqui você pode passar os dados dos veículos para a view
            'marcas' => $marcas, // Passa as marcas para a view
            'categorias' => $categorias // Passa as categorias para a view
        ]);
    }

    public function store()
    {
        $data = $_POST;

        // --------------------------
        // VALIDAÇÃO
        // --------------------------
        if (empty($data['modelo']) || empty($data['preco'])) {
            echo "Modelo e preço são obrigatórios.";
            return;
        }

        if (!is_numeric($data['ano']) || $data['ano'] < 1900 || $data['ano'] > date('Y') + 1) {
            echo "Ano inválido.";
            return;
        }

        // --------------------------
        // CRIAR VEÍCULO
        // --------------------------
        $car = new Car($data);
        $carId = $this->carRepo->create($car);

        echo "Carro criado com ID: $carId" . $carId; // Debug: exibe o ID do carro criado
        exit; // Interrompe a execução para verificar o ID retornado

        if (!$carId) {
            echo "Erro ao criar veículo.";
            return;
        }

        // --------------------------
        // UPLOAD DAS IMAGENS
        // --------------------------
        if (!empty($_FILES['fotos']['name'][0])) {
            $this->uploadImages($_FILES['fotos'], $carId);
        }

        // --------------------------
        // REDIRECT
        // --------------------------
        header('Location: /admin/veiculos');
        exit;
    }

    private function uploadImages($images, $carId)
    {
        $uploadDir = __DIR__ . '/../../../../storage/uploads/cars/';

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
        $veiculo = $this->carRepo->findById($id);
        if (!$veiculo) {
            echo "Veículo não encontrado.";
            return;
        }
        $this->view('deashboad/detalhes-veiculo', [
            'veiculo' => $veiculo
        ]);
    }
}
