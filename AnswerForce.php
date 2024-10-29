<?php
/*
Plugin Name: AnswerForce 
Description: AnswerForce live-chat is a customer communication, support and analytics tool that enables you to provide direct support to visitors on your WordPress site.
Version: 2.3
Author: AnswerForce
Author URI:  https://www.answerforce.com/live-chat/
License: GPL2
 */
/*===========================================
Do the work, create a database field
===========================================*/

/* Runs on plugin deactivation*/
register_deactivation_hook( __FILE__, 'af_deleteDB' );

function af_deleteDB() {
	/* Deletes the database field */
  delete_option('projectId');
  delete_option('widgetThemeColor');
  delete_option('logoUrl');
  delete_option('widgetName');
  delete_option('widgetDomain');
}

function af_saveDB() {
//register our settings
register_setting( 'register-settings-group', 'projectId' );
register_setting( 'register-settings-group', 'widgetThemeColor' );
register_setting( 'register-settings-group', 'logoUrl' );
register_setting( 'register-settings-group', 'widgetName' );
register_setting( 'register-settings-group', 'widgetDomain' );
}
/*===========================================
Create an admin menu to me loaded
===========================================*/

if ( is_admin() ){
/* Call the html code */
add_action('admin_menu', 'af_adminMenu');

function af_adminMenu() {
  add_action( 'admin_init', 'af_saveDB' );
  $page_title = 'AnswerForce';
  $menu_title = 'AnswerForce';
  $capability = 'manage_options';
  $menu_slug  = 'AnswerForce';
  $function   = 'answerforce_InfoPage';
  $icon_url   = plugins_url( '/images/answerforce-logo.png', __FILE__);
  // $icon_url = 'dashicons-format-chat';
  $position   = 13;

add_menu_page( $page_title,
$menu_title,
$capability,
$menu_slug,
$function,
$icon_url,
$position );
}
}
/*===========================================
Add all the necessary dependencies
===========================================*/
add_action('init','af_load_scripts');
add_action("plugins_loaded", "af_load_widget");


function af_load_scripts() {
  // wp_enqueue_style( 'reset', plugins_url( '/css/reset.css', __FILE__));
  wp_enqueue_style( 'wordpress-style', plugins_url( '/css/wordpress-style.css', __FILE__));
}

/*===========================================
	Add HTML for the Chat SuppoerWidget
 ===========================================*/

 function af_load_widget() {
  if ( ! is_admin() ) {
    $projectId = get_option('projectId');
    if($projectId){
    ?>
      <script> 
      ( function( a , b , c , d , e , f , g ) { c[d] = c[d] || function() { (c[d].q = c[d].q || []).push(arguments); }; c[ "_lsAlias" ] = c[ d ]; e = a.createElement(b); e.type = "text/javascript"; e.async = true; e.src = "https://app.chatsupport.co/api/client/get/script/<?php echo $projectId ?>"; f = function() { g = a.getElementsByTagName(b)[0]; g.parentNode.insertBefore( e , g ); }; c.addEventListener( "load" , f ); } )( document , "script" , window , "_ls" ); _ls( "init" , { "projectId" : "<?php echo $projectId ?>" } ) 
      </script>

    <?php

    }
  }

}
  
