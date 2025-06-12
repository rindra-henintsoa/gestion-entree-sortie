<?php

namespace App\Models;

use CodeIgniter\Model;

class LogPortiqueModel extends Model
{
    protected $table = 'log_portiques';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id',
        'name',
        'time',
        'pin',
        'card_no',
        'device_id',
        'device_sn',
        'device_name',
        'verified',
        'state',
        'event_point_id',
        'event_point_name'
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
        GROUP BY name
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
            lp.Name, 
            lp.pin AS Matricule, 
            GROUP_CONCAT(DISTINCT card_no) AS cartes,
            DATE_FORMAT(MIN(lp.time), '%H:%i:%s') AS heure_premiere_entree, 
            sortie_info.heure_derniere_sortie, 
            COUNT(DISTINCT DATE_FORMAT(lp.time, '%H:%i')) AS total_entrees, 
            sortie_info.total_sorties,
            GREATEST(
                ROUND(
                    TIMESTAMPDIFF(SECOND, MIN(lp.time), sortie_info.heure_sortie_raw) / 3600 - 8,
                    2
                ),
                0
            ) AS volume_pause_heures
        FROM 
            log_portiques lp
        INNER JOIN (
            SELECT 
                lo.pin, 
                DATE_FORMAT(MAX(lo.time), '%H:%i:%s') AS heure_derniere_sortie, 
                MAX(lo.time) AS heure_sortie_raw,               -- Ajouté
                COUNT(*) AS total_sorties
            FROM 
                log_portiques lo
            WHERE 
                DATE(lo.time) = DATE('".$date."') 
                AND lo.event_point_name LIKE '%SORTIE%'
            GROUP BY 
                lo.pin
        ) AS sortie_info ON sortie_info.pin = lp.pin
        WHERE 
            DATE(lp.time) = DATE('".$date."') 
            AND lp.event_point_name LIKE '%ENTREE%'
        GROUP BY 
            lp.Name;
        ";

        return $this->db->query($sql, [$date])->getResultArray();
    }
}
