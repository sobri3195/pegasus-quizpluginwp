<?php defined('BASEPATH') OR exit('No direct script access allowed');
class QuestionController extends Admin_Controller {
    function __construct() {
        parent::__construct();
        $this->add_css_theme('all.css');
        $this->add_css_theme('dropzone.css');
        $this->add_css_theme('bootstrap4-toggle.min.css');
        $this->add_js_theme('dropzone.js');
        $this->add_js_theme('bootstrap4-toggle.min.js');

        $this->add_css_theme('select2.min.css');
        $this->add_css_theme('summernote.css');
        $this->add_js_theme('plugin/taginput/bootstrap-tagsinput.min.js');
        $this->add_css_theme('plugin/taginput/bootstrap-tagsinput.css');
        $this->add_js_theme('summernote.min.js');

        $this->add_css_theme('sweetalert.css');
        $this->add_js_theme('sweetalert-dev.js');
        $this->add_js_theme('question.js');

        $this->load->model('QuestionModel');
        $this->load->model('QuizModel');
        $this->load->library('form_validation');
        $this->load->helper("My_custom_field_helper");
        $this->load->helper('url');
        $this->load->library('resize_image');
        // set constants
        define('REFERRER', "referrer");
        define('THIS_URL', base_url('admin/questions'));
        define('DEFAULT_LIMIT', 10);
        define('DEFAULT_OFFSET', 0);
        define('DEFAULT_SORT', "id");
        define('DEFAULT_DIR', "asc");
    }
    function index() {
        $this->set_title(lang('admin_questions_list'));
        $data = $this->includes;
        $content_data = array();
        $data['content'] = $this->load->view('admin/questions/list', $content_data, TRUE);
        $this->load->view($this->template, $data);
    }

    function add($quiz_id = false) 
    {

        $quiz_data = $this->QuizModel->get_quiz_by_id($quiz_id);
        $this->form_validation->set_rules('title', 'Title', 'required|trim|is_unique[questions.title]');
        $this->form_validation->set_rules('choices[]', 'Choices', 'required|trim');

        $choices_array = $this->input->post('choices') ? $this->input->post('choices') : array();
        $arr_is_correct = $this->input->post('is_correct') ? $this->input->post('is_correct') : array();
        $count_of_option = count($choices_array);
        $no_of_option = $count_of_option > 0 ? $count_of_option - 1 : 0;

        if ($this->form_validation->run() == false) 
        {
            $this->form_validation->error_array();
        } 
        else 
        {
            action_not_permitted();

            $user_id = $this->user['id'] ? $this->user['id'] : NULL;
            $is_multiple = $this->input->post('is_multiple',TRUE);
            $is_multiple = $is_multiple ? 1 : 0;
            $question_image = NULL; 

            if(isset($_FILES['image']['name']) && $_FILES['image']['name'])
            {
                $question_image_upload = $this->do_upload_image('image');

                if($question_image_upload['status'] == 'error')
                {
                    $this->session->set_flashdata('error', lang('invalid_file_formate'));
                    $question_image = NULL;
                }
                else
                {
                    $question_image = $question_image_upload['upload_data']['file_name'];
                }
            }

            $correct_choice = array();

            foreach ($choices_array as $key => $option_value) 
            {
                if(isset($arr_is_correct[$key]) && $arr_is_correct[$key])
                {
                    $correct_choice[$key] = $option_value;
                }
            }

            $question_content = array();
            $question_content['quiz_id'] = $quiz_id;
            $question_content['title'] = $this->input->post('title',TRUE);
            $question_content['is_multiple'] = $is_multiple;
            $question_content['choices'] = json_encode($choices_array);
            $question_content['correct_choice'] = json_encode($correct_choice);
            $question_content['image'] = $question_image;
            $question_content['added'] =  date('Y-m-d H:i:s');

            $question_id = $this->QuestionModel->insert_question($question_content);

            if($question_id)
            {
                $this->session->set_flashdata('message', lang('admin_record_added_successfully'));  
            }
            else
            {
                $this->session->set_flashdata('error', lang('admin_error_adding_record')); 
            }
            
            redirect(base_url('admin/quiz/questions/'.$quiz_id));
        }

        $quiz_name_array = array();
        $all_quiz = $this->QuestionModel->get_all_quiz();
        foreach ($all_quiz as $quiz_array) 
        {
            $quiz_name_array[''] = lang('select_quiz');
            $quiz_name_array[$quiz_array->id] = $quiz_array->title;
        }
        
        $this->set_title(lang('admin_add_question').": ".$quiz_data->title);
        $data = $this->includes;

        $content_data = array('quiz_name_array' => $quiz_name_array,'choices_array' => $choices_array, 'no_of_option' => $no_of_option, 'arr_is_correct' => $arr_is_correct,'quiz_id'=>$quiz_id,);
        // load views
        $data['content'] = $this->load->view('admin/questions/form', $content_data, TRUE);
        $this->load->view($this->template, $data);
    }

