<?php
/*
Plugin Name: iLen Video Locker
Plugin URI: https://wordpress.org/plugins/ilen-video-locker/
Description: Share your viral videos and get traffic to your website.
Version: 1.5
Author: iLen
Author URI:
*/


if ( !class_exists('ilen_video_lock') ) {

define('IVL_PATH',plugin_dir_path( __FILE__ ));

require_once 'assets/functions/options.php';
require_once "assets/ilenframework/assets/lib/Mobile_Detect.php";

class ilen_video_lock extends ilen_video_lock_make{

    var $mobil_detect = null;
    function __construct(){

        // get utils framework:IF_get_option
        require_once 'assets/ilenframework/assets/lib/utils.php';

        // ajax nonce for stats
        add_action( 'wp_ajax_nopriv_ajax-video', array( &$this, 'my_shared_video' ) );
        add_action( 'wp_ajax_ajax-video', array( &$this, 'my_shared_video' ) );

        parent::__construct(); 

        if( is_admin() ){

            // add support feature image
            add_theme_support( 'post-thumbnails' );

            // set styles and script back-end
            add_action( 'admin_enqueue_scripts', array( &$this,'script_and_style_admin' ) );

            // when active plugin verify db
            register_activation_hook( __FILE__, array( &$this,'ilenvideolock_install' ) );

            // add button 'video lock' to editor
            add_action('init', array( &$this,'add_button_dice') );

            // validate error (only developer)
            /*add_action('activated_plugin',array( &$this,'save_error'));
            echo get_option('plugin_error');*/

        }elseif( ! is_admin() ) {

            global $option_ilenvideolock;

            // mobil detect
            $this->mobil_detect = new Mobile_Detect;

            // create shortcode
            add_shortcode('ilenvideolock', array( &$this,'show_ilenvideolock') );

            // get option plugin
            $option_ilenvideolock = IF_get_option( $this->parameter['name_option'] );

            // add filter on hook wp_head
            add_filter('wp_head', array( &$this,'add_fb_meta_image') );

            // set styles and script front-end
            add_action( 'wp_enqueue_scripts', array( &$this,'script_and_style_front' ) );

        }

    }


    /*function save_error(){
        //update_option('plugin_error',  ob_get_contents());
        //update_option('plugin_error',  '');
    }*/


    /* FUNCTION AJAX */
    function my_shared_video() {
        

        //if ( ! wp_verify_nonce( $_REQUEST['nonce'], 'shared-videos-nonce' ) ) {
            //die( 'Security check' ); 
        //}

        require_once("assets/ilenframework/assets/lib/geo.php");
       
        global $IF_MyGEO,$wpdb;
        global $wpdb;

        $table_name = $wpdb->prefix . 'ilenvideolock';
        $post_id    = $_REQUEST["post_id"];
        $social     = $_REQUEST["social"];
        $video_id   = $_REQUEST["video_id"];
        $type_video = $_REQUEST["type_video"];

        $la = $IF_MyGEO::$latitude;
        $lo = $IF_MyGEO::$longitude;
        $cc = $IF_MyGEO::$countryCode;
        $cn = $IF_MyGEO::$countryName;
        $re = $IF_MyGEO::$region;
        $ci = $IF_MyGEO::$city;
        $ip = $IF_MyGEO::$ip;

        $wpdb->insert(
            $table_name, 
            array(
                'creation'     => current_time('timestamp'), 
                'creation2'    => current_time('mysql'), 
                'post_id'      => $post_id, 
                'la'           => "$la", 
                'lo'           => "$lo", 
                'country_code' => "$cc", 
                'country'      => "$cn", 
                'region'       => "$re", 
                'city'         => "$ci", 
                'ip'           => "$ip",
                'type_social'  => "$social",
                'id_video'     => "$video_id",
                'type_video'   => "$type_video"
            )
        );

        // send some information back to the javascipt handler
        header( "Content-Type: application/json" );
        echo json_encode( array(
          'success' => 'ok '.$table_name,
          'times'   => time()
        ) );
        exit;

    }


    function script_and_style_front(){

        global $option_ilenvideolock;

        wp_enqueue_script('facebook-js', 'http://connect.facebook.net/en_US/all.js#xfbml=1', array('jquery'),$this->parameter['version'],FALSE);
        

        wp_enqueue_style( 'front-'.$this->parameter["name_option"], plugins_url('/assets/css/front.css',__FILE__),'all',$this->parameter['version']);
        wp_enqueue_script( 'front-js-'.$this->parameter["name_option"], plugins_url('/assets/js/front.js',__FILE__), array( 'jquery' ), $this->parameter['version'], true );

        /* AJAX */
        wp_enqueue_script( 'ajax-video', plugin_dir_url( __FILE__ ) . 'assets/js/ajax.js', array( 'jquery' ) );
        wp_localize_script( 'ajax-video', 'AjaxVideo', array(
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'nonce' => wp_create_nonce( 'ajax-example-nonce' )
        ) );

    }



    function script_and_style_admin(){

        wp_enqueue_style( 'admin-'.$this->parameter["name_option"], plugins_url('/assets/css/admin.css',__FILE__),'all',$this->parameter['version']);
        wp_enqueue_script('admin-js-'.$this->parameter["name_option"], plugins_url('/assets/js/admin.js',__FILE__), array( 'jquery' ), $this->parameter['version'], true );

    }
    


    function show_ilenvideolock( $atts, $content = null ){

        if ( !is_singular() )
                return;
        
        global $post, $option_ilenvideolock;

        $type_video = "";

        if( isset($option_ilenvideolock->video_thumbnail) && $option_ilenvideolock->video_thumbnail  ){

            
            $image_share = IF_getyoutubeThumbnail( $id_video = IF_getyoutubeID( $content ) ) ;    
            
            
        }else{

            $image_share = IF_get_image( "medium" );
            $id_video    = $content;
            $image_share = $image_share["src"];

        }

        // hash Youtube for identify each video
        $video_id_hash = sha1( $id_video );
        
        // url
        $url_path = plugins_url().'/'.$this->parameter["name_plugin_url"]; //esc_url( home_url( '/' ) ).'wp-content/plugins/'.$this->parameter["name_plugin_url"];
        $url_post = get_permalink();

        $_html = "";
        $_html = "<div class='ilenvideolock $responsive ilenvideolock_id_$video_id_hash type_$type_video' style='width:".($option_ilenvideolock->video_width)."px;height:".($option_ilenvideolock->video_height)."px;background-image:url($image_share);background-repeat:no-repeat;background-size:cover;background-color: #000;background-position:center;' data-image='$image_share' data-id='$id_video' data-width='$option_ilenvideolock->video_width' data-height='$option_ilenvideolock->video_height' data-version='".$this->parameter["version"]."'>";
        $_html .= "<img src='$image_share' class='ilenvideolock_img ilenvideolock_img_$video_id_hash' />";
        $_html .= "<div class='ilenboxshare'>";
        $_html .= "<div class='overlay'></div>";
        $_html .= "<div class='boxxshare'>
                        <div class='msg'><div>".html_entity_decode( $option_ilenvideolock->video_text )."</div>";
                $_html .= "<div class='boxxshare_buttons'>";

                        // SET BUTTON FACBOOK
                        $_html .="<div class='facebook-ilenvideolock-id-$video_id_hash'>";
                        $_html .="<a href='#' onclick=\"ilenvideolock_fb(".$post->ID.",'".$url_path."','$video_id_hash', '')\"> ";
                            $_html .="<img src='{$this->parameter["theme_imagen"]}/button-shared.jpg' width='290' style='margin-bottom: 5px;' />";
                        $_html .="</a>";
                        $_html .= "</div>";
                        
            $_html .="     </div>
                        </div>
                   </div>
                   </div>";

        
            $_html .= "<div class='class-play-button' style='width:".($option_ilenvideolock->video_width)."px;height:".($option_ilenvideolock->video_height - 35)."px;position:relative;'>";
                $_html .= "<div class='play-button'></div>";
            $_html .= "</div>";
            $_html .= "<div class='controlls' style='width:".($option_ilenvideolock->video_width)."px;position:relative;'>";
                $_html .= "<div class='left-controlls' ></div>";
                $_html .= "<div class='right-controlls' ></div>";
            $_html .= "</div>";
        
        $_html .= "</div>";
 
        return $_html;
        

    }


    // Add items to the header!
    function add_fb_meta_image() {
        if(  is_singular() ){

            // get shortcode 'ilenvideolock' on content post
            // @see http://codex.wordpress.org/Function_Reference/get_shortcode_regex
            global $post;
            $pattern = get_shortcode_regex();
            if (   preg_match_all( '/'. $pattern .'/s', $post->post_content, $matches )
                && array_key_exists( 2, $matches )
                && in_array( 'ilenvideolock', $matches[2] ) )
            {

                if( isset($matches[5][0]) )
                    $url_youtube = $matches[5][0];

                    $image_share =   IF_get_image( "medium",  IF_getyoutubeThumbnail( IF_getyoutubeID( $url_youtube ) ) );
                    $image_share = $image_share["src"];
                    echo '<meta property="og:image" content="'.$image_share.'"/>';    
            }
 
        }
        
    }


    



    // add button in the editor [shortcode (Dice)]
    /**
    * @link http://www.wpexplorer.com/wordpress-tinymce-tweaks/
    */
    function add_button_dice() {

        if ( current_user_can('edit_posts') &&  current_user_can('edit_pages') )  
        {
          add_filter('mce_external_plugins', array( &$this,'add_plugin_dice') );  
          add_filter('mce_buttons', array( &$this,'register_button_dice') );  
        }
    }  
    function register_button_dice($buttons) {  
       array_push($buttons, "ilenvideolock");  
       return $buttons;  
    }  
    function add_plugin_dice($plugin_array) {  
       $plugin_array['ilenvideolock'] = plugins_url()."/ilen-video-locker/assets/js/button.js";
       return $plugin_array;  
    }

 
    /**
    * @see http://codex.wordpress.org/Creating_Tables_with_Plugins
    */
    function ilenvideolock_install(){

        global $wpdb;
        global $ivl_db_version;

        $ivl_db_version =  $this->parameter["db_version"];
        $table_name = $wpdb->prefix . 'ilenvideolock';

        $installed_ver = get_option( $this->parameter["name_option"].'_db_version' );

        if ( $installed_ver != $ivl_db_version ) {

            /*
             * We'll set the default character set and collation for this table.
             * If we don't do this, some characters could end up being converted 
             * to just ?'s when saved in our table.
             */

            $charset_collate = '';

            if ( ! empty( $wpdb->charset ) ) {
              $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}";
            }

            if ( ! empty( $wpdb->collate ) ) {
              $charset_collate .= " COLLATE {$wpdb->collate}";
            }

            $sql = "CREATE TABLE $table_name (

                    id mediumint(9) NOT NULL AUTO_INCREMENT,
                    creation int(11) NOT NULL,
                    creation2 datetime NOT NULL,
                    post_id int(11) NOT NULL,
                    la varchar(50) NULL,
                    lo varchar(50) NULL,
                    country_code varchar(50) NULL,
                    country varchar(100) NULL,
                    region  varchar(100) NULL,
                    city  varchar(100) NULL,
                    ip  varchar(50) NULL,
                    id_video varchar(250) NULL,
                    type_video VARCHAR(25) DEFAULT 'youtube' NOT NULL,
                    type_social VARCHAR(40) DEFAULT 'facebook' NOT NULL,

                UNIQUE KEY id (id)
            ) $charset_collate;";
            
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta( $sql );

            if( ! $installed_ver ){
                add_option( $this->parameter["name_option"].'_db_version', $ivl_db_version );
            }else{
                update_option( $this->parameter["name_option"].'_db_version', $ivl_db_version );
            }
        }


    }



} // end class
} // end if

global $IF_CONFIG;
unset($IF_CONFIG);
$IF_CONFIG = null;
$IF_CONFIG = new ilen_video_lock;

require_once "assets/ilenframework/core.php";
?>