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

    // Listar veículos com todas as imagens
    public function index()
    {
        $veiculos = $this->carRepo->getAllWithImages(); // já traz imagens e foto principal
        $marcas = (new \App\Repositories\MarcaRepository())->getAll();
        $categorias = (new \App\Repositories\CategoriaRepository())->getAll();

        $this->view('dashboard/veiculos', [
            'veiculos'   => $veiculos,   // Veículos com todas as imagens
            'marcas'     => $marcas,
            'categorias' => $categorias
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
        $veiculo = $this->carRepo->getByIdWithImages($id);
        if (!$veiculo) {
            echo "Veículo não encontrado.";
            return;
        }
        
        $this->view('dashboard/detalhes-veiculo', [
            'veiculo' => $veiculo
        ]);
    }
}
