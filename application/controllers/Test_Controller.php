<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Test_Controller extends Public_Controller {

    /**
     * Constructor
     */
    function __construct() 
    {
        parent::__construct();
        $this->load->model('TestModel');
        $this->add_js_theme('Chart.min.js');
        $this->add_js_theme('test.js');
        $this->add_js_theme('jquery.simple.timer.js');
        $this->add_js_theme('dojo.js');
        $this->add_js_theme('notify.min.js');
        $this->add_css_theme('sweetalert.css');
        $this->add_js_theme('sweetalert-dev.js');
        $this->load->library('form_validation'); 
        $this->load->library('session');
        $this->add_js_theme('perfect-scrollbar.min.js');
        $this->add_css_theme('table-main.css');
        $this->add_css_theme('perfect-scrollbar.css');
    }

    function set_test_session($quiz_id = NULL) 
    {
        $leader_bord_user_name = $this->session->leader_bord_user_name;

        if(empty($leader_bord_user_name) && empty($this->user['id']))
        {
           $this->session->set_flashdata('error', lang('user_required')); 
           redirect(base_url(''));
        }

        $quiz_data = $this->TestModel->get_quiz_by_id($quiz_id);

        if(empty($quiz_data))
        {
           $this->session->set_flashdata('error', lang('invalid_id')); 
           redirect(base_url('404_override'));
        }

        if($quiz_data->price > 0)
        {
            $quiz_last_paymetn_status = $this->Payment_model->get_quiz_last_paymetn_status($quiz_id);
            $quiz_last_paypal_status = $this->Payment_model->get_quiz_last_paypal_status($quiz_id);
            if(empty($quiz_last_paymetn_status) && empty($quiz_last_paypal_status))
            {
                return redirect(base_url("quiz-pay/payment-mode/$quiz_id"));
            }
        }

        $quiz_data = json_decode(json_encode($quiz_data), true);
        $is_random = $quiz_data['is_random'];
        $number_questions = $quiz_data['number_questions'];
        if($is_random == 1)
        {
            $quiz_question_data = $this->TestModel->get_random_question_by_quiz_id($quiz_id,$number_questions);
        }
        else
        {
            $quiz_question_data = $this->TestModel->get_question_by_quiz_id($quiz_id,$number_questions);
        }
        $quiz_question_data = json_decode(json_encode($quiz_question_data), true);
        $user_id = $this->user['id'] ? $this->user['id'] : NULL;           
        if(empty($quiz_question_data))
        {
            $this->session->set_flashdata('error', lang('there_no_question_in_this_quiz'));
            return redirect(base_url());
        }

        if($this->session->quiz_session)
        { 
            $session_quiz_id = $this->session->quiz_session['quiz_data']['id'];
            return redirect(base_url("test/$session_quiz_id/1"));
        }
        else
        {
            $start_time = date('Y-m-d H:i:s');
            $start_time_data = date('Y-m-d H:i:s');
            $end_time = date('Y-m-d H:i:s', strtotime('+30 minutes'));
            $user_id = $this->user['id'] ? $this->user['id'] : NULL;
            $participants_content = array(); 
            $participants_content['user_id'] = $user_id;
            $participants_content['guest_name'] = $this->session->leader_bord_user_name;
            $participants_content['quiz_id'] = $quiz_data['id'];
            $participants_content['questions'] = COUNT($quiz_question_data);
            $participants_content['correct'] = 0;
            $participants_content['started'] = $start_time;
            $participants_content['completed'] = NULL;

            $participant_id = $this->TestModel->insert_participant($participants_content); 
            $participants_content['end_time'] = $end_time;               
            $participants_content['participant_id'] = $participant_id;               
            $quiz_session = array(
                                'quiz_data'=>$quiz_data,
                                'quiz_question_data'=>$quiz_question_data,
                                'participants_content'=>$participants_content,
                                );

            $this->session->set_userdata('quiz_session', $quiz_session);
        }

        //save view detail
        if($this->session->quiz_session['quiz_data'])
        {
            $savedata = array();
            $savedata['quiz_id'] = $this->session->quiz_session['quiz_data']['id'];
            $savedata['ip_address'] = $this->input->ip_address();
            $savedata['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
            $inserted_data = $this->TestModel->save_quiz_view_data($savedata);
        }

        return redirect(base_url("test/$quiz_id/1"));
    }

    function test($quiz_id = NULL, $question_id = NULL) 
    {
        $quiz_data = $this->TestModel->get_quiz_by_id($quiz_id);
        if(empty($quiz_data))
        {
           $this->session->set_flashdata('error', lang('invalid_id')); 
           redirect(base_url('404_override'));
        }

        if($quiz_data->price > 0)
        {
            $quiz_last_paymetn_status = $this->Payment_model->get_quiz_last_paymetn_status($quiz_id);
            $quiz_last_paypal_status = $this->Payment_model->get_quiz_last_paypal_status($quiz_id);
            if(empty($quiz_last_paymetn_status) && empty($quiz_last_paypal_status))
            {
                return redirect(base_url("quiz-pay/payment-mode/$quiz_id"));
            }
        }

        if(empty($this->session->quiz_session))
        {
            $this->session->set_flashdata('error', lang('schedule_quiz_first'));
            return redirect(base_url("Test_Controller/set_test_session/$quiz_id"));
        }

        $total_question = count($this->session->quiz_session['quiz_question_data']);

        if(empty($question_id) OR $total_question < 1)
        {
            return redirect(base_url('404_override'));
        }
        
        if($total_question < $question_id)
        {
            return redirect(base_url('404_override'));
        }

        $tes_over = $this->session->quiz_session['participants_content']['end_time'];

        if($tes_over < date('Y-m-d H:i:s'))
        {
            return redirect(base_url('Test_Controller/test_result'));
        } 

        $added_time = $this->session->quiz_session['participants_content']['started']; 

        $dt = new DateTime($added_time);
        $minutes_to_add = $this->session->quiz_session['quiz_data']['duration_min'];
        $time = new DateTime($added_time);
        $time->add(new DateInterval('PT' . $minutes_to_add . 'M'));
        $expire_time = $this->session->quiz_session['participants_content']['end_time'];

        $expire_time = strtotime($expire_time);
        $current_time = strtotime(date('Y-m-d H:i:s'));
        $left_time = $expire_time - $current_time;

        $answer = $this->input->POST('answer') ? $this->input->POST('answer') : array();
        $q_number = $question_id-1;
        $question_url_id = $question_id > 1 ? $question_id-1 : $question_id;
        
        $next_question = $question_id < $total_question ? $question_id+1 : $question_id;

        if($this->input->POST('mark_or_next_quiz'))
        {
            $this->set_question_status($q_number,'mark_or_next_quiz',$answer);
            return redirect(base_url("test/$quiz_id/$next_question"));
        }
        if($this->input->POST('save_or_next_quiz'))
        {
            $this->set_question_status($q_number,'save_or_next_quiz',$answer);
            return redirect(base_url("test/$quiz_id/$next_question"));
        }
        
        if($this->input->POST('submit_test'))
        {
             return redirect(base_url("result/$quiz_id"));
        }
        elseif ($this->input->POST('preview_quiz')) 
        {
            $this->set_question_status($q_number,'preview_quiz');
            return redirect(base_url("test/$quiz_id/$question_url_id"));

        }
        elseif ($this->input->POST('next_quiz')) 
        {
            $this->set_question_status($q_number,'next_quiz');
            return redirect(base_url("test/$quiz_id/$next_question"));

        }
        elseif ($this->input->POST('submit_quiz')) 
        {
             return redirect(base_url("result/$quiz_id"));
        }
        else
        {   
            $this->set_question_status($q_number,'visited');
        }

        $running_quiz_question_data = $this->session->quiz_session['quiz_question_data'];
        $runn_total_question = count($this->session->quiz_session['quiz_question_data']);
        $runn_attemp = 0;
        $runn_mark = 0;
        $runn_answered = 0;
        $runn_visited = 0;

        foreach ($running_quiz_question_data as $runn_quiz_question_array) 
        {
            if(isset($runn_quiz_question_array['answer']))
            {   
                $runn_attemp++;
            }

            if(isset($runn_quiz_question_array['status']) && $runn_quiz_question_array['status'] == 'answer')
            {   
                $runn_answered++;
            }

            if(isset($runn_quiz_question_array['status']) && $runn_quiz_question_array['status'] == 'mark')
            {   
                $runn_mark++;
            }

            if(isset($runn_quiz_question_array['status']) && $runn_quiz_question_array['status'] == 'visited')
            {   
                $runn_visited++;
            }
        }

        $runn_not_visited = $runn_total_question - $runn_attemp - $runn_visited; 
        $this->set_title("Test", $this->settings->site_name);

        $get_quiz_session = $this->session->quiz_session;
        $quiz_data = $get_quiz_session['quiz_data'];
        $quiz_question_data = $get_quiz_session['quiz_question_data'];
        $question_data = $get_quiz_session['quiz_question_data'][$q_number];

        $content_data = array('Page_message' => $quiz_data['title'], 'page_title' => $quiz_data['title'], 'quiz_data' => $quiz_data, 'quiz_question_data' => $quiz_question_data, 'question_data' => $question_data, 'left_time' => $left_time, 'question_url_id' => $question_url_id, 'question_id' => $question_id,'runn_attemp'=>$runn_attemp, 'runn_mark' => $runn_mark, 'runn_answered' => $runn_answered, 'runn_visited' => $runn_visited, 'runn_not_visited' => $runn_not_visited);

        $data = $this->includes;
        $data['content'] = $this->load->view('test', $content_data, TRUE);

        $this->load->view($this->template, $data);
    }

    public function set_question_status($q_number = NULL, $action = NULL, $answer = NULL)
    {
        $get_quiz_session = $this->session->quiz_session;
        if(empty($get_quiz_session['quiz_question_data'][$q_number]['status']) && $action == 'visited')
        {
            $get_quiz_session['quiz_question_data'][$q_number]['status'] = 'visited';
            $quiz_session = $get_quiz_session;
            $this->session->set_userdata('quiz_session', $quiz_session);
        }

        if(empty($get_quiz_session['quiz_question_data'][$q_number]['status']) && $action == 'preview_quiz')
        {
            $get_quiz_session['quiz_question_data'][$q_number]['status'] = 'preview_quiz';
            $quiz_session = $get_quiz_session;
            $this->session->set_userdata('quiz_session', $quiz_session);
        }

        if(empty($get_quiz_session['quiz_question_data'][$q_number]['status']) && $action == 'next_quiz')
        {
            $get_quiz_session['quiz_question_data'][$q_number]['status'] = 'visited';
            $quiz_session = $get_quiz_session;
            $this->session->set_userdata('quiz_session', $quiz_session);
        }

        if($action == 'mark_or_next_quiz')
        {
            $get_quiz_session['quiz_question_data'][$q_number]['status'] = 'mark';
            $get_quiz_session['quiz_question_data'][$q_number]['mark'] = 1;
            $get_quiz_session['quiz_question_data'][$q_number]['answer'] = $answer;
            $quiz_session = $get_quiz_session;
            $this->session->set_userdata('quiz_session', $quiz_session);
        }

        if($action == 'save_or_next_quiz')
        {
            $get_quiz_session['quiz_question_data'][$q_number]['status'] = 'answer';
            $get_quiz_session['quiz_question_data'][$q_number]['mark'] = 0;
            $get_quiz_session['quiz_question_data'][$q_number]['answer'] = $answer;
            $quiz_session = $get_quiz_session;
            $this->session->set_userdata('quiz_session', $quiz_session);
        }
        return TRUE;
    }

    public function test_result($quiz_id = NULL)
    {
        $user_id = $this->user['id'] ? $this->user['id'] : NULL;
        $right_answer = 0;
        $total_question =0;
        $correct = 0;
        $correct_ans = 0;
        $total_attemp = 0;

        if($this->session->quiz_session)
        { 
            $quiz_question_data = $this->session->quiz_session['quiz_question_data'];
            $total_question = count($this->session->quiz_session['quiz_question_data']);
            $participant_id = $this->session->quiz_session['participants_content']['participant_id'];

            foreach ($quiz_question_data as $quiz_question_array) 
            {
                $answer = isset($quiz_question_array['answer']) ? $quiz_question_array['answer'] : array();
                $question_id = isset($quiz_question_array['id']) ? $quiz_question_array['id'] : NULL;
                $question_title = isset($quiz_question_array['title']) ? $quiz_question_array['title'] : NULL;
                $choices = isset($quiz_question_array['choices']) ? $quiz_question_array['choices'] : '[""]';
                $correct_choice = isset($quiz_question_array['correct_choice']) ? $quiz_question_array['correct_choice'] : '[""]';
                $correct_choice = json_decode($correct_choice);
                $is_correct = 0;
                $correct_ans = 0; 

                foreach ($answer as $key => $answer_value) 
                {
                    foreach ($correct_choice as  $valueee) 
                    {
                        if($correct_ans != 1)
                        {
                           $correct_ans = $answer_value == $valueee ? 1 : 0;
                        }
                    }
                }

                if ($correct_ans == 1)  
                {
                    $is_correct = 1;
                    $right_answer++;
                }
                if($answer)
                {
                    $total_attemp++;
                }

                $user_questions = array();
                $user_questions['user_id'] = $user_id;
                $user_questions['participant_id'] = $participant_id;
                $user_questions['question_id'] = $question_id;
                $user_questions['question'] = $question_title;
                $user_questions['given_answer'] = json_encode($answer);
                $user_questions['is_correct'] = $is_correct;
                $user_questions['choices'] = $choices;
                $user_questions['correct_choice'] = json_encode($correct_choice);
                $user_questions['timestamp'] = date('Y-m-d H:i:s');

                $user_question_id = $this->TestModel->insert_user_questions($user_questions); 
            }

            $participants_content = array();
            $participants_content['correct'] = $right_answer;
            $participants_content['total_attemp'] = $total_attemp;
            $participants_content['completed'] = date('Y-m-d H:i:s');

            $this->session->unset_userdata('quiz_session');
            $this->session->unset_userdata('leader_bord_user_name');

            $update_participant = $this->TestModel->update_participant($quiz_id, $participants_content,$participant_id);
            $correct = $right_answer;

            return redirect(base_url('my/test/summary/'.$participant_id));
        }
        else
        {
            return redirect(base_url('my/history'));
        }

        $this->set_title(lang('test_result'), $this->settings->site_name);

        $content_data = array('Page_message' => lang('Ttest_result'), 'page_title' => lang('test_result'),'correct' => $correct,'total_question' => $total_question, 'total_attemp' => $total_attemp);

        $data = $this->includes;
        $data['content'] = $this->load->view('result', $content_data, TRUE);
        $this->load->view($this->template, $data);
    }

    function test_submit_request()
    {
        if($this->session->quiz_session)
        {
            $quiz_question_data = $this->session->quiz_session['quiz_question_data'];
            $total_question = count($this->session->quiz_session['quiz_question_data']);
            $attemp = 0;

            foreach ($quiz_question_data as $quiz_question_array) 
            {
                if(isset($quiz_question_array['answer']))
                {
                    $attemp++;
                }
            }

            $respons['attemp'] = $attemp;
            $respons['status'] = 'success';
            $respons['msg'] = 'Test Submit Request !';
        }
        else
        {
            $respons['status'] = 'error';
            $respons['msg'] = 'Invalid Test Submit Request !';
        } 

        echo json_encode($respons);
        return json_encode($respons);
    }

    function test_summary($participant_id)
    {
        $participant_data = $this->TestModel->get_participant_by_id($participant_id);
        if(empty($participant_data))
        {
            return redirect(base_url('404_override'));
        }
        $user_question_data = $this->TestModel->get_user_question_by_participant_id($participant_id);

        if(empty($user_question_data))
        {
            $this->session->set_flashdata('error', 'Test Not complet ');
            return redirect(base_url());
        }

        $quiz_id = $participant_data['quiz_id'];
        $quiz_data = $this->TestModel->get_quiz_by_id($quiz_id);

        $correct = $participant_data['correct'] ? $participant_data['correct'] : 0;
        $total_question = $participant_data['questions'] ? $participant_data['questions'] : 0;
        $total_attemp = $participant_data['total_attemp'] ? $participant_data['total_attemp'] : 0;

        $this->set_title(lang('test_result'), $this->settings->site_name);

        $content_data = array('Page_message' => lang('test_result'), 'page_title' => lang('test_result'),'correct' => $correct,'total_question' => $total_question, 'total_attemp' => $total_attemp, 'quiz_data' => $quiz_data, 'participant_data' => $participant_data, 'user_question_data' => $user_question_data );

        $data = $this->includes;
        $data['content'] = $this->load->view('result', $content_data, TRUE);

        $this->load->view($this->template, $data);
    }

}
