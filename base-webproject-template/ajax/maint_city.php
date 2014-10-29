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
	$table = TABLENAME_PREFIX . 'cidade';

	if($connection['success']){
		//retrieve session user
		session_start();
		$session_user_id     = (isset($_SESSION['user_id']) && trim($_SESSION['user_id']) !== ""  ? trim($_SESSION['user_id']) : null);
		$session_is_admin    = (isset($_SESSION['is_admin']) && trim($_SESSION['is_admin']) !== false  ? trim($_SESSION['is_admin']) : false);
		$session_user_status = (isset($_SESSION['user_status']) && trim($_SESSION['user_status']) !== ''  ? trim($_SESSION['user_status']) : null);

		//retrieve action from GET
		$action = (isset($_GET['action']) && trim($_GET['action']) !== ""  ? trim($_GET['action']) : null);
		$estado_id = (isset($_GET['estado_id']) && trim($_GET['estado_id']) !== ""  ? trim($_GET['estado_id']) : null);
		$cidade_id = (isset($_GET['cidade_id']) && trim($_GET['cidade_id']) !== ""  ? trim($_GET['cidade_id']) : null);

		//check if there is an user logged in the system and if the user is enabled, otherwise only select can be made
		if($session_user_id && $session_user_status == USER_STATUS_APPROVED || $action == 'SEL'){

			//check if an action informed
			if($action){

				switch(strtoupper($action)){
					case 'SEL':
						if($estado_id) $where['estado_id'] = $estado_id;
						if($cidade_id) $where['cidade_id'] = $cidade_id;
						//if($where) $where['OR estado_id'] = '0'; // para trazer a cidade "não disponível"
						
						$fields[0] = '*';
						echo $db->request($table, $fields, (isset($where) ? $where : null), null);
						break;



					case 'ADD':
						if($session_is_admin){
							$fields['cidade_nome']     = $_POST['cidade_nome'];
							$fields['estado_id']       = $_POST['estado_id'];
							echo $db->add($table, $fields);
						}
						break;



					case 'UPD':
						if($session_is_admin){
							$fields['cidade_nome'] = $_POST['cidade_nome'];
							$fields['estado_id']   = $_POST['estado_id'];
							$where['cidade_id']    = $cidade_id;
							echo $db->update($table, $fields);
						}
						break;



					case 'DEL':
						if($session_is_admin){
							$where['cidade_id'] = $cidade_id;
							echo $db->remove($table, $where);
						}
						break;
				}
			}
		}
    }
?>