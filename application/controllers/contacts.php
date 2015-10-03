<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class contacts extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('contacts_model');
        $this->load->library('doctrine');
        $em = $this->doctrine->em;
    }

    public function index() {
        $this->load->view('contact_list_view');
    }

    function getlist() {
       $page = $this->uri->segment(3, 0);
       $page -= 1;
       $per_page = 5; 
       $start = $page * $per_page;
       $data['records'] = $this->contacts_model->get_contact_list($start,$per_page);
       $data['counts_all'] = $this->contacts_model->counts_record();
        echo json_encode($data);
    }

    
    function save_data(){
       $data = json_decode(file_get_contents("php://input"));
       $this->contacts_model->save_data($data);
       $data_array['counts_all'] = $this->contacts_model->counts_record();
       echo json_encode($data);
        
    }
    
  
     function generate_classes(){     
        
    $this->em->getConfiguration()->setMetadataDriverImpl(new DatabaseDriver($this->em->getConnection()->getSchemaManager()));

    $cmf = new DisconnectedClassMetadataFactory();
    $cmf->setEntityManager($this->em);
    $metadata = $cmf->getAllMetadata();     
    $generator = new EntityGenerator();
    
    $generator->setUpdateEntityIfExists(true);
    $generator->setGenerateStubMethods(true);
    $generator->setGenerateAnnotations(true);
    $generator->generate($metadata, APPPATH."models/Entities");
    
  }
    
    
    
    
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */