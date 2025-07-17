<?php
class CartModel {
    private $db;
    private $userId;

    public function __construct($db, $userId) {
        $this->db = $db;
        $this->userId = $userId;
    }

    public function getCart() {
        $stmt = $this->db->prepare("
            SELECT ci.id AS cart_item_id, p.*, ci.quantity, ps.label AS size_label
            FROM cart c
            JOIN cart_item ci ON ci.cart_id = c.id
            JOIN products p ON p.id = ci.product_id
            LEFT JOIN product_sizes ps ON ps.id = ci.size_id
            WHERE c.user_id = ?
        ");
        $stmt->execute([$this->userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addItem($productId, $sizeId, $quantity) {
        $cartId = $this->getOrCreateCartId();

        $stmt = $this->db->prepare("INSERT INTO cart_item (cart_id, product_id, size_id, quantity) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$cartId, $productId, $sizeId, $quantity]);
    }

    private function getOrCreateCartId() {
        $stmt = $this->db->prepare("SELECT id FROM cart WHERE user_id = ?");
        $stmt->execute([$this->userId]);
        $cartId = $stmt->fetchColumn();

        if ($cartId) return $cartId;

        $this->db->prepare("INSERT INTO cart (user_id) VALUES (?)")->execute([$this->userId]);
        return $this->db->lastInsertId();
    }
}
