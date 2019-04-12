<?php
add_action("wp_ajax_wp_persian_calendar_custom_load","wp_persian_calendar_custom_load");
function wp_persian_calendar_custom_load(){
    $year =  ta_latin_num(parsidate_custom("Y"));
    $month  = ta_latin_num(parsidate_custom("m"));
    $json_string = wpp_get_calendar_json($year,$month);
    wp_send_json(["message"=>$json_string],200);
}
add_action("wp_ajax_wp_persian_calendar_custom_next_month","wp_persian_calendar_custom_next_month");
function wp_persian_calendar_custom_next_month(){
    $year =  intval($_POST["year"]);
    $month  = intval($_POST["month"]);
    $json_string = wpp_get_calendar_json($year,$month);
    wp_send_json(["message"=>$json_string],200);


}
add_action("wp_ajax_wp_persian_calendar_custom_prev_month","wp_persian_calendar_custom_prev_month");
function wp_persian_calendar_custom_prev_month(){
    $year =  intval($_POST["year"]);
    $month  = intval($_POST["month"]);
    $json_string = wpp_get_calendar_json($year,$month);
    wp_send_json(["message"=>$json_string],200);


}

