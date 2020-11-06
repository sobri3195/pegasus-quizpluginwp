<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Language extends Admin_Controller {
    /**
     * Constructor
     */
    function __construct() {
        parent::__construct();
        $this->add_css_theme('all.css');
        $this->add_js_theme('jquery.multi-select.min.js');
        $this->add_js_theme('language.js');
        $this->add_css_theme('sweetalert.css');
        $this->add_js_theme('sweetalert-dev.js');
        $this->add_js_theme('bootstrap-notify.min.js');

        // load the category model
        $this->load->model('languageModel');
        //load the form validation
        $this->load->library('form_validation');
        // set constants
        define('REFERRER', "referrer");
        define('THIS_URL', base_url('admin/language'));
        define('DEFAULT_LIMIT', 10);
        define('DEFAULT_OFFSET', 0);
        define('DEFAULT_SORT', "id");   
        define('DEFAULT_DIR', "asc");
    }

    function index() 
    {
        $this->set_title(lang('language_list'));
        $data = $this->includes;
        $content_data = array();
        $data['content'] = $this->load->view('admin/language/list', $content_data, TRUE);
        $this->load->view($this->template, $data);
    }

    function add() 
    {
        $this->form_validation->set_rules('lang', lang('language language name'), 'required|trim|is_unique[language.lang]');

        if ($this->form_validation->run() == false) 
        {
            $this->form_validation->error_array();
        } 
        else 
        {
            action_not_permitted();
            $language_content = array();
            $is_rtl = $this->input->post('is_rtl', TRUE) ? 1 : 0;

            $lang_name = strtolower($this->input->post('lang',TRUE));
            $language_content['lang'] = ucwords($lang_name);
            $language_content['is_rtl'] = $is_rtl;
            $language_content['added'] =  date('Y-m-d H:i:s');

            $language_id = $this->languageModel->insert_language($language_content);

            if($language_id)
            { 

                $lang_tokens = $this->languageModel->get_language_tokens(1);
                $token_content_array =array();
                foreach ($lang_tokens as $lang_token_array) 
                {
                    $token = $lang_token_array->token;
                    $description = $lang_token_array->description;

                    $token_content = array();
                    $token_content['language_id'] = $language_id;
                    $token_content['token'] = $token;
                    $token_content['description'] = $description;
                    $token_content['group_name'] = $lang_token_array->group_name;
                    $token_content['added'] =  date('Y-m-d H:i:s');
                    $token_content_array[] =  $token_content;

                }
                $insert =   NULL;

                if($token_content_array)
                {                    
                    $insert = $this->languageModel->insert_language_tokens($token_content_array);
                }
                if($insert)
                {
                    $this->session->set_flashdata('message', lang('admin_record_added_successfully'));
                }
                else
                {
                    $this->session->set_flashdata('error', lang('admin_error_adding_record')); 
                }
            }
            else
            {
                $this->session->set_flashdata('error', lang('admin_error_adding_record')); 
            }
            redirect(base_url('admin/language'));
        }
            
        $this->set_title(lang('admin_add_language'));
        $data = $this->includes;

        $content_data = array('lang_name' => '');
        // load views
        $data['content'] = $this->load->view('admin/language/form', $content_data, TRUE);
        $this->load->view($this->template, $data);
    }

    function update($language_id = NULL) 
    {
        if(empty($language_id))
        {
           $this->session->set_flashdata('error', lang('invalid_url')); 
           redirect(base_url('admin/language'));
        }

        $language_data = $this->languageModel->get_language_by_id($language_id);

        if(empty($language_data))
        {
           $this->session->set_flashdata('error', lang('admin_invalid_id')); 
           redirect(base_url('admin/language'));
        }

        $title_unique = $this->input->post('lang')  != $language_data->lang ? '|is_unique[language.lang]' : '';

        $this->form_validation->set_rules('lang', 'Language', 'required|trim'.$title_unique);

        if ($this->form_validation->run() == false) 
        {
            $this->form_validation->error_array();
        } 
        else 
        {
            action_not_permitted();
            $language_content = array();

            $is_rtl = $this->input->post('is_rtl', TRUE) ? 1 : 0;
            $lang_name = strtolower($this->input->post('lang',TRUE));
            $language_content['lang'] = ucwords($lang_name);
            $language_content['is_rtl'] = $is_rtl;
            $language_content['updated'] =  date('Y-m-d H:i:s');
            $language_update_status = $this->languageModel->update_language($language_id, $language_content);

            if($language_update_status)
            {
                $this->session->set_flashdata('message', lang('admin_record_updated_successfully'));
            }
            else
            {
                $this->session->set_flashdata('error', lang('admin_error_during_update_record')); 
            }
            redirect(base_url('admin/language'));
        }

        $this->set_title(lang('admin_update_language'));
        $data = $this->includes;
        $lang_name = $language_data->lang;
        $content_data = array('language_id' => $language_id, 'language_data' => $language_data, 'lang_name' => $lang_name);
        // load views
        $data['content'] = $this->load->view('admin/language/form', $content_data, TRUE);
        $this->load->view($this->template, $data);
    }

    function translation($language_id) 
    {
        $language_data = $this->languageModel->get_language_by_id($language_id);

        if(empty($language_data))
        {
           $this->session->set_flashdata('error', lang('admin_invalid_id')); 
           redirect(base_url('admin/language'));
        }

        $lang_tokens = $this->languageModel->get_language_tokens($language_id);
        $this->form_validation->set_rules('token[]', 'Token', 'required|trim');

        if ($this->form_validation->run() == false) 
        {
            $this->form_validation->error_array();
        } 
        else 
        {
            action_not_permitted();
            $update = array();
            $token_array = $this->input->post('token') ? $this->input->post('token') : array();

            foreach ($lang_tokens as $lang_token_array) 
            {
                $token = $lang_token_array->token;
                $lang_token_id = $lang_token_array->id;
                $description = isset($token_array[$lang_token_id]) ? $token_array[$lang_token_id] : NULL;

                $token_content = array();
                $token_content['description'] = $description;
                $token_content['group_name'] = $lang_token_array->group_name;
                $token_content['updated'] =  date('Y-m-d H:i:s');
                $update[] = $this->languageModel->update_language_tokens($token_content, $language_id, $lang_token_id);
            }

            if($update)
            {
                 $this->session->set_flashdata('message', lang('admin_record_updated_successfully'));  
            }
            else
            {
                $this->session->set_flashdata('error', lang('admin_error_during_update_record')); 
            } 

            redirect(base_url('admin/language'));
        }
            
        $this->set_title('Update Language Tokens');
        $data = $this->includes;

        $content_data = array('language_id' => $language_id, 'lang_tokens' => $lang_tokens, 'lang_name' => $language_data->lang );

        // load views
        $data['content'] = $this->load->view('admin/language/tokens', $content_data, TRUE);

        $this->load->view($this->template, $data);
    }

    function delete($language_id = NULL)
    {
        action_not_permitted();
        $language_data = $this->languageModel->get_language_by_id($language_id);

        if(empty($language_data))
        {
           $this->session->set_flashdata('error', lang('admin_invalid_id')); 
           redirect(base_url('admin/language'));
        }

        if($language_id == 1)
        {
             $this->session->set_flashdata('error', lang('admin_you_cannot_delete_this_language')); 
        }
        else
        {   
            $current_lang = $language_data->lang == $this->session->language ? 'English' : $this->session->language;
            $this->session->language = $current_lang;

            $status = $this->languageModel->delete_language($language_id);
            $status_token = $this->languageModel->delete_language_token($language_id);
            if ($status) 
            {
                $this->session->set_flashdata('message', lang('admin_record_delete_successfully'));  
            }
            else
            {
                $this->session->set_flashdata('error', lang('admin_error_during_delete_record')); 
            }
        }
        redirect(base_url('admin/language'));
    }

    function language_list() 
    {
        $data = array();
        $list = $this->languageModel->get_languages();
        $no = $_POST['start'];
        foreach ($list as $language_data) 
        {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = ucfirst($language_data->lang);
            $button = '';
            if($language_data->id !=1)
            {
                $button .= '<a href="' . base_url("admin/language/update/". $language_data->id) . '" data-toggle="tooltip"  title="'.lang("admin_edit_record").'" class="btn btn-primary btn-action mr-1"><i class="fas fa-pencil-alt"></i></a>';
            }

            $button .= '<a href="' . base_url("admin/language/translation/". $language_data->id) . '" data-toggle="tooltip"  title="'.lang("admin_translation_record").'" class="btn btn-warning btn-action mr-1"><i class="fas fa-globe-asia"></i></a>';

            if($language_data->id !=1)
            {
                $button.= '<a href="' . base_url("admin/language/delete/" . $language_data->id) . '"  data-toggle="tooltip" title="'.lang("admin_delete_record").'" class="btn btn-danger btn-action mr-1 common_delete"><i class="fas fa-trash"></i></a>';
            }
            $row[] = $button;
            $data[] = $row;
        }
        $output = array("draw" => $_POST['draw'], "recordsTotal" => $this->languageModel->count_all(), "recordsFiltered" => $this->languageModel->count_filtered(), "data" => $data,);
        //output to json format
        echo json_encode($output);
    }

    public function language_token_check()
    {
        if ($this->input->post('category') && $this->input->post('token'))
        {
            $token = $this->input->post('token');
            $category = $this->input->post('category');
            $language_id = $this->input->post('language_id');

            $language = $this->languageModel->chek_laguage_by_token_or_category($token,$category,$language_id);

           if($language)
           {
                $this->form_validation->set_message('language_token_check', lang('admin_token_or_category_exist'));
                return FALSE;
           }
           else
           {
               return TRUE;
           }            
        }
        else 
        {
            return TRUE;
        }
    }

    function add_token()
    {
        $content_data['languages'] = $this->languageModel->get_languages_for_token();
        $this->form_validation->set_rules('tokenname','Token', 'trim|callback_alpha_dash_space');
        if ($this->form_validation->run() == false) 
        {
            $this->form_validation->error_array();
        }
        else
        {
            
            $lang_data = array();
            foreach ($content_data['languages'] as $langkey => $langvalue) 
            {
                $language_token = array();
                $token_value = $this->input->post('tokenname');
                $token_exist = $this->languageModel->get_token_and_languageid($token_value,$langvalue->id);

                if(!empty($token_exist))
                {
                    $language_token['language_id'] = $langvalue->id;
                    $language_token['token'] = $token_value;
                    $language_token['group_name'] = $this->input->post('group');
                    $language_token['description'] = $this->input->post('tokendesc')[$langvalue->id];
                    $language_token['updated'] =  date('Y-m-d H:i:s');    
                    $update_data = $this->languageModel->update_token_data($token_exist->id,$language_token);
                }
                else
                {
                    $language_token['language_id'] = $langvalue->id;
                    $language_token['token'] = $token_value;
                    $language_token['group_name'] = $this->input->post('group');
                    $language_token['description'] = $this->input->post('tokendesc')[$langvalue->id];
                    $language_token['added'] =  date('Y-m-d H:i:s');    
                    $insert_data = $this->languageModel->insert_token_data($language_token);
                }   
            }
            if($update_data)
            {
                $this->session->set_flashdata('message', lang('admin_record_updated_successfully'));              
                redirect(base_url('admin/language/add_token'));
            }
            else
            {
                $this->session->set_flashdata('message', lang('admin_record_added_successfully'));    
                redirect(base_url('admin/language/add_token'));
            }
        }
        
        $this->set_title(lang('add_token'));
        $data = $this->includes;
        // load views
        $data['content'] = $this->load->view('admin/language/tokenform', $content_data, TRUE);
        $this->load->view($this->template, $data);        
    }

    function alpha_dash_space($token)
    {
        if (! preg_match('/^[a-z_]+$/', $token)) {
            $this->form_validation->set_message('alpha_dash_space', 'The %s field may only contain small characters');
            return FALSE;
        } 
        else 
        {
            return TRUE;
        }
    } 
}
