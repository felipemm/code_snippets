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
    $connection = json_decode($db->connect(), true);
	$table = TABLENAME_PREFIX . 'usuario';
	
	if($connection['success']){
    
		//retrieve username and password from request
		$username = !empty($_POST['username']) ? addslashes(trim($_POST['username'])) : '';
		$password = !empty($_POST['password']) ? addslashes($_POST['password']) : '';

		//check if variables are not empty
		if($username != '' && $password != ''){
			//Try yo get the user information in the database
			$where['usuario_nome']  = $username;
			$where['usuario_senha'] = sha1($password);
			$fields[0] = '*';
			$request = json_decode($db->request($table, $fields, $where, null));
			
			//check if user data is good to login
			if($request->success && $request->result->num_records == 1 && $request->result->data[0]->status_id == USER_STATUS_APPROVED){

				//TODO: handle a session in the database and assign a session token to this session
				$session = new Session(MYSQL_HOSTNAME, MYSQL_DATABASE, MYSQL_USERNAME, MYSQL_PASSWORD);
				
				//populate the SESSION data with user information
				//var_dump($request->result);
				//session_start();
				$_SESSION['user_name']   = $request->result->data[0]->usuario_nome;
				$_SESSION['user_id']     = $request->result->data[0]->usuario_id;
				$_SESSION['is_admin']    = $request->result->data[0]->usuario_admin;
				$_SESSION['user_status'] = $request->result->data[0]->status_id;
				$_SESSION['client_ip']   = get_ip_real();
				echo json_encode(array('success'=>true,'usuario'=>$request->result->data[0]->usuario_nome,'usuario_id'=>$request->result->data[0]->usuario_id,"msg"=>"Seja bem vindo, ".$request->result->data[0]->usuario_nome));
			} else {
				//if(MYSQL_HOSTNAME == 'localhost'){
				//	$_SESSION['user_name']   = 'felipeeee';
				//	$_SESSION['user_id']     = 1;
				//	$_SESSION['is_admin']    = 1;
				//	$_SESSION['user_status'] = USER_STATUS_APPROVED;
				//	echo json_encode(array('success'=>true,'usuario'=>$_SESSION['user_name'],"msg"=>"Seja bem vindo, ".$_SESSION['user_name']));	
				//} else {
					echo json_encode(array('success'=>false,'usuario'=>'',"msg"=>"Usuário inexistente, inativo ou senha incorreta. Tente novamente."));				
				//}
			}
		} else {
			echo json_encode(array('success'=>false,'usuario'=>'',"msg"=>"Usuário e/ou senha não informados."));
		}
	} else {
		echo json_encode(array('success'=>false,'usuario'=>'',"msg"=>"Não foi possível conectar ao banco de dados"));
	}
?>