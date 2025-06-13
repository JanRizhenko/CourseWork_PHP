<?php

namespace App\Models;

use Core\Database;
use PDO;
use PDOException;

class User
{
    protected $table = 'users';
    protected $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }
    public function getTotalCount()
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM users");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    }
    public function getAllUsers()
    {
        $stmt = $this->db->prepare("SELECT * FROM users");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM {$this->table}");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function login($email, $password)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return false;
    }

    public function findByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} (name, email, password, phone, registration_date, is_admin) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);

        $isAdmin = isset($data['is_admin']) && $data['is_admin'] ? 1 : 0;
        $registrationDate = date('Y-m-d H:i:s');

        $result = $stmt->execute([
            $data['name'],
            $data['email'],
            $data['password'],
            $data['phone'] ?? null,
            $registrationDate,
            $isAdmin
        ]);

        return $result ? $this->db->lastInsertId() : false;
    }

    public function update($id, $data)
    {
        $fields = [];
        $values = [];

        foreach ($data as $key => $value) {
            $fields[] = "$key = ?";
            $values[] = $value;
        }

        $values[] = $id;
        $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id = ?";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($values);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getUserBookingsCount($userId)
    {
        try {
            $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM bookings WHERE user_id = ?");
            $stmt->execute([$userId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result ? (int)$result['count'] : 0;
        } catch (PDOException $e) {
            error_log("Error getting user bookings count: " . $e->getMessage());
            return 0;
        }
    }
}