/*===========================================
AnswerForce HTML page
===========================================*/
if( !function_exists("answerforce_InfoPage") )
{
function answerforce_InfoPage(){
?>
<style>
.wordpress-project-clips > aside figure{
  width: 40px;
  height: 40px;
  border-radius: 100%;
  background-color: #6699ff;
  position: absolute;
  left: 22px;
  top: 50%;
  margin: -20px 0px 0px;
  background-image: url(plugins_url( '/images/chatsupport-white-logo-icon.svg', __FILE__));
  background-repeat: no-repeat;
  background-position: center;
  background-size: 20px;
}
#submit {display: none;}
</style>  
 <?php 
      echo '<link rel="stylesheet" href="' . esc_url( plugins_url( '/css/reset.css', __FILE__ ) ) . '">';  
         ?>    
<form method="post" action="options.php">
<?php settings_fields( 'register-settings-group' ); ?>
<?php do_settings_sections( 'register-settings-group' ); ?>
<main class="wordpress-plugin-wrp">
  <section id="connect-widget-setup" class="wordpress-plugin-setup display-none">
      <div>
      <?php 
      echo '<img src="' . esc_url( plugins_url( '/images/wordpress-logo.svg', __FILE__ ) ) . '">';  
      echo '<img src="' . esc_url( plugins_url( '/images/link-icon.svg', __FILE__ ) ) . '" >';
      echo '<img src="' . esc_url( plugins_url( '/images/answerforce-logo.svg', __FILE__ ) ) . '" >';
      ?>
      </div>
      <?php   echo '<img src="' . esc_url( plugins_url( '/images/template-icon.png', __FILE__ ) ) . '">';  ?>
      <button class="btn-progressing" style="display:none"><span>Connect to AnswerForceM</span></button>
      <input type="button" id="cwa-login" siteUrl="<?php echo get_bloginfo('wpurl');?>" value="Connect to AnswerForce">
  </section>
  <section id ="multiple-widget-setup" class="widget-install display-none">
      <?php  echo '<img src="'. esc_url( plugins_url( '/images/answerforce-logo.svg', __FILE__ ) ) . '">'; ?>
      <h3>Install your widget</h3>
      <h4>Select one of your active widgets to install it.</h4>
      <section class="wordpress-project-clips">
      </section>
  </section>
  <section  id ="single-widget-setup" class="widget-install display-none">
    <input type="hidden" name="projectId" value="<?php echo get_option('projectId')?>" id="projectId">
    <input type="hidden" name="widgetThemeColor" value="<?php echo get_option('widgetThemeColor')  ?>" id="widgetThemeColor">
    <input type="hidden" name="logoUrl" value="<?php echo get_option('logoUrl')  ?>" id="logoUrl">
    <input type="hidden" name="widgetName" value="<?php echo get_option('widgetName')  ?>" id="widgetName">
    <input type="hidden" name="widgetDomain" value="<?php echo get_option('widgetDomain')  ?>" id="widgetDomain">
    <?php echo '<img src="' . esc_url( plugins_url( '/images/answerforce-logo.svg', __FILE__ ) ) . '">';  ?>
    <h3>Widget Installed</h3>
    <h4>Your widget has been installed</h4>
  <section class="wordpress-project-clips">
    <aside>
    </aside>
  </section>
  <button id="preview-widget" onclick='window.open("<?php echo get_bloginfo('wpurl') ?>")'><span>Visit your site</span></button>
  <button class="btn-outline"  onclick='window.open("https://app.chatsupport.co/app/<?php echo substr(get_option('projectId'),3);  ?>/settings/manage/widget/yours")'>Customise widget</button>
</section>
  <!-- wordpress-loader -->
  <div class="wordpress-loader" style="display:none">
    <svg width="32px" height="34px" viewBox="0 0 32 34" version="1.1" xmlns="http://www.w3.org/2000/svg">
        <g stroke-linecap="round">
            <g transform="translate(-64.000000, -38.000000)">
                <path d="M80,71 L80,71 C82.1693626,71 84.2378875,70.5682622 86.124339,69.786022 C88.0799687,68.9750963 89.8399266,67.7874904 91.3137085,66.3137085 C92.7874904,64.8399266 93.9750963,63.0799687 94.786022,61.124339 M80,39 C75.581722,39 71.581722,40.790861 68.6862915,43.6862915 C67.2125096,45.1600734 66.0249037,46.9200313 65.213978,48.875661"></path>
            </g>
        </g>
    </svg>
  </div>
       <!-- bg -->
  <div class="bg" style="display:none;"></div>
  <div class="wordpress-bg" style="display:block"></div>
  <div class="wordpress-error" style="display: none;">
    <?php echo '<img src="' . esc_url( plugins_url( '/images/cloud.svg', __FILE__ ) ) . '">';  ?>
    <h2>Unknown error occured</h2>    
    <input type="button" id ="error-frame" value="Retry" style="width: 100px"/>
</div>
</main>
<?php submit_button(); ?>
</form>
<br/>
<?php  echo '<script id="ChatSupportScript" type="text/javascript" src="'. esc_url( plugins_url( '/script/chatSupportFormScript.js', __FILE__ ) ) . '"></script>';
}
} ?>
