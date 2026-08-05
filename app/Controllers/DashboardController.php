<?php
namespace App\Controllers;

use App\Models\MedicalRecord;

class DashboardController {
    public function index(): void {
        $recordModel = new MedicalRecord();

        // Get overall summary statistics
        $stats = $recordModel->getSummaryStats();

        // Get monthly summary (current month)
        $currentMonthStart = date('Y-m-01');
        $currentMonthEnd = date('Y-m-t');

        $monthlyStats = $recordModel->getSummaryStats([
            'start_date' => $currentMonthStart,
            'end_date' => $currentMonthEnd
        ]);

        // Get lens type distribution for period
        $lensDistribution = $recordModel->getLensDistribution([
            'start_date' => $currentMonthStart,
            'end_date' => $currentMonthEnd
        ]);

        // Get recent 5 examinations
        $allRecords = $recordModel->getAll();
        $recentRecords = array_slice($allRecords, 0, 5);

        // View titles
        $title = "Dashboard Rekam Medis - OPTIK FOCUS";
        
        // Render views
        require dirname(__DIR__, 2) . '/views/templates/header.php';
        require dirname(__DIR__, 2) . '/views/dashboard.php';
        require dirname(__DIR__, 2) . '/views/templates/footer.php';
    }
}
