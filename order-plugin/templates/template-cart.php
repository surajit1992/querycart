<?php
/*
 * Template Name: Cart
 */

get_header();
global $wpdb;
//print_r($_SESSION);
//$args = array( 'post_status' => 'publish', 'post_type' => 'product' );
//$the_query = new WP_Query( $args );
//$the_query->have_posts()
//$the_query->the_post()
?>
<div class="site-content-contain">
<div id="primary" class="site-content">
  <div id="container">
  	
    		<?php while ( have_posts() ) : the_post() ; ?>
    		<?php
    		//echo get_the_id();
    		$old_item = $_SESSION['cart_items'];
    		//print_r($old_item);
    		 ?>
<!-- Cart Page section start here --><div id="no-more-tables" class="cart_cont">
<?php  if(count($old_item)>0)
{ ?>
        
            <table class="table-bordered table-striped table-condensed cf">
        		<thead class="cf">
        			<tr>
        				<th></th>
        				<th></th>
        				<th class="numeric">Product</th>
        				<th class="numeric">Query</th>
        				<th class="numeric">Email</th>
        			</tr>
        		</thead>
        		<tbody>
        		<?php foreach($old_item as $product_id=>$single_product)
        		{
        			$product_img = get_attachent_url($product_id);
        			$product_title = get_product_name($product_id);
        			//echo $product_img.$product_title."<br>";
        		 ?>
        			<tr id="<?php echo $product_id; ?>">
        				<td data-title=""><a href="javascript:remove_cart_item(<?php echo $product_id; ?>);" class="cart_cross">X</a></td>
        				<td data-title="" class="img_cart"><img src="<?php echo $product_img; ?>" class="img-responsive" alt="" /></td>
        				<td data-title="Product" class="numeric"><?php echo $product_title; ?></td>
        				<td data-title="Price" class="numeric"><?php echo show_query_str($single_product); ?></td>
        				<td data-title="Price" class="numeric"><?php echo show_query_str($single_product,"email"); ?></td>
        			</tr>
        			<?php } ?>         
        		</tbody>
        	</table>
             <?php }
             else 
             {
             echo "<p>Your cart is empty</p><style>.checkout_btn{ display:none; }</style>";
             }?>
             
             <div class="checkout_cont">
             	<button name="cont_shop" onclick="window.location.href='<?php echo get_permalink(get_option('shop_page_id')); ?>';" type="button" class="btn btn-default pull-right cnt_shop">Continue Shopping</button>
             	<!-- div class="checkout_text"><input name="checkout_cart" type="text" /></div-->
                <button name="checkout_btn" onclick="window.location.href='<?php echo get_permalink(get_option('checkout_page_id')); ?>';" type="button" class="btn checkout_btn">Proceed to checkout</button>
             </div>
        </div>
<?php endwhile; // end of the loop. ?>

<!-- Cart page section end here -->
 </div><!-- #content -->
</div><!-- #primary -->
</div>
<?php
get_footer();
 ?>

