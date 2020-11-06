<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Sponsors extends Admin_Controller {
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
        $this->add_js_theme('admin-sp.js');

        $this->load->model('SponsorsModel');
        $this->load->library('form_validation');
        $this->load->helper('url');
        $this->load->library('Resize_image');
        // set constants
        define('REFERRER', "referrer");
        define('THIS_URL', base_url('admin/sponsors'));
        define('DEFAULT_LIMIT', 10);
        define('DEFAULT_OFFSET', 0);
        define('DEFAULT_SORT', "id");
        define('DEFAULT_DIR', "asc");
    }
    function index() {
        
        $this->set_title(lang('admin_sponsors_list'));
        $data = $this->includes;
        $content_data = array();
        $data['content'] = $this->load->view('admin/sponsors/list', $content_data, TRUE);
        $this->load->view($this->template, $data);
    }

    function add() 
    {
        $sponsors_logo = NULL;

        $this->form_validation->set_rules('name', lang('admin_sponser_name'), 'required|trim');
        $this->form_validation->set_rules('link', lang('admin_sponser_link'), 'required|trim');

        if (empty($_FILES['logo']['name'])) 
        {
            $this->form_validation->set_rules('logo',lang('admin_sponsors_logo'), 'required|trim');
        }
        else
        {
            $config['upload_path'] = "./assets/images/sponsors";
            $config['allowed_types'] = 'jpg|png|bmp|jpeg';
            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('logo')) 
            {
                $error = $this->upload->display_errors();
                $this->session->set_flashdata('error', $error);
                $this->form_validation->set_rules('logo', lang('admin_sponsors_logo'), 'required|trim');
            }

            $file = $this->upload->data();
            $sponsors_logo = $file['file_name'];
        
        }

        if ($this->form_validation->run() == false) 
        {
            $this->form_validation->error_array();
        } 
        else 
        {
            action_not_permitted();
            $sponsors_content = array();
            $sponsors_content['name'] = $this->input->post('name',TRUE);
            $sponsors_content['link'] = $this->input->post('link',TRUE);
            if($sponsors_logo)
            {                
                $sponsors_content['logo'] = $sponsors_logo;
            }
            $sponsors_content['added'] =  date('Y-m-d H:i:s');

            $sponsors_id = $this->SponsorsModel->insert_sponsors($sponsors_content);

            if($sponsors_id)
            {                
                $this->session->set_flashdata('message', lang('admin_record_added_successfully'));                  
            }
            else
            {
                $this->session->set_flashdata('error', lang('admin_error_adding_record')); 
            }
            redirect(base_url('admin/sponsors'));
        }
            
        $this->set_title(lang('admin_sponser_add'));
        $data = $this->includes;

        $content_data = array();
        // load views
        $data['content'] = $this->load->view('admin/sponsors/form', $content_data, TRUE);
        $this->load->view($this->template, $data);
    }

    function update($sponsors_id = NULL) 
    {
        if(empty($sponsors_id))
        {
           $this->session->set_flashdata('error', lang('invalid_url')); 
           redirect(base_url('admin/sponsors'));
        }

        $sponsors_data = $this->SponsorsModel->get_sponsors_by_id($sponsors_id);

        if(empty($sponsors_data))
        {
           $this->session->set_flashdata('error', lang('admin_invalid_id')); 
           redirect(base_url('admin/sponsors'));
        }

        $this->form_validation->set_rules('name', lang('admin_sponser_name'), 'required|trim');
        $this->form_validation->set_rules('link', lang('admin_sponser_link'), 'required|trim');

        if (empty($_FILES['logo']['name']) && empty($sponsors_data->logo)) 
        {
            $this->form_validation->set_rules('logo',lang('admin_sponsors_logo'), 'required|trim');
        }
        
        $sponsors_logo = NULL;
        if(isset($_FILES['logo']['name']) && $_FILES['logo']['name'])
        {
            $new_name = time().$_FILES["logo"]['name'];
            $config['upload_path'] = "./assets/images/sponsors";
            $config['allowed_types'] = 'jpg|png|bmp|jpeg';
            $config['file_name'] = $new_name;
            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('logo')) 
            {
                $error = $this->upload->display_errors();
                $this->session->set_flashdata('error', $error);
                $this->form_validation->set_rules('logo', lang('admin_sponsors_logo'), 'required|trim');
                
            }

            $file = $this->upload->data();
            $sponsors_logo = $file['file_name'];
        
        }
        
        if ($this->form_validation->run() == false) 
        {
            $this->form_validation->error_array();
        } 
        else 
        {
            action_not_permitted();
            $sponsors_content = array();
            $sponsors_content['name'] = $this->input->post('name',TRUE);
            $sponsors_content['link'] = $this->input->post('link',TRUE);

            if($sponsors_logo)
            {                
                $sponsors_content['logo'] = $sponsors_logo;
            }

            $sponsors_content['updated'] =  date('Y-m-d H:i:s');

            $page_update_status = $this->SponsorsModel->update_sponsors($sponsors_id, $sponsors_content);

            if($page_update_status)
            {
                $this->session->set_flashdata('message', lang('admin_record_updated_successfully'));
            }
            else
            {
                $this->session->set_flashdata('error', lang('admin_error_during_update_record')); 
            }
            redirect(base_url('admin/sponsors'));
        }

        
        $this->set_title(lang('admin_sponsors_update'));
        $data = $this->includes;

        $content_data = array('sponsors_id' => $sponsors_id, 'sponsors_data' => $sponsors_data);
        // load views
        $data['content'] = $this->load->view('admin/sponsors/form', $content_data, TRUE);
        $this->load->view($this->template, $data);
    }

    function delete($sponsors_id = NULL)
    {
        action_not_permitted();
        $status = $this->SponsorsModel->delete_sponsors($sponsors_id);
        if ($status) 
        {
            $this->session->set_flashdata('message', lang('admin_record_delete_successfully'));  
        }
        else
        {
            $this->session->set_flashdata('error', lang('admin_error_during_delete_record')); 
        }
        redirect(base_url('admin/sponsors'));
    }

    function admin_sp_list() 
    {
        $data = array();
        $path = base_url('/assets/images/sponsors/');
        $list = $this->SponsorsModel->get_sponsors();

        $no = $_POST['start'];
        foreach ($list as $sponsors_data) {
            $sponsors_logo = $sponsors_data->logo ? $sponsors_data->logo : 'default.png';
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = ucfirst($sponsors_data->name);
            $row[] = '<img class="testimonial_list_profile" src="'.$path.$sponsors_logo.'">';
            $button = '<a href="' . base_url("admin/sponsors/update/". $sponsors_data->id) . '" data-toggle="tooltip"  title="'.lang('admin_sponsors_update').'" class="btn btn-primary btn-action mr-1"><i class="fas fa-pencil-alt"></i></a>';

            $button.= '<a href="' . base_url("admin/sponsors/delete/" . $sponsors_data->id) . '" data-toggle="tooltip"  title="'.lang('admin_sponsors_delete').'" class="btn btn-danger btn-action mr-1 common_delete"><i class="fas fa-trash"></i></a>';

            $row[] = $button;
            $data[] = $row;
        }
        $output = array("draw" => $_POST['draw'], "recordsTotal" => $this->SponsorsModel->count_all(), "recordsFiltered" => $this->SponsorsModel->count_filtered(), "data" => $data,);
        //output to json format
        echo json_encode($output);
    }
}
