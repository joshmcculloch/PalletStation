<?php

class Data extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function add($device, $time, $temp, $humid, $soil) {
        $this->db->query("INSERT INTO `Data` (device, `time`, temperature, humidity, soil) VALUES (?, ?, ?, ?, ?)",
            array($device, $time, $temp, $humid, $soil, $lat, $lon));
    }

    public function add_now($device, $temp, $humid, $soil, $rssi, $retries) {
        $this->db->query("INSERT INTO `Data` (device, `time`, temperature, humidity, soil, rssi, retries) VALUES (?, UTC_TIMESTAMP(), ?, ?, ?, ?, ?)",
            array($device, $temp, $humid, $soil, $rssi, $retries));
    }

    public function add_event($device, $type) {
        $this->db->query("INSERT INTO `Events` (device, `time`, `type`) VALUES (?, UTC_TIMESTAMP(), ?)",
            array($device, $type));
    }
}