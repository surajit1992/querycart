<div class="wrap">
    <h1 class="wp-heading-inline">Send Enquiry To</h1>
    <hr class="wp-header-end">
    <?php
    if(!empty($_POST))
{
    $post_data = $_POST;

        echo '<div id="message" class="updated notice notice-success is-dismissible"><p>This section is under construction.</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';

}
$protocol = isset($_SERVER["HTTPS"]) ? 'https' : 'http';
//echo $protocol;
$post_url = admin_url( 'admin.php?page=order-plugin%2Fsend-enquiry.php', $protocol );

    ?>

    <form method="post" action="<?php echo $post_url; ?>" >
    Send Enquiry To: <input type="email" name="send_enquiry_to" required="" />
<br />
<input type="submit" value="Send" />
    </form>

</div>
