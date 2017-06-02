<?php
/*
 * Template Name: Shop
 */

get_header();
$args = array( 'post_status' => 'publish', 'post_type' => 'product' );
$the_query = new WP_Query( $args );
?>
<div class="container">
    		<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
    		<?php
    		//echo get_the_id();
    		$post_thumbnail_id = get_post_thumbnail_id( get_the_id() );
    		$parsed = parse_url( wp_get_attachment_url( $post_thumbnail_id ) );
    		$att_url = !empty($parsed["path"])?$parsed["scheme"]."://".$parsed["host"]."/".$parsed["path"]:"";
    		$att_url = !empty($att_url)?$att_url:NO_IMG_URL;
    		 ?>

			<a href="<?php echo get_permalink(get_the_id());  ?>" class="shop_products">
			
				
					<img src="<?php echo $att_url;  ?>" alt="<?php the_title();  ?>" />

					<h2><?php the_title();  ?></h2>
					<!-- h3><?php //the_excerpt();  ?></h3-->
                    <button type="button">View &nbsp; <i class="fa fa-shopping-cart" aria-hidden="true"></i></button>
			</a>

    		<?php endwhile; // end of the loop. ?>


  </div><!-- #content -->
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

<?php
get_footer();
 ?>

