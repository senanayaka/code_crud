<?php

/*
  Document   : admin model
  Created on :2012-10-02
  Author     : parakrama
 */

class contacts_model extends CI_Model {

    function __construct() {
        parent::__construct();

        $this->load->database();
    }

    function get_contact_list($start, $per_page) {
        $query = $this->db->get('contacts_details', $per_page, $start);
        $data = $query->result();
        return $data;
    }

    function counts_record() {
        return $this->db->count_all("contacts_details");
    }
    
    function save_data($data){
        
        if(isset($data->contact_id) && $data->contact_id!=""){
             $dta = array(
                    'contact_name' => $data->contact_name ,
                    'contact_no' =>$data->contact_no ,
                    'email' => $data->email
                 );
            $this->db->where('contact_id', $data->contact_id);
            $this->db->update('contacts_details', $dta); 
            
        }else{
             $dta = array(
                    'contact_name' => $data->contact_name ,
                    'contact_no' =>$data->contact_no ,
                    'email' => $data->email
                 );
 
             $this->db->insert('contacts_details', $dta);

            
        }
       
        


    }
    
    
}
