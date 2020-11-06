<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Overwriting the timezones function to include Arizona timezone
 */
if (!function_exists('custominputVariant')) {
    function custominputVariant($variant_Id = NULL, $custom_field_id) {
        $ci = & get_instance();
        $ci->load->database();
        $editcustomfield_value = $ci->product_model->getcustomvariantValue($variant_Id, $custom_field_id);
        return $editcustomfield_value;
    }
}
