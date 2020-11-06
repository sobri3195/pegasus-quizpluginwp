<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Overwriting the timezones function to include Arizona timezone
 */
if (!function_exists('get_admin_setting')) {
    function get_admin_setting($field_name) {
        $ci = & get_instance();
        $ci->load->database();
        $menu_setting = $ci->AdminSettingModel->get_admin_setting_by_field_name($field_name);
        if (isset($menu_setting->value)) {
            return $menu_setting->value;
        } else {
            return false;
        }
    }
}
if (!function_exists('get_currency_symbol')) {
    function get_currency_symbol($currency_symbol) {
        $locale = 'en-US'; //browser or user locale
        $currency = $currency_symbol;
        $fmt = new NumberFormatter($locale . "@currency=$currency", NumberFormatter::CURRENCY);
        $symbol = $fmt->getSymbol(NumberFormatter::CURRENCY_SYMBOL);
        header("Content-Type: text/html; charset=UTF-8;");
        return $symbol;
    }
}
