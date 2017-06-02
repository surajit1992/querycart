<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
get_header( );
global $post,$wpdb,$current_user;
get_currentuserinfo();
$user_id = $current_user->ID;
$user_email = "";
if(!empty($user_id))
{
	$user_info = get_userdata($user_id);
	$user_email = $user_info->user_email;
}
$fetch_query_count = "select * from `".$wpdb->prefix."order_meta` order by `field_order` asc";
//post section start here
if(!empty($_POST))
{
	$post_item = $_POST;
	$product_id = $post_item["product_id"]; $cart_url = $post_item["cart_url"];
	unset($post_item["product_id"]); unset($post_item["cart_url"]);
	$post = array(); $session = array();
	foreach ($post_item as $single_post_key=>$single_post_itm)
	{
		$post_key = base64_decode($single_post_key);
		//echo $post_key." ".$post_val;
		$post[$post_key] = $single_post_itm;
	}
	/* if(!empty($post["dev_name"]))
	{
		$post["name"] = $post["dev_name"];
		unset($post["dev_name"]);
	}*/
	$session = $_SESSION["cart_items"];
	//$_SESSION["cart_items"]="";
	//$full_enq = "";
	/*foreach ($post as $enq_key=>$eng)
	{
		$full_enq .= "$enq_key: $eng, ";
	}
	$full_enq = rtrim($full_enq,", ");*/
	$session[$product_id] = json_encode($post);
	$_SESSION["cart_items"] = $session;
	//print_r($_SESSION);
	//die();
	//echo $cart_url;
	wp_redirect( $cart_url,301 );
	die("<script>window.location.href='$cart_url';</script>");
	//cart_items
}
//post section end here
    		 ?>
    		 <link rel="stylesheet" href="<?php  echo MY_PLUGIN_URL; ?>dist/css/lightbox.min.css">
 <div class="product_detail">
		<?php while ( have_posts() ) : the_post(); ?>
            <div class="product_img">
			<?php
			//echo get_the_id();

    		//echo "<a href='".get_permalink(get_option( 'cart_page_id' ))."'>cart</a><br />";

			$post_thumbnail_id = get_post_thumbnail_id( get_the_id() );
    			$parsed = parse_url( wp_get_attachment_url( $post_thumbnail_id ) );
    			$att_url = !empty($parsed["path"])?$parsed["scheme"]."://".$parsed["host"]."/".$parsed["path"]:"";
    			echo  "<img width='383px' style='padding: 8px;' src='$att_url'  />";
    			echo "<div class='single-image-light-box'>";
				//the_post_thumbnail( 'thumbnail' , array( 'id' => 'thumb_img' ) );
    			$galleryArray = get_post_gallery_ids(get_the_id());
    			 foreach ($galleryArray as $id) 
    			 	{ ?>
    			    <a class="example-image-link" href="<?php echo wp_get_attachment_url( $id ); ?>" data-lightbox="example-set" data-title="">
    			    	<img class="example-image" alt="" width="110" height="110" src="<?php echo wp_get_attachment_url( $id ); ?>" />
    			    </a>
    		<?php } 
    		echo "</div>";
			 ?>
             </div>
             <div class="product_des">
             	<h1><?php the_title(); ?></h1>
             	<?php 
             	/*$categories = get_categories( array(
             			'orderby' => 'name',
             			'order'   => 'ASC'
             	) );*/
             	$categories = get_the_category();
             	//echo "<pre>";print_r($categories);
             	echo "<p>Categories: ";
             	$cat_list = "";
             	foreach( $categories as $category ) {
             		/*
             		$category_link = sprintf(
             				'<a href="%1$s" alt="%2$s">%3$s</a>',
             				esc_url( get_category_link( $category->term_id ) ),
             				esc_attr( sprintf( __( 'View all posts in %s', 'textdomain' ), $category->name ) ),
             				esc_html( $category->name )
             				);
             		 
             		echo '<p>' . sprintf( esc_html__( 'Category: %s', 'textdomain' ), $category_link ) . '</p> ';
             		echo '<p>' . sprintf( esc_html__( 'Description: %s', 'textdomain' ), $category->description ) . '</p>';
             		echo '<p>' . sprintf( esc_html__( 'Post Count: %s', 'textdomain' ), $category->count ) . '</p>';*/
             		$cat_list .= $category->name.", ";
             	}
             	$cat_list = rtrim($cat_list,", Uncategorized");
             	echo "$cat_list</p>";
             	?>
                <p><?php the_content();
                
                if($wpdb->get_var("select COUNT(*) from `".$wpdb->prefix."order_meta`") == 0){
                	//echo "No Meta Found";
                } else {
                	$wp_get_results = $wpdb->get_results($fetch_query_count);
                	foreach($wp_get_results as $single_value)
                	{
                		$save_key = strtolower(str_replace(' ', '_', $single_value->field_name));
                		$chk_val = "visiable_".$single_value->field_meta_id;
                		$get_chk_val_chk = get_post_meta($post->ID, $chk_val, true);
                		if($get_chk_val_chk == "1")
                		{
                			$get_save_val = get_post_meta($post->ID, $save_key, true);
                			//echo "<p>$save_key - $get_save_val</p>";
                			echo  "<p>$single_value->field_name - $get_save_val</p>";
                		}
                	}
                }
                ?></p>
                <!-- p class="qty_box"><b>Quantity</b> <input name="qty" id="qty" type="number" /></p-->
                <div class="form_cont">
               <?php 
               echo "<form action='".get_permalink(get_the_id())."' method='post'>";//get_permalink(get_option( 'checkout_page_id' ))
               $fetch_query = "select * from `".$wpdb->prefix."enquiry_form_meta` order by `field_order` ASC"; //where `userid` = '$user->ID'
               $fetch_query_count = "select COUNT(*) from `".$wpdb->prefix."enquiry_form_meta`";
               //$order = get_option( 'enquiry_form_order' );
               if($wpdb->get_var($fetch_query_count)>0)
               {
               ?>
               <h1>Have a question?</h1>
               <!-- form section -->
               <?php 
               	$wp_get_results = $wpdb->get_results($fetch_query);
               	foreach($wp_get_results as $single_value)
               	{
               		//$result_arr[$single_value->field_meta_id] = $single_value;
               		echo "<label>$single_value->field_name: </label>";
               		$input_type="text";
               		$save_key = $single_value->field_name;
               		$save_key1 = strtolower(str_replace(' ', '_', $save_key));
               		//$save_key = ($save_key == "name")?"dev_name":$save_key;
               		$input_val = "";
               		if (strpos($save_key1, 'email') !== false) 
               		{
               			$input_type="email";
               			$input_val = $user_email;
               		}
               		$save_key = base64_encode($save_key);
               		echo "<input type='$input_type' name='$save_key' value='$input_val' required='' /><br/>";
               	}
               }
               echo "<input type='hidden' name='cart_url' value='".get_permalink(get_option('cart_page_id'))."' />";
               echo "<br /><input type='hidden' name='product_id' value='".get_the_id()."' /><input type='submit' value='Add to Cart'/></form>";
               
               ?>
               <!-- form section -->
               <!-- img src="http://localhost/testing/wp-content/uploads/2017/01/form_img.jpg" />
           	   <button name="add_cart" type="button">Add to cart</button-->	
           </div>
             
           
		
		<?php endwhile;  ?>
		<!-- Relaed product start here -->
		<?php