    function update($quiz_id = NULL, $question_id = NULL) 
    {

        $question_data = $this->QuestionModel->get_question_by_id($question_id);

        if(empty($question_data))
        {
           $this->session->set_flashdata('error', lang('admin_invalid_id')); 
           redirect(base_url('admin/questions'));
        }

        $title_unique = $this->input->post('title')  != $question_data->title ? '|is_unique[questions.title]' : '';

        $this->form_validation->set_rules('title', 'Title', 'required|trim'.$title_unique);
        $this->form_validation->set_rules('choices[]', 'Choices', 'required|trim');
  
        $choices_array = $this->input->post('choices') ? $this->input->post('choices') : array();
        $arr_is_correct = $this->input->post('is_correct') ? $this->input->post('is_correct') : array();

        if(empty($choices_array))
        {
            $choices_array = json_decode($question_data->choices);
            $arr_is_correct = json_decode($question_data->correct_choice);
        }
        $arr_is_correct = json_decode(json_encode($arr_is_correct), true);

        $count_of_option = count($choices_array);
        $no_of_option = $count_of_option > 0 ? $count_of_option - 1 : 0;

        if ($this->form_validation->run() == false) 
        {
            $this->form_validation->error_array();
        } 
        else 
        {
            action_not_permitted();
            $question_content = array();
            
            $user_id = $this->user['id'] ? $this->user['id'] : NULL;
            $is_multiple = $this->input->post('is_multiple',TRUE);
            $is_multiple = $is_multiple ? 1 : 0;

            if(isset($_FILES['image']['name']) && $_FILES['image']['name'])
            {
                $question_image_upload = $this->do_upload_image('image');

                if($question_image_upload['status'] == 'error')
                {
                    $this->session->set_flashdata('error', lang('invalid_file_formate'));
                }
                else
                {
                    $question_image = $question_image_upload['upload_data']['file_name'];
                    $question_content['image'] = $question_image;

                }
            }

            $correct_choice = array();
            foreach ($choices_array as $key => $option_value) 
            {
                if(isset($arr_is_correct[$key]) && $arr_is_correct[$key])
                {
                    $correct_choice[$key] = $option_value;
                }
            }

            $question_content['quiz_id'] = $quiz_id;
            $question_content['title'] = $this->input->post('title',TRUE);
            $question_content['is_multiple'] = $is_multiple;
            $question_content['choices'] = json_encode($choices_array);
            $question_content['correct_choice'] = json_encode($correct_choice);
            $question_content['updated'] =  date('Y-m-d H:i:s');

            $question_update_status = $this->QuestionModel->update_question($question_id, $question_content);

            if($question_update_status)
            {
                $this->session->set_flashdata('message', lang('admin_record_updated_successfully'));
            }
            else
            {
                $this->session->set_flashdata('error', lang('admin_error_during_update_record')); 
            }
            redirect(base_url('admin/quiz/questions/'.$quiz_id));
        }

        $quiz_name_array = array();
        $all_quiz = $this->QuestionModel->get_all_quiz();
        foreach ($all_quiz as $quiz_array) 
        {
            $quiz_name_array[''] = lang('select_quiz');
            $quiz_name_array[$quiz_array->id] = $quiz_array->title;
        }

        $this->set_title(lang('admin_update_question'));
        $data = $this->includes;
        $question_data = json_decode(json_encode($question_data),TRUE);
        $content_data = array('question_id' => $question_id, 'quiz_name_array' => $quiz_name_array,'choices_array' => $choices_array,'question_data' => $question_data,'no_of_option' => $no_of_option, 'arr_is_correct' => $arr_is_correct,'quiz_id' => $quiz_id,);
        // load views
        $data['content'] = $this->load->view('admin/questions/form', $content_data, TRUE);
        $this->load->view($this->template, $data);
    }

