<?php
	 
	 
	 $server_url = "http://127.0.0.1";
	 $default_country = "New Zealand";
	 $admin_email = "youremail@yourcompany.com";
	 $webmaster_email = "youremail@yourcompany.com";
	 $noreply_email = "noreply@yourcompany.com";
	 $smtp_server_host = "mail.smtp2go.com";
	 $smtp_user = "youremail@yourcompany.com";
	 $smtp_pass = "yourpassword";
	 $smtp_port = "2525";
	 

	//Prompt to confirm we have the correct address 127.0.0.1	
	echo "What is your AtomJump Messaging Appliance's base web address? [" . $server_url . "]\nPush enter to keep '" . $server_url . "', or type in a different address:\n";
	$input = rtrim(fgets(STDIN));
	if($input != "") {
		$server_url = $input;
		echo "Using server URL:" . $server_url . "\n";
	}
	
	echo "What is your country location? [" . $default_country . "]\nPush enter to keep '" . $default_country . "', or type in a different country (note: this is only used for display purposes):\n";
	$input = rtrim(fgets(STDIN));
	if($input != "") {
		$default_country = $input;
		echo "Using country:" . $default_country . "\n";
	}
	
	echo "What is your admin email address? [" . $admin_email . "]\nPush enter to keep '" . $admin_email . "', or type in a different email address (note: this is only used on this Appliance, internally, and is not shared):\n";
	$input = rtrim(fgets(STDIN));
	if($input != "") {
		$admin_email = $input;
		$webmaster_email = $admin_email;
		$noreply_email = $admin_email;		
		echo "Using admin email:" . $admin_email . "\n";
	}
	
	echo "What is your webmaster email address? [" . $webmaster_email . "]\nPush enter to keep '" . $webmaster_email . "', or type in a different email address (note: this is potentially visible to users):\n";		//TODO: check and explain why this is different.
	$input = rtrim(fgets(STDIN));
	if($input != "") {
		$webmaster_email = $input;
		echo "Using webmaster email:" . $webmaster_email . "\n";
	}
	
	echo "What is your 'no-reply' email address? [" . $noreply_email . "]\nPush enter to keep '" . $noreply_email . "', or type in a different email address (note: this will be visible to users in email notifications):\n";		
	$input = rtrim(fgets(STDIN));
	if($input != "") {
		$noreply_email = $input;
		echo "Using no-reply email:" . $noreply_email . "\n";
	}
	
	echo "What is your SMTP email sending server's host address? [" . $smtp_server_host . "]\nPush enter to keep '" . $smtp_server_host . "', or type in a different host address (Note: for SMTP2GO usage you will need to create an account with smtp2go.com):\n";		
	$input = rtrim(fgets(STDIN));
	if($input != "") {
		$smtp_server_host = $input;
		echo "Using SMTP host:" . $smtp_server_host . "\n";
	}
	
	echo "What is your SMTP account's username? [" . $smtp_user . "]\nPush enter to keep '" . $smtp_user . "', or type in a different username:\n";		
	$input = rtrim(fgets(STDIN));
	if($input != "") {
		$smtp_user = $input;
		echo "Using SMTP username:" . $smtp_user . "\n";
	}
	
	echo "What is your SMTP account's password? [" . $smtp_pass . "]\nPush enter to keep '" . $smtp_pass . "', or type in a different username:\n";		
	$input = rtrim(fgets(STDIN));
	if($input != "") {
		$smtp_pass = $input;
		echo "Using SMTP password:" . $smtp_pass . "\n";
	}
	
	echo "What is your SMTP account's port number? [" . $smtp_port . "]\nPush enter to keep '" . $smtp_port . "', or type in a different port number. (Note: this is the standard port for SMTP servers):\n";		
	$input = rtrim(fgets(STDIN));
	if($input != "") {
		$smtp_port = $input;
		echo "Using SMTP password:" . $smtp_port . "\n";
	}
	
	
	
	$config_file = "/jet/www/default/vendor/atomjump/loop-server/plugins/notifications/config/config.json";
	
	$config_file_str = file_get_contents($config_file); 
	
	$config_json = json_decode($config_file_str);
	if($config_json) {
		
		$config_json->serverPath = "/jet/www/default/vendor/atomjump/loop-server/";
		$config_json->streamingAppLink = $server_url . ":8000";
		$config_json->androidNotifications->use = false;
		$config_json->androidNotifications->apiKey = "";
		$config_json->iosNotifications->use = false;
		$config_json->iosNotifications->apiKeyFile = "";
		$config_json->atomjumpNotifications->use = true;
		$config_json->atomjumpNotifications->serverPool = array("Default" => array($server_url . ":5566"));
		$config_json->atomjumpNotifications->countryServerResidingIn = array("Default" => $default_country);		//TODO: allow a different default country
		
		
		file_put_contents($config_file, json_encode($config_json, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
		echo "Updated notifications plugin configuration.\n";
		
	} else {
		echo "Sorry, your config file " . $config_file . " is not correct JSON format. Please check the contents inside a JSON validation tool.\n";	
	}
	
	
	//Now update the main configuration for email setup.
	$config_file = "/jet/www/default/vendor/atomjump/loop-server/config/config.json";
	$config_file_str = file_get_contents($config_file); 
	
	$config_json = json_decode($config_file_str);
	if($config_json) {
	
		$config_json->staging->email->adminEmail = $admin_email;
		$config_json->staging->email->webmasterEmail = $webmaster_email;
		$config_json->staging->email->noReplyEmail = $noreply_email;
		
		$config_json->staging->email->sending->use = "smtp";
		$config_json->staging->email->sending->smtp = $smtp_server_host;
		$config_json->staging->email->sending->user = $smtp_user;
		$config_json->staging->email->sending->pass = $smtp_pass;
		$config_json->staging->email->sending->port = $smtp_port;
		
	
	
		file_put_contents($config_file, json_encode($config_json, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
		echo "Updated main Appliance configuration.\n";
		
	} else {
		echo "Sorry, your config file " . $config_file . " is not correct JSON format. Please check the contents inside a JSON validation tool.\n";	
	}
?>