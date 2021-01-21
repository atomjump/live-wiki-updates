<?php
	// /jet/www/default/livewiki/js/chat-1.0.9.js
		    //Allow tweaks to the other messages file within the javascript
			
			//TODO: Change the base js file (note, we already have all 15 languages in here)
			/*
			if(file_exists("/jet/www/default/vendor/atomjump/loop-server/js/")) {          //looking for chat-inner*, but so long as the dir exists a file should be there
                //Put the chat-inner* file into a string
                $file_array = glob("/jet/www/default/vendor/atomjump/loop-server/js/chat-inner*");
                if($file_array[0]) {
                        $message_script_str = file_get_contents("chat-inner.js");            //Get the new messages for this js file
                        $js_script_str = file_get_contents($file_array[0]);
                        $new_js_script_str = preg_replace('#lsmsg = (.*?)var lang#s',$message_script_str . "\nvar lang", $js_script_str);
                		//Write out the new live js file (not version controlled), alongside the old one (which is version controlled). The config.json points at this live version.
                		$new_js_filename = str_replace("chat-inner-", "chat-inner-live-" , $file_array[0]);
						file_put_contents($new_js_filename, $new_js_script_str);
                							
						//And make sure the config is using this latest version
						$new_js_filename_escaped = str_replace("../..", "", $new_js_filename);
						$new_js_filename_escaped = str_replace("/", "\/", $new_js_filename_escaped);
						
						$replacer_command = "sudo sed -ie 's/\"chatInnerJSFilename\": \".*\"/\"chatInnerJSFilename\": \"" . $new_js_filename_escaped . "\"/g' /jet/www/default/vendor/atomjump/loop-server/config/config.json";
						echo $replacer_command . "\n";
						exec($replacer_command);

                		
                }

        	}*/
        	
        	
        	//Also tweak the front chat js.
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
						$new_js_script_str = preg_replace('#var lsmsg = (.*?)var lang#s','' . $message_script_str . '\nvar lang', $js_script_str);
						//Write out the new js file, overwriting the old one
						file_put_contents($chatjs, $new_js_script_str);
					}
				}
			}


?>