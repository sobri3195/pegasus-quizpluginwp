<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Pages extends Admin_Controller {
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
        $this->add_js_theme('pages.js');

        $this->load->model('PagesModel');
        $this->load->library('form_validation');
        $this->load->helper('url');
        $this->load->library('Resize_image');
        // set constants
        define('REFERRER', "referrer");
        define('THIS_URL', base_url('admin/pages'));
        define('DEFAULT_LIMIT', 10);
        define('DEFAULT_OFFSET', 0);
        define('DEFAULT_SORT', "id");
        define('DEFAULT_DIR', "asc");
    }

    function index() {        
        $this->set_title(lang('admin_page_list'));
        $data = $this->includes;
        $content_data = array();
        $data['content'] = $this->load->view('admin/pages/list', $content_data, TRUE);
        $this->load->view($this->template, $data);
    }

    function add() 
    {
        $featured_image = NULL;
        $this->form_validation->set_rules('title', lang('admin_page_title'), 'required|trim|is_unique[pages.title]');
        $this->form_validation->set_rules('content', lang('admin_page_content'), 'required|trim');

        if (empty($_FILES['featured_image']['name'])) 
        {
            $this->form_validation->set_rules('featured_image', lang('admin_page_image'), 'required|trim');
        }
        else
        {
            $config['upload_path'] = "./assets/images/page/";
            $config['allowed_types'] = 'jpg|png|bmp|jpeg';
            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('featured_image')) 
            {
                $error = $this->upload->display_errors();
                $this->session->set_flashdata('error', $error);
                $this->form_validation->set_rules('featured_image', lang('admin_page_image'), 'required|trim');
            }

            $file = $this->upload->data();
            $featured_image = $file['file_name'];
        
        }

        if ($this->form_validation->run() == false) 
        {
            $this->form_validation->error_array();
        } 
        else 
        {
            action_not_permitted();
            $page_content = array();

            $title_slug = slugify_string($this->input->post('title',TRUE));
            $slug_count = $this->PagesModel->page_slug_like_this($title_slug,NULL);

            $page_content['title'] = $this->input->post('title',TRUE);
            $page_content['slug'] = $title_slug.$slug_count;
            $page_content['content'] = $this->input->post('content',TRUE);
            $page_content['on_menu'] = $this->input->post('on_menu',TRUE) ? 1 : 0;
            if($featured_image)
            {                
                $page_content['featured_image'] = $featured_image;
            }
            $page_content['added'] =  date('Y-m-d H:i:s');

            $page_id = $this->PagesModel->insert_pages($page_content);

            if($page_id)
            {                
                $this->session->set_flashdata('message', lang('admin_record_added_successfully'));                  
            }
            else
            {
                $this->session->set_flashdata('error', lang('admin_error_adding_record')); 
            }
             redirect(base_url('admin/pages'));

        }
            
        $this->set_title(lang('admin_add_page'));
        $data = $this->includes;

        $content_data = array();
        // load views
        $data['content'] = $this->load->view('admin/pages/form', $content_data, TRUE);
        $this->load->view($this->template, $data);
    }

    function update($page_id = NULL) 
    {
        if(empty($page_id))
        {
           $this->session->set_flashdata('error', lang('invalid_url')); 
           redirect(base_url('admin/pages'));
        }

        $page_data = $this->PagesModel->get_pages_by_id($page_id);

        if(empty($page_data))
        {
           $this->session->set_flashdata('error', lang('admin_invalid_id')); 
           redirect(base_url('admin/pages'));
        }

        $title_unique = $this->input->post('title')  != $page_data->title ? '|is_unique[pages.title]' : '';

        $this->form_validation->set_rules('title', lang('admin_page_title'), 'required|trim'.$title_unique);
        $this->form_validation->set_rules('content', lang('admin_page_content'), 'required|trim');

        if (empty($_FILES['featured_image']['name']) && empty($page_data->featured_image)) 
        {
            $this->form_validation->set_rules('featured_image',lang('admin_page_image'), 'required|trim');
        }
        
        $featured_image = NULL;
        if(isset($_FILES['featured_image']['name']) && $_FILES['featured_image']['name'])
        {
            $new_name = time().$_FILES["featured_image"]['name'];
            $config['upload_path'] = "./assets/images/page/";
            $config['allowed_types'] = 'jpg|png|bmp|jpeg';
            $config['file_name'] = $new_name;
            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('featured_image')) 
            {
                $error = $this->upload->display_errors();
                $this->session->set_flashdata('error', $error);
                $this->form_validation->set_rules('featured_image', lang('admin_page_image'), 'required|trim');
            }

            $file = $this->upload->data();
            $featured_image = $file['file_name'];
        }
        
        if ($this->form_validation->run() == false) 
        {
            $this->form_validation->error_array();
        } 
        else 
        {
            action_not_permitted();
            $page_content = array();
            $title_slug = slugify_string($this->input->post('title',TRUE));
            $slug_count = $this->PagesModel->page_slug_like_this($title_slug, $page_id);

            $page_content['title'] = $this->input->post('title',TRUE);
            $page_content['slug'] = $title_slug.$slug_count;
            $page_content['content'] = $this->input->post('content',TRUE);
            $page_content['on_menu'] = $this->input->post('on_menu',TRUE) ? 1 : 0;

            if($featured_image)
            {
                $page_content['featured_image'] = $featured_image;
            }

            $page_content['updated'] =  date('Y-m-d H:i:s');

            $page_update_status = $this->PagesModel->update_pages($page_id, $page_content);

            if($page_update_status)
            {
                $this->session->set_flashdata('message', lang('admin_record_updated_successfully'));
            }
            else
            {
                $this->session->set_flashdata('error', lang('admin_error_during_update_record')); 
            }

            redirect(base_url('admin/pages'));
        }

        $this->set_title(lang('admin_page_update'));
        $data = $this->includes;

        $content_data = array('page_id' => $page_id, 'page_data' => $page_data);
        // load views
        $data['content'] = $this->load->view('admin/pages/form', $content_data, TRUE);
        $this->load->view($this->template, $data);
    }

    public function copy($page_id = NULL) 
    {
        action_not_permitted();
        if(empty($page_id))
        {
           $this->session->set_flashdata('error', lang('invalid_url')); 
           redirect(base_url('admin/pages'));
        }

        $page_data = $this->PagesModel->get_pages_by_id($page_id);

        if(empty($page_data))
        {
           $this->session->set_flashdata('error', lang('admin_invalid_id')); 
           redirect(base_url('admin/pages'));
        }

        $page_name_count = $this->PagesModel->pages_name_like_this($page_data->title);
        $slug_count = $this->PagesModel->page_slug_like_this($title_slug, NULL);
        
        $page_name_count = $page_name_count > 0 ? "-".$page_name_count : '';
        
        $page_content = array();

        $page_content['title'] = $page_data->title.'-copy'.$page_name_count;
        $page_content['slug'] = $page_data->slug;
        $page_content['content'] = $page_data->content;
        $page_content['content'] = $page_data->on_menu;
        $page_content['featured_image'] = NULL;
        $page_content['added'] =  date('Y-m-d H:i:s');

        $page_new_id = $this->PagesModel->insert_pages($page_content);
       
        if($page_new_id)
        {
          $this->session->set_flashdata('message', lang('record_copied_successfully'));  
        }
        else
        {
            $this->session->set_flashdata('error', lang('admin_error_during_copying_record')); 
        } 

        redirect(base_url('admin/pages'));
    }

    function delete($page_id = NULL)
    {
        action_not_permitted();
        $status = $this->PagesModel->delete_page($page_id);
        if ($status) 
        {
            $this->session->set_flashdata('message', lang('admin_record_delete_successfully'));  
        }
        else
        {
            $this->session->set_flashdata('error', lang('admin_error_during_delete_record')); 
        }
        redirect(base_url('admin/pages'));
    }

    function page_list() 
    {
        $data = array();
        $list = $this->PagesModel->get_pages();

        $no = $_POST['start'];
        foreach ($list as $page_data) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = ucfirst($page_data->title);
            $row[] = ucfirst($page_data->slug);
            $button = '<a href="' . base_url("admin/pages/update/". $page_data->id) . '" data-toggle="tooltip" title="'.lang("admin_edit_record").'" class="btn btn-primary btn-action mr-1">
            <i class="fas fa-pencil-alt"></i>
            </a>';

            $button.= '<a href="' . base_url("admin/pages/copy/" . $page_data->id) . '" data-toggle="tooltip"  title="'.lang("admin_copy_record").'" class="common_copy_record btn btn-warning mr-1"><i class="far fa-copy"></i></a>';
            $button.= '<a href="' . base_url("admin/pages/delete/" . $page_data->id) . '" data-toggle="tooltip"  title="'.lang("admin_delete_record").'" class="btn btn-danger btn-action mr-1 common_delete"><i class="fas fa-trash"></i></a>';
            $row[] = $button;
            $data[] = $row;
        }
        $output = array("draw" => $_POST['draw'], "recordsTotal" => $this->PagesModel->count_all(), "recordsFiltered" => $this->PagesModel->count_filtered(), "data" => $data,);
        //output to json format
        echo json_encode($output);
    }
}