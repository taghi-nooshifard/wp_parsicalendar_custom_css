<div class="wrap">
    <?php if ( isset( $_GET['message'] )
        && $_GET['message'] == '1' ) { ?>
        <div id='message' class='updated fade'><p><strong>تنظیمات ذخیره شد</strong></p></div>
    <?php } elseif ( isset( $_GET['message'] )
        && $_GET['message'] == '2' ) { ?>
        <div id='message' class='updated
fade'><p><strong>تنظیمات به حالت پیش فرض برگشت</strong></p></div>
    <?php } ?>
    <form name="ch2pit_options_form" method="post"
          action="admin-post.php">
        <input type="hidden" name="action"
               value="save_wp_parsi_calendar_css" />
        <?php wp_nonce_field(WP_OPTION_NAME); ?>
<label >تنظیم نمایش تقویم:
    <textarea name="stylesheet" rows="20" cols="60" style="fontfamily:
Consolas,Monaco,monospace;direction: ltr">
        <?php echo get_option(WP_OPTION_NAME); ?>
    </textarea><br />
    <input type="submit" name="save_wp_parsi_calendar_css" value="ذخیره" class="button-primary" />
    <input type="submit" value="مقدار پیش فرض" name="resetstyle"
           class="button-primary" />
</label>
    </form>

</div>
