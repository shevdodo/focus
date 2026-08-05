<?php
namespace App\Models;

use App\Config\Database;
use PDO;

class MedicalRecord {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    /**
     * Get all medical records with patient details & BPJS info
     */
    public function getAll(array $filters = []): array {
        $sql = "
            SELECT 
                r.*,
                p.mr_number,
                p.name AS patient_name,
                p.gender AS patient_gender,
                p.dob AS patient_dob,
                p.phone AS patient_phone,
                p.address AS patient_address,
                p.bpjs_class AS patient_bpjs_class,
                p.bpjs_number AS patient_bpjs_number
            FROM medical_records r
            JOIN patients p ON r.patient_id = p.id
            WHERE 1=1
        ";

        $params = [];

        if (!empty($filters['search'])) {
            $sql .= " AND (p.name LIKE :search OR p.mr_number LIKE :search OR p.bpjs_number LIKE :search OR r.record_number LIKE :search OR r.frame_code LIKE :search)";
            $params[':search'] = '%' . $filters['search'] . '%';
        }

        if (!empty($filters['lens_type'])) {
            $sql .= " AND r.lens_type = :lens_type";
            $params[':lens_type'] = $filters['lens_type'];
        }

        if (!empty($filters['start_date'])) {
            $sql .= " AND r.exam_date >= :start_date";
            $params[':start_date'] = $filters['start_date'];
        }

        if (!empty($filters['end_date'])) {
            $sql .= " AND r.exam_date <= :end_date";
            $params[':end_date'] = $filters['end_date'];
        }

        $sql .= " ORDER BY r.exam_date DESC, r.id DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Get a single medical record by ID with BPJS info
     */
    public function getById(int $id): ?array {
        $sql = "
            SELECT 
                r.*,
                p.mr_number,
                p.name AS patient_name,
                p.gender AS patient_gender,
                p.dob AS patient_dob,
                p.phone AS patient_phone,
                p.address AS patient_address,
                p.bpjs_class AS patient_bpjs_class,
                p.bpjs_number AS patient_bpjs_number
            FROM medical_records r
            JOIN patients p ON r.patient_id = p.id
            WHERE r.id = :id
            LIMIT 1
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $record = $stmt->fetch();
        return $record ?: null;
    }

    /**
     * Generate unique RM Number per year with 6-digit padding (e.g. RM-2026-000001)
     * Accommodates up to 999,999 patients per year.
     */
    public function generateMrNumber(): string {
        $year = date('Y');
        $prefix = "RM-{$year}-";

        $stmt = $this->db->prepare("
            SELECT mr_number FROM patients 
            WHERE mr_number LIKE :prefix 
            ORDER BY id DESC LIMIT 1
        ");
        $stmt->execute([':prefix' => $prefix . '%']);
        $lastMr = $stmt->fetchColumn();

        if ($lastMr) {
            $num = (int)substr($lastMr, strlen($prefix));
            $nextSeq = $num + 1;
        } else {
            $nextSeq = 1;
        }

        return $prefix . str_pad((string)$nextSeq, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Create or retrieve patient and save examination record
     */
    public function createRecord(array $data): bool {
        try {
            $this->db->beginTransaction();

            $patientId = $data['patient_id'] ?? null;

            // If patient_id is not provided, create a new patient record with BPJS fields
            if (empty($patientId)) {
                $mrNumber = $this->generateMrNumber();
                $stmtP = $this->db->prepare("
                    INSERT INTO patients (mr_number, name, gender, dob, phone, address, bpjs_class, bpjs_number, created_at)
                    VALUES (:mr_number, :name, :gender, :dob, :phone, :address, :bpjs_class, :bpjs_number, :created_at)
                ");
                $stmtP->execute([
                    ':mr_number' => $mrNumber,
                    ':name' => trim($data['patient_name']),
                    ':gender' => $data['patient_gender'] ?? 'L',
                    ':dob' => $data['patient_dob'] ?? null,
                    ':phone' => trim($data['patient_phone'] ?? ''),
                    ':address' => trim($data['patient_address'] ?? ''),
                    ':bpjs_class' => $data['bpjs_class'] ?? 'Non-BPJS',
                    ':bpjs_number' => trim($data['bpjs_number'] ?? ''),
                    ':created_at' => date('Y-m-d H:i:s')
                ]);
                $patientId = $this->db->lastInsertId();
            }

            // Create medical record entry
            $recordNumber = 'REC-' . date('Ymd') . '-' . rand(100, 999);
            $stmtR = $this->db->prepare("
                INSERT INTO medical_records (
                    patient_id, record_number, exam_date, examiner_name,
                    od_sph, od_cyl, od_axis, od_add, od_va,
                    os_sph, os_cyl, os_axis, os_add, os_va,
                    pd, lens_type, frame_code, notes, total_price, created_at
                ) VALUES (
                    :patient_id, :record_number, :exam_date, :examiner_name,
                    :od_sph, :od_cyl, :od_axis, :od_add, :od_va,
                    :os_sph, :os_cyl, :os_axis, :os_add, :os_va,
                    :pd, :lens_type, :frame_code, :notes, :total_price, :created_at
                )
            ");

            $stmtR->execute([
                ':patient_id' => $patientId,
                ':record_number' => $recordNumber,
                ':exam_date' => $data['exam_date'] ?? date('Y-m-d'),
                ':examiner_name' => $data['examiner_name'] ?? ($_SESSION['user']['name'] ?? 'Optometris'),
                ':od_sph' => (float)($data['od_sph'] ?? 0),
                ':od_cyl' => (float)($data['od_cyl'] ?? 0),
                ':od_axis' => (int)($data['od_axis'] ?? 0),
                ':od_add' => (float)($data['od_add'] ?? 0),
                ':od_va' => $data['od_va'] ?? '6/6',
                ':os_sph' => (float)($data['os_sph'] ?? 0),
                ':os_cyl' => (float)($data['os_cyl'] ?? 0),
                ':os_axis' => (int)($data['os_axis'] ?? 0),
                ':os_add' => (float)($data['os_add'] ?? 0),
                ':os_va' => $data['os_va'] ?? '6/6',
                ':pd' => (float)($data['pd'] ?? 62.0),
                ':lens_type' => $data['lens_type'] ?? 'Single Vision Antiradiasi',
                ':frame_code' => trim($data['frame_code'] ?? ''),
                ':notes' => trim($data['notes'] ?? ''),
                ':total_price' => (float)($data['total_price'] ?? 0),
                ':created_at' => date('Y-m-d H:i:s')
            ]);

            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            return false;
        }
    }

    /**
     * Update an existing medical record
     */
    public function updateRecord(int $id, array $data): bool {
        try {
            $stmt = $this->db->prepare("
                UPDATE medical_records SET
                    exam_date = :exam_date,
                    examiner_name = :examiner_name,
                    od_sph = :od_sph,
                    od_cyl = :od_cyl,
                    od_axis = :od_axis,
                    od_add = :od_add,
                    od_va = :od_va,
                    os_sph = :os_sph,
                    os_cyl = :os_cyl,
                    os_axis = :os_axis,
                    os_add = :os_add,
                    os_va = :os_va,
                    pd = :pd,
                    lens_type = :lens_type,
                    frame_code = :frame_code,
                    notes = :notes,
                    total_price = :total_price
                WHERE id = :id
            ");

            return $stmt->execute([
                ':exam_date' => $data['exam_date'],
                ':examiner_name' => $data['examiner_name'],
                ':od_sph' => (float)$data['od_sph'],
                ':od_cyl' => (float)$data['od_cyl'],
                ':od_axis' => (int)$data['od_axis'],
                ':od_add' => (float)$data['od_add'],
                ':od_va' => $data['od_va'],
                ':os_sph' => (float)$data['os_sph'],
                ':os_cyl' => (float)$data['os_cyl'],
                ':os_axis' => (int)$data['os_axis'],
                ':os_add' => (float)$data['os_add'],
                ':os_va' => $data['os_va'],
                ':pd' => (float)$data['pd'],
                ':lens_type' => $data['lens_type'],
                ':frame_code' => $data['frame_code'],
                ':notes' => $data['notes'],
                ':total_price' => (float)$data['total_price'],
                ':id' => $id
            ]);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Delete a medical record
     */
    public function deleteRecord(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM medical_records WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Get all patients
     */
    public function getAllPatients(): array {
        $stmt = $this->db->query("SELECT * FROM patients ORDER BY name ASC");
        return $stmt->fetchAll();
    }

    /**
     * Get Summary Statistics for Dashboard & Reports
     */
    public function getSummaryStats(array $filters = []): array {
        $whereSql = " WHERE 1=1";
        $params = [];

        if (!empty($filters['start_date'])) {
            $whereSql .= " AND exam_date >= :start_date";
            $params[':start_date'] = $filters['start_date'];
        }

        if (!empty($filters['end_date'])) {
            $whereSql .= " AND exam_date <= :end_date";
            $params[':end_date'] = $filters['end_date'];
        }

        // Total patients
        $totalPatients = (int)$this->db->query("SELECT COUNT(*) FROM patients")->fetchColumn();

        // Total exams in period / overall
        $stmtExams = $this->db->prepare("SELECT COUNT(*) FROM medical_records $whereSql");
        $stmtExams->execute($params);
        $totalExams = (int)$stmtExams->fetchColumn();

        // Total revenue from optical prescriptions in period
        $stmtRev = $this->db->prepare("SELECT SUM(total_price) FROM medical_records $whereSql");
        $stmtRev->execute($params);
        $totalRevenue = (float)($stmtRev->fetchColumn() ?: 0);

        // Exams today
        $today = date('Y-m-d');
        $stmtToday = $this->db->prepare("SELECT COUNT(*) FROM medical_records WHERE exam_date = :today");
        $stmtToday->execute([':today' => $today]);
        $examsToday = (int)$stmtToday->fetchColumn();

        return [
            'total_patients' => $totalPatients,
            'total_exams' => $totalExams,
            'total_revenue' => $totalRevenue,
            'exams_today' => $examsToday
        ];
    }

    /**
     * Get distribution of lens types prescribed
     */
    public function getLensDistribution(array $filters = []): array {
        $sql = "
            SELECT lens_type, COUNT(*) as total_count, SUM(total_price) as total_amount
            FROM medical_records
            WHERE 1=1
        ";
        $params = [];

        if (!empty($filters['start_date'])) {
            $sql .= " AND exam_date >= :start_date";
            $params[':start_date'] = $filters['start_date'];
        }

        if (!empty($filters['end_date'])) {
            $sql .= " AND exam_date <= :end_date";
            $params[':end_date'] = $filters['end_date'];
        }

        $sql .= " GROUP BY lens_type ORDER BY total_count DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}
