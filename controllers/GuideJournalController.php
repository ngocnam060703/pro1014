<?php
require_once "models/GuideJournalModel.php";
require_once "models/GuideModel.php";

class GuideJournalController {

    private $journal;
    private $guide;

    public function __construct() {
        $this->journal = new GuideJournalModel();
        $this->guide = new GuideModel();
    }

    public function index() {
        $data = $this->journal->all();
        include "views/admin/guide_journal_list.php";
    }

    public function create() {
        $guides = $this->guide->all();
        $departures = pdo_query("SELECT * FROM departures");
        include "views/admin/guide_journal_create.php";
    }

    public function store() {
        $data = [
            "guide_id" => $_POST["guide_id"],
            "departure_id" => $_POST["departure_id"],
            "note" => $_POST["note"]
        ];

        $this->journal->store($data);
        header("Location: index.php?act=guide-journal");
    }

    public function edit() {
        $id = $_GET["id"];
        $guides = $this->guide->all();
        $departures = pdo_query("SELECT * FROM departures");
        $journal = $this->journal->find($id);

        include "views/admin/guide_journal_edit.php";
    }

    public function update() {
        $id = $_POST["id"];

        $data = [
            "guide_id" => $_POST["guide_id"],
            "departure_id" => $_POST["departure_id"],
            "note" => $_POST["note"]
        ];

        $this->journal->updateData($id, $data);
        header("Location: index.php?act=guide-journal");
    }

    public function delete() {
        $id = $_GET["id"];
        $this->journal->delete($id);

        header("Location: index.php?act=guide-journal");
    }
}
