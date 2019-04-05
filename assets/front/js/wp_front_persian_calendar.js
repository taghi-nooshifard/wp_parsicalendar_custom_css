jQuery(document).ready(function ($) {

    load();
    function load(){
        // var node = document.createElement("div");
        // $(node).attr('id','wp_persian_calendar_main').html('<h1>تقویم فارسی</h1>');
        //
        // document.getElementById("wpbody-content").appendChild(node);
        console.log("wp_pesian_calendar loaded!")
        WidgetConstructor(null);

    }
    function getClassName($class_name1,$mystate) {
        if($mystate!=NaN && $mystate==true)
            return " "+$class_name1+" ";
        else
            return "";
    }
    function ajax_send(year,month) {
        $.ajax({
            url:'wp-admin/admin-ajax.php',
            // url:'admin-ajax.php',
            type:'post',
            data:{
                action:'wp_persian_calendar_custom_next_month',
                year:year,
                month:month,
            },
            success:function (response) {


                console.log(response);
                console.log(response.message);
                let my_json = JSON.parse(response.message);
                WidgetConstructor(my_json);


            },
            error:function (error) {
                console.log(error);

                $("#wp_persian_calendar_main").html(error.responseText);
            }

        });
    }
    function generateTableHeader(json_persian_dates) {
        let content =   "<caption>"
            +"<div style='height: 24px' class='shamsi_header'><span style='width: 20%;float: right' class=\"dashicons dashicons-arrow-down-alt month_select_icon\"></span> <div class='persian' style='width: 50%;float: right' id='wp_persian_tbl_caption_shamsi'"+
            " data-syear='"+ parseInt(json_persian_dates[0].header.shamsi["data-syear"])+
            "' data-smonth='"+ parseInt(json_persian_dates[0].header.shamsi["data-smonth"])
            +" ' data-current-year='"+parseInt(json_persian_dates[0].header.today["year"])
            +"' "
            +" data-current-month='"+parseInt(json_persian_dates[0].header.today["month"])+"'"
            +"  "
            +" > "
            +json_persian_dates[0].header.shamsi["data-sstring"]
            +"</div><span style='width: 30%;float: right' class=\"dashicons dashicons-arrow-down-alt year_select_icon \"></span></div>"

            +"<div class='milady_header'><div class='english' id='wp_persian_tbl_caption_miladi_month_from' data-mmonth-from='"
            +json_persian_dates[0].header.milady["data-mmonth-from"]

            +"'>"
            +json_persian_dates[0].header.milady["data-mstring-month-from"]
            +"</div>";
            if(json_persian_dates[0].header.milady["data-myear-from"]
                == json_persian_dates[0].header.milady["data-myear-to"]){
                content = content  +"<div class='english' style='width:44%' id='wp_persian_tbl_caption_miladi_month_to' data-mmonth-to='"
                    +json_persian_dates[0].header.milady["data-mmonth-to"]

                    +"'>"
                    +json_persian_dates[0].header.milady["data-mstring-month-to"]
                    +"</div>";
            }
            content = content + "<div class='english' id='wp_persian_tbl_caption_miladi_year_from' data-myear-from='"+
                json_persian_dates[0].header.milady["data-myear-from"]
               + "' >"
            +json_persian_dates[0].header.milady["data-myear-from"]
            +"</div>";
            if(json_persian_dates[0].header.milady["data-myear-from"]
                != json_persian_dates[0].header.milady["data-myear-to"]){
                content = content  +"<div  class='english'  style='width:25%' id='wp_persian_tbl_caption_miladi_month_to' data-mmonth-to='"
                    +json_persian_dates[0].header.milady["data-mmonth-to"]

                    +"'>"
                    +json_persian_dates[0].header.milady["data-mstring-month-to"]
                    +"</div>";
                content = content + "<div class='english' id='wp_persian_tbl_caption_miladi_year_to' data-myear-to='"+
                    json_persian_dates[0].header.milady["data-myear-to"]
                    +"'>"
                    +json_persian_dates[0].header.milady["data-myear-to"]
                    +"</div>";
            }


        content = content  +"</div>";

        content = content  +"<div class='hejri_header'><div class='arabic' id='wp_persian_tbl_caption_hejri_month_from' data-hmonth-from='"
        +json_persian_dates[0].header.hejri["data-hmonth-from"]

        +"'>"
        +json_persian_dates[0].header.hejri["data-hstring-month-from"]
        +"</div>";
        if(json_persian_dates[0].header.hejri["data-hyear-from"]
            == json_persian_dates[0].header.hejri["data-hyear-to"]){
            content = content  +"<div style='width:44%' class='arabic' id='wp_persian_tbl_caption_hejri_month_to' data-hmonth-to='"
                +json_persian_dates[0].header.hejri["data-hmonth-to"]

                +"'>"
                +json_persian_dates[0].header.hejri["data-hstring-month-to"]
                +"</div>";
        }
        content = content + "<div class='arabic' id='wp_persian_tbl_caption_hejri_year_from' data-hyear-from='"+
            json_persian_dates[0].header.hejri["data-hyear-from"]
            + "' >"
            +json_persian_dates[0].header.hejri["data-hyear-from"]
            +"</div>";
        if(json_persian_dates[0].header.hejri["data-hyear-from"]
            != json_persian_dates[0].header.hejri["data-hyear-to"]){
            content = content  +"<div style='width:25%' class='arabic' id='wp_persian_tbl_caption_hejri_month_to' data-hmonth-to='"
                +json_persian_dates[0].header.hejri["data-hmonth-to"]

                +"'>"
                +json_persian_dates[0].header.hejri["data-hstring-month-to"]
                +"</div>";
            content = content + "<div class='arabic' id='wp_persian_tbl_caption_hejri_year_to' data-hyear-to='"+
                json_persian_dates[0].header.hejri["data-hyear-to"]
                +"'>"
                +json_persian_dates[0].header.hejri["data-hyear-to"]
                +"</div>";
        }

            content = content+"</div></div></caption>" +
            "<thead>"+


            "<tr>";
        for(var i=0;i<json_persian_dates[1].day_names.length;i++)
            content = content +"<th>"+json_persian_dates[1].day_names[i]+"</th>";
        content = content +"</tr>" +
            "</thead>" ;
        return content;
    }
    function generateTableFooter(json_persian_dates) {
        let content ="<tfoot><tr>" +
            "<td colspan=\"2\" id=\"prev\"><a id=\"prev_month\" data-in='"+json_persian_dates[2].prevlink.year+"' data-out='"+json_persian_dates[2].prevlink.month+ "' href=\"#\">&laquo;" + json_persian_dates[2].prevlink.month_name+"</a></td>" +
            "<td colspan=\"3\" class=\"pad\"><button id='today_button' class='button-primary'>امروز</button></td>"+
            "<td colspan=\"2\" id=\"next\"><a id=\"next_month\" data-in='"+json_persian_dates[3].nextlink.year+"' data-out='"+json_persian_dates[3].nextlink.month+"' href=\"#\">" +json_persian_dates[3].nextlink.month_name+ " &raquo;</a></td>"+
            "</tr></tfoot>";
        return content;
    }
    function generateTableBody(json_persian_dates) {
        let content = "<tbody><tr>";
        if(json_persian_dates[3].nextlink.hasOwnProperty('first_pad') && parseInt(json_persian_dates[3].nextlink.first_pad)!=0){
            content = content +"<td colspan='" +json_persian_dates[3].nextlink.first_pad+ "' class=\"pad\">&nbsp;</td>" ;
        }
        for(let row_index=4;row_index<json_persian_dates.length;row_index++){
            let col_index=0;
            for(col_index=0;col_index<json_persian_dates[row_index].week.length;col_index++){
                content = content + "<td>" +
                    "<div class='  "+
                    "  "+
                    +" shamsi_day "
                    +" persian "
                    +"  "
                    +getClassName("today",json_persian_dates[row_index].week[col_index].day["is_today"])
                    +"  "
                    +getClassName("friday",json_persian_dates[row_index].week[col_index].day["is_friday"])
                    +"  "
                    +getClassName("dayoff",json_persian_dates[row_index].week[col_index].day["data-sday-ocassion-is-off"])
                    +"  "
                    +"  "
                    +getClassName("dayoff",json_persian_dates[row_index].week[col_index].day["data-hday-ocassion-is-off"])
                    +"  "
                    +"'" +
                    " data-sday='"+json_persian_dates[row_index].week[col_index].day["data-sday"]+"'" +
                    " title='"+json_persian_dates[row_index].week[col_index].day["data-sday-ocassion-desc"]+"'" +
                    " data-sday-ocassion-is-off='"+json_persian_dates[row_index].week[col_index].day["data-sday-ocassion-is-off"]+"'" +
                    ">"+json_persian_dates[row_index].week[col_index].day["data-sday"]+"</div>"

                    +"<div class='milady_day english "+
                    +"  "
                    +getClassName("dayoff",json_persian_dates[row_index].week[col_index].day["data-mday-ocassion-is-off"])
                    +"  "
                    +"' data-myear='"+json_persian_dates[row_index].week[col_index].day["data-myear"]+"' "+
                    " data-mmonth='"+json_persian_dates[row_index].week[col_index].day["data-mmonth"]+"'"+
                    " data-mday='"+json_persian_dates[row_index].week[col_index].day["data-mday"]+"'"+
                    " title='"+json_persian_dates[row_index].week[col_index].day["data-mday-ocassion-desc"]+"'"+
                    " data-mday-ocassion-is-off='"+json_persian_dates[row_index].week[col_index].day["data-mday-ocassion-is-off"]+"'>"+
                     json_persian_dates[row_index].week[col_index].day["data-mday"]+"</div>"

                    +"<div class='"+
                    " hejri_day arabic "+
                    +"  "
                    +getClassName("dayoff",json_persian_dates[row_index].week[col_index].day["data-hday-ocassion-is-off"])
                    +"  "
                    +"'"
                    +" data-hyear='"+json_persian_dates[row_index].week[col_index].day["data-hyear"]+"' "+
                    " data-hmonth='"+json_persian_dates[row_index].week[col_index].day["data-hmonth"]+"'"+
                    " data-hday='"+json_persian_dates[row_index].week[col_index].day["data-hday"]+"'"+
                    " title='"+json_persian_dates[row_index].week[col_index].day["data-hday-ocassion-desc"]+"'"+
                    " data-hday-ocassion-is-off='"+json_persian_dates[row_index].week[col_index].day["data-hday-ocassion-is-off"]+"'"
                    +">"+
                    json_persian_dates[row_index].week[col_index].day["data-hday"]+"</div>"


                    +"</td>";
            }
            if(json_persian_dates.hasOwnProperty('last_pad') && (col_index-1)>=0)
                content = content + "<td class=\"pad\" colspan='" +json_persian_dates[row_index].week[col_index-1].last_pad.last_pad + "'>&nbsp;</td>";

            content = content + "</tr><tr>";

        }


        content = content +"</tr></tbody>" ;

        return content;
    }
    function generateTable(json_persian_dates){

        let content = "<table id='wp_persian_calendar_table'>" ;

        content = content + generateTableHeader(json_persian_dates);
        content = content + generateTableFooter(json_persian_dates);
        content = content + generateTableBody(json_persian_dates);

        content = content +"</table>";

        return content;
    }
    function add_event_click_to_next_link() {
        $("#next_month").on("click",function (event) {
            event.preventDefault();
            console.log("next_month clicked!");
            ajax_send($("#next_month").attr('data-in'),$("#next_month").attr('data-out'));
        });
    }
    function add_event_click_to_prev_link() {
        $("#prev_month").on("click",function (event) {
            console.log("prev_month clicked!");
            event.preventDefault();
            ajax_send($("#prev_month").attr('data-in'),$("#prev_month").attr('data-out'));

        });
    }
    function add_event_click_to_days() {
        $("#wp_persian_calendar_table tbody td:not(.pad)").on({

            mouseenter: function () {
                if($(this).hasClass('dayoff'))
                    $(this).addClass('dayoff_over');
                else
                    $(this).addClass('mouseOver');
            },
            mouseleave: function () {
                if($(this).hasClass('dayoff'))
                    $(this).removeClass('dayoff_over');
                else
                    $(this).removeClass('mouseOver');
            }

        });
        $("#today_button").on("click",function (event) {
            event.preventDefault();
            console.log("today_button clicked!");
            ajax_send($("#wp_persian_tbl_caption_shamsi").attr('data-current-year'),$("#wp_persian_tbl_caption_shamsi").attr('data-current-month'));


        });
    }
    function add_dialog_box_and_events() {

        $(".month_select_icon").on({

            mouseenter: function () {
                if($(this).hasClass('dayoff'))
                    $(this).addClass('dayoff_over');
                else
                    $(this).addClass('mouseOver');
            },
            mouseleave: function () {
                if($(this).hasClass('dayoff'))
                    $(this).removeClass('dayoff_over');
                else
                    $(this).removeClass('mouseOver');
            }

        });
        $(".year_select_icon").on({

            mouseenter: function () {
                if($(this).hasClass('dayoff'))
                    $(this).addClass('dayoff_over');
                else
                    $(this).addClass('mouseOver');
            },
            mouseleave: function () {
                if($(this).hasClass('dayoff'))
                    $(this).removeClass('dayoff_over');
                else
                    $(this).removeClass('mouseOver');
            }

        });


        $("#year_select_dialog").dialog({
            autoOpen: false,
            show: {
                effect: "blind",
                duration: 2000
            },
            hide: {
                effect: "explode",
                duration: 1000
            },
            modal: true,
            resizable:true,
            closeText:'بستن',
            position: { my: "center center", at: "center center", of: $("#wp_persian_calendar_table") }

        });

        $(".year_select_icon").on("click",function (ev) {
            ev.preventDefault();
            $("#year_select_dialog").dialog('open');

        });

        $("#year_select_dialog_close").on("click",function (ev) {
            ev.preventDefault();
            $("#year_select_dialog").dialog('close');
            if($.isNumeric($("#year_select_text").val()) && $("#year_select_text").val()>1349){
                console.log("مقدار وارد شده، صحیح است");
                ajax_send($("#year_select_text").val(),$("#wp_persian_tbl_caption_shamsi").attr('data-smonth'));
            }
            else {
                console.log("مقدار وارد شده، صحیح نیست");

            }
        });

        $("#month_select_dialog").dialog({
            autoOpen: false,
            show: {
                effect: "blind",
                duration: 2000
            },
            hide: {
                effect: "explode",
                duration: 1000
            },
            modal: true,
            resizable:true,
            position: { my: "center center", at: "center center", of: $("#wp_persian_calendar_table") }

        });

        $(".month_select_icon").on("click",function (ev) {
            ev.preventDefault();
            console.log("#month_select");
            $("#month_select_dialog").dialog('open');

        });

        $(".month_select_button").on("click",function (ev) {
            ev.preventDefault();
            console.log($(this).attr('data-out'));
            ajax_send($("#wp_persian_tbl_caption_shamsi").attr('data-syear'),$(this).attr('data-out'));

            $("#month_select_dialog").dialog('close');

        });

    }
    function WidgetConstructor(json_persian_dates){
        if(json_persian_dates!=null)
            $("#wp_persian_calendar_main").html(generateTable(json_persian_dates));

        add_event_click_to_next_link();

        add_event_click_to_prev_link();

        add_event_click_to_days();

        add_dialog_box_and_events();

        $('body').persianNum();
        $( document ).tooltip();

    }

    $("#wp_persian_calendar_button").on("click",function () {
        $.ajax({
            // url:'wp-admin/admin-ajax.php',
            url:'admin-ajax.php',
            type:'post',
            data:{
                action:'wp_persian_calendar_custom_load',
                year:$("#next_month").attr('data-in'),
                month:$("#next_month").attr('data-out'),
            },
            success:function (response) {

                load();
                let my_json = JSON.parse(response.message);


                console.log(my_json);

                console.log(my_json[0].header.shamsi["data-sstring"]);
                console.log(my_json[4].week[0].day["data-sday-ocassion-desc"]);
                console.log(my_json[6].week[4].day["data-hday-ocassion-is-off"]);
                console.log(getClassName("dayoff",my_json[6].week[4].day["data-hday-ocassion-is-off"]));


                WidgetConstructor(my_json);
            },
            error:function (error) {
                console.log(error);

                $("#wp_persian_calendar_main").html(error.responseText);
            }

        });


    });


});