<!--
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<link href="bootstrap.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
-->

<?php
//include get_home_path()."wp-config.php";
global $wpdb, $current_user;
get_currentuserinfo();
$curr_table_prefix = $wpdb->prefix;
$user = wp_get_current_user();
//echo "Table Prefix: $curr_table_prefix , current user: $current_user->user_login ";
$fetch_query = "select * from `".$wpdb->prefix."enquiry_data` where `enquiry_status` = '1' order by `enquiry_id` desc"; //where `userid` = '$user->ID'
$fetch_query_count = "select COUNT(*) from `".$wpdb->prefix."enquiry_data` where `enquiry_status` = '1'";
//echo $fetch_query_count;
$action = "add";
$this_page_url = admin_url("admin.php?page=".$_GET["page"]);
$reply_page_url = admin_url("admin.php?page=order-plugin/enquiry-edit.php");//order-plugin/enquiry-edit.php
//echo $this_page_url;
?>
<!--style>
    table.field_display, th, td {
   border: 1px solid black !important;
}
</style-->
<div class="wrap">
    <h1 class="wp-heading-inline">Posted Enquiry</h1>







    <hr class="wp-header-end">

	

    <?php
    if($wpdb->get_var($fetch_query_count)>0)
    {
	?>
	<table class="wp-list-table widefat fixed striped posts" id="">
	<thead>
	<tr>
		<td id="cb" class="manage-column column-cb check-column">
			<!--<label class="screen-reader-text" for="cb-select-all-1">Select All</label><input id="cb-select-all-1" type="checkbox">-->
		</td>

		<th scope="col" id="title" class="manage-column column-title column-primary sortable desc"><a href="#"><span>User</span><!-- span class="sorting-indicator"></span--></a></th>
		
		<th scope="col" id="title" class="manage-column column-title column-primary sortable desc"><a href="#"><span>Product</span><!-- span class="sorting-indicator"></span--></a></th>
		
		<!--th scope="col" id="title" class="manage-column column-title column-primary sortable desc"><a href="#"><span>Enquery Data</span><!-- span class="sorting-indicator"></span--><!--/a></th-->

		<th scope="col" id="date" class="manage-column column-date sortable asc"><a href="#"><span>Reply</span><!-- span class="sorting-indicator"></span--></a></th>
	</tr>
	</thead>
	<?php
        //$action = "edit";
        //$thepost = $wpdb->get_row( $wpdb->prepare( $fetch_query ) );
        //echo $thepost->post_title;
        $results = $wpdb->get_results($fetch_query);
		

        echo "<ul id='sortable-list'>";
        $id_str = "";
        foreach($results as $single_result) {
        	$id_str .= "$single_result->enquiry_id,";

		?>
			<tr <?php  echo "id='".$single_result->enquiry_id."' title='$single_result->enquiry_id'";  ?> class="iedit author-self level-0 post-8 type-product status-publish has-post-thumbnail hentry">
				<th scope="row" class="check-column">			
					
				</th>



				<td class="title column-title has-row-actions column-primary page-title enq_field" data-colname="Title" >
		<?php
					echo "$single_result->user_id";

		?>
				</td>
				<td>
		<?php
					$product_url = site_url("wp-admin/post.php?post=".$single_result->products."&action=edit");
					echo "<a href='$product_url'>".get_product_name($single_result->products)."</a>";
		?>
				</td>
				<!--td>
					<?php 
					//echo $single_result->enquiry_data;
					?>
				</td-->
				<td>
					<?php 
					echo "<a href='$reply_page_url&reply=$single_result->enquiry_id'>Reply</a>";
					?>
				</td>
			</tr>
		<?php
        }
		?>
		</tbody>

	<!-- tfoot>
	<tr>
		
		
		<th scope="col" id="title"><a href="#"><span>Product</span></a></th>
		<th scope="col" id="title"><a href="#"><span>Product</span></a></th>
		<th scope="col" id="title"><a href="#"><span>Enquiry Data</span></a></th>
		<th scope="col"><a href="#"><span>Reply</span></a></th>	
	</tr>
	</tfoot-->


	</table>

		<?php
    }
	else
	{
		echo "<p><strong>No Posted Enquiry</strong></p>";
	}
	?>
	
</div>
