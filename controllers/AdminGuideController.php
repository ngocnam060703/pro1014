<?php
require_once "models/GuideAssignModel.php";
require_once "models/GuideJournalModel.php";
require_once "models/GuideIncidentModel.php";
require_once "models/GuideModel.php";

class AdminGuideController {

    private $assignModel;
    private $journalModel;
    private $incidentModel;
    private $guideModel;

    public function __construct() {
        $this->assignModel = new GuideAssignModel();
        $this->journalModel = new GuideJournalModel();
        $this->incidentModel = new GuideIncidentModel();
        $this->guideModel = new GuideModel();
    }

    // ===============================
    // Xem lịch làm việc HDV
    // ===============================
    public function schedule() {
        $guides = $this->guideModel->all();
        $assigns = $this->assignModel->all();
        include "views/admin/schedule.php";
    }

    // ===============================
    // Xem nhật ký tour của HDV
    // ===============================
    public function journal() {
        $guides = $this->guideModel->all();
        $journals = $this->journalModel->all();
        include "views/admin/journal.php";
    }

    // ===============================
    // Xem báo cáo sự cố HDV
    // ===============================
    public function incidents() {
        $guides = $this->guideModel->all();
        $incidents = $this->incidentModel->all();
        include "views/admin/incidents.php";
    }
}
