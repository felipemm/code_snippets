<?php
    //============ SYSTEM INCLUDES ============
	require('database.php');

	
    //============ DATABASE SETTINGS ============
	// LOCAL
	if ($_SERVER['SERVER_ADDR'] == "127.0.0.1") {		
		//development environment
		define('MYSQL_HOSTNAME', 'localhost');
		define('MYSQL_USERNAME', 'root');
		define('MYSQL_PASSWORD', '');
		define('MYSQL_DATABASE', 'fmm_finance');

	// LIVE SERVER
	} else {
		//production environment
		define('MYSQL_HOSTNAME', 'mysql.felipematos.com');
		define('MYSQL_USERNAME', 'fmm_dbadmin');
		define('MYSQL_PASSWORD', 'fmoreira1986');
		define('MYSQL_DATABASE', 'fmm_finance');
	}


	
	
    //============ SYSTEM MESSAGES ============
	
	
    //============ GLOBAL VARIABLES ============
    define('TABLENAME_PREFIX', '');
		define('USER_STATUS_APPROVED', 1);
    define('USER_STATUS_PENDING', 2);
    define('USER_STATUS_REJECTED', 3);
    define('USER_STATUS_IN_APPROVAL', 4);
    define('USER_STATUS_CANCELLED', 5);
    define('USER_STATUS_NO_PAYMENT', 6);
	
    //============ SYSTEM FUNCTIONS ============
	/**
	 * Tenta Obter o IP real do cliente
	 * @return string
	 */
	function get_ip_real() {
		static $ip_real = null;
		if ($ip_real !== null) {
			return $ip_real;
		}
		if (array_key_exists('HTTP_CLIENT_IP', $_SERVER)) {
			$ip = trim($_SERVER['HTTP_CLIENT_IP']);
			if (validar_ip($ip)) {
				$ip_real = $ip;
				return $ip;
			}
		}
		if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) {
			$ip = trim($_SERVER['HTTP_X_FORWARDED_FOR']);
			if (validar_ip($ip)) {
				$ip_real = $ip;
				return $ip;
			} elseif (strpos($ip, ',') !== false) {
				$ips = explode(',', $ip);
				foreach ($ips as $ip) {
					$ip = trim($ip);
					if (validar_ip($ip)) {
						$ip_real = $ip;
						return $ip;
					}
				}
			} elseif (strpos($ip, ';') !== false) {
				$ips = explode(';', $ip);
				foreach ($ips as $ip) {
					$ip = trim($ip);
					if (validar_ip($ip)) {
						$ip_real = $ip;
						return $ip;
					}
				}
			}
		}
		if (array_key_exists('REMOTE_ADDR', $_SERVER)) {
			$ip = trim($_SERVER['REMOTE_ADDR']);
			if (validar_ip($ip)) {
				$ip_real = $ip;
				return $ip;
			}
		}
		
		$ip_real = '0.0.0.0';
		return $ip_real;
	}

	/**
	 * Valida um IP v4
	 * @param string $ip: IP a ser validado
	 * @return bool
	 */
	function validar_ip($ip) {

		// IPv4
		$vetor = explode('.', $ip);
		if (count($vetor) != 4) {
			return false;
		}
		foreach ($vetor as $i) {
			if (!is_numeric($i) || $i < 0 || $i > 255) {
				return false;
			}
		}
		return true;
	}
?>