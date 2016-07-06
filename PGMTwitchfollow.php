<?php
	session_start();
	include_once 'twitchtv.php';

	$twitchtv = new Twitchtv;
	if(!empty($_POST['follow'])) 
	{
		if (!isset($_SESSION["access_token"])) {
		    $ttv_code = $_GET['code'];
		    $access_token = $twitchtv->get_access_token($ttv_code);
		    $_SESSION["access_token"] = $access_token;		    
		} else {
		    $access_token = $_SESSION["access_token"];
		}	

		$user_name = $twitchtv->authenticated_user($access_token);
				
		if($_POST['follow'] == "false") {
			$response = $twitchtv->unfollow_channel($user_name, "vsdarknight", $access_token);
			
			echo $response;
			
		}
		elseif($_POST['follow'] === "true") {
			$response = $twitchtv->follow_channel($user_name, "vsdarknight", $access_token);

			echo $response;
		}
	}	
?>
