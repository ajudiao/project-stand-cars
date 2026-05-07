<?php

define('BASE_PATH', dirname(__FILE__));

require_once BASE_PATH . '/vendor/autoload.php';
require_once BASE_PATH . '/config/constants.php';
require_once BASE_PATH . '/app/Helpers/Helpers.php';

use App\Core\Database;

// Verificar conexão com BD
try {
    $pdo = Database::getInstance();
    $stmt = $pdo->query('SELECT COUNT(*) FROM veiculo_imagens');
    $totalImages = $stmt->fetchColumn();
    echo "✓ Conexão com BD OK\n";
    echo "  Total de imagens no BD: $totalImages\n\n";
} catch (Exception $e) {
    echo "✗ Erro na conexão com BD: " . $e->getMessage() . "\n\n";
}

// Verificar permissões de pasta
$uploadDir = BASE_PATH . '/public/uploads/cars/';
echo "Verificando pasta de uploads:\n";
echo "  Caminho: $uploadDir\n";
echo "  Existe: " . (is_dir($uploadDir) ? "Sim ✓" : "Não ✗") . "\n";
if (is_dir($uploadDir)) {
    echo "  Permissões: " . substr(sprintf('%o', fileperms($uploadDir)), -4) . "\n";
    echo "  Escrever: " . (is_writable($uploadDir) ? "Sim ✓" : "Não ✗") . "\n";
    $files = glob($uploadDir . '*.{jpg,jpeg,png,webp}', GLOB_BRACE);
    echo "  Arquivos: " . count($files) . "\n";
}

// Verificar ini settings
echo "\nConfiguração do PHP:\n";
echo "  post_max_size: " . ini_get('post_max_size') . "\n";
echo "  upload_max_filesize: " . ini_get('upload_max_filesize') . "\n";
echo "  file_uploads: " . (ini_get('file_uploads') ? "On ✓" : "Off ✗") . "\n";

// Verificar últimas imagens adicionadas
echo "\nÚltimas 5 imagens no BD:\n";
$stmt = $pdo->query('SELECT v.id, v.modelo, COUNT(vi.id) as num_imagens FROM veiculos v LEFT JOIN veiculo_imagens vi ON vi.id_veiculo = v.id GROUP BY v.id ORDER BY v.id DESC LIMIT 5');
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "  Veículo #{$row['id']}: {$row['modelo']} ({$row['num_imagens']} imagens)\n";
}
