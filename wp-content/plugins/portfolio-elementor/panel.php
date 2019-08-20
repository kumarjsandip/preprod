<?php


// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
   Panel
*/
add_action('admin_menu', 'elpt_setup_menu');
 
function elpt_setup_menu(){

	//Enqueue color picker
	wp_enqueue_style( 'wp-color-picker' );
	//wp_enqueue_script( 'elemenfolio-js', get_template_directory_uri().'/myscript.js', array( 'wp-color-picker','jquery' ), false, true );
	wp_enqueue_script( 'elemenfolio-js', plugin_dir_url( __FILE__ ) .  'js/elemenfolio-admin.js', array( 'wp-color-picker' ), '20151218', true );

	//Create Admin Page
 	$page_title = 'Elementor Portfolio';
    $menu_title = 'Elementor Portfolio';
    $capability = 'edit_posts';
    $menu_slug = 'elementor_portfolio';
    $function = 'elpt_options_page';
    $icon_url = 'dashicons-layout';
    $position = 99;

    add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );

    //Create Settings
    $option_group = 'elpt';

	register_setting( $option_group, 'elpt_color' );

	// Color Section
	$settings_section = 'elpt_main';
	$page = 'elpt';
	add_settings_section( $settings_section, __( 'Settings', 'elpt' ), '', $page );
	add_settings_field( 'elpt_color', __('Color Scheme', 'elpt'), 'elpt_color_callback', $page, 'elpt_main' );

	//Shortcode Section
	//add_settings_section( 'elpt_howto', __( 'How to display the portfolio grid', 'elpt' ), 'elpt_shortcode_callback', $page );
}

//Fields Callback
function elpt_color_callback(){
	echo '<input type="text" name="elpt_color" class="color-picker" value="' .get_option("elpt_color") .'"> Select the main color of your website <br>';
}	

//Texts
function elpt_shortcode_callback() {
	
}


//Page
function elpt_options_page() {
?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Elementor Portfolio- Settings', 'elemenfolio' ) ?></h1>
		<form action="options.php" method="post">
			<?php settings_fields( 'elpt' ); ?>
			<?php do_settings_sections( 'elpt' ); ?>
			<input name="Submit" type="submit" value="<?php esc_attr_e( 'Save Changes', 'elemenfolio' ); ?>" class="button button-primary" />
			<br/><br/><br/><hr/><br/>
			<h2><?php esc_html_e( 'How to display the Portfolio Grid', 'elemenfolio' ); ?></h2>
			<div ><p><strong><?php esc_html_e( 'The widget will be available in Elementor items. Just drag it to your website and select the customization options :)', 'elemenfolio' ); ?></strong></p></div>
			<h2><?php esc_html_e( 'Display using a shortcode', 'elemenfolio' ); ?></h2>
			<p><?php esc_html_e( 'You can also display the portfolio grid on a page/post using the [elemenfolio] shortcode.', 'elemenfolio' ); ?></p>
			<code>[elemenfolio]</code>
			<p><?php esc_html_e( 'You can customize it using these options:', 'elemenfolio' ); ?></p>
				<ul>
					<li><strong><?php esc_html_e('postsperpage'); ?></strong>: <?php esc_html_e( 'Set a number of posts to show', 'elemenfolio' ); ?> <i>(eg: postsperpage="12").</i></li>
					<li><strong><?php esc_html_e('type' ); ?></strong>: <?php esc_html_e( 'Set it to yes if you want to show a specific portfolio category. Options: ', 'elemenfolio' ); ?>  <i>yes/no. (eg: type="yes")</i>.</li>
					<li><strong><?php esc_html_e('taxonomy'); ?></strong>: <?php esc_html_e( 'Set the specific taxonomy slug. You need to set type="yes" to use this feature.', 'elemenfolio' ); ?>  <i>(eg: taxonomy="print")</i>.</li>
					<li><strong><?php esc_html_e('showfilter' ); ?></strong>: <?php esc_html_e( 'Show the category filter on the top of the grid. Options: ', 'elemenfolio' ); ?>  <i> yes/no. (eg: showfilter="yes")</i>.</li>
					<li><strong><?php esc_html_e('style'); ?></strong>: <?php esc_html_e( 'Set the grid style of the portfolio. Options: ', 'elemenfolio' ); ?>  <i> masonry/box. (eg: style="box")</i>.</li>
					<li><strong><?php esc_html_e('linkto'); ?></strong>: <?php esc_html_e( 'Set the link type of the portfolio item. If is set to image, it will open the Featured Image on a lightbox. Options: ', 'elemenfolio' ); ?>  <i> image/project. (eg: linkto="image")</i>.</li>
					<li><strong><?php esc_html_e('columns'); ?></strong>: <?php esc_html_e( 'Set the columns per row of the portfolio grid.  Options: ', 'elemenfolio' ); ?>  <i> 2/3/4. (eg: columns="4")</i>.</li>
					<li><strong><?php esc_html_e('margin'); ?></strong>: <?php esc_html_e( 'Choose if you want a margin between the items or no.  Options: ', 'elemenfolio' ); ?>  <i> yes/no. (eg: margin="no")</i>.</li>
				</ul>
			<h3><?php esc_html_e( 'Example of a complete shortcode:', 'elemenfolio' ); ?></h3>
			<code>[elemenfolio postsperpage="12" type="no" showfilter="yes" style="masonry" linkto="image" columns="4" margin="no"]</code>		
			<h3><?php esc_html_e( 'Example of a complete shortcode without the set properties:', 'elemenfolio' ); ?></h3>
			<code>[elemenfolio postsperpage="" type="" taxonomy="" showfilter="" style="" linkto="" columns="" margin=""]</code>				
		</form>
	</div>
	<div>
		
	</div>
<?php
}