$category_obj = get_the_category();
$category = $category_obj[0]->slug;
//$category = $category_obj[0]->term_id;
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => 3,
        'post__not_in' => array( get_the_ID() ),
        'category_name'     => $category
    );
    //$query = new WP_Query( $args );
    $get_products = get_posts($args);
    /*
     'meta_query' => array(
                    array(
                    'key' => 'recommended_article',
                    'value' => '1',
                    'compare' => '=='
                        )
                    )
     */
//echo "<pre>";
//print_r($query->posts);
if(count($get_products)>0)
{
?>
<h2 style="margin: 0 auto;padding:10px">Related Products</h2>

    <?php //if( $query->have_posts() ) : while( $query->have_posts() ) : $query->the_post();
foreach ($get_products as $post)
{
	setup_postdata($post); ?>
	<div style="display: inline-block;padding:10px;">
<div class="relted-title"><a class="popup-article-picture" href="<?php the_permalink(); ?>" style="background-image: url('<?php the_post_thumbnail_url(); ?>');">
<img src="<?php the_post_thumbnail_url(); ?>" alt="<?php the_title(); ?>" /></a></div>
<div class="related-contant">
<a class="popup-article-title" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></div>
</div>
	<?php 
}
}
?>

    <?php //endwhile; endif; 
wp_reset_postdata(); ?>
	<!-- Related product end here -->	
</div>
        </div>
        
<!-- script src="<?php  echo MY_PLUGIN_URL . 'js/script.js';  ?>"></script-->
  <script src="<?php  echo MY_PLUGIN_URL; ?>dist/js/lightbox-plus-jquery.min.js"></script>
<?php
get_footer();

