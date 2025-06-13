<?php

namespace App\Models;

use Core\Database;
use PDO;

class Room
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getTotalCount()
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM rooms");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    }

    public function getAllRooms($filters = [])
    {
        $sql = "SELECT * FROM rooms WHERE 1=1";
        $params = [];

        if (!empty($filters['category'])) {
            $sql .= " AND category = :category";
            $params[':category'] = $filters['category'];
        }

        if (!empty($filters['capacity'])) {
            switch ($filters['capacity']) {
                case '2-3':
                    $sql .= " AND capacity BETWEEN 2 AND 3";
                    break;
                case '4-5':
                    $sql .= " AND capacity BETWEEN 4 AND 5";
                    break;
                case '6+':
                    $sql .= " AND capacity >= 6";
                    break;
            }
        }

        if (!empty($filters['priceRange'])) {
            switch ($filters['priceRange']) {
                case '0-500':
                    $sql .= " AND price <= 500";
                    break;
                case '500-800':
                    $sql .= " AND price BETWEEN 500 AND 800";
                    break;
                case '800-1200':
                    $sql .= " AND price BETWEEN 800 AND 1200";
                    break;
                case '1200+':
                    $sql .= " AND price >= 1200";
                    break;
            }
        }

        if (!empty($filters['difficulty'])) {
            $sql .= " AND difficulty = :difficulty";
            $params[':difficulty'] = $filters['difficulty'];
        }

        $sql .= " ORDER BY id ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRoomById($id)
    {
        $sql = "SELECT * FROM rooms WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createRoom($data)
    {
        $sql = "INSERT INTO rooms (title, description, category, difficulty, duration, image, capacity, price) 
                VALUES (:title, :description, :category, :difficulty, :duration, :image, :capacity, :price)";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':category', $data['category']);
        $stmt->bindParam(':difficulty', $data['difficulty']);
        $stmt->bindParam(':duration', $data['duration'], PDO::PARAM_INT);
        $stmt->bindParam(':image', $data['image']);
        $stmt->bindParam(':capacity', $data['capacity'], PDO::PARAM_INT);
        $stmt->bindParam(':price', $data['price']);

        return $stmt->execute();
    }

    public function updateRoom($id, $data)
    {
        $sql = "UPDATE rooms SET 
                title = :title, 
                description = :description, 
                category = :category,
                difficulty = :difficulty,
                duration = :duration,
                image = :image, 
                capacity = :capacity, 
                price = :price 
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':category', $data['category']);
        $stmt->bindParam(':difficulty', $data['difficulty']);
        $stmt->bindParam(':duration', $data['duration'], PDO::PARAM_INT);
        $stmt->bindParam(':image', $data['image']);
        $stmt->bindParam(':capacity', $data['capacity'], PDO::PARAM_INT);
        $stmt->bindParam(':price', $data['price']);

        return $stmt->execute();
    }

    public function deleteRoom($id)
    {
        $sql = "DELETE FROM rooms WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function getRoomsByCategory($category)
    {
        $sql = "SELECT * FROM rooms WHERE category = :category ORDER BY id ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':category', $category);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getFilterOptions()
    {
        $options = [
            'categories' => [],
            'priceRange' => ['min' => 0, 'max' => 0],
            'capacityRange' => ['min' => 0, 'max' => 0]
        ];

        $sql = "SELECT DISTINCT category FROM rooms WHERE category IS NOT NULL";
        $stmt = $this->db->query($sql);
        $options['categories'] = $stmt->fetchAll(PDO::FETCH_COLUMN);

        $sql = "SELECT MIN(price) as min_price, MAX(price) as max_price FROM rooms";
        $stmt = $this->db->query($sql);
        $priceRange = $stmt->fetch(PDO::FETCH_ASSOC);
        $options['priceRange'] = [
            'min' => $priceRange['min_price'] ?? 0,
            'max' => $priceRange['max_price'] ?? 0
        ];

        $sql = "SELECT MIN(capacity) as min_capacity, MAX(capacity) as max_capacity FROM rooms";
        $stmt = $this->db->query($sql);
        $capacityRange = $stmt->fetch(PDO::FETCH_ASSOC);
        $options['capacityRange'] = [
            'min' => $capacityRange['min_capacity'] ?? 0,
            'max' => $capacityRange['max_capacity'] ?? 0
        ];

        return $options;
    }
}