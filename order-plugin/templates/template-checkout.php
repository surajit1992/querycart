<?php
/*
 * Template Name: Checkout
 */
get_header();
global $wpdb, $current_user;
get_currentuserinfo();
$args = array( 'post_status' => 'publish', 'post_type' => 'product' );
$the_query = new WP_Query( $args );
//$the_query->
?>
<div class="site-content-contain">
<div id="primary" class="site-content">
  <div id="container">
    		<?php while ( have_posts() ) : the_post(); ?>

    		<?php
    		//print_r($_SESSION['cart_items']);
    		if(!empty($_SESSION['cart_items']) && is_array($_SESSION['cart_items']))
    		{
    			$products=$_SESSION['cart_items'];
    			//$products = array_unique($products);
    			//echo "<pre>";print_r($counts); print_r($products);
    			$user_id = $current_user->ID;
    			$user_email = "";
    			/*
    			if(!empty($user_id))
    			{
    				$user_info = get_userdata($user_id);
    				$user_email = $user_info->user_email;
    			}*/
    			foreach($products as $product_id=>$single_product)
    			{
    				$user_id = empty($user_email)?extract_email($single_product):$user_email;
    				$insert_query = "insert `".$wpdb->prefix."enquiry_data` set `user_id` = '$user_id', `enquiry_data`='".addslashes($single_product)."',`products`='$product_id'";
    				$wpdb->query($wpdb->prepare($insert_query)) or die("Failed");
    				//echo $insert_query."<br>";	
    			}
    			unset($_SESSION['cart_items']);
    			echo "<p>Your Enquiry Send Successfully</p>";
    			
    			
    		}
    		else
    		{
    			wp_redirect(get_permalink(get_option( 'shop_page_id' )));
    			echo "<script>window.location.href='".get_permalink(get_option( 'shop_page_id' ))."'</script>";
    			wp_die();
    		}
    		?>

    		<?php endwhile;  ?>
    	</div>
  </div><!-- #content -->
</div><!-- #primary -->

<?php get_footer(); ?>

