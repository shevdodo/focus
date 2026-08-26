<?php
namespace App\Controllers;

use App\Models\MedicalRecord;

class ReportController {
    private MedicalRecord $recordModel;

    public function __construct() {
        $this->recordModel = new MedicalRecord();
    }

    /**
     * Render Optical Record analytics and periodic report
     */
    public function index(): void {
        $selectedMonth = $_GET['month'] ?? date('Y-m');
        $bpjsType = $_GET['bpjs_type'] ?? '';
        $start_date = $selectedMonth . '-01';
        $end_date = date('Y-m-t', strtotime($start_date));

        $filterParams = [
            'start_date' => $start_date,
            'end_date' => $end_date,
            'bpjs_type' => $bpjsType
        ];

        // Fetch records for period
        $records = $this->recordModel->getAll($filterParams);

        // Fetch summary statistics
        $summary = $this->recordModel->getSummaryStats($filterParams);

        // Fetch lens distribution
        $lensDistribution = $this->recordModel->getLensDistribution($filterParams);

        $title = "Laporan Rekam Medis & Rekap Optik";
        $subtitle = "Laporan rekapitulasi pemeriksaan pasien & resep kacamata Klinik OPTIK FOCUS";

        // Render views
        require dirname(__DIR__, 2) . '/views/templates/header.php';
        require dirname(__DIR__, 2) . '/views/reports/index.php';
        require dirname(__DIR__, 2) . '/views/templates/footer.php';
    }
}
