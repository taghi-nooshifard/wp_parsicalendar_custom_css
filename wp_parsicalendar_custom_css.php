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

include_once WP_PERSIAN_CALENDAR_CUSTOM_CSS_INC.'functions.php';
include WP_PERSIAN_CALENDAR_CUSTOM_CSS_INC.'front'.DIRECTORY_SEPARATOR.'PersianCalendarWidget.php';

add_action("admin_menu","wp_parsicalendar_custom_admin_menu_handle");
function wp_parsicalendar_custom_admin_menu_handle(){
    add_menu_page("تنظیمات تقویم مناسبتی",
        "تنطیمات تقویم فارسی",
        "manage_options",
        "wp_parsicalendar_custom_css",
        "wp_parsicalendar_custom_admin_menu_page");
}
function wp_parsicalendar_custom_admin_menu_page(){

    $year =  ta_latin_num(parsidate_custom("Y"));
    $month  = ta_latin_num(parsidate_custom("m"));
//    $day  = ta_latin_num(parsidate_custom("d"));
//    echo wpp_get_calendar_custom($year,$month,true);

//    $current_day = getdate(time());
//
//    $milady = $current_day["year"].'-'.$current_day["mon"].'-'.$current_day["mday"];
//
//    $shamsi = ta_latin_num(parsidate_custom("Y")).'-'.ta_latin_num(parsidate_custom("m")).'-'.ta_latin_num(parsidate_custom("d"));
//
//
//    $hejri = "";
//    bn_parsidate_custom::getInstance();
//    $my_array = bn_parsidate_custom::getInstance()->hjConvert($current_day["year"],$current_day["mon"],$current_day["mday"]);
//    $hejri = $my_array[0].'-'.$my_array[1].'-'.$my_array[2];
//    $send_date = "Milady: ".$milady." Shamsi:".$shamsi." Hejri:".$hejri;
//    wp_send_json(["message"=>$send_date],200);
//      wpp_get_calendar_json($year,$month);
//      wp_send_json(["message"=>wpp_get_calendar_json($year,$month)],200);
//
    echo "<hr>
<button class='button-primary' id=\"wp_persian_calendar_button\">بارگذاری</button>
        <br>";
}

add_action("admin_enqueue_scripts","wp_parsicalendar_custom_script_handle");
add_action("wp_enqueue_scripts","wp_parsicalendar_custom_script_handle");
function wp_parsicalendar_custom_script_handle(){
    if(is_admin()){


        wp_register_script("wp_front_persian_number",WP_PERSIAN_CALENDAR_CUSTOM_CSS_ASSETS.'front/js/persianNum.jquery-2.min.js',['jquery'],'1.0',true);
        wp_enqueue_script("wp_front_persian_number");

        wp_register_script("wp_front_persian_calendar",WP_PERSIAN_CALENDAR_CUSTOM_CSS_ASSETS.'front/js/wp_front_persian_calendar.js',['jquery','jquery-ui-core','jquery-ui-dialog','jquery-ui-tabs','jquery-ui-button','jquery-effects-core','jquery-effects-fade','jquery-effects-explode'],'1.0',true);
        wp_enqueue_script("wp_front_persian_calendar");


        wp_register_style("wp_front_persian_calendar_css",WP_PERSIAN_CALENDAR_CUSTOM_CSS_ASSETS.'front/css/wp_front_persian_calendar.css',null,'1.0');
        wp_enqueue_style("wp_front_persian_calendar_css");

    }
    else{

        wp_register_script("wp_front_persian_number",WP_PERSIAN_CALENDAR_CUSTOM_CSS_ASSETS.'front/js/persianNum.jquery-2.min.js',['jquery'],'1.0',true);
        wp_enqueue_script("wp_front_persian_number");

        wp_register_script("wp_front_persian_calendar",WP_PERSIAN_CALENDAR_CUSTOM_CSS_ASSETS.'front/js/wp_front_persian_calendar.js',['jquery','jquery-ui-core','jquery-ui-dialog','jquery-ui-tabs','jquery-ui-button','jquery-effects-core','jquery-effects-fade','jquery-effects-explode'],'1.0',true);
        wp_enqueue_script("wp_front_persian_calendar");

        wp_register_style("wp_front_persian_calendar_css",WP_PERSIAN_CALENDAR_CUSTOM_CSS_ASSETS.'front/css/wp_front_persian_calendar.css',null,'1.0');
        wp_enqueue_style("wp_front_persian_calendar_css");

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
