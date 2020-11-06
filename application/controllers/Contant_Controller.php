<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Contant_Controller extends Public_Controller {
    /**
     * Constructor
     */
    function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('PagesModel');
    }
    
    function index($page_slug) 
    {
        if(empty($page_slug))
        {
           $this->session->set_flashdata('error', lang('invalid_url')); 
           redirect(base_url());
        }

        $page_data = $this->PagesModel->get_pages_by_slug($page_slug);

        if(empty($page_data))
        {
           $this->session->set_flashdata('error', lang('admin_invalid_id')); 
           redirect(base_url());
        }

        $page_title = $page_data->title.$this->settings->site_name;
        $this->set_title($page_title);

        $content_data = array('Page_message' => $page_title, 'page_title' => $page_title,'page_data' => $page_data);
        
        $data = $this->includes;
        $data['content'] = $this->load->view('content_page', $content_data, TRUE);
        
        $this->load->view($this->template, $data);
    }
}