    public function copy($question_id = NULL) 
    {
        action_not_permitted();
        $question_data = $this->QuestionModel->get_question_by_id($question_id);

        if(empty($question_data))
        {
           $this->session->set_flashdata('error', lng('invalid_footer_link_id')); 
           redirect(base_url('admin/questions'));
        }

        $question_name_count = $this->QuestionModel->question_name_like_this(NULL,$question_data->title);
        $count = $question_name_count > 0 ? '-' . $question_name_count : '';
        
        $question_content = array();

        $question_content['quiz_id']        = $question_data->quiz_id;
        $question_content['title']          = $question_data->title.'-copy '.$count;
        $question_content['is_multiple']    =  $question_data->is_multiple;
        $question_content['choices']        = $question_data->choices;
        $question_content['correct_choice'] =  $question_data->correct_choice;
        $question_content['image']          =  NULL;
        $question_content['added']          =  date('Y-m-d H:i:s');

        $question_new_id = $this->QuestionModel->insert_question($question_content);

        if($question_new_id)
        {
          $this->session->set_flashdata('message', lang('record_copied_successfully'));  
        }
        else
        {
            $this->session->set_flashdata('error', lang('admin_error_during_copying_record')); 
        } 

        redirect(base_url('admin/questions'));
    }

    function delete($question_id = NULL)
    {
        action_not_permitted();
        $status = $this->QuestionModel->delete_question($question_id);
        if ($status) 
        {
          $this->session->set_flashdata('message', lang('admin_record_delete_successfully'));  
        }
        else
        {
            $this->session->set_flashdata('error', lang('admin_error_during_delete_record')); 
        }
        redirect(base_url('admin/questions'));
    }

    function quiz_upload_file() 
    {
        $image = array();
        $name = $_FILES['file']['name'];
        $config['upload_path'] = "./assets/images/quiz";
        $config['allowed_types'] = 'jpg|png|bmp|jpeg';
        $this->load->library('upload', $config);
        $status = $this->upload->do_upload('file');
        if ($status) 
        {
            $file = $this->upload->data();
            $full_path = "./assets/images/quiz/".$file['file_name'];
            $resize_to = "./assets/images/quiz/";
            $thumb = $this->resize_image->resize_to_thumb($full_path,$resize_to.'thumbnail');
            $thumb = $thumb ? lang('thumbnail_resize_success') : lang('thumbnail_resize_error');
            
            $small = $this->resize_image->resize_to_small($full_path,$resize_to.'small');
            $small = $small ? lang('small_resize_success') : lang('small_resize_errors');
            
            $medium = $this->resize_image->resize_to_medium($full_path,$resize_to.'medium');
            $medium = $medium ? lang('medium_resize_success') : lang('medium_resize_errors');

            $success = array('status' => true, 'messages' => lang('upload_success'), 'name' => $file['file_name'], 'original_name' => $name,);
            echo json_encode($success);
        } 
        else 
        {
            $image['msg'] = 'error';
            echo json_encode($image);
        }
    }

