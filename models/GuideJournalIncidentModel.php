<?php
require_once __DIR__ . "/../commons/function.php";

class GuideJournalIncidentModel {
    
    public function store($data) {
        $sql = "INSERT INTO guide_journal_incidents 
                (journal_id, incident_time, description, affected_customers, solution, severity)
                VALUES (?, ?, ?, ?, ?, ?)";
        
        return pdo_execute($sql,
            $data['journal_id'],
            $data['incident_time'] ?? null,
            $data['description'],
            $data['affected_customers'] ?? null,
            $data['solution'] ?? null,
            $data['severity']
        );
    }
    
    public function getLastInsertId() {
        return pdo_last_insert_id();
    }
    
    public function getByJournalId($journalId) {
        $sql = "SELECT * FROM guide_journal_incidents 
                WHERE journal_id = ? 
                ORDER BY incident_time DESC";
        return pdo_query($sql, $journalId);
    }
    
    public function find($id) {
        $sql = "SELECT * FROM guide_journal_incidents WHERE id = ?";
        return pdo_query_one($sql, $id);
    }
    
    public function delete($id) {
        $sql = "DELETE FROM guide_journal_incidents WHERE id = ?";
        return pdo_execute($sql, $id);
    }
}

