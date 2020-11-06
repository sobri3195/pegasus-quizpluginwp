<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Admin_setting_Controller extends Admin_Controller {
    /**
     * Constructor
     */
    function __construct() {
        parent::__construct();
        // load the language files
    }
    function index() {
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
            // save the settings
            $saved = $this->SettingsModel->save_settings($this->input->post(), $user['id'], TRUE);
            if ($saved) {
                $this->session->set_flashdata('message', lang('admin_settings_msg_save_success'));
                // reload the new settings
                $settings = $this->SettingsModel->get_settings();
                foreach ($settings as $setting) {
                    $this->settings->{$setting['name']} = @json_decode($setting['value']);
                }
            } else {
                $this->session->set_flashdata('error', lang('admin_settings_error_save_failed'));
            }
            // reload the page
            redirect('admin/settings');
        }
        // setup page header data
        $this->add_css_theme('summernote.css');
        $this->add_js_theme('summernote.min.js');
        $this->add_js_theme('settings_i18n.js', TRUE);
        $this->set_title(lang('admin settings title'));
        $data = $this->includes;
        // set content data
        $content_data = array('settings' => $settings, 'cancel_url' => "/admin");
        // load views
        $data['content'] = $this->load->view('admin/settings/form', $content_data, TRUE);
        $this->load->view($this->template, $data);
    }
}
