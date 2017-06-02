<?php get_header(); ?>
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
<div class="container">
<?php 

$categories = get_terms();
//print_r($categories);
//echo "<pre>";
foreach($categories as $single_category)
{
	if($single_category->taxonomy == "category" && $single_category->count > 0 && $single_category->name != "Uncategorized")
	{
		//print_r($single_category);
		$cat_id = $single_category->term_id;
		$cat_img_url = !empty(z_taxonomy_image_url($cat_id))?z_taxonomy_image_url($cat_id):NO_IMG_URL;
		$cat_title = $single_category->name;
		$category_link = get_category_link( $cat_id );
		?>
		<a href="<?php echo $category_link;  ?>" class="shop_products">
			
				
					<img src="<?php echo $cat_img_url;  ?>" alt="<?php echo $cat_title;  ?>" />
					<h2><?php echo $cat_title; ?></h2>
					<h3><?php echo NO_IMG_URL;  ?></h3>
                    <button type="button">View &nbsp; <i class="fa fa-shopping-cart" aria-hidden="true"></i></button>
			</a>
		<?php 
	}
}

?>
</div>
<?php get_footer(); ?>