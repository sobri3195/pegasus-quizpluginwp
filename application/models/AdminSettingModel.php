<?php defined('BASEPATH') OR exit('No direct script access allowed');
class AdminSettingModel extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    function get_admin_setting_by_field_name($field_name) {
        return $this->db->where('name', $field_name)->get('settings')->row();
    }
}
