<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Overwriting the timezones function to include Arizona timezone
 */

if (!function_exists('page_meta_key_words')) 
{
    function page_meta_key_words($detail_product_id)
    {
        $ci = & get_instance();
        $ci->load->database();
        $product_array = $ci->AdminSettingModel->get_product_detail_by_id($detail_product_id);
    }
}

                