<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Testimonial extends Admin_Controller {
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
        $this->add_js_theme('testimonial.js');

        $this->load->model('TestimonialModel');
        $this->load->library('form_validation');
        $this->load->helper('url');
        $this->load->library('Resize_image');
        // set constants
        define('REFERRER', "referrer");
        define('THIS_URL', base_url('admin/testimonial'));
        define('DEFAULT_LIMIT', 10);
        define('DEFAULT_OFFSET', 0);
        define('DEFAULT_SORT', "id");
        define('DEFAULT_DIR', "asc");
    }
    function index() {
        $this->set_title(lang('admin_testimonial_list'));
        $data = $this->includes;
        $content_data = array();
        $data['content'] = $this->load->view('admin/testimonial/list', $content_data, TRUE);
        $this->load->view($this->template, $data);
    }

    function add() 
    {
        $profile_img = NULL;

        $this->form_validation->set_rules('name', lang('admin_testimonial_name'), 'required|trim');
        $this->form_validation->set_rules('content',lang('admin_testimonial_message'), 'required|trim');

        if (empty($_FILES['profile']['name'])) 
        {
            $this->form_validation->set_rules('profile', lang('admin_testimonial_image'), 'required|trim');
        }
        else
        {
            $config['upload_path'] = "./assets/images/testimonial";
            $config['allowed_types'] = 'jpg|png|bmp|jpeg';
            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('profile')) 
            {
                $error = $this->upload->display_errors();
                $this->session->set_flashdata('error', $error);
                $this->form_validation->set_rules('profile', lang('admin_testimonial_image'), 'required|trim');
            }

            $file = $this->upload->data();
            $profile_img = $file['file_name'];
        }

        if ($this->form_validation->run() == false) 
        {
            $this->form_validation->error_array();
        } 
        else 
        {
            action_not_permitted();
            $testimonial_content = array();

            $testimonial_content['name'] = $this->input->post('name',TRUE);
            $testimonial_content['content'] = $this->input->post('content',TRUE);
            if($profile_img)
            {
                $testimonial_content['profile'] = $profile_img;
            }

            $testimonial_content['added'] =  date('Y-m-d H:i:s');
            $testimonial_id = $this->TestimonialModel->insert_testimonial($testimonial_content);

            if($testimonial_id)
            {
                $this->session->set_flashdata('message', lang('admin_record_added_successfully'));
            }
            else
            {
                $this->session->set_flashdata('error', lang('admin_error_adding_record')); 
            }

            redirect(base_url('admin/testimonial'));
        }

        $this->set_title(lang('admin_testimonial_add'));
        $data = $this->includes;

        $content_data = array();
        // load views
        $data['content'] = $this->load->view('admin/testimonial/form', $content_data, TRUE);
        $this->load->view($this->template, $data);
    }

    function update($testimonial_id = NULL) 
    {
        if(empty($testimonial_id))
        {
           $this->session->set_flashdata('error', lang('invalid_url')); 
           redirect(base_url('admin/testimonial'));
        }

        $testimonial_data = $this->TestimonialModel->get_testimonial_by_id($testimonial_id);

        if(empty($testimonial_data))
        {
           $this->session->set_flashdata('error', lang('admin_invalid_id')); 
           redirect(base_url('admin/testimonial'));
        }

        $this->form_validation->set_rules('name', lang('admin_testimonial_name'), 'required|trim');
        $this->form_validation->set_rules('content', lang('admin_testimonial_message'), 'required|trim');

        if (empty($_FILES['profile']['name']) && empty($testimonial_data->profile)) 
        {
            $this->form_validation->set_rules('profile',lang('admin_testimonial_image'), 'required|trim');
        }

        $profile_img = NULL;
        if(isset($_FILES['profile']['name']) && $_FILES['profile']['name'])
        {
            $new_name = time().$_FILES["profile"]['name'];
            $config['upload_path'] = "./assets/images/testimonial";
            $config['allowed_types'] = 'jpg|png|bmp|jpeg';
            $config['file_name'] = $new_name;
            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('profile')) 
            {
                $error = $this->upload->display_errors();
                $this->session->set_flashdata('error', $error);
                $this->form_validation->set_rules('profile', lang('admin_testimonial_image'), 'required|trim');
            }

            $file = $this->upload->data();
            $profile_img = $file['file_name'];
        }

        if ($this->form_validation->run() == false) 
        {
            $this->form_validation->error_array();
        } 
        else 
        {
            action_not_permitted();
            $testimonial_content = array();

            $testimonial_content['name'] = $this->input->post('name',TRUE);
            $testimonial_content['content'] = $this->input->post('content',TRUE);

            if($profile_img)
            {
                $testimonial_content['profile'] = $profile_img;
            }

            $testimonial_content['updated'] =  date('Y-m-d H:i:s');
            $page_update_status = $this->TestimonialModel->update_testimonial($testimonial_id, $testimonial_content);

            if($page_update_status)
            {
                $this->session->set_flashdata('message', lang('admin_record_updated_successfully'));
            }
            else
            {
                $this->session->set_flashdata('error', lang('admin_error_during_update_record')); 
            }
            
            redirect(base_url('admin/testimonial'));
        }

        $this->set_title(lang('admin_testimonial_update'));
        $data = $this->includes;

        $content_data = array('testimonial_id' => $testimonial_id, 'testimonial_data' => $testimonial_data);
        // load views
        $data['content'] = $this->load->view('admin/testimonial/form', $content_data, TRUE);
        $this->load->view($this->template, $data);
    }

    function delete($testimonial_id = NULL)
    {
        action_not_permitted();
        $status = $this->TestimonialModel->delete_testimonial($testimonial_id);
        if ($status) 
        {
            $this->session->set_flashdata('message', lang('admin_record_delete_successfully'));  
        }
        else
        {
            $this->session->set_flashdata('error', lang('admin_error_during_delete_record')); 
        }
        redirect(base_url('admin/testimonial'));
    }

    function testimonial_list() 
    {
        $data = array();
        $path = base_url('/assets/images/testimonial/');
        $list = $this->TestimonialModel->get_testimonial();

        $no = $_POST['start'];
        foreach ($list as $testimonial_data) {
            $testimonial_profile = $testimonial_data->profile ? $testimonial_data->profile : 'default.png';
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = ucfirst($testimonial_data->name);
            $row[] = '<img class="testimonial_list_profile" src="'.$path.$testimonial_profile.'">';
            $button = '<a href="' . base_url("admin/testimonial/update/". $testimonial_data->id) . '"  data-toggle="tooltip"  title="'.lang("admin_testimonial_edit").'" class="btn btn-primary btn-action mr-1"><i class="fas fa-pencil-alt"></i></a>';

            $button.= '<a href="' . base_url("admin/testimonial/delete/" . $testimonial_data->id) . '"  data-toggle="tooltip" title="'.lang("admin_testimonial_delete").'" class="btn btn-danger btn-action mr-1 common_delete"><i class="fas fa-trash"></i></a>';
            $row[] = $button;
            $data[] = $row;
        }
        $output = array("draw" => $_POST['draw'], "recordsTotal" => $this->TestimonialModel->count_all(), "recordsFiltered" => $this->TestimonialModel->count_filtered(), "data" => $data,);
        //output to json format
        echo json_encode($output);
    }

}
