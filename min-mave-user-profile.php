<?php
/**
 * Plugin Name: Min-mave User Profile widget
 * Description: Brugerblog user profile widget
 * Author: Donald Chi
 * Version: 0.01
 * Author URI: http://chidev.me
*/
$minmaveUserProfileAdmin = new MinmaveUserProfileAdmin();
//add_action('widgets_init', array('MinmaveUserProfile', 'registerMinmaveUserProfile'));
add_action('admin_menu', array( $minmaveUserProfileAdmin, 'minmave_profile_settings_menu'));
add_action('admin_enqueue_scripts', array( $minmaveUserProfileAdmin, 'admin_scripts' ));

class MinmaveUserProfileAdmin
{
	public function minmave_profile_settings_menu() 
	{
		$site_category = get_option('site_category'); 
		
		add_submenu_page('options-general.php', __('User Profile Widget Settings', 'BTCRS'), __('User Profile Widget Settings', 'BTCRS'), 'manage_options', 'user-profile-widget-settings', 'MinmaveUserProfileAdmin::minmave_userprofile_settings');
	}
	
	public static function minmave_userprofile_settings()
	{
		if(isset($_POST['user_profile_html']))
		{
			$input = $_POST;
			
			update_option('user_profile_html', $input['user_profile_html']);
			
			$message = __('Indstillinger gemt.', 'BTCRS');
		}
		
		require_once('admin/min-mave-user-profile-settings.phtml');
	}
        
        public function admin_scripts()
        {
            $plugin_url  = plugins_url( '/', __FILE__ );
            
            if(isset($_GET['page']) && $_GET['page'] == 'user-profile-widget-settings')
            {
                wp_register_script( 'customTMCE2', $plugin_url.'tinymce/tinymce.min.js', false, false, true );
                wp_enqueue_script( 'customTMCE2' );
                wp_register_script( 'customTMCEwidget', $plugin_url. 'js/script.js?v=1', false, false, true );
                wp_enqueue_script( 'customTMCEwidget' );
            }
        }
}

$minmaveUserProfile = new MinmaveUserProfile();
add_action('widgets_init', array( $minmaveUserProfile, 'registerMinmaveUserProfile'));

class MinmaveUserProfile extends WP_Widget 
{
	
    public function __construct() 
    {
        $widget_options = array('description' => __('Minmave User Profile', 'BTCRS'));
        //$control_ops = array( 'width' => 200, 'height' => 350, 'id_base' => 'banner-ads' );
        $this->WP_Widget( 'user-profile', __('Minmave User Profile', 'BTCRS'), $widget_options);        
    }
      
    public function form($instance) 
    {
    	$widget_id = $this->id_base . '-' . $this->number;
		
    	$defaults = array( 'title' => __('Title'));
        $instance = wp_parse_args( (array) $instance, $defaults );
        $title = strip_tags($instance['title']); 
		
?>
		<p>
			<label><?php _e('Title','BTCRS')?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name('title')?>" type="text" value="<?php echo esc_attr($title)?>" />
		</p>
<?php 
    }	
	
    public function update($new_instance, $old_instance)
    {
        $instance = $old_instance; 
        $instance['title'] = strip_tags($new_instance['title']);

        return $instance;        
    }
	  
    public function widget($args, $instance)
    {
        $before_widget = $args[ 'before_widget' ];
        $before_title = $args[ 'before_title' ];
        $title = apply_filters('widget_title', $instance['title'] );
        $after_title = $args[ 'after_title' ];
        $after_widget = $args[ 'after_widget' ];
     
        $user_profile_content = get_option('user_profile_html');
        
        if(!empty($user_profile_content))
        {
?>
		<div id="user-profile" class="block"><?php echo stripslashes($user_profile_content)?></div>
<?php 
        }
    }  
    
    /*public function registerMinmaveUserProfile()
    {
    	$site_category = get_option('site_category'); 
		
		if($site_category == 'Brugerblog')
		{
        	register_widget('MinmaveUserProfile'); 
		}       
    }*/

    public function registerMinmaveUserProfile()
    {
        register_widget('MinmaveUserProfile');
    }
}