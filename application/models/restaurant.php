<?php

class Restaurant extends CI_Model {
     
    // Users table
     function get_all_restaurants()
     {
         return $this->db->query("SELECT * FROM restaurants ORDER BY id DESC")->result_array();
     }
     
     function get_restaurant_by_id($restaurant_id)
     {
         return $this->db->query("SELECT * FROM restaurants WHERE id = ?", array($restaurant_id))->row_array();
     }
     
     function add_restaurant($info)
     {
         $query = "INSERT INTO restaurants (name, address, latitude, longitude, distance, duration, cuisine, place_id) VALUES (?,?,?,?,?,?,?,?)";
         $values = array($info['name'],
                         $info['address'],
                         $info['lat'],
                         $info['long'],
                         $info['distance'],
                         $info['duration'],
                         $info['cuisine'],
                         $info['place_id']); 
         return $this->db->query($query, $values);
     }

}

?>