<?php

/**
  * Plugin Name: Plugin Twitch PGM
  * Description: Plugin pour l'intégration des données live et chat Twitch PGM
  * Version: 1.0
  * Author: Hidden
  */

  class PGMTwitch_plugin
  {
  	protected $client_id;
  	protected $client_secret;
  		

  	public function __construct($client_id = 't6a5c7t3yr8usx1yh3kuse4w3uwlq5r', $client_secret = 'o6phe6l29f9v5798tqvs15nk7f2b3im')
  	{
  		$this->client_id = $client_id;
  		$this->client_secret = $client_secret;
  		
  		add_action('widgets_init', function()
			{register_widget('PGM_live_twitch_widget');}
		);		
  	}

  	public function embed_live_twitch($atts)
  	{
		extract(shortcode_atts(array(
		    'username' => "puregamemedia",
		    'width' => "100%",
		    'height' => "450",
		  ), $atts));
			  
		$output = '<iframe 
		        src="http://player.twitch.tv/?channel=?>'.$username.'" 
		        height="'.$height.'" 
		        width="'.$width.'" 
		        frameborder="0" 
		        scrolling="no"
		        allowfullscreen="true">
		    </iframe>';

		return $output;
  	}

  	public function embed_chat_twitch($atts)
  	{
  		extract(shortcode_atts(array(
		    'username' => "puregamemedia",
		    'width' => "100%",
		    'height' => "450",
		  ), $atts));		
		
		$output = '<iframe frameborder="0" 
		        scrolling="no" 
		        id="chat_embed" 
		        src="http://www.twitch.tv/'.$username.'/chat" 
		        height="'.$height.'" 
		        width="'.$width.'">
			</iframe>';

		return $output;
  	}  	

  	public function get_url_contents($url) {
		$crl = curl_init();
		$timeout = 5;

		curl_setopt($crl, CURLOPT_URL, $url);
		curl_setopt($crl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($crl, CURLOPT_CONNECTTIMEOUT, $timeout);		
		
		$ret = curl_exec($crl);
		curl_close($crl);

		return $ret;
	}

	public function post_url_contents($url, $fields)
	{
		foreach($fields as $key => $value)
		{
			$fileds_string = $key.'='.urlencode($value).'&';
		}
		rtrim($fields_string, '&');

		$crl = curl_init();
		$timeout = 5;

		curl_setopt($crl, CURLOPT_URL,$url);
	    curl_setopt($crl, CURLOPT_POST, count($fields));
	    curl_setopt($crl, CURLOPT_POSTFIELDS, $fields_string);

	    curl_setopt ($crl, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt ($crl, CURLOPT_CONNECTTIMEOUT, $timeout);

	    $ret = curl_exec($crl);
	    curl_close($crl);

	    return $ret;

	}

	public function embed_datas_twitch($atts)
  	{
  		extract(shortcode_atts(array(
  			'username' => "puregamemedia",
  			'title' => false,
  			'url' => false,
  			'game' => false,
  			'viewers' => false,
  			'followers' => false,
  			'datas' => 'some'

   		  ), $atts));

  		$videourl = "https://api.twitch.tv/kraken/streams/".$username;
  		$videos = self::get_url_contents($videourl);
  		$obj = json_decode($videos, true);
  		
  		if ($datas === "all")
  		{  		
			$title = true;
			$url = true;
			$game = true;
			$viewers = true;
			$followers = true;
		} 
		if ($title) 
		{
			$infos['title'] = $obj['stream']['channel']['status'];
		}
		if ($url)
		{
			$infos['url'] = $obj['stream']['channel']['url'];
		}
		if ($game)
		{
			$infos['game'] = $obj['stream']['game'];
		}
		if ($viewers)
		{
			$infos['viewers'] = $obj['stream']['viewers'];
		}
		if ($followers)
		{
			$infos['followers'] = $obj['stream']['channel']['followers'];
		}		

		return $infos;
  	}

  	public function get_user()
  	{
				
  		if ($_GET['logout'] === "true")
		{
			unset($_SESSION["access_token"]);
			
		}

  		if (!isset($_SESSION['return_url']))
		{
			$_SESSION['return_url'] = $_SERVER["REQUEST_URI"];  				
		}
  		
  		include_once 'twitchtv.php';
		$twitchtv = new TwitchTv($this->client_id, $this->client_secret); 		
		if (!isset($_SESSION["access_token"])) {
		    echo '<a href="' . $twitchtv->authenticate() . '">M\'identifier</a><br/>';
		    $ttv_code = $_GET['code'];
		    $access_token = $twitchtv->get_access_token($ttv_code);
		    $_SESSION["access_token"] = $access_token;
		} else {
		    $access_token = $_SESSION["access_token"];
		}
		$user_name = $twitchtv->authenticated_user($access_token);

		if (isset($user_name)) {		    
		    echo 'Thank you ' . $user_name . '!  Authentication Completed!';
		    echo '<a href="'.$_SESSION['return_url'].'?=logout">Logout</a><br/>';

		    $is_following = $twitchtv->is_following_channel("puregamemedia", $access_token);

		    if ($is_following)
  			{
  				echo('<a href="#">Ne plus suivre</a>');
  			}
  			else
  			{
  				echo('<a href="#">Suivre la chaîne</a>');
  			}
		}
	}	
 	
  }

add_shortcode('embedTwitch', array('PGMTwitch_plugin', 'embed_live_twitch'));
add_shortcode('embedChat', array('PGMTwitch_plugin', 'embed_chat_twitch'));
add_shortcode('embedDatas', array('PGMTwitch_plugin', 'embed_datas_twitch'));
add_shortcode('embedUser', array('PGMTwitch_plugin', 'get_user'));
