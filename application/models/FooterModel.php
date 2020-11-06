<?php defined('BASEPATH') OR exit('No direct script access allowed');
class FooterModel extends CI_Model 
{
    
    public function __construct() 
    {
        parent::__construct();
        $this->load->database();
    }

    function insert_fotter_sections($data) 
    {
        return $this->db->insert_batch('footer_sections', $data);
    }

    function get_footer_section($section_no)
    {
        return $this->db->where('section_number',$section_no)->order_by("section_number", "asc")->get('footer_sections')->result();
    }

    function update_footer_section($section_no, $data) 
    {
        $this->db->set($data)->where('section_number', $section_no)->update('footer_sections');
        return $this->db->affected_rows();
    }

    function delete_fotter() 
    {
        return $this->db->truncate('footer_sections');
    }

}
