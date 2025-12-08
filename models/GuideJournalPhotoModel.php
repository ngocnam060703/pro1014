<?php
require_once __DIR__ . "/../commons/function.php";

class GuideJournalPhotoModel {
    
    public function store($data) {
        $sql = "INSERT INTO guide_journal_photos 
                (journal_id, incident_id, file_path, file_name, file_size, file_type)
                VALUES (?, ?, ?, ?, ?, ?)";
        
        return pdo_execute($sql,
            $data['journal_id'],
            $data['incident_id'] ?? null,
            $data['file_path'],
            $data['file_name'],
            $data['file_size'] ?? null,
            $data['file_type'] ?? 'image'
        );
    }
    
    public function getByJournalId($journalId) {
        $sql = "SELECT * FROM guide_journal_photos 
                WHERE journal_id = ? 
                ORDER BY uploaded_at ASC";
        return pdo_query($sql, $journalId);
    }
    
    public function getByIncidentId($incidentId) {
        $sql = "SELECT * FROM guide_journal_photos 
                WHERE incident_id = ? 
                ORDER BY uploaded_at ASC";
        return pdo_query($sql, $incidentId);
    }
    
    public function delete($id) {
        $sql = "SELECT file_path FROM guide_journal_photos WHERE id = ?";
        $photo = pdo_query_one($sql, $id);
        
        if ($photo && file_exists($photo['file_path'])) {
            @unlink($photo['file_path']);
        }
        
        $sql = "DELETE FROM guide_journal_photos WHERE id = ?";
        return pdo_execute($sql, $id);
    }
    
    public function deleteByJournalId($journalId) {
        $photos = $this->getByJournalId($journalId);
        foreach ($photos as $photo) {
            if (file_exists($photo['file_path'])) {
                @unlink($photo['file_path']);
            }
        }
        
        $sql = "DELETE FROM guide_journal_photos WHERE journal_id = ?";
        return pdo_execute($sql, $journalId);
    }
}

