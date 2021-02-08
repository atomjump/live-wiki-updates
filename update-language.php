<?php
	
		//Allow tweaks to the main chat-inner-x.y.z.js file, so that the latest translations can be used.
		
		//Change the base js file (note, we already have all 15 languages in here - this is an update)
		
		if(file_exists("/jet/www/default/vendor/atomjump/loop-server/js/")) {          //looking for chat-inner*, but so long as the dir exists a file should be there
			//Put the chat-inner* file into a string
			$file_array = glob("/jet/www/default/vendor/atomjump/loop-server/js/chat-inner*");
			if($file_array[0]) {
					$message_script_str = file_get_contents("chat-inner.js");            //Get the new messages for this js file
					$js_script_str = file_get_contents($file_array[0]);
					$new_js_script_str = str_replace("//Language & messages configuration\n","", $js_script_str); 	//Remove the old lines at the top
					$new_js_script_str = str_replace("//Note: also see /config/messages.json for further messages configuration\n","", $new_js_script_str); 		//Remove the old lines at the top
					
					$new_js_script_str = preg_replace('#var lsmsg = (.*?)var lang#s',$message_script_str . "var lang", $new_js_script_str);
					//Write out the new live js file (not version controlled), alongside the old one (which is version controlled). The config.json points at this live version.
					$new_js_filename = str_replace("chat-inner-", "chat-inner-live-" , $file_array[0]);
					file_put_contents($new_js_filename, $new_js_script_str);
										
					//And make sure the config is using this latest version
					$new_js_filename_escaped = str_replace("../..", "", $new_js_filename);
					$new_js_filename_escaped = str_replace("/", "\/", $new_js_filename_escaped);
					
					//Check if 'chatInnerJSFilename' already exists in the config file. Add to staging/production if not.
					$config_file = "/jet/www/default/vendor/atomjump/loop-server/config/config.json";
					$config_str = file_get_contents($config_file);
					if(strpos($config_str, "chatInnerJSFilename") != false) {
						//it already exists in the file.
					} else {
						//Add in the chatInnerJSFilename to the config file.
						$config_str = str_replace("\"phpPath\": \"/usr/bin/php\",","\"phpPath\": \"/jet/bin/php\",", $config_str); //Swap out any old Production PHP paths
						$config_str = str_replace("\"phpPath\": \"/jet/bin/php\",",
													"\"phpPath\": \"/jet/bin/php\",\n      \"chatInnerJSFilename\": \"\",", $config_str);
						
						//Rewrite the file
						file_put_contents($config_file, $config_str);
					}
						
					$replacer_command = "sudo sed -ie 's/\"chatInnerJSFilename\": \".*\"/\"chatInnerJSFilename\": \"" . $new_js_filename_escaped . "\"/g' /jet/www/default/vendor/atomjump/loop-server/config/config.json";
					echo $replacer_command . "\n";
					exec($replacer_command);

					
			}

		}
		
		
		//Also tweak the front chat js. E.g. /jet/www/default/livewiki/js/chat-1.0.9.js
		//Loop through each chat-*.js
		echo "Scanning for chat-*.js\n";
		$js_files = scandir("/jet/www/default/livewiki/js/");
		foreach($js_files as $js_file) {
			echo "JS file:" . $js_file . "\n";
			if(strpos($js_file, "chat") !== false) {
				
				$chatjs = "/jet/www/default/livewiki/js/" . $js_file;
				echo "Chat.js file being updated:" . $chatjs . "\n";
		
				//E.g. $chatjs = "/jet/www/default/livewiki/js/chat.js";
				if(file_exists($chatjs)) {          //looking for chat.js, but so long as the dir exists a file should be there
					//Put the chat.js file into a string               
					$message_script_str = file_get_contents("chats.js");            //Get the new messages for this js file
					$js_script_str = file_get_contents($chatjs);
					$new_js_script_str = str_replace("//Language & messages configuration\n","", $js_script_str); 	//Remove the old lines at the top
					$new_js_script_str = str_replace("//Note: also see /config/messages.json for further messages configuration\n","", $new_js_script_str); 		//Remove the old lines at the top
	
					$new_js_script_str = preg_replace('#var lsmsg = (.*?)var lang#s','' . $message_script_str . 'var lang', $new_js_script_str);
					//Write out the new js file, overwriting the old one
					file_put_contents($chatjs, $new_js_script_str);
				}
			}
		}


		//And now update the notifications .config.json 
		$config_file = "/jet/www/default/vendor/atomjump/loop-server/plugins/notifications/config/config.json";
		$config_original_file = "/jet/www/default/vendor/atomjump/loop-server/plugins/notifications/config/configDEFAULT.json";
		$config_json = json_decode(file_get_contents($config_file), true);
		$config_original_json = json_decode(file_get_contents($config_original_file), true);
		
		//Copy across new Android link
		$android_app_link = $config_original_json['androidAppLink'];
		$config_json['androidAppLink'] = $android_app_link;
		
		//Copy across the new messages
		$msgs = $config_original_json['msgs'];
		$config_json['msgs'] = $msgs;
		
		//And update the config file
		file_put_contents($config_file, json_encode($config_json, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
		echo "Updated notifications plugin configuration.\n";

?>