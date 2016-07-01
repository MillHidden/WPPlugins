<?

/**
  * Plugin Name: widget live PGM
  * Description: widget pour l'intégration du live PGM
  * Version: 1.0
  * Author: Hidden
  */

class PGM_live_twitch_widget extends WP_widget{

	public function __construct()
	{
		parent::__construct(
			'PGM_live_twitch',
			__('PGM live Twitch', 'text_domain'),
			array(
				'description' => __('PGM live Twitch', 'text_domain'),
			)
		);
		
	}	

	public function widget($args, $instance) {
		echo $before_widget;		
		?>
		<iframe 
		        src="http://player.twitch.tv/?channel=puregamemedia" 		         
		        frameborder="0"
		        height="<?php echo $instance["height"]; ?>" 
		        width="<?php echo $instance["width"]; ?>"> 
		        scrolling="no"
		        allowfullscreen="true">
		</iframe>
		<?php 
		echo $after_widget;
	}

	public function form($instance) {	
		?>
		<p>
			<label for="<?php echo $this->get_field_id("height"); ?>">Hauteur : </label>
			<input value="<? echo $instance["height"]; ?>" name="<?php echo $this->get_field_name("height"); ?>" id="<?php echo $this->get_field_id("height"); ?>" type="text"/>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id("width"); ?>">largeur : </label>
			<input value="<? echo $instance["width"]; ?>" name="<?php echo $this->get_field_name("width"); ?>" id="<?php echo $this->get_field_id("width"); ?>" type="text"/>
		</p>			
		<?php 
	}

	public function update($new, $old) {		
		return $new;
	}

}

function register_PGM_live_twitch_widget()
{
	register_widget('PGM_live_twitch_widget');
}
add_action('widgets_init', 'register_PGM_live_twitch_widget');