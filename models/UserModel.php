<?php
require_once __DIR__ . '/../includes/db.php';

class UserModel
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function register($data)
    {
        $stmt = $this->db->prepare("INSERT INTO users (first_name, last_name, email, phone_number, address, password, email_verified)
            VALUES (?, ?, ?, ?, ?, ?, 0)");
        $stmt->execute([
            $data['first_name'],
            $data['last_name'],
            $data['email'],
            $data['phone'],
            $data['address'],
            password_hash($data['password'], PASSWORD_BCRYPT)
        ]);

        return $this->db->lastInsertId();
    }

    public function saveVerificationToken($userId, $token)
    {
        $stmt = $this->db->prepare("INSERT INTO verification_tokens (user_id, token) VALUES (?, ?)");
        return $stmt->execute([$userId, $token]);
    }

    public function verifyUser($userId, $token)
    {
        $stmt = $this->db->prepare("SELECT token FROM verification_tokens WHERE user_id = ?");
        $stmt->execute([$userId]);
        $storedToken = $stmt->fetchColumn();

        if ($storedToken === $token) {
            $this->db->prepare("UPDATE users SET email_verified = 1 WHERE id = ?")->execute([$userId]);
            $this->db->prepare("DELETE FROM verification_tokens WHERE user_id = ?")->execute([$userId]);
            return true;
        }

        return false;
    }

    public function login($email, $password)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || !$user['email_verified']) {
            return false;
        }

        if (password_verify($password, $user['password'])) {
            return $user;
        }

        return false;
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
