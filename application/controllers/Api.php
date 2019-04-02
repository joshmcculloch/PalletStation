<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

	public function __construct () {
		parent::__construct();
		$this->load->model('Device');
		$this->load->model("Data");
	}

	public function update($id="", $key="")
	{
		if ($this->Device->verify($id, $key)) {
			$time = $this->input->get("time");
			$temp =  $this->input->get("temp");
			$humid = $this->input->get("humid");
			$soil = $this->input->get("soil");
			$rssi =  $this->input->get("rssi");
			$retries =  $this->input->get("retries");
			$version = $this->input->get("version");
			
			$status_code = 200;
			$status_message = "success";
			
			if ($time && ($temp || $humid)) {
				$this->Data->add($id, $time, $temp, $humid, $soil);
			}
			else if ($temp || $humid || $soil || $rssi || $retries) {
				$this->Data->add_now($id, $temp, $humid, $soil, $rssi, $retries);
			}
			else {
				$status_code = 400;
				$status_message = "invalid data";
			}
			
			if ($version && $this->Device->shouldUpdate($id, $version)) {
				$status_code = 610;
				$status_message = "http://sense.joshmcculloch.nz/firmware/firmware.bin";
			}
			
			$this->output->set_status_header($code=$status_code, $text=$status_message);
			echo $status_message;
			
		} else {
			$this->output->set_status_header($code=401);
			echo $id."  --  ".$key;
			echo "Auth failure";
		}
	}

	public function event($id="", $key="")
	{
		if ($this->Device->verify($id, $key)) {
			$type = $this->input->get("type");
			if ($type) {
				$this->Data->add_event($id, $type);
				$this->output->set_status_header($code=200);
				echo "Inserted";
			}
			else {
				$this->output->set_status_header($code=400);
				echo "invalid data";
			}
		} else {
			$this->output->set_status_header($code=401);
			echo $id."  --  ".$key;
			echo "Auth failure";
		}

	}

}
