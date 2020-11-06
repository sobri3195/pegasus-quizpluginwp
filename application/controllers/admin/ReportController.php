<?php defined('BASEPATH') OR exit('No direct script access allowed');
class ReportController extends Admin_Controller {

    function __construct() {
    	parent::__construct();
        $this->add_css_theme('all.css');
     
        $this->add_css_theme('bootstrap4-toggle.min.css');  
     
        $this->add_js_theme('bootstrap4-toggle.min.js');

     
        $this->add_css_theme('summernote.css');
     
     
        $this->add_js_theme('summernote.min.js');

        $this->add_css_theme('sweetalert.css');
        $this->add_js_theme('sweetalert-dev.js');
        $this->add_external_js(base_url("/{$this->settings->themes_folder}/quizzy/js/Chart.min.js"));	
        $this->add_js_theme('report.js');

        $this->load->model('ReportModel');
        $this->load->model('QuizModel');

        $this->load->helper('url');
        
        // set constants
        define('REFERRER', "referrer");
        define('THIS_URL', base_url('admin/quiz'));
        define('DEFAULT_LIMIT', 10);
        define('DEFAULT_OFFSET', 0);
        define('DEFAULT_SORT', "id");
        define('DEFAULT_DIR', "asc");
    }    

    function index($quiz_id = NULL) {
    	$quiz_data = $this->QuizModel->get_quiz_by_id($quiz_id);

        $this->set_title(lang('admin_report').": ".$quiz_data->title);
        $data = $this->includes;
        $content_data = array('quiz_id' => $quiz_id,);
        $data['content'] = $this->load->view('admin/report/list', $content_data, TRUE);
        $this->load->view($this->template, $data);
    }

    function quiz_history_list()
    {	
    	$quiz_id = $_POST['quiz_id'];
    	$data = array();
        $list = $this->ReportModel->get_quiz_history($quiz_id); 
        
        $no = $_POST['start'];
        foreach ($list as $quiz_history) {

        	$total_attemp = $quiz_history->total_attemp - $quiz_history->correct;
            $total_attemp = $total_attemp ? $total_attemp : 0;
            $wrong = $total_attemp - $quiz_history->correct;
            $date_of_quiz = date( "d M Y", strtotime($quiz_history->started));
            $button = '<a href="' . base_url("admin/report/summary/". $quiz_history->id) . '" data-toggle="tooltip"  title="'.lang("quiz_summary").'" class="btn btn-primary btn-action mr-1"><i class="fas fa-eye"></i></a>';

            $no++;
            $row = array();
            $row[] = $no;
            $row[] = xss_clean($quiz_history->first_name. " ".$quiz_history->last_name);
            $row[] = xss_clean($quiz_history->questions);
            $row[] = xss_clean($total_attemp); 
            $row[] = xss_clean($quiz_history->correct); 
            $row[] = xss_clean($wrong); 
            $row[] = xss_clean($date_of_quiz); 
            $row[] = $button;
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'], 
            "recordsTotal" => $this->ReportModel->count_all($quiz_id), 
            "recordsFiltered" => $this->ReportModel->count_filtered($quiz_id), 
            "data" => $data
        );

        //output to json format
        echo json_encode($output);
    }

    function summary($participant_id = NULL)
    {

    	$participant_data = $this->ReportModel->get_participant_by_id($participant_id);

        if(empty($participant_data))
        {
            return redirect(base_url('404_override'));
        }
        $user_question_data = $this->ReportModel->get_user_question_by_participant_id($participant_id);
        // print_r($user_question_data);die;

        if(empty($user_question_data))
        {
            $this->session->set_flashdata('error', 'Test Not complet ');
            return redirect(base_url('admin'));
        }

        $quiz_id = $participant_data['quiz_id'];
        $quiz_data = $this->ReportModel->get_quiz_by_id($quiz_id);
        
        $correct = $participant_data['correct'] ? $participant_data['correct'] : 0;
        $total_question = $participant_data['questions'] ? $participant_data['questions'] : 0;
        $total_attemp = $participant_data['total_attemp'] ? $participant_data['total_attemp'] : 0;

 		$this->set_title(lang('test_result').": ".$quiz_data->title);
        $data = $this->includes;
        $content_data = array('Page_message' => lang('test_result'), 'page_title' => lang('test_result'),'correct' => $correct,'total_question' => $total_question, 'total_attemp' => $total_attemp, 'quiz_data' => $quiz_data, 'participant_data' => $participant_data, 'user_question_data' => $user_question_data );
        $data['content'] = $this->load->view('admin/report/summary', $content_data, TRUE);
        $this->load->view($this->template, $data);   	
    }
}