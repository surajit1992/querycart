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
$protocol = isset($_SERVER["HTTPS"]) ? 'https' : 'http';
//echo $protocol;
$post_url = admin_url( 'admin.php?page=order-plugin%2Fenquiry-form-fields.php', $protocol );
if(!empty($_POST))
{
    $post = $_POST;
    if(!empty($post["action"]))
    {
        $action = $post["action"];
        unset($post["action"]);
        if($action == "add")
        {
            $query_builder = "";
            foreach($post as $single_key=>$single_val)
            {
                $query_builder .= "`$single_key`='$single_val',";
            }
            $query_builder = rtrim($query_builder,",");
            $query = "insert `".$wpdb->prefix."enquiry_form_meta` set $query_builder";
            $wpdb->query( $wpdb->prepare( $query ) );
			$insert_id = $wpdb->insert_id;
			update_field_order($wpdb->prefix."enquiry_form_meta",$insert_id,$insert_id,"field_meta_id");
            echo '<div id="message" class="updated notice notice-success is-dismissible"><p>Enquiry Meta Inserted.</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';

        }
        else
        {
            echo '<div id="message" class="updated notice notice-success is-dismissible"><p>This section is under construction.</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
        }
    }
}
$fetch_query = "select * from `".$wpdb->prefix."enquiry_form_meta`";
$results = $wpdb->get_results($fetch_query);
foreach($results as $single_result)
{
	$id_arr[] = $single_result->field_meta_id;
}
$fetch_query = "select * from `".$wpdb->prefix."enquiry_form_meta` order by `field_order` asc"; //where `userid` = '$user->ID'
$fetch_query_count = "select COUNT(*) from `".$wpdb->prefix."enquiry_form_meta`";
//echo $fetch_query_count;
$action = "add";

?>
<!--style>
    table.field_display, th, td {
   border: 1px solid black !important;
}
</style-->
<div class="wrap">
    <h1 class="wp-heading-inline">Meta Fields</h1>

	<a href="#demo"  class="page-title-action" data-toggle="collapse">Add New</a>

	<div id="demo" class="collapse">

    <form action="<?php echo $post_url; ?>" method="post">
        <table class="wp-list-table widefat fixed striped posts" id="newmeta">
        <thead></thead>
        <tbody>
            <tr>
                <td style="width:2%;text-align:left;padding-left:20px;"><span><strong>Field Name</strong></span></td>
                <td style="width:10%;text-align:left;padding-left:20px;">
					<input type="text" name="field_name" required="" value="<?php echo !empty($thepost->field_name)?$thepost->field_name:"";  ?>" style="width:100%;" /></td>
                <input type="hidden" name="userid" value="<?php echo $user->ID;  ?>" />
                <input type="hidden" name="action" value="<?php echo $action;  ?>" />
                <td style="width:5%;text-align:left;padding-left:20px;"><strong>Can Be Deleted / Can Not Be Deleted ? </strong></td>
				<td style="width:1%;text-align:left;"><input type="checkbox" value="1" <?php echo !empty($thepost->remarks)?"checked":"";  ?> name="remarks" /></td>
                <td colspan="2" style="width:2%;text-align:left;"><input type="submit" value="Save"/></td>
            </tr>
        </tbody>
    </table>
    </form>

	</div>





    <hr class="wp-header-end">

	<table class="wp-list-table widefat fixed striped posts" id="diagnosis_list">
	<thead>
	<tr>
		<td id="cb" class="manage-column column-cb check-column">
			<!--<label class="screen-reader-text" for="cb-select-all-1">Select All</label><input id="cb-select-all-1" type="checkbox">-->
		</td>

		<th scope="col" id="title" class="manage-column column-title column-primary sortable desc"><a href="#"><span>Fields Name</span><!-- span class="sorting-indicator"></span--></a></th>
		
		<th scope="col" id="title" class="manage-column column-title column-primary sortable desc"><a href="#"><span>Status</span><!-- span class="sorting-indicator"></span--></a></th>

		<th scope="col" id="date" class="manage-column column-date sortable asc"><a href="#"><span>Date</span><!-- span class="sorting-indicator"></span--></a></th>
	</tr>
	</thead>

    <?php
    if($wpdb->get_var($fetch_query_count)>0)
    {
        //$action = "edit";
        //$thepost = $wpdb->get_row( $wpdb->prepare( $fetch_query ) );
        //echo $thepost->post_title;
        $results = $wpdb->get_results($fetch_query);
        echo "<ul id='sortable-list'>";
        $id_str = "";
        foreach($results as $single_result) {
        	$id_str .= "$single_result->field_meta_id,";

		?>
			<tr <?php  echo "id='".$single_result->field_meta_id."_list' title='$single_result->field_meta_id'";  ?> class="iedit author-self level-0 post-8 type-product status-publish has-post-thumbnail hentry">
				<th scope="row" class="check-column">			
					<!--<label class="screen-reader-text" for="cb-select-8">Select product 1</label>
					<input id="cb-select-8" name="post[]" value="8" type="checkbox">
					<div class="locked-indicator">
					<span class="locked-indicator-icon" aria-hidden="true"></span>
					<span class="screen-reader-text">“product 1” is locked</span>
					</div>-->
				</th>



				<td class="title column-title has-row-actions column-primary page-title enq_field" data-colname="Title" >
		<?php
					echo "$single_result->field_name";

		?>
				</td>
				<td>
		<?php
					if(!empty($single_result->field_meta_id) && !empty($single_result->remarks))
					{
						echo "<p class=''><a onclick='delete_order($single_result->field_meta_id)'>Can Be Deleted</a></p>";
					}
					else
					{
						echo "<p class=''><a>Can Not Be Deleted</a></p>";
					}
					//echo "</li>";
					//echo "<tr><td>$single_result->field_name</td><td>order</td><td>$single_result->remarks</td></tr>";
		?>
				</td>
				<td>
					<?php 
					$published_date = $single_result->timestamp;
					$date_part = explode(" ",$published_date);
					$show_date = change_date_format($date_part[0]); 
					echo "Published $show_date"; ?>
				</td>
			</tr>
		<?php
        }
        
        echo "</ul> <input type='hidden' name='id_arr' id='id_arr' value='".json_encode($id_arr)."' />";
    }

	?>
	</tbody>

	<tfoot>
	<tr>
		<td class="manage-column column-cb check-column">
		<!--<label class="screen-reader-text" for="cb-select-all-2">Select All</label><input id="cb-select-all-2" type="checkbox">-->
		</td>
		
		<th scope="col" class="manage-column column-title column-primary sortable desc"><a href="#"><span>Fields Name</span><span class="sorting-indicator"></span></a></th>
		
		<th scope="col" id="title" class="manage-column column-title column-primary sortable desc"><a href="#"><span>Status</span><!-- span class="sorting-indicator"></span--></a></th>
		<th scope="col" class="manage-column column-date sortable asc"><a href="#"><span>Date</span><span class="sorting-indicator"></span></a></th>	</tr>
	</tfoot>


	</table>

    <script>
    var page = "enquiry";
    </script>
</div>
