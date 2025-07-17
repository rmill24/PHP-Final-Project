<?php
class ProductModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getFeatured($limit = 4) {
        $stmt = $this->db->prepare("SELECT * FROM products ORDER BY RAND() LIMIT ?");
        $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAll($category = null) {
        if ($category) {
            $stmt = $this->db->prepare("SELECT p.*, c.name AS category_name FROM products p
                                        LEFT JOIN categories c ON p.category_id = c.id
                                        WHERE c.name = ? ORDER BY p.name");
            $stmt->execute([$category]);
        } else {
            $stmt = $this->db->query("SELECT p.*, c.name AS category_name FROM products p
                                      LEFT JOIN categories c ON p.category_id = c.id
                                      ORDER BY p.name");
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
