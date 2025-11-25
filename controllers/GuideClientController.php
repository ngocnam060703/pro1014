<?php
class GuideClientController {

    public function dashboard() {
        require_once __DIR__ . '/../views/client_hdv/dashboard.php';
    }

    public function schedule() {
        require_once __DIR__ . '/../views/client_hdv/schedule.php';
    }

    public function journal() {
        require_once __DIR__ . '/../views/client_hdv/journal.php';
    }

    public function incident() {
        require_once __DIR__ . '/../views/client_hdv/incident.php';
    }
}
