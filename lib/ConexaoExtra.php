<?php

class ConexaoExtra extends PDO {
	
	public function __construct() {
		try {
			$driver   = get_option('db_driver');
			$host 	  = get_option('db_host');
			$database = get_option('db_database');
			$username = get_option('db_username');
			$password = get_option('db_password');
			
			if ($driver == 'mysql')
				parent::__construct("$driver:host=$host;dbname=$database", $username, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));
			else
				parent::__construct("$driver:host=$host;dbname=$database;charset=UTF-8'", $username, $password);
				
			parent::exec('SET QUOTED_IDENTIFIER ON; SET ANSI_WARNINGS ON');			
								
		} catch (Exception $e) {
			echo $e->getMessage(); exit;
		}
	}
}