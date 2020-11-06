<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Settings extends Admin_Controller {
    /**
     * Constructor
     */
    function __construct() {
        parent::__construct();
    }
    /**
     * Settings Editor
     */
    function index() {

        $all_lang = $this->SettingsModel->all_lang_list();
        $lang_array = array();
        foreach ($all_lang as  $all_lang_array) 
        {
            $lang_array[$all_lang_array->lang] = $all_lang_array->lang;
        }
        $lang_array = $lang_array ? $lang_array : $lang_array['English'] = 'English';
        $lang_array = json_encode($lang_array);
        $this->SettingsModel->update_lang_options($lang_array);

        // get settings
        $settings = $this->SettingsModel->get_settings();

        // form validations
        $this->form_validation->set_error_delimiters($this->config->item('error_delimeter_left'), $this->config->item('error_delimeter_right'));
        foreach ($settings as $setting) {
            if ($setting['validation']) {
                if ($setting['translate']) {
                    // setup a validation for each translation
                    foreach ($this->session->languages as $language_key => $language_name) {
                        $this->form_validation->set_rules($setting['name'] . "[" . $language_key . "]", $setting['label'] . " [" . $language_name . "]", $setting['validation']);
                    }
                } else {
                    // single validation
                    $this->form_validation->set_rules($setting['name'], $setting['label'], $setting['validation']);
                }
            }
        }
        if ($this->form_validation->run() == TRUE) {
            action_not_permitted();
            $user = $this->session->userdata('logged_in');
            $form_data = $this->input->post();
            if (isset($_FILES['site_logo']['name']) && $_FILES['site_logo']['name']) {
                $status = NULL;
                $status = $this->do_upload($_FILES['site_logo'], 'site_logo');
                if ($status['status'] == 'error') {
                    $this->session->set_flashdata('error', 'Site Logo ' . $status['response']);
                    return redirect(base_url('admin/settings'));
                } else {
                    $form_data['site_logo'] = $status['file_name'];
                }
            }
            if (isset($_FILES['site_favicon']['name']) && $_FILES['site_favicon']['name']) {
                $site_favicon = NULL;
                $site_favicon = $this->do_upload($_FILES['site_favicon'], 'site_favicon');
                if ($site_favicon['status'] == 'error') {
                    $this->session->set_flashdata('error', 'Site Favicon ' . $site_favicon['response']);
                    return redirect(base_url('admin/settings'));
                } else {
                    $form_data['site_favicon'] = $site_favicon['file_name'];
                }
            }
            if (isset($_FILES['home_first_slide']['name']) && $_FILES['home_first_slide']['name']) {
                $home_first_slide = NULL;
                $home_first_slide = $this->do_upload($_FILES['home_first_slide'], 'home_first_slide');
                if ($home_first_slide['status'] == 'error') {
                    $this->session->set_flashdata('error', 'Home First Slide ' . $home_first_slide['response']);
                    return redirect(base_url('admin/settings'));
                } else {
                    $form_data['home_first_slide'] = $home_first_slide['file_name'];
                }
            }
            if (isset($_FILES['home_slide_second']['name']) && $_FILES['home_slide_second']['name']) {
                $home_slide_second = NULL;
                $home_slide_second = $this->do_upload($_FILES['home_slide_second'], 'home_slide_second');
                if ($home_slide_second['status'] == 'error') {
                    $this->session->set_flashdata('error', 'Home Second Slide ' . $home_slide_second['response']);
                    return redirect(base_url('admin/settings'));
                } else {
                    $form_data['home_slide_second'] = $home_slide_second['file_name'];
                }
            }
            if (isset($_FILES['home_third_slide']['name']) && $_FILES['home_third_slide']['name']) {
                $home_third_slide = NULL;
                $home_third_slide = $this->do_upload($_FILES['home_third_slide'], 'home_third_slide');
                if ($home_third_slide['status'] == 'error') {
                    $this->session->set_flashdata('error', 'Home First Slide ' . $home_third_slide['response']);
                    return redirect(base_url('admin/settings'));
                } else {
                    $form_data['home_third_slide'] = $home_third_slide['file_name'];
                }
            }
            // save the settings
            $saved = $this->SettingsModel->save_settings($form_data, $user['id']);
            if ($saved) {
                $this->session->set_flashdata('message', lang('admin_record_added_successfully'));
                // reload the new settings
                $settings = $this->SettingsModel->get_settings();
                foreach ($settings as $setting) {
                    $this->settings->{$setting['name']} = @json_decode($setting['value']);
                }
            } else {
                $this->session->set_flashdata('error', lang('admin_error_adding_record'));
            }
            // reload the page
            redirect('admin/settings');
        }
        // setup page header data
        $this->add_css_theme('summernote.css');
        $this->add_js_theme('summernote.min.js');
        $this->add_js_theme('settings_i18n.js', TRUE);
        $this->set_title(lang('admin_settings'));
        $data = $this->includes;
        // set content data
        $content_data = array('settings' => $settings, 'cancel_url' => "/admin",);
        // load views
        $data['content'] = $this->load->view('admin/settings/form', $content_data, TRUE);
        $this->load->view($this->template, $data);
    }
    public function do_upload($file_data, $file_name) {
        $config['upload_path'] = "./assets/images/logo/";
        $config['allowed_types'] = 'gif|jpg|png|ico';
        $config['overwrite'] = TRUE;
        $new_name = time() . $file_data['name'];
        $config['file_name'] = $new_name;
        $this->load->library('upload');
        $this->upload->initialize($config);
        if (!$this->upload->do_upload($file_name)) {
            $response['response'] = $this->upload->display_errors();
            $response['status'] = 'error';
            return $response;
        } else {
            $response['success'] = $this->upload->data();
            $response['status'] = 'success';
            $response['file_name'] = $this->upload->data('file_name');
            return $response;
        }
    }
}
