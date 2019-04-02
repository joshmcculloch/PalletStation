<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Browse extends CI_Controller {

	public function __construct () {
		parent::__construct();
		$this->load->model('Device');
	}


	public function index()
	{
		$this->load->view('welcome_message');
	}

	public function device($id=-1) {
		$data["id"] = $id;
		if ($this->Device->validID($id)) {
			$this->load->view('header');
			$this->load->view('device_data_page',$data);
			$this->load->view('tail');
		}

	}

	public function data($id=-1) {
		$callback = $this->input->get("callback");
		$hours = $this->input->get("hours");
		if (!$hours) {$hours = "24";}
		$hours = (int)$hours;
		if (!$callback) {$callback = "?";}
		if ($this->Device->validID($id) && $callback) {
			$data["device_data"] = $this->Device->getData($id, $hours);
			$data["callback"] = $callback;
			$this->load->view("data", $data);

		}
	}

	public function temperature($id=-1) {
		$callback = $this->input->get("callback");
		if (!$callback) {$callback = "?";}
		if ($this->Device->validID($id) && $callback) {
			$data["device_data"] = $this->Device->getData($id);
			$data["callback"] = $callback;
			$this->load->view("temp_data", $data);

		}
	}

	public function humidity($id=-1) {
		$callback = $this->input->get("callback");
		if (!$callback) {$callback = "?";}
		if ($this->Device->validID($id) && $callback) {
			$data["device_data"] = $this->Device->getData($id);
			$data["callback"] = $callback;
			$this->load->view("humid_data", $data);

		}
	}

	public function events($id=-1) {
		$interval = $this->input->get("interval");
		$callback = $this->input->get("callback");
		if (!$interval) {$interval = "none";}
		if (!$callback) {$callback = "?";}
		if ($this->Device->validID($id)) {
			$data["device_events"] = $this->Device->getEvents($id,$interval);
			$data["callback"] = $callback;
			$this->load->view("events", $data);

		}
	}

	public function events24($id=-1) {
		$callback = $this->input->get("callback");
		$hours = $this->input->get("hours");
		if (!$callback) {$callback = "?";}
		if (!$hours) {$hours = "24";}
		$hours = (int)$hours;
		if ($this->Device->validID($id)) {
			$data["device_events"] = [];
			$data["callback"] = $callback;

			for ($h = -$hours; $h <= 0; $h++) {
				array_push($data["device_events"], $this->Device->eventsInHour($id,$h));
			}
			$this->load->view("events", $data);
			//$data["callback"] = $callback;
			//$this->load->view("events", $data);

		}
	}
}
