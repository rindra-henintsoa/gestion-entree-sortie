<?php

namespace App\Models;

use CodeIgniter\Model;

class LogPortiqueModel extends Model
{
    protected $table = 'log_portiques';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id', 'name', 'time', 'pin', 'card_no', 'device_id',
        'device_sn', 'device_name', 'verified', 'state',
        'event_point_id', 'event_point_name'
    ];
    public $db;

    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
    }

    public function getCollaborateursAvecCartes($limit, $offset)
    {
        $sql = "
        SELECT 
            name, 
            pin, 
            GROUP_CONCAT(DISTINCT card_no) AS cartes
        FROM log_portiques
        GROUP BY name, pin
        LIMIT " . $limit . " OFFSET " . $offset;

    return $this->db->query($sql)->getResultArray();
    }

    public function countCollaborateurs()
    {
        $sql = "
            SELECT COUNT(*) AS total
            FROM (
                SELECT name, pin
                FROM log_portiques
                GROUP BY name, pin
            ) AS subquery
        ";

        $result = $this->db->query($sql)->getRowArray();
        return $result['total'];
    }

    public function getLogsParDate($date)
    {
        $sql = "
            SELECT 
                name, 
                pin, 
                GROUP_CONCAT(DISTINCT card_no ORDER BY card_no SEPARATOR ',') AS cartes,
                MIN(CASE WHEN event_point_name = 'entrée' THEN time END) AS premiere_entree,
                MAX(CASE WHEN event_point_name = 'sortie' THEN time END) AS derniere_sortie,
                COUNT(CASE WHEN event_point_name = 'pause' THEN 1 END) AS nb_pauses,
                TIMESTAMPDIFF(SECOND,
                    MIN(CASE WHEN event_point_name = 'pause_debut' THEN time END),
                    MAX(CASE WHEN event_point_name = 'pause_fin' THEN time END)
                ) / 3600 AS volume_pause
            FROM log_portiques
            WHERE DATE(time) = ?
            GROUP BY name, pin
        ";

        return $this->db->query($sql, [$date])->getResultArray();
    }
}