    function dropzone_quiz_file_remove() 
    {
        $filename = $_POST['filename'];
        $path = "./assets/images/article/$filename";
        if ($path) {
            unlink($path);
            unlink("./assets/images/article/thumbnail/$filename");
            unlink("./assets/images/article/small/$filename");
            unlink("./assets/images/article/medium/$filename");

            $status = json_encode($filename);
            echo xss_clean($status);
            return $status;
        }
        echo false;
        return false;
    }

    function question_list() 
    {
        $data = array();
        $list = $this->QuestionModel->get_question(); 
        $no = $_POST['start'];
        foreach ($list as $question) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = mb_convert_encoding($question->quiz_name, 'UTF-8', 'UTF-8');
            $row[] = mb_convert_encoding($question->title, 'UTF-8', 'UTF-8');
            $button = '<a href="' . base_url("admin/questions/update/". $question->id) . '" data-toggle="tooltip"  title="'.lang("admin_edit_record").'" class="btn btn-primary btn-action mr-1"><i class="fas fa-pencil-alt"></i></a>';

            $button .= '<a href="' . base_url("admin/questions/copy/" . $question->id) . '" data-toggle="tooltip" title="'.lang("admin_copy_record").'" class="common_copy_record btn btn-warning mr-1"><i class="far fa-copy"></i></a>';

            $button .= '<a href="' . base_url("admin/questions/delete/" . $question->id) . '" data-toggle="tooltip" title="'.lang("admin_delete_record").'" class="btn btn-danger btn-action mr-1 common_delete"><i class="fas fa-trash"></i></a>';

            $row[] = $button;
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'], 
            "recordsTotal" => $this->QuestionModel->count_all(), 
            "recordsFiltered" => $this->QuestionModel->count_filtered(), 
            "data" => $data
        );

        //output to json format
        echo json_encode($output);
    }

    public function delete_image($question_id = NULL) 
    {
        $image_name = $this->input->post('image_name', TRUE);
        if ($question_id && $image_name) 
        {
            $question_data = $this->QuestionModel->get_quiz_by_id($question_id);
            $image_array = json_decode($question_data->image);
            $image_array = json_decode(json_encode($image_array), True);
            if (($key = array_search($image_name, $image_array)) !== false) 
            {
                unset($image_array[$key]);
            }
            $updated_image_value = json_encode($image_array);
            $result = $this->QuestionModel->update_quiz_images_by_id($question_id, $updated_image_value);
            $path = "./assets/images/quiz/$image_name";
            unlink($path);
            unlink("./assets/images/quiz/thumbnail/$image_name");
            unlink("./assets/images/quiz/small/$image_name");
            unlink("./assets/images/quiz/medium/$image_name");
            echo xss_clean($result);
            return $result;
        } 
        else 
        {
            echo false;
            return false;
        }
    }

    function image_resize_library($image_address = null)
    {
        $resize_status = $this->resize_image->resize_to_thumb('./assets/images/quiz/default-1.png', './assets/images/');

        echo $resize_status ? lang('file_copy_success') : lang('resize_errors');        
    } 

    public function do_upload_image()
    {
        $new_name = time().$_FILES["image"]['name'];
        $config['upload_path']          = "./assets/images/questions";
        $config['allowed_types']        = 'jpg|png|jpeg';
        $config['max_size']             = 100;
        $config['max_width']            = 1024;
        $config['max_height']           = 768;
        $config['file_name'] = $new_name;

        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload('image'))
        {
            $respons = array(   'status' => 'error',
                                'error' => $this->upload->display_errors()
                            );
        }
        else
        {
            $respons = array('status' => 'success',
                             'upload_data' => $this->upload->data(),
                            );
        }

        return $respons;
    }


}
