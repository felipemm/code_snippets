<?php
//==============================================================================
// Filename: $sys_dir/ajax/check_login.php
// 
// Description: php script to authenticate user in the system. It will connect
//              to database and verify user credentials. 
// 
// INPUT: POST variables 'username' and 'password'
// 
// OUTPUT: It returns a JSON object to the requester with the status of the 
//         request and a message to be showed to the user.
//==============================================================================
    include('../include/config.php');
	include('../include/session.php');

    //database connection
    $db = new database(MYSQL_HOSTNAME, MYSQL_DATABASE, MYSQL_USERNAME, MYSQL_PASSWORD);
	$session = new Session(MYSQL_HOSTNAME, MYSQL_DATABASE, MYSQL_USERNAME, MYSQL_PASSWORD);
    $connection = json_decode($db->connect(), true);
	$tableSession = TABLENAME_PREFIX . 'sessions';
	$tableUser = TABLENAME_PREFIX . 'usuario';
	
	if($connection['success']){
    
		$_SESSION['last_updated'] = "1800-01-01 00:00:00";
		if(isset($_SESSION['user_name']) && $_SESSION['user_name'] != ''){
			if($_SESSION['client_ip'] == get_ip_real()){
				//Try to get the user information in the database
				$where['usuario_nome']  = $_SESSION['user_name'];
				$fields[0] = '*';
				$request = json_decode($db->request($tableUser, $fields, $where, null));
				
				//check if user data is good to login
				if($request->success && $request->result->num_records == 1 && $request->result->data[0]->status_id == USER_STATUS_APPROVED){
					echo json_encode(array('success'=>true,'usuario'=>$request->result->data[0]->usuario_nome,'usuario_id'=>$request->result->data[0]->usuario_id,"msg"=>"Sessão validada!"));
				} else {
					echo json_encode(array('success'=>false,'usuario'=>'',"msg"=>"Usuário inexistente ou não aprovado."));
				}
			} else {
				echo json_encode(array('success'=>false,'usuario'=>'',"msg"=>"IP do usuário não bate com o da sessão"));				
			}
		} else {
			echo json_encode(array('success'=>false,'usuario'=>'',"msg"=>"Não existem usuários logados."));				
		}
	} else {
		echo json_encode(array('success'=>false,'usuario'=>'',"msg"=>"Não foi possível conectar ao banco de dados"));
	}
?>