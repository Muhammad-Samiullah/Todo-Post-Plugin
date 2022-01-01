function changeStatus(id, status) {
	console.log("changing status");
    var action = 'change_status';
    var form_data = {'id': id, 'status': status, 'action': action};
    jQuery(document).ready(function($) {
		$.ajax({
			url: frontend_ajax_object.ajaxurl,
		    type:"POST",
		    dataType:'text',
		    data : form_data,
			success: function( response ) {
				console.log(response);
			},
			error: function( response ) {
				console.log(response);
			}
		});
	});
}



function checkMark(element) {
	changeStatus(jQuery(element).data("id"), jQuery(element).is(":checked"));
	element = jQuery(element).parent().parent().parent();
	console.log(jQuery(element).find("input").is(":checked"));
	
    if(jQuery(element).find("input").is(":checked")) {
		jQuery(element).addClass("completed");
        jQuery(element).find(".status").html("Completed");

    }
    else {
		jQuery(element).removeClass("completed");
        jQuery(element).find(".status").html("Pending");
    }
}

function getTodoData() {
 
}

getTodoData();

function addTodoContent(form_data) {
    jQuery(document).ready(function($) {
		$.ajax({
			url: frontend_ajax_object.ajaxurl,
		    type:"POST",
		    dataType:'text',
		    data : form_data,
			success: function( response ) {
				console.log(response);
                location.reload();
			},
			error: function( response ) {
				console.log(response);
			}
		});
	});
}

jQuery(document).on('click', "#todo-form-btn", function(e){
    let todo_content_1 = jQuery("#todo-1").val();
    let todo_content_2 = jQuery("#todo-2").val();
    let todo_content_3 = jQuery("#todo-3").val();
	let todo_content_4 = jQuery("#todo-4").val();
	let todo_content_5 = jQuery("#todo-5").val();
    let post_id = jQuery(this).attr("data-post-id");
    let user_id = jQuery(this).attr("data-user-id");
    let action = 'todo_data_handler';
    let form_data = {'todo-content-1': todo_content_1, 'todo-content-2': todo_content_2, 'todo-content-3': todo_content_3, 'todo-content-4': todo_content_4, 'todo-content-5': todo_content_5, 'post-id': post_id, 'user-id': user_id, 'action': action};
	console.log(form_data);
    addTodoContent(form_data);
})

