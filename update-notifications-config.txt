<?php
	 
	 
	 $server_url = "http://127.0.0.1";
	 $default_country = "New Zealand";

	//Prompt to confirm we have the correct address 127.0.0.1	
	echo "What is your AtomJump Messaging Appliance's base web address? [http://127.0.0.1]\nPush enter to keep 'http://127.0.0.1', or type in a different address:\n";
	$input = rtrim(fgets(STDIN));
	if($input != "") {
		$server_url = $input;
		echo "Using server URL:" . $server_url . "\n";
	}
	
	echo "What is your AtomJump Messaging Appliance's country location? [New Zealand]\nPush enter to keep 'New Zealand', or type in a different country (note: this is only used for display purposes):\n";
	$input = rtrim(fgets(STDIN));
	if($input != "") {
		$default_country = $input;
		echo "Using country:" . $default_country . "\n";
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


?>