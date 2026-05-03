<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;

class NewsRepository
{
    private PDO $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance();
    }

    public function getAll(): array
    {
        $stmt = $this->conn->prepare('SELECT n.*, u.nome AS autor_nome FROM noticias n LEFT JOIN usuarios u ON n.id_autor = u.id ORDER BY n.data_publicacao DESC');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->conn->prepare('SELECT n.*, u.nome AS autor_nome FROM noticias n LEFT JOIN usuarios u ON n.id_autor = u.id WHERE n.id = ?');
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function getAllPublished(): array
    {
        $stmt = $this->conn->prepare('SELECT * FROM noticias WHERE status = ? ORDER BY data_publicacao DESC');
        $stmt->execute(['publicado']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getBySlug(string $slug): ?array
    {
        $stmt = $this->conn->prepare('SELECT * FROM noticias WHERE slug = ? AND status = ?');
        $stmt->execute([$slug, 'publicado']);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function getRecent(int $limit = 5): array
    {
        $stmt = $this->conn->prepare('SELECT * FROM noticias WHERE status = ? ORDER BY data_publicacao DESC LIMIT ?');
        $stmt->execute(['publicado', $limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(array $data): int|false
    {
        // Gerar slug a partir do título
        $slug = $this->generateSlug($data['titulo']);
        
        $stmt = $this->conn->prepare('
            INSERT INTO noticias (titulo, slug, conteudo, resumo, imagem_capa, id_autor, status, data_publicacao, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
        ');

        $success = $stmt->execute([
            $data['titulo'],
            $slug,
            $data['conteudo'],
            $data['resumo'] ?? substr(strip_tags($data['conteudo']), 0, 200),
            $data['imagem_capa'] ?? null,
            $data['id_autor'] ?? $_SESSION['user_id'] ?? null,
            $data['status'] ?? 'rascunho',
            $data['data_publicacao'] ?? date('Y-m-d H:i:s')
        ]);

        if ($success) {
            return (int) $this->conn->lastInsertId();
        }

        return false;
    }

    public function update(int $id, array $data): bool
    {
        $slug = $this->generateSlug($data['titulo'] ?? '');
        
        $stmt = $this->conn->prepare('
            UPDATE noticias SET 
                titulo = ?,
                slug = ?,
                conteudo = ?,
                resumo = ?,
                imagem_capa = ?,
                status = ?,
                data_publicacao = ?,
                updated_at = NOW()
            WHERE id = ?
        ');

        return $stmt->execute([
            $data['titulo'],
            $slug,
            $data['conteudo'],
            $data['resumo'] ?? substr(strip_tags($data['conteudo']), 0, 200),
            $data['imagem_capa'] ?? null,
            $data['status'] ?? 'rascunho',
            $data['data_publicacao'] ?? date('Y-m-d H:i:s'),
            $id
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->conn->prepare('DELETE FROM noticias WHERE id = ?');
        return $stmt->execute([$id]);
    }

    private function generateSlug(string $text): string
    {
        // Converter para minúsculas e remover acentos
        $text = strtolower($text);
        $text = preg_replace('/[áàâã]/', 'a', $text);
        $text = preg_replace('/[éè]/', 'e', $text);
        $text = preg_replace('/[í]/', 'i', $text);
        $text = preg_replace('/[óôõ]/', 'o', $text);
        $text = preg_replace('/[ú]/', 'u', $text);
        $text = preg_replace('/[ç]/', 'c', $text);
        // Remover caracteres especiais e substituir espaços por hífens
        $text = preg_replace('/[^a-z0-9]+/', '-', $text);
        $text = trim($text, '-');
        return $text;
    }
}