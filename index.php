<?php 
/*
Plugin Name: Todo
Description: This plugin provides the ability to add todo list to posts
Author: Muhammad Bilal
Version: 1.0.0
*/
add_filter('the_content', 'xai_my_class');
function xai_my_class($content){
	$content .= "<script>
	var postID = '" .  get_the_ID() . "';
	var userID = '" .  get_current_user_id() . "';
	</script>";
	
	
    //Replace the instance with the Class/ID markup.
    //$content .= file_get_contents (plugin_dir_path( __FILE__ ) . "todo-list.txt");
	$content .= data_getter_todo();
    return $content;
}




// Including Bootstrap 4
add_action('wp_enqueue_scripts', 'wp_enqueue_bootstrap4');
function wp_enqueue_bootstrap4() {
    wp_enqueue_style( 'bootstrap', '//maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css' );
    wp_enqueue_script( 'boot3','//maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js', array( 'jquery' ),'',true );
	
	wp_enqueue_style('main-styles', plugins_url( 'css/style.css' , __FILE__ ), array(), rand(), false);
    wp_enqueue_style('todostyler');
    wp_register_style('fontawesome', "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css", array(), '5.13.0', 'all');
    wp_enqueue_style('fontawesome');
    wp_enqueue_script( 'frontend-ajax', plugins_url( 'js/demo.js?x=' . rand(), __FILE__ ), array('jquery'), null, true );
    wp_localize_script( 'frontend-ajax', 'frontend_ajax_object',
        array( 'ajaxurl' => admin_url( 'admin-ajax.php' ))
    	);
}

// Retrieve Todo Information
add_action( 'wp_ajax_data_getter_todo', 'data_getter_todo' );
function data_getter_todo(){
    global $wpdb;
	$pid = $_POST['pid'];
	$postID = get_the_ID() ;
	$userID = get_current_user_id() ;
	$table1 = $wpdb->prefix . "todo_list_items_status";
    $table2 = $wpdb->prefix . "todo_list_items";
	
	
	
	$query = 'SELECT * FROM '.$table1.' t1 JOIN '.$table2.' t2 ON t1.todo_id=t2.id where post_id=' . $postID . ' AND user_id='. $userID;
		$rows = $wpdb->get_results($query);
		
		if($rows) {
			foreach($rows as $row) {				
				if($row->todo_status) {
					echo "<script>
									document.getElementById('".$row->todo_id."').checked = true;
								</script>";
				}
				
			}
		}
	
	
	
	
	$query = 'SELECT * FROM '.$table2.' where post_id=' . $postID ;
	//echo $query;
	$rows = $wpdb->get_results($query);

	if ( $rows ) {
	   $list= json_encode($rows, JSON_FORCE_OBJECT );
		
		$list = '<section class="action-items">
    <h2 class="card-title">To-do list</h2>
    
    <div class="progress">
      <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
        <span class="sr-only">0% Complete</span>
      </div>
    </div>
    <div>
      <ul class="to-do-list">';
		
		foreach($rows as $key => $row) {
    		//echo  $row->post_id ;
			//echo  $row->todo_content ;
		$list .= '
        <li class="to-do-item  d-flex align-items-center">
          <div>
            <label class="container"><input type="checkbox" onclick="checkMark(this)" id="'.$row->id.'" data-id="' . $row->id . '">
			<span class="checkmark"></span></label>
          </div>
          <p class="text">' . $row->todo_content . '</p>
          <!-- Status Indicators -->
          <p class="status">Pending</p>
          <!-- Replaces p.status node on hover if item is not completed -->
          <p class="status-on-hover">Mark</p>
        </li>';
		}

		$list .= '</ul>
    </div>
      
    </div>
  </section>';
		
		
    } else {
		if($userID == 1) {
			$list = "
		Enter Todo List Items: <br>
        <input type='text' id='todo-1' class='form-control' placeholder='Todo Content 1' name='todo-1' /><br>
		<input type='text' id='todo-2' class='form-control' placeholder='Todo Content 2' name='todo-2' /><br>
		<input type='text' id='todo-3' class='form-control' placeholder='Todo Content 3' name='todo-3' /><br>
		<input type='text' id='todo-4' class='form-control' placeholder='Todo Content 4' name='todo-4' /><br>
		<input type='text' id='todo-5' class='form-control' placeholder='Todo Content 5' name='todo-5' /><br>
		<button data-post-id='" . $postID . "' data-user-id='". $userID ."' class='btn btn-primary' id='todo-form-btn'>
		Add Todo List</button>";	
		}
		else {
			$list = "";
		}

	}
	return $list;
}

