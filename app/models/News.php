<?php

namespace App\Models;

use Core\Database;
use PDO;

class News
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll()
    {
        $stmt = $this->db->prepare("SELECT id, title, content, created_at FROM news ORDER BY created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLatest($limit = 3)
    {
        $stmt = $this->db->prepare("SELECT id, title, content, created_at FROM news ORDER BY created_at DESC LIMIT :limit");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT id, title, content, created_at FROM news WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($title, $content)
    {
        $stmt = $this->db->prepare("INSERT INTO news (title, content) VALUES (:title, :content)");
        $stmt->bindValue(':title', $title);
        $stmt->bindValue(':content', $content);
        return $stmt->execute();
    }

    public function update($id, $title, $content)
    {
        $stmt = $this->db->prepare("UPDATE news SET title = :title, content = :content WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':title', $title);
        $stmt->bindValue(':content', $content);
        return $stmt->execute();
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM news WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}