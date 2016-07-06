<?

/**
  * Plugin Name: widget login PGM
  * Description: widget pour l'intégration du login PGM
  * Version: 1.0
  * Author: Hidden
  */


class PGM_login_twitch_widget extends WP_widget{

	
	public function __construct()
	{
		parent::__construct(
			'PGM_login_twitch',
			__('PGM login Twitch', 'text_domain'),
			array(
				'description' => __('PGM login Twitch', 'text_domain'),
			)
		);
		
	}	

	public function widget($args, $instance) {			
		
		extract($args);

		if (!empty($_POST['logout']) && ($_POST['logout'] === "true"))
		{
			unset($_SESSION["access_token"]);
			
		}
		
		if (!isset($_SESSION['return_url']))
		{
			$_SESSION['return_url'] = $_SERVER["REQUEST_URI"];  				
		}  		
  		
  		include_once 'twitchtv.php';
		
		echo $before_widget;	
		
		$client_id = $instance['client_id'];
		$client_secret = $instance['client_secret'];
		
		$twitchtv = new TwitchTv($client_id, $client_secret); 

		if (!isset($_SESSION["access_token"])) {
		    echo '<a href="' . $twitchtv->authenticate() . '">M\'identifier</a><br/>';
		    $ttv_code = $_GET['code'];
		    $access_token = $twitchtv->get_access_token($ttv_code);
		    $_SESSION['access_token'] = $access_token;
		} else {
		    $access_token = $_SESSION["access_token"];		    
		}

		$user_name = $twitchtv->authenticated_user($access_token);
		
		if (isset($user_name)) {
			$stream_name = $instance['stream_name'];		
		    
		    echo $user_name;
		    echo '<form action="'.$_SESSION['return_url'].'" method="post">
		    		<input type="hidden" name="logout" value="true">
					 <p><input type="submit" value="logout"></p>
				  </form>';

		    $is_following = $twitchtv->is_following_channel($stream_name, $access_token);

		    if ($is_following)
  			{
  				echo '<form id="form_follow" action="/">
					  	<input type="submit" id="follow_submit" data-value="false" value="Ne plus suivre">
					  </form>
					  <div id="result"></div>
				  	  ';  				
  			}
  			else
  			{
  				echo '<form id="form_follow" action="/">
					  	<input type="submit" id="follow_submit" data-value="true" value="Suivre la chaine">
					  </form>
					  <div id="result"></div>
				  	  ';  				
  			}  			
		}

		echo $after_widget;
		
	}

	public function form($instance) {	
		?>
		<p>
			<label for="<?php echo $this->get_field_id("client id"); ?>">Client id : </label>
			<input value="<? echo $instance["client_id"]; ?>" name="<?php echo $this->get_field_name("client_id"); ?>" id="<?php echo $this->get_field_id("client_id"); ?>" type="text"/>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id("client secret"); ?>">Client secret : </label>
			<input value="<? echo $instance["client_secret"]; ?>" name="<?php echo $this->get_field_name("client_secret"); ?>" id="<?php echo $this->get_field_id("client_secret"); ?>" type="text"/>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id("stream name"); ?>">stream name: </label>
			<input value="<? echo $instance["stream_name"]; ?>" name="<?php echo $this->get_field_name("stream_name"); ?>" id="<?php echo $this->get_field_id("client_secret"); ?>" type="text"/>
		</p>					
		<?php 
	}

	public function update($new, $old) {		
		return $new;
	}

}

function register_PGM_login_twitch_widget()
{
	register_widget('PGM_login_twitch_widget');
}

function js_setup()
{
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script("PGMscript", plugin_dir_url(__FILE__).'js/PGMscript.js', array(), '1.0.0', true );
}

function jquery_jquery_ui() {
 if (!is_admin()) {
  wp_deregister_script('jquery');

  // Google API (CDN)
  wp_register_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js', false, '1.12.4', true);
 
  wp_enqueue_script('jquery');
  wp_enqueue_script('jquery-ui');
 }
}
add_action('init', 'jquery_jquery_ui');

add_action('widgets_init', 'register_PGM_login_twitch_widget');
add_action('wp_enqueue_scripts', 'js_setup');
