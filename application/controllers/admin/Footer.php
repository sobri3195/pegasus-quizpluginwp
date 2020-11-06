<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Footer extends Admin_Controller {
    function __construct() {
        parent::__construct();
        $this->add_css_theme('all.css');
        $this->add_css_theme('bootstrap4-toggle.min.css');
        $this->add_js_theme('bootstrap4-toggle.min.js');

        $this->add_css_theme('select2.min.css');
        $this->add_css_theme('summernote.css');
        $this->add_js_theme('summernote.min.js');

        $this->add_css_theme('sweetalert.css');
        $this->add_js_theme('sweetalert-dev.js');
        $this->add_js_theme('jquery-ui.min.js');
        $this->add_js_theme('footer_admin.js');

        $this->load->model('FooterModel');
        $this->load->library('form_validation');
        $this->load->helper('url');
        $this->load->library('Resize_image');
        // set constants
        define('REFERRER', "referrer");
        define('THIS_URL', base_url('admin/footer'));
        define('DEFAULT_LIMIT', 10);
        define('DEFAULT_OFFSET', 0);
        define('DEFAULT_SORT', "id");
        define('DEFAULT_DIR', "asc");
    }

    function index() 
    {
        $featured_image = NULL;
        $this->form_validation->set_rules('input_type', lang('admin_footer_input_type'), 'required|trim');
        $this->form_validation->set_rules('footer_section', lang('admin_footer_section'), 'required|trim');

        if ($this->form_validation->run() == false) 
        {
            $this->form_validation->error_array();
        } 
        else 
        {
            action_not_permitted();
            $post_data = $this->input->post('post_data');
            $field_type = array('text','link','editor','image');
            $i = 1;
            $data_to_be_inserted = array();

            foreach ($post_data as $section_no => $input_field_array) 
            {
                foreach ($input_field_array as  $type => $title_value) 
                {
                    if($type == 'image')
                    {
                        foreach ($title_value['title'] as $key => $title) 
                        {                            
                            $config['upload_path'] = "./assets/images/footer/section";
                            $config['allowed_types'] = 'jpg|png|bmp|jpeg';

                            $featured_image = NULL;
                            $_FILES['image_name']['name']       = $_FILES['post_data']['name'][$section_no][$type]['value'][$key];
                            $_FILES['image_name']['type']       = $_FILES['post_data']['type'][$section_no][$type]['value'][$key];
                            $_FILES['image_name']['tmp_name']   = $_FILES['post_data']['tmp_name'][$section_no][$type]['value'][$key];
                            $_FILES['image_name']['error']      = $_FILES['post_data']['error'][$section_no][$type]['value'][$key];
                            $_FILES['image_name']['size']       = $_FILES['post_data']['size'][$section_no][$type]['value'][$key];

                            $new_name                   = time().'_TIME_'.$_FILES["image_name"]['name'];
                            $config['file_name']        = $new_name;

                            $this->load->library('upload', $config);

                            if (!$this->upload->do_upload('image_name')) 
                            {
                                $error = $this->upload->display_errors();
                                $this->session->set_flashdata('error', $error);
                                $this->form_validation->set_rules('image_name', 'Image', 'required|trim');
                                
                                if(isset($post_data[$section_no]['image']['last_img'][$key]))
                                {
                                     $featured_image = $post_data[$section_no]['image']['last_img'][$key];
                                }
                            }
                            else
                            {
                                $file = $this->upload->data();
                                $featured_image = $file['file_name'];
                            }

                            $data_to_be_inserted[] = array(
                                'section_number' => $section_no, 
                                'type' => $type, 
                                'title' => $title, 
                                'value' => $featured_image,
                                'position' => $i, 
                                'updated' =>  date('Y-m-d H:i:s'),
                            );                        
                        }
                    }
                    else
                    {
                        foreach ($title_value['title'] as $key => $title) {
                            $data_to_be_inserted[] = array(
                                'section_number' => $section_no, 
                                'type' => $type, 
                                'title' => $title, 
                                'value' => $title_value['value'][$key],
                                'position' => $key, 
                                'updated' =>  date('Y-m-d H:i:s'),
                            );
                        }
                    }
                }
            }

            $this->FooterModel->delete_fotter();

            $status = $this->FooterModel->insert_fotter_sections($data_to_be_inserted);
            if($status)
            {
                 $this->session->set_flashdata('message', lang('admin_record_updated_successfully'));    
            }
            else
            {
                 $this->session->set_flashdata('error', lang('admin_error_during_update_record')); 
            }
            redirect(base_url('admin/footer'));
        }

        $footer_section_data['first'] = $this->FooterModel->get_footer_section(1);
        $footer_section_data['second'] = $this->FooterModel->get_footer_section(2);
        $footer_section_data['third'] = $this->FooterModel->get_footer_section(3);
        $footer_section_data['fourth'] = $this->FooterModel->get_footer_section(4);
            
        $this->set_title(lang('admin_footer_section'));
        $data = $this->includes;

        $content_data = array('footer_section_data'=> $footer_section_data);
        // load views
        $data['content'] = $this->load->view('admin/footer/form', $content_data, TRUE);
        $this->load->view($this->template, $data);
    }

    function delete($page_id = NULL)
    {
        action_not_permitted();
        redirect(base_url('admin/footer'));
        $status = $this->FooterModel->delete_page($page_id);
        if ($status) 
        {
            $this->session->set_flashdata('message', lang('admin_record_delete_successfully'));  
        }
        else
        {
            $this->session->set_flashdata('error', lang('admin_error_during_delete_record')); 
        }
        redirect(base_url('admin/footer'));
    }
}
