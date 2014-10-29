<?php
//==============================================================================
// Filename: $sys_dir/ajax/maint_status.php
// 
// Description: php script to handle maintenance of the status table. 
// 
// INPUT: POST variable action
// 
// OUTPUT: It returns a JSON object to the requester with all the status.
//==============================================================================
    include('../include/config.php');

    //database connection
    $db = new database(MYSQL_HOSTNAME, MYSQL_DATABASE, MYSQL_USERNAME, MYSQL_PASSWORD);
    $connection = json_decode($db->connect(), true);
	$table = 'fmm_status';
	
	if($connection['success']){
		//retrieve session user
		session_start();
		$session_user_id     = (isset($_SESSION['user_id']) && trim($_SESSION['user_id']) !== ""  ? trim($_SESSION['user_id']) : null);
		$session_is_admin    = (isset($_SESSION['is_admin']) && trim($_SESSION['is_admin']) !== false  ? trim($_SESSION['is_admin']) : false);
		$session_user_status = (isset($_SESSION['user_status']) && trim($_SESSION['user_status']) !== ''  ? trim($_SESSION['user_status']) : null);
		
		//retrieve action from GET
		$action = (isset($_GET['action']) && trim($_GET['action']) !== ""  ? trim($_GET['action']) : null);
		$status_id = (isset($_GET['status_id']) && trim($_GET['status_id']) !== ""  ? trim($_GET['status_id']) : null);

		//check if there is an user logged in the system and if the user is enabled
		if($session_user_id && $session_user_status == USER_STATUS_APPROVED){
			
			//check if an action informed
			if($action){
	
				switch(strtoupper($action)){
					case 'SEL':
						$where = null;
						if($status_id) $where['status_id'] = $status_id;
						$fields[0] = '*';
						echo $db->request($table, $fields, $where, null);
						break;
						
						
					case 'ADD':
						if($session_is_admin){
							$fields['status_name'] = $_POST['status_name'];
							echo $db->add($table, $fields);
						}
						break;						
						
					case 'UPD':
						if($status_id && $session_is_admin){
							$fields['status_name'] = $_POST['status_name'];
							$where['status_id'] = $status_id;
							echo $db->update($table, $fields);
						}
						break;
						
						
					case 'DEL':
						if($status_id && $session_is_admin){
							$where['status_id'] = $status_id;
							echo $db->remove($table, $where);
						}
						break;
				}
			}
		}
    }
?>