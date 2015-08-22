<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Restaurants extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->load->model('Restaurant');
		$restaurants = $this->Restaurant->get_all_restaurants();
		$data = array('restaurants' => $restaurants);
		$this->load->view('index', $data);
	}

	public function add_restaurants() {
		$this->load->model('Restaurant');
		$coords = $this->getCoordinates($this->input->post('address'));
		$distance_duration = $this->calculate_distance_duration($this->input->post('address'));
		$data = array('name' => $this->input->post('name'),
					  'address' => $this->input->post('address'),
					  'lat' => $coords[0], 
					  'long' => $coords[1],
					  'place_id' => $coords[2],
					  'distance' => $distance_duration['distance'], 
					  'duration' => $distance_duration['duration'],
					  'cuisine' => $this->input->post('cuisine'));
		$this->Restaurant->add_restaurant($data);
		redirect('/');
	}

	public function getCoordinates($address) {
	    $address = urlencode($address);
	    $url = "http://maps.google.com/maps/api/geocode/json?sensor=false&address=" . $address;
	    $response = file_get_contents($url);
	    $json = json_decode($response,true);
	 
	    $lat = $json['results'][0]['geometry']['location']['lat'];
	    $lng = $json['results'][0]['geometry']['location']['lng'];
	 	$place_id = $json['results'][0]['place_id'];
	    return array($lat, $lng, $place_id);
	}

	public function calculate_distance_duration($address) {
		$from = "1980 Zanker Rd, San Jose, CA";
		$to = $address;

		$from = urlencode($from);
		$to = urlencode($to);

		$data = file_get_contents("http://maps.googleapis.com/maps/api/distancematrix/json?origins=$from&destinations=$to&language=en-EN&sensor=false");
		$data = json_decode($data);

		$time = 0;
		$distance = 0;

		foreach($data->rows[0]->elements as $road) {
		    $time += $road->duration->value;
		    $distance += $road->distance->value;
		}

		$distance = $distance / 1.6 / 1000;

		return array('distance' => $distance, 'duration' => $time);
	}
}

//end of main controller