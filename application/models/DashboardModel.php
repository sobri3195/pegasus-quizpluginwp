<?php defined('BASEPATH') OR exit('No direct script access allowed');
class DashboardModel extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    function categories_count() {
        return $this->db->select('count(*) as count')->get('category')->row();
    }
    function user_count() {
        return $this->db->select('count(*) as count')->get('users')->row();
    }
    function pages_count() {
        return $this->db->select('count(*) as count')->get('pages')->row();
    }
    function quiz_count() {
        return $this->db->select('count(*) as count')->get('quizes')->row();
    }

    function question_count() {
        return $this->db->select('count(*) as count')->get('questions')->row();
    }
    function langues_count() {
        return $this->db->select('count(*) as count')->get('language')->row();
    }
}
