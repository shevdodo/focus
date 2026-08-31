<?php
namespace App\Controllers;

use App\Models\MasterLens;
use App\Models\MasterFrame;

class MasterDataController {
    private MasterLens $lensModel;
    private MasterFrame $frameModel;

    public function __construct() {
        $this->lensModel = new MasterLens();
        $this->frameModel = new MasterFrame();
    }

    /**
     * Display Master Data management page (Lensa & Frame)
     */
    public function index(): void {
        $activeTab = $_GET['tab'] ?? 'lensa';

        // Lensa Filters & Data
        $searchLens = $_GET['search_lens'] ?? '';
        $brandLens = $_GET['brand_lens'] ?? '';
        $categoryLens = $_GET['category_lens'] ?? '';
        $lenses = $this->lensModel->getAll($searchLens, $brandLens, $categoryLens);
        $lensBrands = $this->lensModel->getBrands();
        $lensCategories = $this->lensModel->getCategories();
        $lensStats = $this->lensModel->getSummaryStats();

        // Frame Filters & Data
        $searchFrame = $_GET['search_frame'] ?? '';
        $brandFrame = $_GET['brand_frame'] ?? '';
        $typeFrame = $_GET['type_frame'] ?? '';
        $frames = $this->frameModel->getAll($searchFrame, $brandFrame, $typeFrame);
        $frameBrands = $this->frameModel->getBrands();
        $frameTypes = $this->frameModel->getTypes();
        $frameStats = $this->frameModel->getSummaryStats();

        $title = "Master Data Lensa & Frame";
        $subtitle = "Manajemen katalog jenis lensa, bingkai kacamata, dan stok produk OPTIK FOCUS";

        require dirname(__DIR__, 2) . '/views/templates/header.php';
        require dirname(__DIR__, 2) . '/views/master/index.php';
        require dirname(__DIR__, 2) . '/views/templates/footer.php';
    }

    /**
     * Create new lens item
     */
    public function storeLens(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            if (empty($name)) {
                setFlash('error', 'Nama jenis lensa wajib diisi!');
                header('Location: ' . baseUrl('master?tab=lensa'));
                exit;
            }

            $success = $this->lensModel->create($_POST);
            if ($success) {
                setFlash('success', "Item lensa '{$name}' berhasil ditambahkan ke Master Data!");
            } else {
                setFlash('error', 'Gagal menambahkan lensa baru. Pastikan Kode Lensa belum pernah digunakan.');
            }
        }
        header('Location: ' . baseUrl('master?tab=lensa'));
        exit;
    }

    /**
     * Update existing lens item
     */
    public function updateLens(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['id'] ?? 0);
            $name = trim($_POST['name'] ?? '');

            if ($id <= 0 || empty($name)) {
                setFlash('error', 'Data lensa tidak valid untuk diperbarui.');
                header('Location: ' . baseUrl('master?tab=lensa'));
                exit;
            }

            $success = $this->lensModel->update($id, $_POST);
            if ($success) {
                setFlash('success', "Data lensa '{$name}' berhasil diperbarui!");
            } else {
                setFlash('error', 'Gagal memperbarui data lensa.');
            }
        }
        header('Location: ' . baseUrl('master?tab=lensa'));
        exit;
    }

    /**
     * Delete lens item
     */
    public function deleteLens(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['id'] ?? 0);
            if ($id <= 0) {
                setFlash('error', 'ID lensa tidak valid!');
                header('Location: ' . baseUrl('master?tab=lensa'));
                exit;
            }

            $success = $this->lensModel->delete($id);
            if ($success) {
                setFlash('success', 'Data lensa berhasil dihapus dari Master Data.');
            } else {
                setFlash('error', 'Gagal menghapus data lensa.');
            }
        }
        header('Location: ' . baseUrl('master?tab=lensa'));
        exit;
    }

    /**
     * Create new frame item
     */
    public function storeFrame(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $code = trim($_POST['code'] ?? '');

            if (empty($name) || empty($code)) {
                setFlash('error', 'Kode Frame dan Nama Frame wajib diisi!');
                header('Location: ' . baseUrl('master?tab=frame'));
                exit;
            }

            $success = $this->frameModel->create($_POST);
            if ($success) {
                setFlash('success', "Frame '{$name}' [{$code}] berhasil ditambahkan ke Master Data!");
            } else {
                setFlash('error', 'Gagal menambahkan frame baru. Pastikan Kode Frame unik.');
            }
        }
        header('Location: ' . baseUrl('master?tab=frame'));
        exit;
    }

    /**
     * Update existing frame item
     */
    public function updateFrame(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['id'] ?? 0);
            $name = trim($_POST['name'] ?? '');

            if ($id <= 0 || empty($name)) {
                setFlash('error', 'Data frame tidak valid untuk diperbarui.');
                header('Location: ' . baseUrl('master?tab=frame'));
                exit;
            }

            $success = $this->frameModel->update($id, $_POST);
            if ($success) {
                setFlash('success', "Data frame '{$name}' berhasil diperbarui!");
            } else {
                setFlash('error', 'Gagal memperbarui data frame.');
            }
        }
        header('Location: ' . baseUrl('master?tab=frame'));
        exit;
    }

    /**
     * Delete frame item
     */
    public function deleteFrame(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['id'] ?? 0);
            if ($id <= 0) {
                setFlash('error', 'ID frame tidak valid!');
                header('Location: ' . baseUrl('master?tab=frame'));
                exit;
            }

            $success = $this->frameModel->delete($id);
            if ($success) {
                setFlash('success', 'Data frame berhasil dihapus dari Master Data.');
            } else {
                setFlash('error', 'Gagal menghapus data frame.');
            }
        }
        header('Location: ' . baseUrl('master?tab=frame'));
        exit;
    }
}
