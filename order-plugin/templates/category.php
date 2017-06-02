<?php 
get_header();
$cat_id = get_query_var('cat');
$args = array( 'post_status' => 'publish', 'post_type' => 'product', 'category' => $cat_id );
$get_products = get_posts($args);

//echo "<pre>"; print_r($get_products);
?>
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
<!-- 
<div class="site-content-contain">
 <div id="primary" class="site-content">
 -->
  <div id="container">
  <?php //while ( $the_query->have_posts() ) : $the_query->the_post();
foreach ($get_products as $single_post)
{
	

?>
    		<?php
    		//echo get_the_id();
    		$post_thumbnail_id = get_post_thumbnail_id( $single_post->ID );
    		$parsed = parse_url( wp_get_attachment_url( $post_thumbnail_id ) );
    		$att_url = !empty($parsed["path"])?$parsed["scheme"]."://".$parsed["host"]."/".$parsed["path"]:"";
    		$att_url = !empty($att_url)?$att_url:"http://www.nelchinainc.com/wp-content/uploads/2011/10/No-product-image.jpg";
    		 ?>

			<a href="<?php echo get_permalink($single_post->ID);  ?>" class="shop_products">
			
				
					<img src="<?php echo $att_url;  ?>" alt="<?php echo $single_post->post_title;  ?>" />

					<h2><?php echo $single_post->post_title;;  ?></h2>
					<!-- h3><?php //the_excerpt();  ?></h3-->
                    <button type="button">View &nbsp; <i class="fa fa-shopping-cart" aria-hidden="true"></i></button>
			</a>

    		<?php 
}
    		 //endwhile; // end of the loop. ?>

  <!-- 
  </div> </div>
   -->
    </div>

<?php 
get_footer();
?>