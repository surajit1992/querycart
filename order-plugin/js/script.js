$ = jQuery;
$(document).ready(function()
{
	//alert("hi");
	show_cart_number();
	jQuery("body").prepend("<div class='div_eqy_cart_full_width'><a id='cart_url' class='cart_section'><div class='wqy_cart_products cart_numbers' id='show_equ_item'></div> <div class='wqy_cart_products'>items in your cart</div></a></div>");
	//$("#show_equ_item").html();
		//Helper function to keep table row from collapsing when being sorted
	var fixHelperModified = function(e, tr) {
		var $originals = tr.children();
		var $helper = tr.clone();
		$helper.children().each(function(index)
		{
		  $(this).width($originals.eq(index).width())
		});
		return $helper;
	};
	//Make diagnosis table sortable
	$("#diagnosis_list tbody").sortable({
    	helper: fixHelperModified,
		stop: function(event,ui) {renumber_table('#diagnosis_list')}
	}).disableSelection();
	/*Delete button in table rows
	$('table').on('click','.btn-delete',function() {
		tableID = '#' + $(this).closest('table').attr('id');
		r = confirm('Delete this item?');
		if(r) {
			$(this).closest('tr').remove();
			renumber_table(tableID);
			}
	});*/
});
function show_cart_number()
{
	var data = {'action': 'total_items','cart_items':'show_cart' };
	jQuery.post(ajaxurl, data, function(response_data) {
		console.log(response_data);
		//var show_html=;
		jQuery("#cart_url").attr("href", response_data.cart_url);
		jQuery("#show_equ_item").html(response_data.total_items);
		//jQuery(".wqy_cart_products").html(response);
	},"json");
}
function add_to_cart(product_id)
{
	//alert(product_id);
	var data = {'action': 'frontend_function','product': product_id };
	jQuery.post(ajaxurl, data, function(response) {
			//alert('Got this from the server: ' + response);
			if(response.status == "success")
			{
				alert(response.cart_items);
			}
		},"json");
}
function update_order_option(page,order)
{
	var id_arr = jQuery("#id_arr").val();
	//alert(id_arr);
	//alert(page);
	//alert(order);
	if(page == "enquiry")
	{
		var data = { 'action': 'update_order','enquiry': order,"id_arr":id_arr };
	}
	else if(page=="product")
	{
		var data = {'action': 'update_order','order': order, "id_arr":id_arr };
	}
	else
	{
		var data = "";
	}

	jQuery.post(ajaxurl, data, function(response) {
			//alert('Got this from the server: ' + response);
		});
}
function delete_order(order_id)
{
	//alert(order_id);



	if(page == "enquiry")
	{
		var data= {'action': 'update_order','delete_enquiry': order_id};
	}
	else if(page=="product")
	{
		var data= {'action': 'update_order','delete_order': order_id};
	}
	else
	{
		var data = "";
	}
	
	r = confirm('Delete this item?');
	
	if(r)
		{
			$.post(ajaxurl, data, function(response) 
				{
					alert(response);
					if(response == "successful")
					{
						jQuery("#"+order_id+"_list").hide();
					}
				});
		}
}


function renumber_table(tableID) 
{
	var total = parseInt($(tableID + " tr").length)-1;
	//alert("total - "+total);
	var i = 0;
	var arr = [];
	$(tableID + " tr").each(function() 
	{
		//alert(i);
		count = $(this).parent().children().index($(this)) + 1;
		$(this).find('.priority').html(count);
		var str_id = $(this).attr('id');
		//alert(str_id);
		if(str_id != undefined)
			{
				var str_id = $(this).attr("title");
				arr.push(str_id);
			}
		if(total == i)
			{
			
				var snd_str = JSON.stringify(arr);
				//alert(snd_str);
				arr = [];
				//alert("this is last");
				//alert(page);
				update_order_option(page,snd_str);
			}
		//alert($(this).attr('id')+ " - " +count);
		i++;
	});
}

function remove_cart_item(product_id)
{
	var data= {'action': 'update_cart','product_id': product_id};
	jQuery.post(ajaxurl, data, function(response) {
		//alert('Got this from the server: ' + response);
		if(response == "success")
		{
			show_cart_number();
			jQuery("#"+product_id).hide("slow");
			//alert(response);
		}
	},"html");
	//alert(product_id);
}