<?php
//==============================================================================
// Filename: $sys_dir/ajax/maint_user.php
// 
// Description: php script to handle maintenance of the user table. 
// 
// INPUT: POST variable action
// 
// OUTPUT: It returns a JSON object to the requester with all the users 
//         registered to the user in the $_SESSION
//==============================================================================
    include('../include/config.php');

    //database connection
    $db = new database(MYSQL_HOSTNAME, MYSQL_DATABASE, MYSQL_USERNAME, MYSQL_PASSWORD);
    $connection = json_decode($db->connect(), true);
	$table = TABLENAME_PREFIX . 'estado';
	
	if($connection['success']){
		//retrieve session user
		session_start();
		//$session_user_id     = (isset($_SESSION['user_id']) && trim($_SESSION['user_id']) !== ""  ? trim($_SESSION['user_id']) : null);
		//$session_is_admin    = (isset($_SESSION['is_admin']) && trim($_SESSION['is_admin']) !== false  ? trim($_SESSION['is_admin']) : false);
		//$session_user_status = (isset($_SESSION['user_status']) && trim($_SESSION['user_status']) !== ''  ? trim($_SESSION['user_status']) : null);
		
		//retrieve action from GET
		$action = (isset($_GET['action']) && trim($_GET['action']) !== ""  ? trim($_GET['action']) : null);
		$pais_id = (isset($_GET['pais_id']) && trim($_GET['pais_id']) !== ""  ? trim($_GET['pais_id']) : null);

		//check if there is an user logged in the system and if the user is enabled
		//if($session_user_id && $session_user_status == USER_STATUS_APPROVED){
			
			//check if an action informed
			if($action){
	
				switch(strtoupper($action)){
					case 'SEL':
						if($pais_id) $where['pais_id'] = $pais_id;
						$fields[0] = '*';
						echo $db->request($table, $fields, (isset($where) ? $where : null), null);
						break;

						
						
					case 'ADD':
						$fields['usuario_nome']     = $_POST['user_name'];
						$fields['usuario_email']    = $_POST['user_email'];
						$fields['usuario_senha']    = sha1($_POST['user_email']);
						$fields['usuario_telefone'] = $_POST['user_cep'];
						$fields['cidade_id']        = $_POST['cidade_id'];
						$fields['status_id']        = USER_STATUS_PENDING;
						$fields['usuario_admin']    = 0;
						echo $db->add($table, $fields);
						break;



					case 'UPD':
						if($user_id){
							$fields['user_name']     = $_POST['user_name'];
							$fields['user_password'] = sha1($_POST['user_password']);
							$fields['user_email']    = $_POST['user_email'];
							$fields['user_token']    = sha1($_POST['user_email']);
							$fields['user_cep']      = $_POST['user_cep'];
							$fields['status_id']     = USER_STATUS_PENDING;
							$fields['user_admin']    = $_POST['user_admin'];					
							$where['user_id'] = $user_id;
							echo $db->update($table, $fields);
						}
						break;					

					
					
					case 'DEL':
						if($user_id == $session_user_id || $session_is_admin){					
							$where['user_id'] = $user_id;
							echo $db->remove($table, $where);
						}
						break;	
				}
			}
		//}
    }
?>