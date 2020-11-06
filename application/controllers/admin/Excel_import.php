<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Rap2hpoutre\FastExcel\FastExcel;
use App\User;

class Excel_import extends Admin_Controller {
    /**
     * @var string
     */
    private $_redirect_url;

    protected $brands = [];

    protected $markets = [];
    protected $groups = [];
    protected $custom_fields = [];
    
    /**
     * Constructor
     */
    function __construct() {
        parent::__construct();
        $this->load->model('ImportModel');
        $this->load->model('QuizModel');
    
    }
    /**************************************************************************************
     * PUBLIC FUNCTIONS
     **************************************************************************************/
    /**
     * Message list page
     */
    function index($post_quiz_id = NULL) 
    {
        $quiz_data = $this->QuizModel->get_quiz_by_id($post_quiz_id);
        
        $this->form_validation->set_rules('quiz_id', lang('admin_excel_quiz_name'), 'required|numeric|trim');
        if(empty($_FILES["excel_file"]['name']))
        {            
            $this->form_validation->set_rules('excel_file', lang('admin_excel_file'), 'required');
        }

        if ($this->form_validation->run() == false OR empty($this->input->post('quiz_id'))) 
        {
            $this->form_validation->error_array();
        }
        else
        {

            $image = time().'-'.$_FILES["excel_file"]['name'];
            
            $config['upload_path']      = "./assets/excel";
            $config['allowed_types']    = 'xlsx|csv|xls';
            $config['file_name']        = $image;

            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('excel_file')) 
            {
                $error =  $this->upload->display_errors();
                $this->session->set_flashdata('error', $error);
                return redirect(base_url('admin/excel_import'));
            }
            else
            {
                $file = $this->upload->data();
                $content['category_image'] = $file['file_name'];
            }
            $quiz_id = $this->input->post('quiz_id',TRUE);
            $over_write = $this->input->post('over_write',TRUE);
            $over_write = $over_write ? 1 : 0;


            $import = $this->import_Excel_data($file['file_name'], $quiz_id,$over_write);

            if($import)
            { 

                $this->session->set_flashdata('message', $import['insert_count'].' '.lang('record_import_successfully').' '.$import['skip_count'].' '.lang('row_skip_during_import'));

                return redirect(base_url('admin/excel_import'));                
            }
            else
            {
                $this->session->set_flashdata('error',lang('data_import_error'));
                return redirect(base_url('admin/excel_import'));        
            }
        }
       
        $quiz_name_array = array();
        $all_quiz = $this->ImportModel->get_all_quiz();
        foreach ($all_quiz as $quiz_array) 
        {
            $quiz_name_array[''] = 'Select Quiz';
            $quiz_name_array[$quiz_array->id] = $quiz_array->title;
        }
        

        $this->set_title(lang('admin_import_quiz_questions_excel').": ".$quiz_data->title);
        $data = $this->includes;
        
        $content_data = array('quiz_name_array' => $quiz_name_array,'post_quiz_id'=>$post_quiz_id);
        // load views
        $data['content'] = $this->load->view('admin/import/form', $content_data, TRUE);
        $this->load->view($this->template, $data);

    }

    function import_Excel_data($file_name, $quiz_id,$over_write) 
    {

        $file_dir = "./assets/excel/".$file_name;

        $questions_array = array();
        try
        {
           $questions_array = (new FastExcel)->import($file_dir);
        }
        catch (Exception $e)
        {
            $error =  lang('unable_to_read_this_file_formate');
            $this->session->set_flashdata('error', $error);
            return redirect(base_url('admin/excel_import'));

        }
        $questions_array = $questions_array ? $questions_array : array();
        $questions_array = json_decode(json_encode($questions_array), true);

        $question_content_array = array();

        $i = 0;
        $insert_count = 0;
        $skip_count = 0;
        foreach ($questions_array as $product_detail_data) 
        {   
            $i++;
            if(empty($product_detail_data['title']))
            {
                break;
            }

            if($i === 1)
            {
                $excel_is_multiple = isset($product_detail_data['is_multiple']) ? 'SUCCESS' : NULL;
                if(empty($product_detail_data['choices']) OR empty($product_detail_data['correct_choice']) OR empty($excel_is_multiple ))
                {
                    $this->session->set_flashdata('error', lang('invalid_file_formate'));
                    return redirect(base_url('admin/excel_import'));
                }
            }

            $is_multiple = $product_detail_data['is_multiple'] == 1 ? 1 : 0;

            if(is_array($product_detail_data['choices']))
            {
                if(isset($product_detail_data['choices']['date']))
                {
                   $product_detail_data['choices'] = date('d M Y',strtotime($product_detail_data['choices']['date'])); 
                }
                else
                {
                    $product_detail_data['choices'] = implode(',', $product_detail_data['choices']);
                }
            }

            $excel_choices_array = explode('||', $product_detail_data['choices']);
            $excel_choices_array = $excel_choices_array ? $excel_choices_array : array();
            $choices_array = array();
            if($excel_choices_array)
            foreach ($excel_choices_array as $key => $choice_value) 
            {
                if($choice_value)
                {
                    $choices_array[] = trim($choice_value);                    
                }
            }

            if(is_array($product_detail_data['correct_choice']))
            {
                if(isset($product_detail_data['correct_choice']['date']))
                {
                   $product_detail_data['correct_choice'] = date('d M Y',strtotime($product_detail_data['correct_choice']['date'])); 
                }
                else
                {
                    $product_detail_data['correct_choice'] = implode(',', $product_detail_data['correct_choice']);
                }
            }

            $excel_correct_choice = explode('||', $product_detail_data['correct_choice']);
            $excel_correct_choice = $excel_correct_choice ? $excel_correct_choice : array();

            $correct_choice = array();
           
            foreach ($excel_correct_choice as $key => $correct_value) 
            {
                if($correct_value)
                {   
                    $correct_choice[] = trim($correct_value);
                }
            }

            if($product_detail_data['title'] && $choices_array && $correct_choice)
            {
                $insert_count++;
                $question_content['quiz_id'] = $quiz_id;
                $question_content['title'] = $product_detail_data['title'];
                $question_content['is_multiple'] = $is_multiple;
                $question_content['choices'] = json_encode($choices_array);
                $question_content['correct_choice'] = json_encode($correct_choice);
                $question_content['image'] = NULL;
                $question_content['deleted'] = 0;
                $question_content['added'] =  date('Y-m-d H:i:s');
                
                $question_content_array[] = $question_content;
            }
            else
            {
                $skip_count++;
            }
        
        }  //end foreach

        if($over_write == 1)
        {            
            $this->ImportModel->delete_question_by_quiz_id($quiz_id);
        }
        
        $status = $this->ImportModel->insert_bulk_question($question_content_array);
          
        $respone['insert_count']   = $insert_count;     
        $respone['skip_count']   = $skip_count;  
        return $respone;
    }

}
