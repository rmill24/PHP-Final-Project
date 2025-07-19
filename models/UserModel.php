<?php
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
        // Delete any existing tokens for this user first
        $this->db->prepare("DELETE FROM verification_tokens WHERE user_id = ?")->execute([$userId]);
        
        // Insert new token with created_at timestamp
        $stmt = $this->db->prepare("INSERT INTO verification_tokens (user_id, token, created_at) VALUES (?, ?, NOW())");
        return $stmt->execute([$userId, $token]);
    }

    public function verifyUser($userId, $token)
    {
        // Check if token exists and is not expired (valid for 5 minutes)
        $stmt = $this->db->prepare("
            SELECT token, created_at 
            FROM verification_tokens 
            WHERE user_id = ? AND created_at >= DATE_SUB(NOW(), INTERVAL 5 MINUTE)
        ");
        $stmt->execute([$userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result && $result['token'] === $token) {
            // Token is valid, verify the user
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

    public function getOrdersWithItems($userId)
    {
        // Fetch orders for user
        $stmt = $this->db->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$userId]);
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$orders) {
            return [];
        }

        // For each order, fetch order items joined with product info
        foreach ($orders as &$order) {
            $stmt = $this->db->prepare("
                SELECT oi.*, p.name AS product_name, p.image_url AS product_image, s.label AS size_label
                FROM order_items oi
                JOIN products p ON oi.product_id = p.id
                JOIN sizes s ON oi.size_id = s.id
                WHERE oi.order_id = ?
        ");
            $stmt->execute([$order['id']]);
            $order['items'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        return $orders;
    }

    public function getRecentOrdersWithItems($userId, $limit = 5)
    {
        // Cast $limit to int to avoid injection risk
        $limit = (int)$limit;

        $stmt = $this->db->prepare("
        SELECT * FROM orders
        WHERE user_id = ?
        ORDER BY created_at DESC
        LIMIT $limit
    ");
        $stmt->execute([$userId]);
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($orders as &$order) {
            $stmtItems = $this->db->prepare("
            SELECT oi.*, p.name AS product_name, p.image_url AS product_image, s.label AS size_label
            FROM order_items oi
            JOIN products p ON oi.product_id = p.id
            JOIN sizes s ON oi.size_id = s.id
            WHERE oi.order_id = ?
        ");
            $stmtItems->execute([$order['id']]);
            $order['items'] = $stmtItems->fetchAll(PDO::FETCH_ASSOC);
        }

        return $orders;
    }

    public function getOrderByIdAndUser($orderId, $userId)
    {
        $stmt = $this->db->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
        $stmt->execute([$orderId, $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getOrderItems($orderId)
    {
        $stmt = $this->db->prepare("
        SELECT oi.*, p.name AS product_name, p.image_url AS product_image, s.label AS size_label
        FROM order_items oi
        JOIN products p ON oi.product_id = p.id
        JOIN sizes s ON oi.size_id = s.id
        WHERE oi.order_id = ?
    ");
        $stmt->execute([$orderId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function isEmailVerified($userId)
    {
        $stmt = $this->db->prepare("SELECT email_verified FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $result = $stmt->fetchColumn();
        return $result == 1;
    }

    public function resendVerificationToken($userId, $token)
    {
        // Check if user exists and is not already verified
        $user = $this->getById($userId);
        if (!$user || $user['email_verified'] == 1) {
            return false;
        }

        // Check if there's a cooldown period (5 minutes) since last token was sent
        if ($this->isInCooldownPeriod($userId)) {
            return 'cooldown';
        }

        // Save new verification token
        return $this->saveVerificationToken($userId, $token);
    }

    public function isInCooldownPeriod($userId)
    {
        $stmt = $this->db->prepare("
            SELECT created_at 
            FROM verification_tokens 
            WHERE user_id = ? AND created_at >= DATE_SUB(NOW(), INTERVAL 5 MINUTE)
        ");
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
    }

    public function getCooldownTimeRemaining($userId)
    {
        $stmt = $this->db->prepare("
            SELECT TIMESTAMPDIFF(SECOND, created_at, DATE_ADD(created_at, INTERVAL 5 MINUTE)) AS seconds_remaining
            FROM verification_tokens 
            WHERE user_id = ? AND created_at >= DATE_SUB(NOW(), INTERVAL 5 MINUTE)
        ");
        $stmt->execute([$userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? max(0, $result['seconds_remaining']) : 0;
    }
}
