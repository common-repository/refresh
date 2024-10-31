<?php

/*
Plugin Name: REFRESH
Description: Transform your wordpress login
Version: 1.0.1
Author: Antoine Lechenault
Author URI: https://profiles.wordpress.org/antoinelechenault/
*/

add_action('plugins_loaded', 'refresh_init');

// ------------------------- End of Wordpress Init ------------------------- //


/**
 * Init Api
 */

function refresh_init()
{
  $rf_api = new REFRESH_API();
  $rf_api->transform_admin();
  $rf_api->transform_login();
}

class REFRESH_API
{

    const MAX_PER_PAGE = 10;
    const DEFAULT_SORT = "date";
    const DEFAULT_ORDER = "DESC";

    public function __construct()
    {

    }

    /*
      Login Part
    */
    public function transform_login()
    {
      add_action('login_enqueue_scripts', [$this, 'update_login_style']);
      add_filter('login_headerurl', [$this, 'update_login_url'] );
      add_filter('login_headertitle', [$this, 'update_login_title'] );
      add_filter( 'login_message', [$this, 'add_custom_login_image']);

    }

    public function add_custom_login_image()
    {
      $my_saved_attachment_post_id = wp_get_attachment_url( get_option( 'refresh_attachment_id' ) );
      if(!empty($my_saved_attachment_post_id))
      {
          $content = "<span id='custom_login' data-img='{$my_saved_attachment_post_id}'></span>";
      }
      $content .= "<span id='custom_login_bg' data-img='".plugin_dir_url( __FILE__ )."/assets/img/login_bg.jpg'></span>";
      return $content;
    }

    public function update_login_style()
    {
      wp_dequeue_style( 'login' );
      wp_enqueue_style(  'refresh-login', plugin_dir_url( __FILE__ ) . '/dist/css/login.min.css' );
      wp_enqueue_script( 'refresh-login', plugin_dir_url( __FILE__ ) . '/dist/js/login.min.js' );
    }

    public function update_login_url()
    {
      return get_bloginfo( 'url' );
    }

    public function update_login_title()
    {
      return 'Hey Buddy!';
    }


    /*
      Admin Part
    */
    public function transform_admin()
    {
      add_action('admin_menu', [$this, 'refresh_menu'] );
    }

    public function refresh_menu()
    {
      if(function_exists('add_menu_page'))
      {
        $page = add_menu_page('Professional Login Settings',
                              'Login Settings',
                              'manage_options',
                              'refresh-login-settings',
                              [$this, 'refresh_admin_page'],
                              'dashicons-heart',
                              200);
      }
    }

    public function refresh_admin_page()
    {
      if (current_user_can('administrator'))
      {

        wp_enqueue_media();
        $nonce_field = basename(__FILE__);

        if ( isset( $_POST['submit_image_selector'] ) && isset( $_POST['image_attachment_id'] ) )
        {
          if ( !check_admin_referer( $nonce_field, 'update_refresh_attachment_verify' ) )
          {

             echo "Sorry, you didn't verify.";
             exit;

          } else {
            update_option( 'refresh_attachment_id', absint( $_POST['image_attachment_id'] ) );
          }
        }

        $my_saved_attachment_post_id = get_option( 'refresh_attachment_id', 0 );

        include_once __DIR__ . '/template/admin.php';

      }
    }

}
