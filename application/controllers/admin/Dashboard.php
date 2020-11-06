<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Dashboard extends Admin_Controller {
    /**
     * Constructor
     */
    function __construct() {
        parent::__construct();
        $this->load->model('DashboardModel');
    }
    /**
     * Dashboard
     */
    function index() {
        // setup page header data
        $this->set_title(lang('admin_dashboard'));
        $data = $this->includes;
        $data['products'] = 400;
        $data['market'] = 30;
        $data['brands'] = 20;
        $data['category'] = $this->DashboardModel->categories_count();
        $data['users'] = $this->DashboardModel->user_count();
        $data['pages'] = $this->DashboardModel->pages_count();
        $data['quiz'] = $this->DashboardModel->quiz_count();
        $data['question'] = $this->DashboardModel->question_count();
        $data['langues_count'] = $this->DashboardModel->langues_count();
        // load views

        $data['content'] = $this->load->view('admin/dashboard', $data, TRUE);
        $this->load->view($this->template, $data);
    }
}