// Add Todo Content
add_action( 'wp_ajax_todo_data_handler', 'todo_data_handler' );
function todo_data_handler(){
    global $wpdb;
    $count = 0;
    $table = $wpdb->prefix . "todo_list_items";
    $todo_content_1 = $_POST['todo-content-1'];
    $todo_content_2 = $_POST['todo-content-2'];
    $todo_content_3 = $_POST['todo-content-3'];
	$todo_content_4 = $_POST['todo-content-4'];
	$todo_content_5 = $_POST['todo-content-5'];
    $post_id = $_POST['post-id'];
    $user_id = get_current_user_id();
	
	
	
	$wpdb->replace($table, array(
   "post_id" => $post_id,
	"todo_content" => $todo_content_1
	));
	
	$wpdb->replace($table, array(
   "post_id" => $post_id,
	"todo_content" => $todo_content_2
	));
	
	$wpdb->replace($table, array(
   "post_id" => $post_id,
	"todo_content" => $todo_content_3
	));
	
	$wpdb->replace($table, array(
   "post_id" => $post_id,
	"todo_content" => $todo_content_4
	));
	
	$wpdb->replace($table, array(
   "post_id" => $post_id,
	"todo_content" => $todo_content_5
	));
	
    $content .= 'Todo Data added successfully.' ; 
	return  $content;
	die();
}



// Todo Status Changer
// add_action('wp_ajax_nopriv_ajaxlogin','ajax_login');
add_action( 'wp_ajax_change_status', 'change_status' );
function change_status() {
    global $wpdb;
	$table = $wpdb->prefix . "todo_list_items_status";

    $id = $_POST['id'];
    $status = $_POST['status'];

	
	
	$wpdb->replace($table, array(
   "todo_id" => $id,
	"user_id" => get_current_user_id(),
   "todo_status" => $status
	));
	
// 	"INSERT INTO ".$table." (todo_id, user_id, todo_status) VALUES ".$.
	
	
	
	
	

		$content = 'Error changing status.';

	echo $wpdb->insert_id . " " . $status;
	die();
}



// Check initial table 
function SMART_AMR_Table_Check(){
    global $wpdb;
    
    $my_products_db_version = '1.0.0';
    $charset_collate = $wpdb->get_charset_collate();
        
    $table_name = $wpdb->prefix . "todo_list_items";
    if ( $wpdb->get_var("SHOW TABLES LIKE '{$table_name}'") != $table_name ) {
        $sql = "CREATE TABLE  $table_name ( 
            `id`  int NOT NULL AUTO_INCREMENT,
            `post_id`  varchar(256)   NOT NULL,
            `todo_content`  varchar(256)   NOT NULL,
            PRIMARY KEY  (id)
            ) $charset_collate;";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
        add_option('my_db_version', $my_products_db_version);
        }

    $table_name = $wpdb->prefix . "todo_list_items_status";
    if ( $wpdb->get_var("SHOW TABLES LIKE '{$table_name}'") != $table_name ) {
        $sql = "CREATE TABLE  $table_name ( 
            `id`  int NOT NULL AUTO_INCREMENT,
            `todo_id`  varchar(256)   NOT NULL,
            `user_id`  varchar(256)   NOT NULL,
            `todo_status`  varchar(256)   NOT NULL,
            PRIMARY KEY  (id)
            ) $charset_collate;";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
        add_option('my_db_version', $my_products_db_version);
    }
}

register_activation_hook( __FILE__, 'SMART_AMR_Table_Check' );

?>