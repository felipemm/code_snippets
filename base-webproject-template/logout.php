<?php

//session está registrada então está tudo pronto para fazer o logout
include 'include/config.php';
include 'include/session.php';

$session = new Session(MYSQL_HOSTNAME, MYSQL_DATABASE, MYSQL_USERNAME, MYSQL_PASSWORD);

//destroi a sessão
$session->delete();


//session_start();
//session_unset();
//session_destroy();

echo "<META HTTP-EQUIV='REFRESH' CONTENT=\"0; URL='index.php'\">";


?>