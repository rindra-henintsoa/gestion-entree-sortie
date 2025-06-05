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
            SELECT log.Name, log.pin Matricule, log.device_name, CAST(min(log.time) AS TIME) heureEntree, 
            po.heureSortie , COUNT(*) as nbrEntree, po.nbrSortie FROM log_portiques log 
            INNER JOIN 
            (SELECT CAST(max(lo.time) AS TIME) as heureSortie, lo.pin, COUNT(*) as nbrSortie FROM log_portiques lo 
            WHERE DATE(lo.time) = DATE('".$date."') and lo.event_point_name LIKE '%SORTIE%' GROUP BY lo.Name) as po ON po.pin = log.pin
            WHERE DATE(log.time) = DATE('".$date."') and log.event_point_name LIKE '%ENTRE%' GROUP BY log.Name
        ";

        return $this->db->query($sql, [$date])->getResultArray();
    }
}
