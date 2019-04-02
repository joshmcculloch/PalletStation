<?php

class Device extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function validID($id) {
        $query = $this->db->query('SELECT count(*) as Devices FROM Devices WHERE id=?', array($id));
        if ($query->row()->Devices == 1) {
            return true;
        } else {
            return false;
        }
    }

    public function verify($id, $auth) {
        $query = $this->db->query('SELECT count(*) as Devices FROM Devices WHERE id=? AND auth=?', array($id, $auth));
        if ($query->row()->Devices == 1) {
            return true;
        } else {
            return false;
        }
    }
	
	public function shouldUpdate($id, $versionString) {
		$query = $this->db->query('UPDATE Devices SET version=? WHERE id=?', array($versionString, $id));
		$query = $this->db->query('SELECT target FROM Devices WHERE id=?', array($id));
		
		if ((float)$versionString < (float)($query->row()->target)) {
			return true;
		}
		return false;
	}

    public function getData($id, $hours) {
        //$query = $this->db->query("SELECT *, unix_timestamp(`time`) as 'unixTime' FROM `Data` WHERE `device`=? ORDER BY `time` ASC", array($id));
        $query = $this->db->query("SELECT *, UNIX_TIMESTAMP(CONVERT_TZ(`time`, '+00:00', @@global.time_zone)) as 'unixTime' FROM `Data` WHERE `device`=? AND `time` > DATE_ADD( NOW(), INTERVAL -? HOUR) ORDER BY `time` ASC", array($id, $hours));
        return $query->result();
    }

    public function eventsInHour($id=0, $hour=0) {
        $sql = "
SELECT COUNT(*) as 'events',
UNIX_TIMESTAMP(
  CONVERT_TZ(
    DATE_ADD(
      DATE(UTC_TIMESTAMP()),
      INTERVAL HOUR(UTC_TIMESTAMP())+? HOUR),
    '+00:00', @@global.time_zone)) as 'unixTime'
FROM `Events` WHERE
`time` < DATE_ADD(
    DATE(UTC_TIMESTAMP()),
    INTERVAL 1+HOUR(UTC_TIMESTAMP())+? HOUR)
AND

`time` > DATE_ADD(
    DATE(UTC_TIMESTAMP()),
    INTERVAL HOUR(UTC_TIMESTAMP())+? HOUR)
AND `device`=?
        ";
        $query = $this->db->query($sql, array($hour,$hour,$hour,$id));
        return $query->row();
    }

    public function getEvents($id, $interval) {
        switch($interval) {
            case "hour":
                $query = $this->db->query("
SELECT COUNT(*) 'events',
UNIX_TIMESTAMP(CONVERT_TZ(`time`, '+00:00', @@global.time_zone)) as 'unixTime'
FROM Events
WHERE `device`=?
GROUP BY DATE(`time`), HOUR(`time`)
ORDER BY `time` DESC",
                    array($id));
                break;
            case "day":
                $query = $this->db->query("
SELECT COUNT(*) 'events',
UNIX_TIMESTAMP(CONVERT_TZ(`time`, '+00:00', @@global.time_zone)) as 'unixTime'
FROM Events
WHERE `device`=?
GROUP BY DATE(`time`)
ORDER BY `time` DESC",
                    array($id));
                break;
            default:
                $query = $this->db->query("
SELECT 1 'events',
UNIX_TIMESTAMP(CONVERT_TZ(`time`, '+00:00', @@global.time_zone)) as 'unixTime'
FROM Events
WHERE `device`=?
ORDER BY `time` DESC",
                    array($id));
        }
        return $query->result();
    }

}