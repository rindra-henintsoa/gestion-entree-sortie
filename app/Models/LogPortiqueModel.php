<?php

namespace App\Models;

use CodeIgniter\Model;

class LogPortiqueModel extends Model
{
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
            SELECT COUNT(DISTINCT name) AS total
            FROM (
                SELECT name, pin
                FROM log_portiques
                GROUP BY name, pin
            ) AS subquery
        ";

        $result = $this->db->query($sql)->getRowArray();
        return $result['total'];
    }

    public function getLogsParDate($date, $limit = 20, $offset = 0)
    {
        $sql = "
            SELECT 
            lp.Name, 
            lp.pin AS Matricule, 
            GROUP_CONCAT(DISTINCT card_no) AS cartes,
            DATE_FORMAT(MIN(lp.time), '%H:%i:%s') AS heure_premiere_entree, 
            sortie_info.heure_derniere_sortie, 
            COUNT(DISTINCT DATE_FORMAT(lp.time, '%H:%i')) AS total_entrees, 
            sortie_info.total_sorties
        FROM 
            log_portiques lp
        INNER JOIN (
            SELECT 
                lo.pin, 
                DATE_FORMAT(MAX(lo.time), '%H:%i:%s') AS heure_derniere_sortie, 
                MAX(lo.time) AS heure_sortie_raw, 
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
            lp.Name
        LIMIT " . $limit . " OFFSET " . $offset;

        return $this->db->query($sql, [$date, $date, (int)$limit, (int)$offset])->getResultArray();
    }

    public function countLogsParDate($date)
    {
        $sql = "
            SELECT COUNT(DISTINCT lp.Name) AS total
            FROM log_portiques lp
            INNER JOIN (
                SELECT lo.pin
                FROM log_portiques lo
                WHERE DATE(lo.time) = ?
                AND lo.event_point_name LIKE '%SORTIE%'
                GROUP BY lo.pin
            ) AS sortie_info ON sortie_info.pin = lp.pin
            WHERE DATE(lp.time) = ?
            AND lp.event_point_name LIKE '%ENTREE%'
        ";

        return $this->db->query($sql, [$date, $date])->getRow()->total;
    }

    public function getVolumePauseParCollaborateur($date)
    {
        $sql = "
            SELECT 
                name, pin, time, event_point_name
            FROM log_portiques
            WHERE DATE(time) = ?
            ORDER BY pin, time
        ";

        $logs = $this->db->query($sql, [$date])->getResultArray();

        $pauses = [];

        foreach ($logs as $log) {
            $pin = $log['pin'];
            $event = $log['event_point_name'];
            $time = strtotime($log['time']);

            if (!isset($pauses[$pin])) {
                $pauses[$pin] = [
                    'name' => $log['name'],
                    'pause' => 0,
                    'last_sortie' => null,
                ];
            }

            if (stripos($event, 'SORTIE') !== false) {
                $pauses[$pin]['last_sortie'] = $time;
            }

            if (
                stripos($event, 'ENTREE') !== false &&
                !empty($pauses[$pin]['last_sortie']) &&
                $pauses[$pin]['last_sortie'] < $time
            ) {
                $pauses[$pin]['pause'] += ($time - $pauses[$pin]['last_sortie']);
                $pauses[$pin]['last_sortie'] = null;
            }
        }

        foreach ($pauses as &$p) {
            $p['pause_heure'] = round($p['pause'] / 3600, 2);
        }

        return $pauses;
    }
}
