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

        if (empty($_FILES['fotos']['name'][0])) {
            \App\Helpers\Helpers::setFlash('error', 'É obrigatório enviar pelo menos uma foto.');
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
        // UPLOAD DAS IMAGENS
        // --------------------------
        $imagesUploaded = $this->uploadImages($_FILES['fotos'], $carId);
        
        // --------------------------
        // Mensagem flash
        // --------------------------
        if ($imagesUploaded > 0) {
            \App\Helpers\Helpers::setFlash('success', "Veículo adicionado com sucesso ({$imagesUploaded} imagem(ns)).");
        } else {
            \App\Helpers\Helpers::setFlash('error', 'Veículo criado, mas nenhuma imagem foi salva. Tente adicionar imagens depois.');
        }

        // --------------------------
        // REDIRECT
        // --------------------------
        header('Location: /admin/automoveis');
        exit;
    }

    private function uploadImages($images, $carId): int
    {
        $uploadDir = __DIR__ . '/../../../public/uploads/cars/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        $maxSize = 2 * 1024 * 1024; // 2MB

        $uploadedCount = 0;
        $existingCount = $this->carRepo->countImages($carId);
        $maxImages = 5;
        $remainingSlots = max(0, $maxImages - $existingCount);

        if ($remainingSlots === 0) {
            error_log("Limite de imagens atingido para o veículo #{$carId}. Nenhuma imagem adicional será salva.");
            return 0;
        }

        $total = count($images['name']);
        $uploadLimit = min($total, $remainingSlots, 5);
        for ($i = 0; $i < $uploadLimit; $i++) {

            if ($images['error'][$i] !== 0) {
                error_log("Erro no upload do arquivo {$images['name'][$i]}: Código de erro {$images['error'][$i]}");
                continue;
            }

            $tmpName = $images['tmp_name'][$i];
            $originalName = $images['name'][$i];
            $size = $images['size'][$i];

            $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

            // validações
            if (!in_array($ext, $allowed)) {
                error_log("Extensão não permitida: $ext para arquivo {$originalName}");
                continue;
            }
            if ($size > $maxSize) {
                error_log("Arquivo muito grande: {$originalName} ({$size} bytes, máximo: {$maxSize})");
                continue;
            }

            // nome único
            $fileName = uniqid('car_') . '.' . $ext;
            $destination = $uploadDir . $fileName;

            if (move_uploaded_file($tmpName, $destination)) {
                // salva no banco
                if ($this->carRepo->saveImage($carId, $fileName)) {
                    $uploadedCount++;
                    error_log("Imagem salva com sucesso: {$fileName} para veículo #{$carId}");
                } else {
                    error_log("Erro ao salvar imagem no BD: {$fileName} para veículo #{$carId}");
                    // Remover arquivo se falhar ao salvar no BD
                    @unlink($destination);
                }
            } else {
                error_log("Erro ao mover arquivo {$originalName} para {$destination}");
            }
        }

        if ($uploadedCount === 0 && count($images['name']) > 0) {
            error_log("Nenhuma imagem foi salva para o veículo #{$carId}");
        }
        
        return $uploadedCount;
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

        $deletedCount = 0;
        if (!empty($_POST['delete_images']) && is_array($_POST['delete_images'])) {
            foreach ($_POST['delete_images'] as $imageName) {
                $imageName = trim($imageName);
                if ($imageName === '') {
                    continue;
                }

                if ($this->carRepo->deleteImageByUrl($id, $imageName)) {
                    $deletedCount++;
                    @unlink(__DIR__ . '/../../../public/uploads/cars/' . $imageName);
                }
            }
        }

        $uploadedCount = 0;
        if (!empty($_FILES['fotos']['name'][0])) {
            $uploadedCount = $this->uploadImages($_FILES['fotos'], $id);
        }

        $message = 'Veículo atualizado com sucesso.';
        if ($deletedCount > 0 || $uploadedCount > 0) {
            $parts = [];
            if ($deletedCount > 0) {
                $parts[] = "{$deletedCount} imagem(ns) removida(s)";
            }
            if ($uploadedCount > 0) {
                $parts[] = "{$uploadedCount} imagem(ns) adicionada(s)";
            }
            $message = 'Veículo atualizado com sucesso. ' . implode(' e ', $parts) . '.';
        }

        \App\Helpers\Helpers::setFlash('success', $message);
        header('Location: /admin/automoveis');
        exit;
    }
}