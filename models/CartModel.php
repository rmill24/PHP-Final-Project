<?php
class CartModel
{
    private $db;
    private $userId;

    public function __construct($db, $userId)
    {
        $this->db = $db;
        $this->userId = $userId;
    }
    
    public function getCart()
    {
        $stmt = $this->db->prepare("
        SELECT ci.id AS cart_item_id, p.*, p.id AS product_id, ci.size_id, ci.quantity, ci.selected, s.label AS size_label
        FROM cart c
        JOIN cart_item ci ON ci.cart_id = c.id
        JOIN products p ON p.id = ci.product_id
        JOIN sizes s ON s.id = ci.size_id
        WHERE c.user_id = ?
    ");
        $stmt->execute([$this->userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSelectedCartItems()
    {
        $stmt = $this->db->prepare("
        SELECT ci.id AS cart_item_id, p.*, p.id AS product_id, ci.size_id, ci.quantity, s.label AS size_label
        FROM cart c
        JOIN cart_item ci ON ci.cart_id = c.id
        JOIN products p ON p.id = ci.product_id
        JOIN sizes s ON s.id = ci.size_id
        WHERE c.user_id = ? AND ci.selected = 1
    ");
        $stmt->execute([$this->userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function addOrUpdateItem($productId, $sizeId, $quantity)
    {
        $cartId = $this->getOrCreateCartId();

        $stmt = $this->db->prepare("SELECT id FROM cart_item WHERE cart_id = ? AND product_id = ? AND size_id = ?");
        $stmt->execute([$cartId, $productId, $sizeId]);
        $existingItemId = $stmt->fetchColumn();

        if ($existingItemId) {
            $stmt = $this->db->prepare("UPDATE cart_item SET quantity = quantity + ? WHERE id = ?");
            return $stmt->execute([$quantity, $existingItemId]);
        } else {
            return $this->addItem($productId, $sizeId, $quantity);
        }
    }

    public function addItem($productId, $sizeId, $quantity)
    {
        $cartId = $this->getOrCreateCartId();

        $stmt = $this->db->prepare("INSERT INTO cart_item (cart_id, product_id, size_id, quantity) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$cartId, $productId, $sizeId, $quantity]);
    }

    public function updateItemQuantity($cartItemId, $quantity)
    {
        $stmt = $this->db->prepare("
            UPDATE cart_item
            SET quantity = ?
            WHERE id = ? AND cart_id IN (SELECT id FROM cart WHERE user_id = ?)
        ");
        return $stmt->execute([$quantity, $cartItemId, $this->userId]);
    }

    public function removeItem($cartItemId)
    {
        $stmt = $this->db->prepare("
            DELETE FROM cart_item
            WHERE id = ? AND cart_id IN (SELECT id FROM cart WHERE user_id = ?)
        ");
        return $stmt->execute([$cartItemId, $this->userId]);
    }

    private function getOrCreateCartId()
    {
        $stmt = $this->db->prepare("SELECT id FROM cart WHERE user_id = ?");
        $stmt->execute([$this->userId]);
        $cartId = $stmt->fetchColumn();

        if ($cartId) return $cartId;

        $this->db->prepare("INSERT INTO cart (user_id) VALUES (?)")->execute([$this->userId]);
        return $this->db->lastInsertId();
    }

    public function updateItemSize($cartItemId, $newSizeId)
    {
        // Get the product ID of this cart item
        $stmt = $this->db->prepare("
        SELECT product_id, cart_id FROM cart_item
        WHERE id = ? AND cart_id IN (SELECT id FROM cart WHERE user_id = ?)
    ");
        $stmt->execute([$cartItemId, $this->userId]);
        $item = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$item) return false;

        // Check if the same product already exists with new size
        $stmt = $this->db->prepare("
        SELECT id FROM cart_item
        WHERE cart_id = ? AND product_id = ? AND size_id = ?
    ");
        $stmt->execute([$item['cart_id'], $item['product_id'], $newSizeId]);
        $existingItemId = $stmt->fetchColumn();

        if ($existingItemId) {
            // Merge: increment quantity and delete old item
            $this->db->prepare("
            UPDATE cart_item SET quantity = quantity + (
                SELECT quantity FROM cart_item WHERE id = ?
            ) WHERE id = ?
        ")->execute([$cartItemId, $existingItemId]);

            $this->db->prepare("DELETE FROM cart_item WHERE id = ?")->execute([$cartItemId]);
            return true;
        } else {
            // Just update size
            $stmt = $this->db->prepare("
            UPDATE cart_item
            SET size_id = ?
            WHERE id = ? AND cart_id IN (SELECT id FROM cart WHERE user_id = ?)
        ");
            $stmt->execute([$newSizeId, $cartItemId, $this->userId]);
            return $stmt->rowCount() > 0;
        }
    }

    public function updateItemSelection($cartItemId, $selected)
    {
        $stmt = $this->db->prepare("
            UPDATE cart_item
            SET selected = ?
            WHERE id = ? AND cart_id IN (SELECT id FROM cart WHERE user_id = ?)
        ");
        return $stmt->execute([$selected ? 1 : 0, $cartItemId, $this->userId]);
    }
}
