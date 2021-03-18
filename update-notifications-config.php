<?php
	 
	 
	 //TODO Prompt to confirm we have the correct address 127.0.0.1
	 $server_url = "http://127.0.0.1";
	
	
	$config_file = "/jet/www/default/vendor/atomjump/loop-server/plugins/notifications/config/config.json";
	
	
	$config_file_str = file_get_contents($config_file); 
	
	$config_json = json_decode($config_file_str);
	if($config_json) {
		
		$config_json['serverPath'] = "/jet/www/default/vendor/atomjump/loop-server/";
		$config_json['streamingAppLink'] = $server_url . ":8000";
		$config_json['androidNotifications']['use'] = false;
		$config_json['androidNotifications']['apiKey'] = "";
		$config_json['iosNotifications']['use'] = false;
		$config_json['iosNotifications']['apiKeyFile'] = "";
		$config_json['atomjumpNotifications']['use'] = true;
		$config_json['atomjumpNotifications']['serverPool'] = array("Default" => array($server_url . ":5566"));
		$config_json['countryServerResidingIn'] = array("Default" => "New Zealand");		//TODO: allow a different default country
		
		
		file_put_contents($config_file, json_encode($config_json, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
		echo "Updated notifications plugin configuration.\n";
		
	} else {
		echo "Sorry, your config file " . $config_file . " is not correct JSON format. Please check the contents inside a JSON validation tool.\n";	
	}


?>