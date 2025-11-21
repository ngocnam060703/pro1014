<?php
class HDVController {
    private $assign;
    private $journal;
    private $incident;

    public function __construct() {
        $this->assign = new GuideAssignModel();
        $this->journal = new GuideJournalModel();
        $this->incident = new GuideIncidentModel();

        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'guide') {
            header("Location: index.php?act=loginForm");
            exit;
        }
    }

    public function schedule() {
        $guideId = $_SESSION['user']['id'];
        $assigns = $this->assign->getByGuide($guideId);
        include "views/hdv/schedule.php";
    }

    public function journal() {
        $guideId = $_SESSION['user']['id'];
        $journals = $this->journal->getByGuide($guideId);
        include "views/hdv/journal.php";
    }

    public function incident() {
        $guideId = $_SESSION['user']['id'];
        $incidents = $this->incident->getByGuide($guideId);
        include "views/hdv/incident.php";
    }

    public function incidentStore() {
        $guideId = $_SESSION['user']['id'];
        $photoPath = null;
        if (!empty($_FILES["photos"]["name"])) {
            $photoPath = uploadFile($_FILES["photos"], "uploads/incidents/");
        }

        $data = [
            "guide_id" => $guideId,
            "departure_id" => $_POST["departure_id"],
            "incident_type" => $_POST["incident_type"],
            "severity" => $_POST["severity"],
            "description" => $_POST["description"],
            "solution" => $_POST["solution"],
            "photos" => $photoPath
        ];

        $this->incident->store($data);
        header("Location: index.php?act=hdv-incident");
    }
}
