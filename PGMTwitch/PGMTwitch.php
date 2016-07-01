<?php

/**
  * Plugin Name: Plugin Twitch PGM
  * Description: Plugin pour l'intégration des données live et chat Twitch PGM
  * Version: 1.0
  * Author: Hidden
  */

  class PGMTwitch_plugin
  {
  	public function __construct()
  	{
  		
  		
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

  	/*public function embed_datas_twitch($atts)
  	{
  		extract(shortcode_atts(array(
  			'username' => "puregamemedia"
  		  ), $atts));

  		$videourl = "https://api.twitch.tv/kraken/streams/".$username;
  		$videos = self::file_get_contents_curl($videourl);
  		$obj = json_decode($videos, true);
  
		$infos['title'] = $obj['stream']['channel']['status'];
		$infos['url'] = $obj['stream']['channel']['url'];		
		$infos['game'] = $obj['stream']['game'];
		$infos['viewers'] = $obj['stream']['viewers'];
		$infos['views'] = $obj['stream']['channel']['views'];
		$infos['followers'] = $obj['stream']['channel']['followers'];

		$output = '<div class="PGM-datas-twitch">
						<ul>
							<li>'.$infos['title'].'</li>
							<li>'.$infos['url'].'</li>							
							<li>'.$infos['game'].'</li>
							<li>'.$infos['viewers'].'</li>
							<li>'.$infos['views'].'</li>
							<li>'.$infos['followers'].'</li>		
						</ul>
				</div>';
		
  		return $output;
  	}*/

  	public function file_get_contents_curl($url) {
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);

		$data = curl_exec($ch);
		curl_close($ch);

		return $data;
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
  		$videos = self::file_get_contents_curl($videourl);
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

		var_dump($infos);		

		return $infos;
  	}
  }

add_shortcode('embedTwitch', array('PGMTwitch_plugin', 'embed_live_twitch'));
add_shortcode('embedChat', array('PGMTwitch_plugin', 'embed_chat_twitch'));
add_shortcode('embedDatas', array('PGMTwitch_plugin', 'embed_datas_twitch'));



  
