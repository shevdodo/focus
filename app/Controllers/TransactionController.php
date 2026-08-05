<?php
namespace App\Controllers;

use App\Models\MedicalRecord;

class TransactionController {
    private MedicalRecord $recordModel;

    public function __construct() {
        $this->recordModel = new MedicalRecord();
    }

    /**
     * List all medical records & refraction prescriptions
     */
    public function index(): void {
        // Collect filters from GET query parameters
        $filters = [
            'search' => $_GET['search'] ?? '',
            'lens_type' => $_GET['lens_type'] ?? '',
            'start_date' => $_GET['start_date'] ?? '',
            'end_date' => $_GET['end_date'] ?? ''
        ];

        // Fetch records
        $records = $this->recordModel->getAll($filters);
        
        // Fetch patients list
        $patients = $this->recordModel->getAllPatients();

        // Summary stats for filtered view
        $summary = $this->recordModel->getSummaryStats($filters);

        $title = "Daftar Rekam Medis & Resep Optik";

        // Render views
        require dirname(__DIR__, 2) . '/views/templates/header.php';
        require dirname(__DIR__, 2) . '/views/transactions/index.php';
        require dirname(__DIR__, 2) . '/views/templates/footer.php';
    }

    /**
     * Render standalone Input Rekam Medis Optik page
     */
    public function create(): void {
        $patients = $this->recordModel->getAllPatients();
        $title = "Input Rekam Medis Pasien";
        $subtitle = "Formulir registrasi rekam medis & resep kacamata Klinik OPTIK FOCUS";

        require dirname(__DIR__, 2) . '/views/templates/header.php';
        require dirname(__DIR__, 2) . '/views/records/create.php';
        require dirname(__DIR__, 2) . '/views/templates/footer.php';
    }

    /**
     * Save new optical medical record entry
     */
    public function store(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $patientName = trim($_POST['patient_name'] ?? '');
            $patientId = $_POST['patient_id'] ?? null;

            if (empty($patientId) && empty($patientName)) {
                setFlash('error', 'Nama pasien wajib dipilih atau diisi!');
                header('Location: ' . baseUrl('records/create'));
                exit;
            }

            $success = $this->recordModel->createRecord($_POST);

            if ($success) {
                setFlash('success', 'Rekam medis & resep kacamata pasien berhasil disimpan!');
                header('Location: ' . baseUrl('transactions'));
                exit;
            } else {
                setFlash('error', 'Gagal menyimpan rekam medis. Silakan periksa kembali data input.');
                header('Location: ' . baseUrl('records/create'));
                exit;
            }
        }

        header('Location: ' . baseUrl('transactions'));
        exit;
    }

    /**
     * Update existing optical medical record
     */
    public function update(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['id'] ?? 0);

            if ($id <= 0) {
                setFlash('error', 'ID rekam medis tidak valid!');
                header('Location: ' . baseUrl('transactions'));
                exit;
            }

            $success = $this->recordModel->updateRecord($id, $_POST);

            if ($success) {
                setFlash('success', 'Data rekam medis & resep kacamata berhasil diperbarui!');
            } else {
                setFlash('error', 'Gagal memperbarui rekam medis.');
            }
        }

        header('Location: ' . baseUrl('transactions'));
        exit;
    }

    /**
     * Delete optical medical record
     */
    public function destroy(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['id'] ?? 0);

            if ($id <= 0) {
                setFlash('error', 'ID rekam medis tidak valid!');
                header('Location: ' . baseUrl('transactions'));
                exit;
            }

            $success = $this->recordModel->deleteRecord($id);

            if ($success) {
                setFlash('success', 'Data rekam medis berhasil dihapus.');
            } else {
                setFlash('error', 'Gagal menghapus rekam medis.');
            }
        }

        header('Location: ' . baseUrl('transactions'));
        exit;
    }
}
