<?php defined('BASEPATH') OR exit('No direct script access allowed');
class History_Controller extends Public_Controller {

    /**
     * Constructor
     */
    function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('HomeModel');
        $this->load->model('TestModel');
        $this->add_css_theme('quiz_box.css');
        $this->add_js_theme('perfect-scrollbar.min.js');
        $this->add_css_theme('table-main.css');
        $this->add_css_theme('perfect-scrollbar.css');
    }
    
    function history($page_no=NULL) 
    {
        $session_quiz_id = 0;
        if($this->session->quiz_session)
        {
            $session_quiz_id = $this->session->quiz_session['quiz_data']['id'];
        }

        $user_id = isset($this->user['id']) ? $this->user['id'] : 0;

        if(empty($user_id))
        {
            $this->session->set_flashdata('error', lang('login_or_view_history'));
            redirect(base_url());
        }

        $my_quiz_history_count = $this->TestModel->my_quiz_history_count($user_id,$session_quiz_id);

        $this->load->library('pagination');

        $config['base_url'] = base_url('my/history');
        $config['total_rows'] = $my_quiz_history_count;
        $config['per_page'] = 10;
        $config['uri_segment'] = 3;
        $config['use_page_numbers'] = TRUE;
        $config['page_query_string'] = FALSE;
        $config['reuse_query_string'] = TRUE;
        $config['first_link'] = 'First';
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['attributes'] = ['class' => 'page-link'];
        $config['first_link'] = false;
        $config['last_link'] = false;
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';
        $config['prev_link'] = '&laquo';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '&raquo';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item active"><a href="#" class="page-link">';
        $config['cur_tag_close'] = '<span class="sr-only">(current)</span></a></li>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $this->pagination->initialize($config);
        $pro_per_page = $config['per_page'];
        $page = $this->uri->segment(3) > 0 ? (($this->uri->segment(3) - 1) * $pro_per_page) : $this->uri->segment(3);
        $page_links = $this->pagination->create_links();

        $my_quiz_history = $this->TestModel->my_quiz_history($user_id,$session_quiz_id, $pro_per_page, $page);
        $page_links = $this->pagination->create_links();

        $this->set_title(lang('quiz_history'), $this->settings->site_name);
        $content_data = array('Page_message' => lang('quiz_history'), 'page_title' => lang('quiz_history'),'my_quiz_history' => $my_quiz_history,'pagination'=>$page_links);

        $data = $this->includes;
        $data['content'] = $this->load->view('history', $content_data, TRUE);        
        $this->load->view($this->template, $data);
    }

    function leader_board($quiz_id = NULL) 
    {
        $session_quiz_id = 0;
        if($this->session->quiz_session)
        {
            $session_quiz_id = $this->session->quiz_session['quiz_data']['id'];
        }

        $user_id = isset($this->user['id']) ? $this->user['id'] : 0;
        $quiz_data = $this->TestModel->get_leader_board_quiz_by_id($quiz_id);

        if(empty($quiz_data))
        {
           redirect(base_url('404_override'));
        }

        $leader_board_quiz_history = $this->TestModel->leader_board_quiz_history($quiz_id, $session_quiz_id);

        $this->set_title(lang("quiz_leader_board"), $this->settings->site_name);
        $content_data = array('Page_message' => lang("quiz_leader_board"), 'page_title' => lang("quiz_leader_board"),'leader_board_quiz_history' => $leader_board_quiz_history, 'quiz_data' => $quiz_data);

        $data = $this->includes;
        $data['content'] = $this->load->view('leader_board', $content_data, TRUE);        
        $this->load->view($this->template, $data);
    }

    function category($category_slug = NULL)
    {
        $session_quiz_data = array();
        $session_quiz_question_data = array();

        if($this->session->quiz_session)
        {
            $get_quiz_session = $this->session->quiz_session;
            $session_quiz_data = $get_quiz_session['quiz_data'];
            $session_quiz_question_data = $get_quiz_session['quiz_question_data'];
        }

        $category_data = $this->HomeModel->get_category_by_slug($category_slug);
        if(empty($category_data))
        {
            return redirect(base_url("404_override"));
        }
        
        $this->add_css_theme('sweetalert.css');
        $this->add_js_theme('sweetalert-dev.js');
        $this->add_js_theme('quiz.js');
        $quiz_data = $this->HomeModel->get_quiz_by_category($category_data->id);

        $this->set_title($category_data->category_title, $this->settings->site_name);
        $content_data = array('Page_message' => lang('category_quiz'), 'page_title' => $category_data->category_title , 'category_data' => $category_data, 'quiz_data' => $quiz_data,'session_quiz_data' => $session_quiz_data, 'session_quiz_question_data' => $session_quiz_question_data);

        $data = $this->includes;
        $data['content'] = $this->load->view('quiz', $content_data, TRUE);        
        $this->load->view($this->template, $data);
    }
}