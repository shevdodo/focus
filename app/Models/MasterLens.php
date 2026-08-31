<?php
namespace App\Models;

use App\Config\Database;
use PDO;

class MasterLens {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    /**
     * Get all master lenses with optional filters
     */
    public function getAll(?string $search = null, ?string $brand = null, ?string $category = null): array {
        $sql = "SELECT * FROM lenses WHERE 1=1";
        $params = [];

        if (!empty($search)) {
            $sql .= " AND (name LIKE ? OR code LIKE ? OR coating LIKE ?)";
            $searchTerm = "%{$search}%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        if (!empty($brand)) {
            $sql .= " AND brand = ?";
            $params[] = $brand;
        }

        if (!empty($category)) {
            $sql .= " AND category = ?";
            $params[] = $category;
        }

        $sql .= " ORDER BY category ASC, brand ASC, name ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get single lens item by ID
     */
    public function getById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM lenses WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /**
     * Get unique brands list
     */
    public function getBrands(): array {
        $stmt = $this->db->query("SELECT DISTINCT brand FROM lenses WHERE brand IS NOT NULL AND brand != '' ORDER BY brand ASC");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * Get unique categories list
     */
    public function getCategories(): array {
        $stmt = $this->db->query("SELECT DISTINCT category FROM lenses WHERE category IS NOT NULL AND category != '' ORDER BY category ASC");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * Create new lens item
     */
    public function create(array $data): bool {
        $code = trim($data['code'] ?? '');
        if (empty($code)) {
            $code = 'LNS-' . strtoupper(substr(md5(uniqid()), 0, 6));
        }

        $stmt = $this->db->prepare("
            INSERT INTO lenses (code, name, brand, category, index_refraction, coating, price, stock, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        return $stmt->execute([
            $code,
            trim($data['name'] ?? ''),
            trim($data['brand'] ?? 'Oriental'),
            trim($data['category'] ?? 'Single Vision'),
            trim($data['index_refraction'] ?? '1.56'),
            trim($data['coating'] ?? '-'),
            (float)($data['price'] ?? 0),
            (int)($data['stock'] ?? 0),
            date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Update existing lens item
     */
    public function update(int $id, array $data): bool {
        $stmt = $this->db->prepare("
            UPDATE lenses
            SET code = ?, name = ?, brand = ?, category = ?, index_refraction = ?, coating = ?, price = ?, stock = ?
            WHERE id = ?
        ");

        return $stmt->execute([
            trim($data['code'] ?? ''),
            trim($data['name'] ?? ''),
            trim($data['brand'] ?? ''),
            trim($data['category'] ?? ''),
            trim($data['index_refraction'] ?? '1.56'),
            trim($data['coating'] ?? '-'),
            (float)($data['price'] ?? 0),
            (int)($data['stock'] ?? 0),
            $id
        ]);
    }

    /**
     * Delete lens item
     */
    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM lenses WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Get grouped catalog for dropdown selects
     * E.g. [ 'Single Vision - Oriental' => [ ['name' => 'SV Ori 1.56', ...], ... ] ]
     */
    public function getGroupedCatalog(): array {
        $lenses = $this->getAll();
        $grouped = [];

        foreach ($lenses as $l) {
            $groupKey = $l['category'] . ' - ' . $l['brand'];
            if (!isset($grouped[$groupKey])) {
                $grouped[$groupKey] = [];
            }
            $grouped[$groupKey][] = $l;
        }

        return $grouped;
    }

    /**
     * Get summary KPI statistics for Lenses
     */
    public function getSummaryStats(): array {
        $totalItems = (int)$this->db->query("SELECT COUNT(*) FROM lenses")->fetchColumn();
        $totalBrands = (int)$this->db->query("SELECT COUNT(DISTINCT brand) FROM lenses")->fetchColumn();
        $totalStock = (int)$this->db->query("SELECT SUM(stock) FROM lenses")->fetchColumn();
        $lowStock = (int)$this->db->query("SELECT COUNT(*) FROM lenses WHERE stock <= 5")->fetchColumn();

        return [
            'total_items' => $totalItems,
            'total_brands' => $totalBrands,
            'total_stock' => $totalStock,
            'low_stock' => $lowStock
        ];
    }
}
