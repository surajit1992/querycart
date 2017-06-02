<?php 
function category_template( $template )
{
	if(is_category())
	{
		if(file_exists(MY_PLUGIN_PATH . 'templates/category.php'))
		{
			return MY_PLUGIN_PATH . 'templates/category.php';
		}
	}
	else 
	{
		return $template;
	}
}
function product_admin_actions()
{
	//remove_submenu_page('edit.php', 'edit-tags.php?taxonomy=category');
	//remove_submenu_page('edit.php', 'edit-tags.php?taxonomy=post_tag');
	add_menu_page( "Enquiry Cart", "Enquiry Cart", 'manage_options', 'order-plugin/product-enquiry-fields.php', '', '', 15 );
	add_submenu_page( 'order-plugin/product-enquiry-fields.php', 'Product Enquiry Fields', 'Product Enquiry Fields','manage_options', 'order-plugin/product-enquiry-fields.php');
	add_submenu_page( 'order-plugin/product-enquiry-fields.php', 'Product', 'Product','manage_options', 'edit.php?post_type=product');
	//add_submenu_page( 'order-plugin/product-enquiry-fields.php', 'Categories', 'Categories','manage_options', 'edit-tags.php?taxonomy=category');
	add_submenu_page( 'order-plugin/product-enquiry-fields.php', 'Enquiry Form Fields', 'Enquiry Form Fields','manage_options', 'order-plugin/enquiry-form-fields.php');
	add_submenu_page( 'order-plugin/product-enquiry-fields.php', 'Unposted Enquiry', 'Unposted Enquiries','manage_options', 'order-plugin/unposted-enquiry.php');
	add_submenu_page( 'order-plugin/product-enquiry-fields.php', 'Posted Enquiry', 'Posted Enquiries','manage_options', 'order-plugin/posted-enquiry.php');
	add_submenu_page( null, 'Reply Enquiry', 'Reply Enquiry','manage_options', 'order-plugin/enquiry-edit.php');//add for edit poster or unposted enquiry
}
function register_session()
{
	$shop_page_id = get_option( 'shop_page_id');
	$cart_page_id = get_option( 'cart_page_id');
	$checkout_page_id = get_option( 'checkout_page_id');
	$shop_category_page_id = get_option('shop_category_page_id');
	if(!empty($shop_page_id))
	{
		update_post_meta($shop_page_id, '_wp_page_template', "template-shop.php");
		update_post_meta($cart_page_id, '_wp_page_template', "template-cart.php");
		update_post_meta($checkout_page_id, '_wp_page_template', "template-checkout.php");
		update_post_meta($shop_category_page_id,'_wp_page_template',"template-category.php");
	}

	if( !session_id() )
	{
		session_start();
	}

}
function client_file()
{
	wp_register_style('custom_styles', MY_PLUGIN_URL."css/style.css");
	wp_enqueue_style('custom_styles');
	wp_enqueue_style('bootstrap_styles','https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css');
	if( !wp_script_is('jquery-ui') )
	{
		wp_enqueue_script( 'jquery-ui' , 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.js' );
	}
	if(!wp_script_is('bootstrap-js'))
	{
		wp_enqueue_script( 'bootstrap-js' , 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js' );
	}
	if( !wp_script_is('jquery') )
	{
		wp_enqueue_script( 'jquery' , 'http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js' );
	}
	wp_register_script( 'custom_script', MY_PLUGIN_URL.'js/script.js');
	wp_enqueue_script('custom_script');
}
function remove_row_actions( $actions )
{
	if( get_post_type() === 'posted-enquiry' || get_post_type() === 'unposted-enquiry' ||get_post_type() === 'product-enquiry' )
		unset( $actions['view'] );
		return $actions;
}
function remove_parmalink()
{
	global $post_type;
	if ($post_type == 'posted-enquiry' || $post_type == 'unposted-enquiry' || $post_type == 'product-enquiry')
	{
		echo "<style>#edit-slug-box {display:none;} #message a{display:none;}</style>";
	}
}
function register_frontent_style()
{
	if ( is_page_template( 'template-shop.php' ) ) {
		wp_enqueue_style( 'frontent_style', MY_PLUGIN_URL.'css/style.css' );
	}
}
function add_featured_galleries_to_ctp( $post_types ) {
	//array_push($post_types, 'product'); // ($post_types comes in as array('post','page'). If you don't want FGs on those, you can just return a custom array instead of adding to it. )
	$post_types = array("product");
	return $post_types;
}
function custom_single_template($single)
{
	global $wp_query, $post;
	/* Checks for single template by post type */
	if ($post->post_type == "product")
	{
		if(file_exists(MY_PLUGIN_PATH . 'templates/single-product.php'))
			return MY_PLUGIN_PATH . 'templates/single-product.php';
	}
	return $single;
}
function register_order_posttype()
{
	$labels = array(
			'name'                  => _x( 'Products', 'Post type general name', 'textdomain' ),
			'singular_name'         => _x( 'Product', 'Post type singular name', 'textdomain' ),
			'menu_name'             => _x( 'Products', 'Admin Menu text', 'textdomain' ),
			'name_admin_bar'        => _x( 'Product', 'Add New on Toolbar', 'textdomain' ),
			'add_new'               => __( 'Add New', 'textdomain' ),
			'add_new_item'          => __( 'Add New Product', 'textdomain' ),
			'new_item'              => __( 'New Product', 'textdomain' ),
			'edit_item'             => __( 'Edit Product', 'textdomain' ),
			'view_item'             => __( 'View Product', 'textdomain' ),
			'all_items'             => __( 'All Products', 'textdomain' ),
			'search_items'          => __( 'Search Products', 'textdomain' ),
			'parent_item_colon'     => __( 'Parent Product:', 'textdomain' ),
			'not_found'             => __( 'No Products found.', 'textdomain' ),
			'not_found_in_trash'    => __( 'No Products found in Trash.', 'textdomain' ),
			//'featured_image'        => _x( 'Book Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'textdomain' ),
			//'set_featured_image'    => _x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'textdomain' ),
			//'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'textdomain' ),
			//'use_featured_image'    => _x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'textdomain' ),
			'archives'              => _x( 'Product archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'textdomain' ),
			'insert_into_item'      => _x( 'Insert into Product', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'textdomain' ),
			'uploaded_to_this_item' => _x( 'Uploaded to this Product', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'textdomain' ),
			'filter_items_list'     => _x( 'Filter Product list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'textdomain' ),
			'items_list_navigation' => _x( 'Product navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'textdomain' ),
			'items_list'            => _x( 'Product list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'textdomain' ),
			//'show_in_menu'          => 'edit.php?post_type=enquiry-cart'
			//'show_in_menu'          => false
	);
	$args = array(						// for product
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => false,
			'query_var'          => true,
			//'rewrite'            => array( 'slug' => 'enquiry-cart' ), //default
			//'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'taxonomies'         => array( 'topics','category' ),
			'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt'  ) //'author','comments', 'custom-fields'
	);
	register_post_type( 'product', $args );
	$labels["name"] = "Product Tag";
	register_taxonomy('product_tag','product',array(
			'hierarchical' => false,
			'labels' => $labels,
			'show_ui' => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var' => true,
			'rewrite' => array( 'slug' => 'tag' ),
	));
}
function add_meta_dy()
{
	//add_meta_box( $id, $title, $callback, $page, $context, $priority, $callback_args );
	//add_meta_box("demo-meta-box", "Custom Meta Box", "custom_meta_box_markup", "post", "side", "high", null);
	add_meta_box('product_meta', 'Product Meta', 'product_meta', 'product', 'normal', 'default');
	add_meta_box('product_enquiry_meta', 'Enquiry Order', 'product_enquiry_meta', 'product-enquiry', 'normal', 'default');
}
function product_meta($post, $args)
{
	global $wpdb;
	$fetch_query_count = "select * from `".$wpdb->prefix."order_meta` order by `field_order` asc";

	?>
		<div id="postcustomstuff">
		<div id="ajax-response"></div>
		<?php
			if($wpdb->get_var("select COUNT(*) from `".$wpdb->prefix."order_meta`") == 0){
				echo "No Meta Found";
			} else {
				echo '<table id="list-table"><thead><tr><th class="left">Frontend Visiability</th><th>Name</th><th>Value</th></tr></thead><tbody id="the-list" data-wp-lists="list:meta">';
						$wp_get_results = $wpdb->get_results($fetch_query_count);
						foreach($wp_get_results as $single_value)
						{
								//print_r($single_value);
								//$result_arr[$single_value->field_meta_id] = $single_value;
								//echo "<label>$single_value->field_name: </label><input type='text' name='$save_key' value='$get_save_val' /><br/>";	
								$save_key = strtolower(str_replace(' ', '_', $single_value->field_name));
								$get_save_val = get_post_meta($post->ID, $save_key, true);
								$chk_val = "visiable_".$single_value->field_meta_id;
								$get_chk_val_chk = get_post_meta($post->ID, $chk_val, true);
							?>
							<tr>
								<td class="left">
								<?php //echo "chk_val-".$get_chk_val_chk; ?>
									<input type="hidden" name="<?php echo $chk_val; ?>" value="0" />
									<input type="checkbox" name="<?php echo $chk_val ; ?>" value="1" <?php if($get_chk_val_chk == "1"){ echo "checked"; } ?>  />
								</td>
								<td>
									<label class="screen-reader-text">Key</label><label style="margin:8px;"><?php echo $single_value->field_name;?></label>
								
								</td>
								<td>
									<label class="screen-reader-text">Value</label>
									<input type="text" name="<?php echo $save_key;?>" value="<?php echo $get_save_val;?>"  /><!-- required="" -->
								</td>
							</tr>
							<?php
						}
						echo "</tbody></table></div>";
					}
}
function product_enquiry_meta($post, $args)
{
	$teaser_loop = get_post_meta($post->ID, 'enquiry_order', true);
	echo "<label>Order: </label><input type='text' name='enquiry_order' value='$teaser_loop' /><br/>";
}
function save_product()
{
	global $post;
	$post_var = $_POST;
	foreach($post_var as $save_post_key=>$save_post_val)
	{
		update_post_meta($post->ID, $save_post_key, $save_post_val);
	}
}
function ST4_columns_head($defaults)
{
	$defaults['post_order'] = 'Order';
	unset($defaults['date']);
	return $defaults;
}
function ST4_columns_content($column_name, $post_ID)
{
	if ($column_name == 'post_order')
	{
		$order_no = get_post_meta($post_ID, 'enquiry_order', true);
		echo $order_no;
	}
}
function catch_plugin_template($template)
{
	// If tp-file.php is the set template
	if( is_page_template('template-shop.php') )
	{
		$template = MY_PLUGIN_PATH . 'templates/template-shop.php';
	}
	if( is_page_template('template-cart.php') )
	{
		$template = MY_PLUGIN_PATH . 'templates/template-cart.php';
	}
	if( is_page_template('template-checkout.php') )
	{
		$template = MY_PLUGIN_PATH . 'templates/template-checkout.php';
	}
	if(is_page_template('template-category.php'))
	{
		$template = MY_PLUGIN_PATH . 'templates/template-category.php';
	}
	return $template;
}
function get_total_number()
{
	$return_data = array();
	$return_data["cart_url"] = get_permalink(get_option( 'cart_page_id'));
	$cart_items = $_SESSION["cart_items"];
	$return_data["total_items"] = count($cart_items);
	wp_die(json_encode($return_data));
}
function cart_function()
{
	global $wpdb;
	if(!empty($_POST["product_id"]))
	{
		$product_id = $_POST["product_id"];
		$session = $_SESSION["cart_items"];
		unset($_SESSION["cart_items"]);
		unset($session[$product_id]);
		$_SESSION["cart_items"] = $session;
		echo "success";
	}
	wp_die();
}
function myplugin_ajaxurl()
{
	echo "<script src='http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.js'></script>";echo "<script src=".MY_PLUGIN_URL.'js/script.js'."></script>";
	echo '<script type="text/javascript">
           var ajaxurl = "' . admin_url('admin-ajax.php') . '";
         </script>';
	//
	echo '<link rel="stylesheet" type="text/css" href="'.MY_PLUGIN_URL.'css/style.css">';
}
function update_field_order($tb,$id,$order,$id_row)
{
	global $wpdb;
	$query = "update `$tb` set `field_order` = '$order' where `$id_row`='$id'";
	//return $query; die($query);
	$wpdb->query($query);//or die($query)
	return "success";
	//return $query;
}
function my_action_callback()
{
	global $wpdb; // this is how you get access to the database
	if(!empty($_POST["order"]))
	{
		//update_option( "product_order", $_POST["order"] );
		$id_arr = stripslashes(urldecode($_POST["id_arr"]));
		$id_arr = json_decode($id_arr);
		$get_order = stripslashes(urldecode($_POST["order"]));
		$order_arr = json_decode($get_order);
		//print_r($order_arr);
		foreach ($order_arr as $single_key=>$single_order)
		{
			//$ret[] = update_field_order($wpdb->prefix."order_meta",$single_key+1,$single_order,"field_meta_id");
			$ret[] = update_field_order($wpdb->prefix."order_meta",$id_arr[$single_key],$single_order,"field_meta_id");
		}
		$success = "success";
		//$success = json_encode($ret);
		echo $success;
	}
	else if(!empty($_POST["enquiry"]))
	{
		//update_option( "enquiry_form_order", $_POST["enquiry"] );
		//update_option( "product_order", $_POST["order"] );
		$id_arr = stripslashes(urldecode($_POST["id_arr"]));
		$id_arr = json_decode($id_arr);
		$get_order = stripslashes(urldecode($_POST["enquiry"]));
		$order_arr = json_decode($get_order);
		//print_r($order_arr);die();
		foreach ($order_arr as $single_key=>$single_order)
		{
			//$ret[] = update_field_order($wpdb->prefix."enquiry_form_meta",$single_key+1,$single_order,"field_meta_id");
			$ret[] = update_field_order($wpdb->prefix."enquiry_form_meta",$id_arr[$single_key],$single_order,"field_meta_id");
		}
		//print_r($ret);
		$success = "success";
		//$success = json_encode($ret);
		echo $success;
		//echo "success";
	}
	else if(!empty($_POST["delete_order"]))
	{
		$order_id = $_POST["delete_order"];
		$delete_query = "DELETE FROM `".$wpdb->prefix."order_meta` WHERE `field_meta_id`=$order_id";
		$wpdb->query($delete_query) or die("failed");
		$get_order_data = get_option( 'product_order' );
		$order_arr = explode(",",$get_order_data);
		foreach($order_arr as $sin_order_arr)
		{
			if($sin_order_arr != $order_id)
			{
				$order_new_arr[]=$sin_order_arr;
			}
		}
		$new_order_arr = array_filter($new_order_arr);
		array_unique($new_order_arr);
		$new_order_arr = implode(",",$order_new_arr);
		update_option( "product_order", $new_order_arr );
		echo "successful";
	}
	else if(!empty($_POST["delete_enquiry"]))
	{
		$order_id = $_POST["delete_enquiry"];
		$delete_query = "DELETE FROM `".$wpdb->prefix."enquiry_form_meta` WHERE `field_meta_id`=$order_id";
		$wpdb->query($delete_query) or die("failed");
		$get_order_data = get_option( 'product_order' );
		$order_arr = explode(",",$get_order_data);
		foreach($order_arr as $sin_order_arr)
		{
			if($sin_order_arr != $order_id)
			{
				$order_new_arr[]=$sin_order_arr;
			}
		}
		$new_order_arr = array_filter($new_order_arr);
		array_unique($new_order_arr);
		$new_order_arr = implode(",",$order_new_arr);
		update_option( "product_order", $new_order_arr );
		echo "successful";
	}
	else
	{

	}
	wp_die();
}
// Activation Hook Start here
function order_activate()
{
	global $wpdb, $current_user;
	get_currentuserinfo();
	$install_smtp_query = "CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."smtp_config` (
			`smtp_id` int(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			`smtp_address` varchar(250) NOT NULL,
			`smtp_port` varchar(100) NOT NULL,
			`authentication` varchar(100) NOT NULL,
			`smtp_user` varchar(250) NOT NULL,
			`smtp_password` varchar(250) NOT NULL,
			`test_email` varchar(250) NOT NULL,
			`user_id` varchar(100) NOT NULL,
			`status` varchar(100) NOT NULL DEFAULT 'active',
			`timestamp` timestamp NOT NULL
		);";
	$install_product_meta_query = "CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."order_meta` (
  			`field_meta_id` int(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  			`field_name` varchar(250) NOT NULL,
  			`field_order` varchar(100) NOT NULL,
  			`remarks` varchar(250) NOT NULL,
  			`timestamp` timestamp NOT NULL,
  			`userid` varchar(100) NOT NULL,
  			`status` varchar(250) NOT NULL DEFAULT 'active'
		);";
	$install_enquiry_form_meta_query = "CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."enquiry_form_meta` (
  			`field_meta_id` int(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  			`field_name` varchar(250) NOT NULL,
  			`field_order` varchar(100) NOT NULL,
  			`remarks` varchar(250) NOT NULL,
  			`timestamp` timestamp NOT NULL,
  			`userid` varchar(100) NOT NULL,
  			`status` varchar(250) NOT NULL DEFAULT 'active'
		);";
	$insert_enquery_form_data = "INSERT `".$wpdb->prefix."enquiry_form_meta`
        		SET `field_name`='Email', `field_order`='1', `timestamp`='".date('Y-m-d h:i:s')."',`userid`='SYSTEM';";
	$install_enquiry = "CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."enquiry_data` (
  			`enquiry_id` int(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  			`user_id` varchar(250) NOT NULL,
  			`enquiry_data` varchar(250) NOT NULL,
  			`products` varchar(250) NOT NULL,
  			`enquiry_status` varchar(250) NOT NULL DEFAULT '0' COMMENT '0 for unposted, 1 for posted',
  			`timestamp` timestamp NOT NULL
		);";
	$wpdb->query($install_enquiry_form_meta_query);
	$wpdb->query($install_smtp_query);
	$wpdb->query($install_product_meta_query);
	$wpdb->query($install_enquiry);
	$wpdb->query($insert_enquery_form_data);

	//Create page Template
	$new_post = array(
			'post_title' => 'Shop',
			'post_content' => '',
			'post_status' => 'publish',
			'post_date' => date('Y-m-d H:i:s'),
			'post_author' => $current_user->ID,
			'post_type' => 'page'
	);
	$new_post_args = array(
			'post_title' => 'Cart',
			'post_content' => '',
			'post_status' => 'publish',
			'post_date' => date('Y-m-d H:i:s'),
			'post_author' => $current_user->ID,
			'post_type' => 'page'
	);
	$new_post_args1 = array(
			'post_title' => 'Checkout',
			'post_content' => '',
			'post_status' => 'publish',
			'post_date' => date('Y-m-d H:i:s'),
			'post_author' => $current_user->ID,
			'post_type' => 'page'
	);
	$new_post_args2 = array(
			'post_title' => 'Shop Category',
			'post_content' => '',
			'post_status' => 'publish',
			'post_date' => date('Y-m-d H:i:s'),
			'post_author' => $current_user->ID,
			'post_type' => 'page'
	);
	$shop_page_id = wp_insert_post($new_post);
	$cart_page_id = wp_insert_post($new_post_args);
	$checkout_page_id = wp_insert_post($new_post_args1);
	$shop_category_page_id = wp_insert_post($new_post_args2);
	add_option( 'shop_page_id', $shop_page_id);
	add_option( 'cart_page_id', $cart_page_id);
	add_option( 'checkout_page_id', $checkout_page_id);
	add_option( 'shop_category_page_id', $shop_category_page_id);
	if (!$shop_page_id || !$cart_page_id || !$checkout_page_id || !$shop_category_page_id) {
		wp_die('Error creating template page');
	} else {
		update_post_meta($shop_page_id, '_wp_page_template', "template-shop.php");
		update_post_meta($cart_page_id, '_wp_page_template', "template-cart.php");
		update_post_meta($checkout_page_id, '_wp_page_template', "template-checkout.php");
		update_post_meta($shop_category_page_id, '_wp_page_template', "template-category.php");
	}
}
function order_deactivate()
{
	global $wpdb;
	$uninstall_smtp_query ="DROP TABLE IF EXISTS `".$wpdb->prefix."smtp_config`;";
	$uninst_product_meta = "DROP TABLE IF EXISTS `".$wpdb->prefix."order_meta`;";
	$uninst_enquiry_form_meta = "DROP TABLE IF EXISTS `".$wpdb->prefix."enquiry_form_meta`;";
	$uninst_enquiry = "DROP TABLE IF EXISTS `".$wpdb->prefix."enquiry_data`;";
	$wpdb->query($uninstall_smtp_query);
	$wpdb->query($uninst_product_meta);
	$wpdb->query($uninst_enquiry_form_meta);
	$wpdb->query($uninst_enquiry);
	//delete_option( "product_order" );
	//delete_option( "enquiry_form_order" );
	wp_delete_post(get_option( 'shop_page_id' ));
	wp_delete_post(get_option( 'cart_page_id' ));
	wp_delete_post(get_option( 'checkout_page_id' ));
	wp_delete_post(get_option( 'shop_category_page_id' ));
	delete_option("shop_page_id");
	delete_option( 'cart_page_id');
	delete_option( 'checkout_page_id');
	delete_option( 'shop_category_page_id');
	session_destroy();
}
function hide_page_attribute()
{
	// Get the Post ID.
	$post_id = $_GET['post'] ? $_GET['post'] : $_POST['post_ID'] ;
	if( !isset( $post_id ) ) return;
	// Hide the editor on the page titled 'Homepage'
	$homepgname = get_the_title($post_id);
	$shop_page_id = get_option( 'shop_page_id');
	$cart_page_id = get_option( 'cart_page_id');
	$checkout_page_id = get_option( 'checkout_page_id');
	$shop_category_page_id = get_option('shop_category_page_id');
	if($homepgname == 'Shop' || $homepgname == 'Cart' || $homepgname == 'Checkout' || $homepgname == "Shop Category" || $post_id == $shop_page_id || $post_id == $cart_page_id || $post_id == $checkout_page_id || $post_id == $shop_category_page_id )
	{
		remove_post_type_support('page', 'editor');
		remove_post_type_support('page','page-attributes');
	}
}
function get_attachent_url($product_id)
{
	$post_thumbnail_id = get_post_thumbnail_id( $product_id );
	$parsed = parse_url( wp_get_attachment_url( $post_thumbnail_id ) );
	$att_url = !empty($parsed["path"])?$parsed["scheme"]."://".$parsed["host"]."/".$parsed["path"]:"";
	return $att_url;
}
function get_email_by_id($userid)
{
	$user = get_user_by( 'id', $userid );
	return $user->user_email;
}
function get_product_name($product_id)
{
	return get_the_title($product_id);
}
function change_date_format($date)
{
	$show_date = date('d-m-Y', strtotime($date));
	return $show_date;
}
function show_query_str($str,$type="plain") //filtering string and get output modified
{
	$str_arr = json_decode($str);
	$ret_data = "";
	if($type == "plain")
	{
		foreach ($str_arr as $qur_key=>$qur)
		{
			if($qur_key != "Email")
			{
				$ret_data .= "<strong>$qur_key</strong>: $qur <br>";
			}
		}
	}
	else 
	{
		$ret_data = $str_arr->Email;
	}
	return $ret_data;
}
function extract_email($string)
{
	$pattern="/(?:[a-z0-9!#$%&'*+=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+=?^_`{|}~-]+)*|\"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*\")@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])/";

	preg_match_all($pattern, $string, $matches);
	$ret_email = "";

	foreach($matches[0] as $email)
	{
		//echo $email.", ";
		$ret_email = $email;
	}
	return $ret_email;
}
function enquiry_native_mail($to, $subject, $message, $headers)
{
	global $wpdb, $current_user;
	mail($to, $subject, $message, $headers);
	/*
	 $mail = new PHPMailer;

	 //$mail->SMTPDebug = 3;                               // Enable verbose debug output

	 $mail->isSMTP();                                      // Set mailer to use SMTP
	 $mail->Host = 'smtp1.example.com;smtp2.example.com';  // Specify main and backup SMTP servers
	 $mail->SMTPAuth = true;                               // Enable SMTP authentication
	 $mail->Username = 'user@example.com';                 // SMTP username
	 $mail->Password = 'secret';                           // SMTP password
	 $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
	 $mail->Port = 587;                                    // TCP port to connect to

	 $mail->setFrom('from@example.com', 'Mailer');
	 $mail->addAddress('joe@example.net', 'Joe User');     // Add a recipient
	 $mail->addAddress('ellen@example.com');               // Name is optional
	 $mail->addReplyTo('info@example.com', 'Information');
	 $mail->addCC('cc@example.com');
	 $mail->addBCC('bcc@example.com');

	 $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
	 $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
	 $mail->isHTML(true);                                  // Set email format to HTML

	 $mail->Subject = 'Here is the subject';
	 $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
	 $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

	 if(!$mail->send())
	 {
		echo 'Message could not be sent.';
		echo 'Mailer Error: ' . $mail->ErrorInfo;
		}
		else
		{
		echo 'Message has been sent';
		}
		*/
}

?>