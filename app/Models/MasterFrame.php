<?php
namespace App\Models;

use App\Config\Database;
use PDO;

class MasterFrame {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    /**
     * Get all master frames with optional filters
     */
    public function getAll(?string $search = null, ?string $brand = null, ?string $type = null): array {
        $sql = "SELECT * FROM frames WHERE 1=1";
        $params = [];

        if (!empty($search)) {
            $sql .= " AND (name LIKE ? OR code LIKE ? OR color LIKE ? OR material LIKE ?)";
            $searchTerm = "%{$search}%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        if (!empty($brand)) {
            $sql .= " AND brand = ?";
            $params[] = $brand;
        }

        if (!empty($type)) {
            $sql .= " AND type = ?";
            $params[] = $type;
        }

        $sql .= " ORDER BY brand ASC, name ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get single frame item by ID
     */
    public function getById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM frames WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /**
     * Get unique frame brands list
     */
    public function getBrands(): array {
        $stmt = $this->db->query("SELECT DISTINCT brand FROM frames WHERE brand IS NOT NULL AND brand != '' ORDER BY brand ASC");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * Get unique frame types list
     */
    public function getTypes(): array {
        $stmt = $this->db->query("SELECT DISTINCT type FROM frames WHERE type IS NOT NULL AND type != '' ORDER BY type ASC");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * Create new frame item
     */
    public function create(array $data): bool {
        $code = trim($data['code'] ?? '');
        if (empty($code)) {
            $code = 'FRM-' . strtoupper(substr(md5(uniqid()), 0, 6));
        }

        $stmt = $this->db->prepare("
            INSERT INTO frames (code, name, brand, type, material, color, price, stock, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        return $stmt->execute([
            $code,
            trim($data['name'] ?? ''),
            trim($data['brand'] ?? 'Generic'),
            trim($data['type'] ?? 'Full Rim'),
            trim($data['material'] ?? 'Acetate'),
            trim($data['color'] ?? 'Black'),
            (float)($data['price'] ?? 0),
            (int)($data['stock'] ?? 0),
            date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Update existing frame item
     */
    public function update(int $id, array $data): bool {
        $stmt = $this->db->prepare("
            UPDATE frames
            SET code = ?, name = ?, brand = ?, type = ?, material = ?, color = ?, price = ?, stock = ?
            WHERE id = ?
        ");

        return $stmt->execute([
            trim($data['code'] ?? ''),
            trim($data['name'] ?? ''),
            trim($data['brand'] ?? ''),
            trim($data['type'] ?? 'Full Rim'),
            trim($data['material'] ?? 'Acetate'),
            trim($data['color'] ?? 'Black'),
            (float)($data['price'] ?? 0),
            (int)($data['stock'] ?? 0),
            $id
        ]);
    }

    /**
     * Delete frame item
     */
    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM frames WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Get summary KPI statistics for Frames
     */
    public function getSummaryStats(): array {
        $totalItems = (int)$this->db->query("SELECT COUNT(*) FROM frames")->fetchColumn();
        $totalBrands = (int)$this->db->query("SELECT COUNT(DISTINCT brand) FROM frames")->fetchColumn();
        $totalStock = (int)$this->db->query("SELECT SUM(stock) FROM frames")->fetchColumn();
        $lowStock = (int)$this->db->query("SELECT COUNT(*) FROM frames WHERE stock <= 3")->fetchColumn();

        return [
            'total_items' => $totalItems,
            'total_brands' => $totalBrands,
            'total_stock' => $totalStock,
            'low_stock' => $lowStock
        ];
    }
}
