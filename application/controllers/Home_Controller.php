<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Home_Controller extends Public_Controller {
    /**
     * Constructor
     */
    function __construct() {
        parent::__construct();
        $this->add_css_theme('sweetalert.css');
        $this->add_js_theme('sweetalert-dev.js'); 
        $this->add_css_theme('set2.css');
        $this->add_css_theme('slick.min.css');
        $this->add_css_theme('slick-theme.min.css');
        $this->add_js_theme('slick.min.js');
        $this->add_js_theme('home.js');
        $this->load->library('form_validation');
        $this->load->model('HomeModel');
        $this->add_css_theme('quiz_box.css');

    }
    
    function index() 
    {
        $this->set_title(sprintf(lang('home'), $this->settings->site_name));

        $category_data = $this->HomeModel->get_category();
        $testimonial_data = $this->HomeModel->get_testmonials();
        $sponser_data = $this->HomeModel->get_sponsers();

        $session_quiz_data = array();
        $session_quiz_question_data = array();

        if($this->session->quiz_session)
        {
            $get_quiz_session = $this->session->quiz_session;
            $session_quiz_data = $get_quiz_session['quiz_data'];
            $session_quiz_question_data = $get_quiz_session['quiz_question_data'];
        }
         
        $latest_quiz_data = $this->HomeModel->get_latest_quiz(4,'quizes.added');

        $popular_quiz_data = $this->HomeModel->get_latest_quiz(4,'total_view');


        $content_data = array('Page_message' => lang('welcome_to_online_quiz'), 'page_title' => lang('home'),'category_data' => $category_data,'testimonial_data'=>$testimonial_data,'latest_quiz_data' => $latest_quiz_data, 'popular_quiz_data' => $popular_quiz_data,'session_quiz_data' => $session_quiz_data, 'session_quiz_question_data' => $session_quiz_question_data,'sponser_data'=>$sponser_data);

        $data = $this->includes;
        $data['content'] = $this->load->view('home', $content_data, TRUE);

        $this->load->view($this->template, $data);
    }

}