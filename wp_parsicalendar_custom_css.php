<?php
/*
Plugin Name: تقویم فارسی با قابلیت تغییر قالب
Plugin URI: https://txtzoom.com/
Description: تقویم فارسی با قابلیت تغییر قالب با کمک از پلاگین تقویم فارسی ورد پرس و همپنین سایت تقویم فارسی تولید شده است.(https://farsicalendar.com/)
Version: 1.0
Author: Taghi Nooshifard
Author URI: https://txtzoom.com/
License: GPLv2 or later
Text Domain: persianCalendar
*/

define('WP_PERSIAN_CALENDAR_CUSTOM_CSS_INC',plugin_dir_path(__FILE__).DIRECTORY_SEPARATOR.'inc'.DIRECTORY_SEPARATOR);
define('WP_PERSIAN_CALENDAR_CUSTOM_CSS_TPL',plugin_dir_path(__FILE__).DIRECTORY_SEPARATOR.'tpl'.DIRECTORY_SEPARATOR);
define('WP_PERSIAN_CALENDAR_CUSTOM_CSS_ASSETS',plugin_dir_url(__FILE__).'/assets/');
define('WP_OPTION_NAME','wp_parsicalendar_custom_css');

include_once WP_PERSIAN_CALENDAR_CUSTOM_CSS_INC.'functions.php';
include WP_PERSIAN_CALENDAR_CUSTOM_CSS_INC.'front'.DIRECTORY_SEPARATOR.'PersianCalendarWidget.php';
register_activation_hook(__FILE__,"wp_parsicalendar_custom_activation_handler");
function wp_parsicalendar_custom_activation_handler(){
    if(get_option(WP_OPTION_NAME)==false){
        $content = file_get_contents(WP_PERSIAN_CALENDAR_CUSTOM_CSS_ASSETS.'front'.DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR.'wp_front_persian_calendar.css');
        add_option(WP_OPTION_NAME,$content);
    }
}

add_action("wp_head","wp_parsicalendar_custom_css_handler");
function wp_parsicalendar_custom_css_handler(){
    echo "<style>".get_option(WP_OPTION_NAME)."</style>";
}

add_action("admin_menu","wp_parsicalendar_custom_admin_menu_handle");
function wp_parsicalendar_custom_admin_menu_handle(){
    add_menu_page("تنظیمات تقویم مناسبتی",
        "تنطیمات تقویم فارسی",
        "manage_options",
        "wp_parsicalendar_custom_css",
        "wp_parsicalendar_custom_admin_menu_page");
}
function wp_parsicalendar_custom_admin_menu_page(){

    if(is_admin())
        include WP_PERSIAN_CALENDAR_CUSTOM_CSS_TPL.'admin'.DIRECTORY_SEPARATOR.'wp_parsicalendar_custom_admin_page.php';
}

add_action("admin_init","add_save_post_save_wp_parsi_calendar_css");
function add_save_post_save_wp_parsi_calendar_css(){
    add_action("admin_post_save_wp_parsi_calendar_css","save_wp_parsi_calendar_css");

}
function save_wp_parsi_calendar_css(){
    if(!current_user_can("manage_options"))
        wp_die("کاربر مجوز ندارد");
    check_admin_referer(WP_OPTION_NAME);
    $message = 1;
    if(isset($_POST["resetstyle"])){
        $stylesheet =  file_get_contents(WP_PERSIAN_CALENDAR_CUSTOM_CSS_ASSETS.'front'.DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR.'wp_front_persian_calendar.css');
        update_option(WP_OPTION_NAME,$stylesheet);
        $message = 2;
    }
    elseif (isset($_POST["save_wp_parsi_calendar_css"])){
        $stylesheet =  $_POST["stylesheet"];
        update_option(WP_OPTION_NAME,$stylesheet);
        $message = 1;
    }

    wp_redirect(add_query_arg(array("page"=>"wp_parsicalendar_custom_css",
            "message"=>$message)
        ,admin_url('admin.php')));
}


add_action("admin_enqueue_scripts","wp_parsicalendar_custom_script_handle");
add_action("wp_enqueue_scripts","wp_parsicalendar_custom_script_handle");
function wp_parsicalendar_custom_script_handle(){
    if(is_admin()){


        wp_register_script("wp_front_persian_number",WP_PERSIAN_CALENDAR_CUSTOM_CSS_ASSETS.'front/js/persianNum.jquery-2.min.js',['jquery'],'1.0',true);
        wp_enqueue_script("wp_front_persian_number");

        wp_register_script("wp_front_persian_calendar",WP_PERSIAN_CALENDAR_CUSTOM_CSS_ASSETS.'front/js/wp_front_persian_calendar.js',['jquery','jquery-ui-core','jquery-ui-dialog','jquery-ui-tabs','jquery-ui-button','jquery-effects-core','jquery-effects-fade','jquery-effects-explode','jquery-ui-tooltip'],'1.0',true);
        wp_enqueue_script("wp_front_persian_calendar");


        wp_register_style("wp_front_persian_calendar_css",WP_PERSIAN_CALENDAR_CUSTOM_CSS_ASSETS.'front/css/wp_front_persian_calendar.css',null,'1.0');
        wp_enqueue_style("wp_front_persian_calendar_css");

    }
    else{

        wp_register_script("wp_front_persian_number",WP_PERSIAN_CALENDAR_CUSTOM_CSS_ASSETS.'front/js/persianNum.jquery-2.min.js',['jquery'],'1.0',true);
        wp_enqueue_script("wp_front_persian_number");

        wp_register_script("wp_front_persian_calendar",WP_PERSIAN_CALENDAR_CUSTOM_CSS_ASSETS.'front/js/wp_front_persian_calendar.js',['jquery','jquery-ui-core','jquery-ui-dialog','jquery-ui-tabs','jquery-ui-button','jquery-effects-core','jquery-effects-fade','jquery-effects-explode','jquery-ui-tooltip'],'1.0',true);
        wp_enqueue_script("wp_front_persian_calendar");

//        wp_register_style("wp_front_persian_calendar_css",WP_PERSIAN_CALENDAR_CUSTOM_CSS_ASSETS.'front/css/wp_front_persian_calendar.css',null,'1.0');
//        wp_enqueue_style("wp_front_persian_calendar_css");

    }
}

function wp_persian_calendar_init()
{

    if ( is_user_logged_in()) {
        include WP_PERSIAN_CALENDAR_CUSTOM_CSS_INC.'front'.DIRECTORY_SEPARATOR.'wp_parcicalendar_logged_in.php';

    } else {
        include WP_PERSIAN_CALENDAR_CUSTOM_CSS_INC.'front'.DIRECTORY_SEPARATOR.'wp_parcicalendar_not_logged_in.php';

    }
}
add_action('init', 'wp_persian_calendar_init');

function PersianCalendarWidget_register_widget() {

    register_widget( 'PersianCalendarWidget' );

}
add_action( 'widgets_init', 'PersianCalendarWidget_register_widget' );
