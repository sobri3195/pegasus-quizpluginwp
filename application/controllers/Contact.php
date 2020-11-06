<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Contact extends Public_Controller {
    /**
     * Constructor
     */
    function __construct() {
        parent::__construct();
        // load the model file
        $this->load->model('ContactModel');
        // load the captcha helper
        $this->load->helper('captcha');
    }
    /**************************************************************************************
     * PUBLIC FUNCTIONS
     **************************************************************************************/
    /**
     * Default
     */
    function index() {
        // validators
        $this->form_validation->set_error_delimiters($this->config->item('error_delimeter_left'), $this->config->item('error_delimeter_right'));
        $this->form_validation->set_rules('name', 'Name', 'required|trim|max_length[64]');
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|min_length[10]|max_length[256]');
        $this->form_validation->set_rules('title', 'Title', 'required|trim|max_length[128]');
        $this->form_validation->set_rules('message', 'Message', 'required|trim|min_length[10]');
        $this->form_validation->set_rules('captcha', 'Captcha', 'required|trim|callback__check_captcha');
        if ($this->form_validation->run() == TRUE) {
            // attempt to save and send the message
            $post_data = $this->security->xss_clean($this->input->post());
            $saved_and_sent = $this->ContactModel->save_and_send_message($post_data, $this->settings);
            if ($saved_and_sent) {
                // redirect to home page
                $this->session->set_flashdata('message', sprintf(lang('contact_msg_send_success'), $this->input->post('name', TRUE)));
                redirect(base_url());
            } else {
                // stay on contact page
                $this->error = sprintf(lang('contact_error_send_failed'), $this->input->post('name', TRUE));
            }
        }
        // create captcha image
        $captcha = create_captcha(array('img_path' => "./{$this->settings->captcha_folder}/", 'img_url' => base_url("/{$this->settings->captcha_folder}/"), 'font_path' => FCPATH . "{$this->settings->themes_folder}/core/fonts/bromine/Bromine.ttf", 'img_width' => 170, 'img_height' => 50));
        $captcha_data = array('captcha_time' => $captcha['time'], 'ip_address' => $this->input->ip_address(), 'word' => $captcha['word']);
        // store captcha image
        $this->ContactModel->save_captcha($captcha_data);
        // setup page header data
        $this->set_title(lang('contact_title'));
        $data = $this->includes;
        // set content data
        $content_data = array('captcha_image' => $captcha['image']);
        // load views
        $data['content'] = $this->load->view('contact/form', $content_data, TRUE);
        $this->load->view($this->template, $data);
    }
    /**************************************************************************************
     * PRIVATE VALIDATION CALLBACK FUNCTIONS
     **************************************************************************************/
    /**
     * Verifies correct CAPTCHA value
     *
     * @param  string $captcha
     * @return string|boolean
     */
    function _check_captcha($captcha) {
        $verified = $this->ContactModel->verify_captcha($captcha);
        if ($verified == FALSE) {
            $this->form_validation->set_message('_check_captcha', lang('error_captcha'));
            return FALSE;
        } else {
            return $captcha;
        }
    }
}
