<?php
/**
 * Created by PhpStorm.
 * User: phpDev
 * Date: 4/3/2019
 * Time: 11:04 AM
 */

class PersianCalendarWidget extends WP_Widget
{
    function __construct() {
        global $wp_version;
        if ( version_compare( $wp_version, '4.3', '>=' ) ) {
            parent::__construct( false, __( 'تاریخ هجری شمسی', 'PersianCalendarWidget' ), 'description=' . __( 'تاریخ هجری شمسی', 'PersianCalendarWidget' ) );
        } else {
            parent::WP_Widget( false, __( 'تاریخ هجری شمسی', 'PersianCalendarWidget' ), 'description=' . __( 'تاریخ هجری شمسی', 'PersianCalendarWidget' ) );
        }
    }

    function form( $instance ) {
        echo "<h3>تاریخ شمسی با قابلیت شخصی سازی</h3>";
    }

    function update( $new_instance, $old_instance ) {
    }

    function widget( $args, $instance ) {
        $header =  "<h3>تاریخ شمسی با قابلیت شخصی سازی</h3>";
        $year =  ta_latin_num(parsidate_custom("Y"));
        $month  = ta_latin_num(parsidate_custom("m"));
        echo $header.wpp_get_calendar_custom($year,$month);

    }

}

