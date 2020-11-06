<?php defined('BASEPATH') OR exit('No direct script access allowed');
class QuizController extends Admin_Controller {
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
        $this->add_js_theme('quiz.js');

        $this->load->model('QuizModel');
        $this->load->model('QuizQuestionModel');
        
        $this->load->library('form_validation');
        $this->load->helper("My_custom_field_helper");
        $this->load->helper('url');
        $this->load->library('resize_image');
        // set constants
        define('REFERRER', "referrer");
        define('THIS_URL', base_url('admin/quiz'));
        define('DEFAULT_LIMIT', 10);
        define('DEFAULT_OFFSET', 0);
        define('DEFAULT_SORT', "id");
        define('DEFAULT_DIR', "asc");
    }
    function index() {

        $this->set_title(lang('quiz_list'));
        $data = $this->includes;
        $content_data = array();
        $data['content'] = $this->load->view('admin/quiz/list', $content_data, TRUE);
        $this->load->view($this->template, $data);
    }

    function add() 
    {
        $this->form_validation->set_rules('user_id', 'User Name', 'required|numeric|trim');
        $this->form_validation->set_rules('category_id', 'Category Name', 'required|numeric|trim');
        $this->form_validation->set_rules('title', 'Title', 'required|trim|is_unique[quizes.title]');
        $this->form_validation->set_rules('number_questions', 'Number Of Question', 'required|trim|numeric');
        $this->form_validation->set_rules('duration_min', 'Duration', 'required|trim|numeric');
        $this->form_validation->set_rules('description', 'Description', 'required|trim');

        if ($this->form_validation->run() == false) 
        {
            $this->form_validation->error_array();
        } 
        else 
        {
            action_not_permitted();
            $user_id = $this->user['id'] ? $this->user['id'] : NULL;

            $leader_board = $this->input->post('leader_board',TRUE);
            $leader_board = $leader_board ? 1 : 0;

            $is_random = $this->input->post('is_random',TRUE);
            $is_random = $is_random ? 1 : 0;

            $quiz_content = array();

            $quiz_content['user_id'] = $this->input->post('user_id',TRUE);
            $quiz_content['category_id'] = $this->input->post('category_id',TRUE);
            $quiz_content['title'] = $this->input->post('title',TRUE);
            $quiz_content['number_questions'] = $this->input->post('number_questions', TRUE);
            $quiz_content['price'] = 0;
            $quiz_content['duration_min'] = $this->input->post('duration_min',TRUE);
            $quiz_content['description'] = $this->input->post('description',TRUE);
            $quiz_content['quiz_instruction'] = $this->input->post('quiz_instruction',TRUE);
            $quiz_content['featured_image'] = ($this->input->post('featured_image')) ? json_encode($this->input->post('featured_image',TRUE)) : '';
            $quiz_content['leader_board'] = $leader_board;
            $quiz_content['is_random'] = $is_random;
            $quiz_content['is_paid'] = 1;
            $quiz_content['deleted'] = 0;
            $quiz_content['added'] =  date('Y-m-d H:i:s');

            $quiz_id = $this->QuizModel->insert_quiz($quiz_content);

            if($quiz_id)
            {
                $this->session->set_flashdata('message', lang('admin_record_added_successfully'));   
            }
            else
            {
                $this->session->set_flashdata('error', lang('admin_error_adding_record')); 
            }

            redirect(base_url('admin/quiz'));
        }

        $category_data = array();
        $all_category = $this->QuizModel->get_all_category();
        foreach ($all_category as $category_array) 
        {
            $category_data[''] = lang('select_category');
            $category_data[$category_array->id] = $category_array->category_title;
        }

        $all_user_data = array();
        $all_user_array = $this->QuizModel->get_all_users();
        foreach ($all_user_array as $user_data_array) 
        {
            $all_user_data[''] = 'Select User';
            $all_user_data[$user_data_array->id] = $user_data_array->first_name.' '.$user_data_array->last_name;
        }

        $this->set_title(lang('admin_add_quiz'));
        $data = $this->includes;

        $content_data = array('category_data' => $category_data,'all_user_data'=>$all_user_data,);
        // load views
        $data['content'] = $this->load->view('admin/quiz/form', $content_data, TRUE);
        $this->load->view($this->template, $data);
    }

    function update($quiz_id = NULL) 
    {
        if(empty($quiz_id))
        {
            $this->session->set_flashdata('error', lang('invalid_url')); 
            redirect(base_url('admin/quiz'));
        }

        $quiz_data = $this->QuizModel->get_quiz_by_id($quiz_id);

        if(empty($quiz_data))
        {
            $this->session->set_flashdata('error', lang('admin_invalid_id')); 
            redirect(base_url('admin/quiz'));
        }

        $title_unique = $this->input->post('title')  != $quiz_data->title ? '|is_unique[quizes.title]' : '';

        $this->form_validation->set_rules('user_id', 'User Name', 'required|numeric|trim');
        $this->form_validation->set_rules('category_id', 'Category Name', 'required|numeric|trim');
        $this->form_validation->set_rules('title', 'Title', 'required|trim'.$title_unique);
        $this->form_validation->set_rules('number_questions', 'Number Question', 'required|trim|numeric');
        $this->form_validation->set_rules('duration_min', 'Duration', 'required|trim|numeric');
        $this->form_validation->set_rules('description', 'Description', 'required|trim');

        $last_featured_image = json_decode($quiz_data->featured_image);
        $last_featured_image = $last_featured_image ? $last_featured_image : array();

        if ($this->form_validation->run() == false) 
        {
            $this->form_validation->error_array();
        } 
        else 
        {
            action_not_permitted();
            $user_id = $this->user['id'] ? $this->user['id'] : NULL;

            $leader_board = $this->input->post('leader_board',TRUE);
            $leader_board = $leader_board ? 1 : 0;

            $is_random = $this->input->post('is_random',TRUE);
            $is_random = $is_random ? 1 : 0;

            $quiz_featured_image = $this->input->post('featured_image') ?  $this->input->post('featured_image',TRUE) : array();
            $featured_image = array_merge($last_featured_image,$quiz_featured_image);

            $quiz_content = array();

            $quiz_content['user_id'] = $this->input->post('user_id',TRUE);
            $quiz_content['category_id'] = $this->input->post('category_id',TRUE);
            $quiz_content['title'] = $this->input->post('title',TRUE);
            $quiz_content['number_questions'] = $this->input->post('number_questions', TRUE);
            $quiz_content['price'] = 0;
            $quiz_content['duration_min'] = $this->input->post('duration_min',TRUE);
            $quiz_content['description'] = $this->input->post('description',TRUE);
            $quiz_content['quiz_instruction'] = $this->input->post('quiz_instruction',TRUE);
            $quiz_content['featured_image'] = json_encode($featured_image);
            $quiz_content['leader_board'] = $leader_board;
            $quiz_content['is_random'] = $is_random;
            $quiz_content['updated'] =  date('Y-m-d H:i:s');

            $article_update_status = $this->QuizModel->update_quiz($quiz_id, $quiz_content);

            if($article_update_status)
            {
                $this->session->set_flashdata('message', lang('admin_record_updated_successfully'));
            }
            else
            {
                $this->session->set_flashdata('error', lang('admin_error_during_update_record')); 
            }
            redirect(base_url('admin/quiz'));
        }

        $category_data = array();
        $all_category = $this->QuizModel->get_all_category();
        foreach ($all_category as $category_array) 
        {
            $category_data[$category_array->id] = $category_array->category_title;
        }


        $all_user_data = array();
        $all_user_array = $this->QuizModel->get_all_users();
        foreach ($all_user_array as $user_data_array) 
        {
            $all_user_data[''] = 'Select User';
            $all_user_data[$user_data_array->id] = $user_data_array->first_name.' '.$user_data_array->last_name;
        }
        
        $this->set_title(lang('update_quiz').': '.$quiz_data->title);
        $data = $this->includes;

        $quiz_data = json_decode(json_encode($quiz_data),TRUE);
        $content_data = array('quiz_id' => $quiz_id, 'quiz_data' => $quiz_data, 'category_data' => $category_data,'all_user_data'=>$all_user_data);
        // load views
        $data['content'] = $this->load->view('admin/quiz/edit_form', $content_data, TRUE);
        $this->load->view($this->template, $data);
    }

    public function copy($quiz_id = NULL) 
    {
        action_not_permitted();
        if(empty($quiz_id))
        {
            $this->session->set_flashdata('error', lang('invalid_url')); 
            redirect(base_url('admin/quiz'));
        }

        $quiz_data = $this->QuizModel->get_quiz_by_id($quiz_id);

        if(empty($quiz_data))
        {
            $this->session->set_flashdata('error', lang('admin_invalid_id')); 
            redirect(base_url('admin/quiz'));
        }

        $quiz_name_count = $this->QuizModel->quiz_name_like_this(NULL,$quiz_data->title);
        $count = $quiz_name_count > 0 ? '-' . $quiz_name_count : '';

        $quiz_content = array();

        $quiz_content['user_id'] = $quiz_data->user_id;
        $quiz_content['category_id'] =  $quiz_data->category_id;
        $quiz_content['title'] = $quiz_data->title.'-copy '.$count;
        $quiz_content['number_questions'] = $quiz_data->number_questions;
        $quiz_content['price'] =  $quiz_data->price;
        $quiz_content['description'] =  $quiz_data->description;
        $quiz_content['featured_image'] =  json_encode(array());
        $quiz_content['duration_min'] =  $quiz_data->duration_min;
        $quiz_content['leader_board'] =  $quiz_data->leader_board;
        $quiz_content['quiz_instruction'] =  $quiz_data->quiz_instruction;
        $quiz_content['deleted'] =  0;
        $quiz_content['added'] =  date('Y-m-d H:i:s');

        $article_new_id = $this->QuizModel->insert_quiz($quiz_content);

        if($article_new_id)
        {
            $this->session->set_flashdata('message', lang('record_copied_successfully'));
        }
        else
        {
            $this->session->set_flashdata('error', lang('admin_error_during_copying_record')); 
        } 

        redirect(base_url('admin/quiz'));
    }

    function delete($quiz_id = NULL)
    {
        action_not_permitted();
        $status = $this->QuizQuestionModel->delete_questions($quiz_id); 
        $status = $this->QuizModel->delete_quiz($quiz_id); 

        if ($status) 
        {
            $this->session->set_flashdata('message', lang('admin_record_delete_successfully'));
        }
        else
        {
            $this->session->set_flashdata('error', lang('admin_error_during_delete_record')); 
        }
        redirect(base_url('admin/quiz'));
    }

    function quiz_upload_file() {
        $image = array();
        $name = $_FILES['file']['name'];
        $config['upload_path'] = "./assets/images/quiz";
        $config['allowed_types'] = 'jpg|png|bmp|jpeg';
        $this->load->library('upload', $config);
        $status = $this->upload->do_upload('file');
        if($status) 
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

    function dropzone_quiz_file_remove() {
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

    function quiz_list() {
        $data = array();
        $list = $this->QuizModel->get_quiz();
        $no = $_POST['start'];
        foreach ($list as $quiz) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = ucfirst($quiz->title);
            $row[] = ucfirst($quiz->category_title);
            $row[] = ucfirst($quiz->number_questions);
            $row[] = ucfirst($quiz->duration_min);
            $button = '<a href="' . base_url("admin/quiz/update/". $quiz->id) . '" data-toggle="tooltip"  title="'.lang("admin_edit_record").'" class="btn btn-primary btn-action mr-1"><i class="fas fa-pencil-alt"></i></a>';

            $button.= '<a href="' . base_url("admin/quiz/copy/" . $quiz->id) . '" data-toggle="tooltip"  title="'.lang("admin_copy_record").'" class="common_copy_record btn btn-warning mr-1"><i class="far fa-copy"></i></a>';
            

            $button.= '<a href="' . base_url("admin/quiz/delete/" . $quiz->id) . '" data-toggle="tooltip"  title="'.lang("admin_delete_record").'" class="btn btn-danger btn-action mr-1 common_delete"><i class="fas fa-trash"></i></a>';

            $row[] = $button;
            $data[] = $row;
        }
        $output = array("draw" => $_POST['draw'], "recordsTotal" => $this->QuizModel->count_all(), "recordsFiltered" => $this->QuizModel->count_filtered(), "data" => $data,);
        //output to json format
        echo json_encode($output);
    }

    public function delete_featured_image($quiz_id = NULL) 
    {
        $featured_image_name = $this->input->post('featured_image_name', TRUE);
        if ($quiz_id && $featured_image_name) 
        {
            $quiz_data = $this->QuizModel->get_quiz_by_id($quiz_id);
            $featured_image_array = json_decode($quiz_data->featured_image);
            $featured_image_array = json_decode(json_encode($featured_image_array), True);
            if (($key = array_search($featured_image_name, $featured_image_array)) !== false) 
            {
                unset($featured_image_array[$key]);
            }
            $updated_image_value = json_encode($featured_image_array);
            $result = $this->QuizModel->update_quiz_images_by_id($quiz_id, $updated_image_value);
            $path = "./assets/images/quiz/$featured_image_name";
            unlink($path);
            unlink("./assets/images/quiz/thumbnail/$featured_image_name");
            unlink("./assets/images/quiz/small/$featured_image_name");
            unlink("./assets/images/quiz/medium/$featured_image_name");
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
        echo ($resize_status) ? lang('file_copy_success') : lang('resize_errors');
    } 

    function questions($quiz_id = NULL)
    {
        $quiz_data = $this->QuizModel->get_quiz_by_id($quiz_id);

        if(empty($quiz_data))
        {
            $this->session->set_flashdata('error', lang('admin_invalid_id')); 
            redirect(base_url('admin/quiz'));
        }

        $this->add_external_js( base_url("/{$this->settings->themes_folder}/admin/js/dataTables.buttons.min.js"));
        $this->add_external_js( base_url("/{$this->settings->themes_folder}/admin/js/buttons.flash.min.js")); 
        $this->add_external_js( base_url("/{$this->settings->themes_folder}/admin/js/jszip.min.js")); 
        $this->add_external_js( base_url("/{$this->settings->themes_folder}/admin/js/pdfmake.min.js")); 
        $this->add_external_js( base_url("/{$this->settings->themes_folder}/admin/js/vfs_fonts.js")); 
        $this->add_external_js( base_url("/{$this->settings->themes_folder}/admin/js/buttons.html5.min.js")); 
        $this->add_external_js( base_url("/{$this->settings->themes_folder}/admin/js/buttons.print.min.js")); 
        $this->add_external_css( base_url("/{$this->settings->themes_folder}/admin/css/buttons.dataTables.min.css")); 

        $this->set_title(lang('admin_questions_list').": ".$quiz_data->title);
        $this->add_js_theme('question.js');
        
        $data = $this->includes;
        $content_data = array('quiz_id' => $quiz_id, 'quiz_data'=> $quiz_data);
        $data['content'] = $this->load->view('admin/quiz/question_list', $content_data, TRUE);
        $this->load->view($this->template, $data);

    }

    function question_list($quiz_id=false) 
    {

        $data = array();
        $list = $this->QuizModel->get_question($quiz_id); 

        $no = $_POST['start'];
        foreach ($list as $question) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = xss_clean($question->title);
            $correct_answer = json_decode($question->correct_choice);
            foreach($correct_answer as $correct_value)
            {
                $answer[] = xss_clean($correct_value);
            }
            $row[] = $answer; 
                
            $button = '<a href="' . base_url("admin/questions/update/". $quiz_id. "/". $question->id) . '" data-toggle="tooltip"  title="'.lang("admin_edit_record").'" class="btn btn-primary btn-action mr-1"><i class="fas fa-pencil-alt"></i></a>';

            $button .= '<a href="' . base_url("admin/questions/copy/" . $question->id) . '" data-toggle="tooltip" title="'.lang("admin_copy_record").'" class="common_copy_record btn btn-warning mr-1"><i class="far fa-copy"></i></a>';

            $button .= '<a href="' . base_url("admin/questions/delete/" . $question->id) . '" data-toggle="tooltip" title="'.lang("admin_delete_record").'" class="btn btn-danger btn-action mr-1 common_delete"><i class="fas fa-trash"></i></a>';

            $row[] = $button;
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'], 
            "recordsTotal" => $this->QuizModel->count_all_question($quiz_id), 
            "recordsFiltered" => $this->QuizModel->count_filtered_question($quiz_id), 
            "data" => $data
        );

        //output to json format
        echo json_encode($output);
    }
}
