<?

/**
  * Plugin Name: widget chat PGM
  * Description: widget pour l'intégration du chat PGM
  * Version: 1.0
  * Author: Hidden
  */

class PGM_chat_twitch_widget extends WP_widget{

	public function __construct()
	{
		parent::__construct(
			'PGM_chat_twitch',
			__('PGM chat Twitch', 'text_domain'),
			array(
				'description' => __('PGM chat Twitch', 'text_domain'),
			)
		);
		
	}	

	public function widget($args, $instance) {		
		extract($args);
		echo $before_widget;		
		?>
		<iframe frameborder="0" 
		        scrolling="no" 
		        id="chat_embed" 
		        src="http://www.twitch.tv/puregamemedia/chat" 
		        height="<?php echo $instance["height"]; ?>" 
		        width="<?php echo $instance["width"]; ?>">
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

function register_PGM_chat_twitch_widget()
{
	register_widget('PGM_chat_twitch_widget');
}
add_action('widgets_init', 'register_PGM_chat_twitch_widget');