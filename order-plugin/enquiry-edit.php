<?php if(empty($_GET["reply"]))
{
	wp_redirect(admin_url(),301);
	die("<script>window.location.href='". admin_url() ."';</script>");
	exit;
}
global $wpdb;
$enquiry_id = $_GET["reply"];
$this_page_url = admin_url("admin.php?page=order-plugin/enquiry-edit.php&reply=$enquiry_id");
$enquery_data = $wpdb->get_row("select * from `".$wpdb->prefix."enquiry_data` where `enquiry_id` = '$enquiry_id'");

//print_r($enquery_data);
if(!empty($_POST))
{
	$redirect_url = ($enquery_data->enquiry_status == 0)?admin_url("admin.php?page=order-plugin/unposted-enquiry.php"):admin_url("admin.php?page=order-plugin/posted-enquiry.php");
	$user_email = $enquery_data->user_id;
	$admin_email = get_option('admin_email');
	$mail_subject = "Reply For Your Enquiry";
	$mail_body = $_POST["reply_txt"];
	
	
	$headers[] = 'MIME-Version: 1.0';
	$headers[] = 'Content-type: text/html; charset=iso-8859-1';
	
	// Additional headers
	//$headers[] = 'To: Mary <mary@example.com>, Kelly <kelly@example.com>';
	$headers[] = 'From: '.$admin_email;
	
	$enquiry_status = $_POST["enquiry_status"];
	
	enquiry_native_mail($user_email, $mail_subject, $mail_body, implode("\r\n", $headers));
	
	$update_query = "update `".$wpdb->prefix."enquiry_data` set `enquiry_status`='$enquiry_status' where `enquiry_id` = '$enquiry_id'";
	$wpdb->query($update_query);
	
	wp_redirect($redirect_url,301);
	die("<script>window.location.href='$redirect_url';</script>");
	exit;
}
$product_url = site_url("wp-admin/post.php?post=".$enquery_data->products."&action=edit");

?>
<div id="wpbody" role="main">
<div id="wpbody-content" aria-label="Main content" tabindex="0" style="overflow: hidden;">
<div class="wrap">
<h1 class="wp-heading-inline">Enquiry</h1>
<hr class="wp-header-end">
<form name="post" action="<?php echo $this_page_url; ?>" method="post" id="post">
<div id="poststuff">
<div id="post-body" class="metabox-holder columns-2">
<div id="post-body-content" style="position: relative;">
<input type="hidden" name="reply" value="<?php echo $enquiry_id; ?>" required="" />
<div id="titlediv">
<p><h5>Product:</h5></p>
<p> <?php echo "<a href='$product_url'>".get_product_name($enquery_data->products)."</a>"; ?></p>
<p><h5>Enquery:</h5></p>
<p><?php echo show_query_str($enquery_data->enquiry_data); $user_email_show = show_query_str($enquery_data->enquiry_data,"email"); ?></p>
<p><strong>User Email</strong>: <a href="mailto: <?php echo $user_email_show ?>"><?php echo $user_email_show; ?></a></p>
<p><h5>Reply</h5></p>
<p><textarea required name="reply_txt" rows="" cols=""></textarea></p>
<p><h5>Save to</h5></p>
<p><select name="enquiry_status">
<option value="0" <?php if($enquery_data->enquiry_status == 0){ echo "selected"; } ?> >Unposted Enquiry</option>
<option value="1" <?php if($enquery_data->enquiry_status == 1){ echo "selected"; } ?> >Posted Enquiry</option>
</select></p>
<!-- div id="titlewrap">
		<label class="" id="title-prompt-text" for="title">Enter title here</label>
	<input name="post_title" size="30" value="" id="title" spellcheck="true" autocomplete="off" type="text">
</div-->


<div id="publishing-action">
<span class="spinner"></span>
		<input name="original_publish" id="original_publish" value="Publish" type="hidden">
		<input name="publish" id="publish" class="button button-primary button-large" value="Publish" type="submit"></div>

</div><!-- /post-body-content -->


</div><!-- /post-body -->
<br class="clear">
</div><!-- /poststuff -->
</form>
</div>
<div class="clear"></div></div><!-- wpbody-content -->
<div class="clear"></div></div>