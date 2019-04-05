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
        $content =  "<h3>تاریخ شمسی با قابلیت شخصی سازی</h3>";
        echo $content;
    }

    function update( $new_instance, $old_instance ) {
    }

    function widget( $args, $instance ) {
        $header =  "<h3>تاریخ شمسی با قابلیت شخصی سازی</h3>

";
        $content ="<div  dir=\"rtl\" id=\"year_select_dialog\" style=\"display: none\">
    <input style=\"font-family: Tahoma;font-size: 12px;width: 70%\"  id=\"year_select_text\" type=\"text\" placeholder=\"عدد سال را بصورت 4 رقمی وارد کنید\" maxlength=\"4\">
    <input class=\" button\" style=\"font-family: Tahoma;font-size: 12px\"  id=\"year_select_dialog_close\" type=\"button\" value=\"برو\">
</div>
<div dir=\"rtl\" id=\"month_select_dialog\" style=\"display: none\">
    <table>
        <caption style=\"font-family: Tahoma;font-size: 12px\" >ماه مورد نظر را انتخاب کنید</caption>
        <thead></thead>
        <tfoot></tfoot>
        <tbody>
        <tr>
            <td><input style=\"width: 80px;font-family: Tahoma;font-size: 12px\" data-out=\"1\" value=\"فروردین\" class=\"month_select_button button\" type=\"button\" ></td>
            <td><input style=\"width: 80px;font-family: Tahoma;font-size: 12px\" data-out=\"2\" value=\"اردیبهشت\" class=\"month_select_button button\" type=\"button\" ></td>
            <td><input style=\"width: 80px;font-family: Tahoma;font-size: 12px\" data-out=\"3\" value=\"خرداد\" class=\"month_select_button button\" type=\"button\" ></td>
        </tr>
        <tr>
            <td><input style=\"width: 80px;font-family: Tahoma;font-size: 12px\" data-out=\"4\" value=\"تیر\" class=\"month_select_button button\" type=\"button\" ></td>
            <td><input style=\"width: 80px;font-family: Tahoma;font-size: 12px\" data-out=\"5\" value=\"مرداد\" class=\"month_select_button button\" type=\"button\" ></td>
            <td><input style=\"width: 80px;font-family: Tahoma;font-size: 12px\" data-out=\"6\" value=\"شهریور\" class=\"month_select_button button\" type=\"button\" ></td>
        </tr>
        <tr>
            <td><input style=\"width: 80px;font-family: Tahoma;font-size: 12px\" data-out=\"7\" value=\"مهر\" class=\"month_select_button button\" type=\"button\" ></td>
            <td><input style=\"width: 80px;font-family: Tahoma;font-size: 12px\" data-out=\"8\" value=\"آبان\" class=\"month_select_button button\" type=\"button\" ></td>
            <td><input style=\"width: 80px;font-family: Tahoma;font-size: 12px\" data-out=\"9\" value=\"آذر\" class=\"month_select_button button\" type=\"button\" ></td>
        </tr>
        <tr>
            <td><input style=\"width: 80px;font-family: Tahoma;font-size: 12px\" data-out=\"10\" value=\"دی\" class=\"month_select_button button\" type=\"button\" ></td>
            <td><input style=\"width: 80px;font-family: Tahoma;font-size: 12px\" data-out=\"11\" value=\"بهمن\" class=\"month_select_button button\" type=\"button\" ></td>
            <td><input style=\"width: 80px;font-family: Tahoma;font-size: 12px\" data-out=\"12\" value=\"اسفند\" class=\"month_select_button button\" type=\"button\" ></td>
        </tr>

        </tbody>
    </table>
</div>
";

        $year =  ta_latin_num(parsidate_custom("Y"));
        $month  = ta_latin_num(parsidate_custom("m"));
        echo $content.$header.wpp_get_calendar_custom($year,$month);

    }

}

