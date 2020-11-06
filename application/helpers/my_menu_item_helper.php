<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Overwriting the timezones function to include Arizona timezone
 */

if (!function_exists('get_header_menu_item_helper')) {
    function get_header_menu_item_helper() {
        $ci = & get_instance();
        $ci->load->database();
        $menu_item = $ci->MenuItemModel->get_header_menu();
        return $menu_item;
    }
}

if (!function_exists('get_footer_section_helper')) {
    function get_footer_section_helper($section_no) {
        $ci = & get_instance();
        $ci->load->database();
        $helper_footer_data = $ci->MenuItemModel->get_footer_section($section_no);
        return $helper_footer_data;
    }
}

if (!function_exists('get_menu_item_helper')) {
    function get_menu_item_helper() {
        $ci = & get_instance();
        $ci->load->database();
        $category_menu_item = $ci->MenuItemModel->get_category_menu_item();
        return $category_menu_item;
    }
}

if (!function_exists('get_other_menu_item_helper')) {
    function get_other_menu_item_helper() {
        $ci = & get_instance();
        $ci->load->database();
        $category_other_menu_item = $ci->MenuItemModel->get_other_menu_item();
        return $category_other_menu_item;
    }
}

if (!function_exists('get_footer_markets_helper')) {
    function get_footer_markets_helper() {
        $ci = & get_instance();
        $ci->load->database();
        $get_footer_markets = $ci->MenuItemModel->get_footer_markets();
        return $get_footer_markets;
    }
}

if (!function_exists('get_all_category_helper')) {
    function get_all_category_helper() {
        $ci = & get_instance();
        $ci->load->database();
        $get_footer_markets = $ci->MenuItemModel->get_all_category_helper();
        return $get_footer_markets;
    }
}
