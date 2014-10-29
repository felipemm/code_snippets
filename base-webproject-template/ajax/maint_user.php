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
	include('../include/session.php');
	
    //database connection
    $db = new database(MYSQL_HOSTNAME, MYSQL_DATABASE, MYSQL_USERNAME, MYSQL_PASSWORD);
    $connection = json_decode($db->connect(), true);
	$table = TABLENAME_PREFIX . 'usuario';
	
	if($connection['success']){
		//retrieve session user
		$session             = new Session(MYSQL_HOSTNAME, MYSQL_DATABASE, MYSQL_USERNAME, MYSQL_PASSWORD);
		$session_user_id     = (isset($_SESSION['user_id']) && trim($_SESSION['user_id']) !== ""  ? trim($_SESSION['user_id']) : null);
		$session_is_admin    = (isset($_SESSION['is_admin']) && trim($_SESSION['is_admin']) !== false  ? trim($_SESSION['is_admin']) : false);
		$session_user_status = (isset($_SESSION['user_status']) && trim($_SESSION['user_status']) !== ''  ? trim($_SESSION['user_status']) : null);
		
		//retrieve action from GET
		$action = (isset($_GET['action']) && trim($_GET['action']) !== ""  ? trim($_GET['action']) : null);
		$user_id = (isset($_GET['user_id']) && trim($_GET['user_id']) !== ""  ? trim($_GET['user_id']) : null);

		//check if there is an user logged in the system and if the user is enabled
		if($session_user_id && $session_user_status == USER_STATUS_APPROVED || $action == 'SEL' || $action == 'ADD'){
			
			//check if an action informed
			if($action){
	
				switch(strtoupper($action)){
					case 'SEL':
						if($user_id) $where['user_id'] = $user_id;
						$fields[0] = '*';
						echo $db->request($table, $fields, $where);
						break;

						
						
					case 'ADD':
						$fields[0] = '*';
						$where['usuario_nome']     = $_POST['usuario_nome'];
						$where['usuario_email']    = $_POST['usuario_email'];
						$check_exists = json_decode($db->request($table, $fields, $where), true);
						//var_dump($check_exists);
						if($check_exists['result']['num_records'] == 0){
							$fields = array();
							$fields['usuario_nome']     = $_POST['usuario_nome'];
							$fields['usuario_email']    = $_POST['usuario_email'];
							$fields['usuario_senha']    = sha1($_POST['usuario_senha']);
							$fields['usuario_telefone'] = $_POST['usuario_telefone'];
							$fields['cidade_id']        = $_POST['cidade_id'];
							
							$fields['status_id']        = ($session_is_admin ? $_POST['status_id'] : USER_STATUS_APPROVED);//USER_STATUS_PENDING);
							$fields['usuario_admin']    = ($session_is_admin ? $_POST['usuario_admin'] : 0);
							echo $db->add($table, $fields);
						} else {
							echo json_encode(array('success'=>false,'result'=>array(),"msg"=>"Usuário já existente!"));
						}
						break;



					case 'UPD':
						if($user_id){
							$fields['usuario_nome']     = $_POST['usuario_nome'];
							$fields['usuario_email']    = $_POST['usuario_email'];
							$fields['usuario_senha']    = sha1($_POST['usuario_senha']);
							$fields['usuario_telefone'] = $_POST['usuario_telefone'];
							$fields['cidade_id']        = $_POST['cidade_id'];
							
							if($session_id_admin){
								$fields['status_id']     = $_POST['status_id'];
								$fields['usuario_admin'] = $_POST['usuario_admin'];
							}
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
		}
    }
?>