<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Quiz_Controller extends Public_Controller {

    /**
     * Constructor
     */
    function __construct() 
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('HomeModel');
        $this->load->model('TestModel');
        $this->add_css_theme('quiz_box.css');
    }
    
    public function set_leader_bord_user_name()
    {
        $response['status'] = 'error';
        $response['msg'] = 'Invalid Response';
        if($this->input->post('inputValue'))
        {
            $response['status'] = 'success';
            $response['name'] = $this->input->post('inputValue');
            $response['msg'] = 'Thanks ! '.$this->input->post('inputValue');
            $this->session->set_userdata('leader_bord_user_name', $this->input->post('inputValue'));
        }
        echo json_encode($response);
    }

    function instruction($quiz_id)
    {
        $quiz_data = $this->TestModel->get_quiz_by_id($quiz_id);
        $leader_bord_user_name = $this->session->leader_bord_user_name;
        
        if(empty($leader_bord_user_name) && empty($this->user['id']))
        {
           $this->session->set_flashdata('error', lang('user_required')); 
           redirect(base_url(''));
        }

        if(empty($quiz_data))
        {
           $this->session->set_flashdata('error', lang('invalid_id')); 
           redirect(base_url('404_override'));
        }

        if($quiz_data->price > 0)
        {
            $quiz_last_paymetn_status = $this->Payment_model->get_quiz_last_paymetn_status($quiz_id);
            $quiz_last_paypal_status = $this->Payment_model->get_quiz_last_paypal_status($quiz_id);
        
            if(empty($quiz_last_paymetn_status) && empty($quiz_last_paypal_status) )
            {
                return redirect(base_url("quiz-pay/payment-mode/$quiz_id"));
            }
        }

        if($this->session->quiz_session)
        {
            $session_quiz_id = $this->session->quiz_session['quiz_data']['id'];
            return redirect(base_url("test/$session_quiz_id/1"));
        }

        $this->set_title(lang('front_quiz_instruction'), $this->settings->site_name);
        $content_data = array('Page_message' => lang('front_quiz_instruction'), 'page_title' => lang('front_quiz_instruction'),'quiz_id' => $quiz_id,'quiz_data' => $quiz_data);

        $data = $this->includes;
        $data['content'] = $this->load->view('instruction', $content_data, TRUE);
        $this->load->view($this->template, $data);
    }

    function category($category_slug=NULL, $page_no=NULL)
    {
        
        $quiz_filter = $this->input->get('most') ? $this->input->get('most') : '';
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


        $quiz_data_array = $this->HomeModel->get_quiz_by_category($category_data->id);
        $count_quiz = count($quiz_data_array);
        $this->load->library('pagination');

        $config['base_url'] = base_url('category/') . $category_slug;
        $config['total_rows'] = $count_quiz;
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

        $filter_by = 'id';
        if($quiz_filter=='recent')
        {
            $filter_by = 'added';
        }
        elseif($quiz_filter=='liked')
        {
            $filter_by = 'total_like';
        }
        elseif($quiz_filter=='attended')
        {
            $filter_by ='total_view';
        }
        
        $quiz_data = $this->HomeModel->get_category_quiz_per_page($category_data->id, $pro_per_page, $page, $filter_by);

        $this->set_title($category_data->category_title, $this->settings->site_name);

        $content_data = array('Page_message' =>  $category_data->category_title, 'page_title' => $category_data->category_title , 'category_data' => $category_data, 'quiz_data' => $quiz_data,'session_quiz_data' => $session_quiz_data, 'session_quiz_question_data' => $session_quiz_question_data, 'pagination' => $page_links);

        $data = $this->includes;
        $data['content'] = $this->load->view('quiz', $content_data, TRUE);
        $this->load->view($this->template, $data);
    }

    function like_quiz()
    {
        $response = [];    
        if($this->session->userdata('logged_in')) 
        {
            $save_quiz_like['quiz_id'] = $_POST['quiz_id'];
            $save_quiz_like['user_id'] = $this->session->userdata('logged_in')['id'];
            $inserted_data = $this->HomeModel->insert_quiz_like($save_quiz_like);
            $get_count_of_likes = $this->HomeModel->get_count_likes_through_quiz_id($_POST['quiz_id']);
            
            if($inserted_data)
            {
                $response['success'] = $get_count_of_likes;
            }
            else
            {
                $response['error'] = 'unsuccessfull';
            }
        }
        else
        {
            $response['status'] = 'redirect';
        }
        echo json_encode($response);
    }

    function like_quiz_delete()
    {
        $response = [];
        if($this->session->userdata('logged_in')) 
        {
            $quiz_id = $_POST['quiz_id'];
            $user_id = isset($this->user['id']) ? $this->user['id'] : 0;
            
            $delete_data = $this->HomeModel->delete_like_quiz_through_quizid($quiz_id, $user_id);
            $get_count_of_likes = $this->HomeModel->get_count_likes_through_quiz_id($_POST['quiz_id']);
            
            if($delete_data)
            {
                $response['success'] = $get_count_of_likes;
            }
            else
            {
                $response['error'] = 'unsuccessfull';
            }
        }
        else
        {
            $response['status'] = 'redirect';
        }
        echo json_encode($response);
    }

    function quiz_detail($quiz_id = false)
    {
        $quiz_data = $this->HomeModel->get_quiz_by_id($quiz_id);

        $this->set_title(lang('front_quiz_detail'), $this->settings->site_name);
        
        $this->add_js_theme('quiz_detail.js');
        $content_data = array('page_title' => lang('front_quiz_detail'),'quiz_id' => $quiz_id, 'quiz_data' => $quiz_data,);

        $data = $this->includes;
        $data['content'] = $this->load->view('quiz_detail', $content_data, TRUE);
        $this->load->view($this->template, $data);      
    } 

    function submit_rating()
    {
        $this->form_validation->set_rules('reviewstar','Review Star', 'required|min_length[1]|max_length[5]');
        $save_rating_data = array();
        if($this->session->userdata('logged_in')) 
        {
           if($this->input->post('save'))
           {
                if ($this->form_validation->run() == TRUE) 
                {
                    $save_rating_data['user_id'] = $this->session->userdata('logged_in')['id'];
                    $save_rating_data['quiz_id'] = $this->input->post('quizid');
                    $save_rating_data['review_content'] = $this->input->post('ratingcontent');
                    $save_rating_data['rating'] = $this->input->post('reviewstar');
                    $inserted_data = $this->HomeModel->insert_rating_data($save_rating_data);
                    if($inserted_data)
                    {
                        $this->session->set_flashdata('message', lang('ratting_added_successfully'));
                    }
                    else
                    {
                        $this->session->set_flashdata('error', lang('eroor_during_ratting_added')); 
                    }
                }
                else
                {
                    $fielderror = $this->form_validation->error_array();
                    
                    $this->session->set_flashdata('error', implode(" ", $fielderror)); 
                    redirect($_SERVER['HTTP_REFERER']);                               
                }
           }         
        }   
        else 
        {
           $this->session->set_flashdata('error', lang('user_required')); 
           redirect(base_url(""));
        } 
        redirect($_SERVER['HTTP_REFERER']);
    }
    
}