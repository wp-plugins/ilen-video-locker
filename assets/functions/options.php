<?php
/**
 * Options Plugin
 * Make configutarion
*/

if ( !class_exists('ilen_video_lock_make') ) {


require_once IVL_PATH.'assets/ilenframework/assets/lib/utils.php';


class ilen_video_lock_make{
    
    var $components = array();

    function __construct(){

        if( is_admin() ){
            self::configuration_plugin();
        }else{
            self::parameters();
        }

    }


    function getHeaderPlugin(){

        $url_plugin = plugins_url();





        return array('id'             =>'ilen_video_lock_id',
                     'id_menu'        =>'ilen-video-locker',
                     'name'           =>'iLen Video Locker',
                     'name_long'      =>'iLen Video Locker',
                     'name_option'    =>'ilen_video_lock',
                     'name_plugin_url'=>'ilen-video-locker',
                     'descripcion'    =>'Share your viral videos and get traffic to your website.',
                     'version'        =>'2.8',
                     'db_version'     =>'1.0',
                     'url'            =>'', 
                     'logo'           =>'<i class="fa fa fa-play" style="padding:10px 10px 9px 15px"></i>',
                     'logo_text'      =>'', // alt of image
                     'slogan'         =>'', // powered by <a href="">iLenTheme</a>
                     'url_framework'  => "$url_plugin/ilen-video-locker/assets/ilenframework",
                     'theme_imagen'   => "$url_plugin/ilen-video-locker/assets/images",
                     'languages'      => "$url_plugin/ilen-video-locker/assets/languages",
                     'twitter'        => '',
                     'wp_review'      => 'https://wordpress.org/support/view/plugin-reviews/ilen-video-locker?filter=5',
                     'wp_support'     => 'http://support.ilentheme.com/forums/forum/plugins/ilen-video-locker/',
                     'link_donate'    => 'https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=ASU4AAQM8N5NL',
                     'type'           =>'plugin-tabs',
                     'method'         =>'free',
                     'themeadmin'     =>'fresh',
                     'scripts_admin'  =>array( 'page' => array('ilen-video-locker' => array('jquery_ui_reset','bootstrap','bootstrap_datetimepicker','flags')), ));
    }


    function getOptionsPlugin(){

    global ${'tabs_plugin_' . $this->parameter['name_option']};
    $url = admin_url('options-general.php?page='.$this->parameter['id_menu']); 
    ${'tabs_plugin_' . $this->parameter['name_option']} = array();
    ${'tabs_plugin_' . $this->parameter['name_option']}['tab01']=array('id'=>'general','name'=>'General','icon'=>'','link'=>$url,'columns'=>2,'sidebar-file'=> plugin_dir_path( __FILE__ ).'/sidebar-general.php','width'=>'200' );
    //${'tabs_plugin_' . $this->parameter['name_option']}['tab02']=array('id'=>'test','name'=>'Test','icon'=>'','link'=>$url,'columns'=>1,'width'=>'200'  );

    return array('a'=>array(                'title'      => __('Basic',$this->parameter['name_option']), 
                                            'title_large'=> __('',$this->parameter['name_option']), 
                                            'description'=> '',  
                                            'icon'       => '',
                                            'tab'        => 'general',
                                            'default'    => 1,
                                            

                                            'options'    => array( 
                                                                array(  'title' =>__('Width',$this->parameter['name_option']),
                                                                        'help'  =>__('Video Width in px',$this->parameter['name_option']),
                                                                        'type'  =>'text',
                                                                        'value' =>'480',
                                                                        'id'    =>$this->parameter['name_option']. '_' . 'video_width',
                                                                        'name'  =>$this->parameter['name_option']. '_' . 'video_width',
                                                                        'class' =>'',
                                                                        'row'   =>array('a','b')),

                                                                array(  'title' =>__('Height',$this->parameter['name_option']),
                                                                        'help'  =>__('Video Height in px',$this->parameter['name_option']),
                                                                        'type'  =>'text',
                                                                        'value' =>'350',
                                                                        'id'    =>$this->parameter['name_option']. '_' . 'video_height',
                                                                        'name'  =>$this->parameter['name_option']. '_' . 'video_height',
                                                                        'class' =>'',
                                                                        'row'   =>array('a','b')),

                                                                array(  'title' =>__('Video text',$this->parameter['name_option']),
                                                                        'help'  =>__('This field will be the text that will show people to share the video',$this->parameter['name_option']),
                                                                        'type'  =>'text',
                                                                        'value' =>'<h3>Share with your friends to unlock the video</h3>',
                                                                        'id'    =>$this->parameter['name_option']. '_' . 'video_text',
                                                                        'name'  =>$this->parameter['name_option']. '_' . 'video_text',
                                                                        'class' =>'',
                                                                        'row'   =>array('a','b')),


                                                                array(  'title' =>__('Use thumbnail Youtube?',$this->parameter['name_option']),
                                                                        'help'  =>__('if you enable this option you will use only the video thumbnail, otherwise you will use the image of the Post',$this->parameter['name_option']),
                                                                        'type'  =>'checkbox',
                                                                        'value' =>'1',
                                                                        'value_check'=>1,
                                                                        'id'    =>$this->parameter['name_option']. '_' . 'video_thumbnail',
                                                                        'name'  =>$this->parameter['name_option']. '_' . 'video_thumbnail',
                                                                        'class' =>'',  
                                                                        'row'   =>array('a','b')),
 
 
                                                            ),
                ),

                'last_update'=>time()

            );
        
    }













    /* NO REMOVE */

    function parameters(){
 
        $this->parameter = self::getHeaderPlugin();
    }

    function myoptions_build(){

        $this->options = self::getOptionsPlugin();

        return $this->options;
        
    }

    function use_components(){
        //code 
        $this->components = array();
        //$this->components = array('bootstrap','flags','');

    }

    function configuration_plugin(){
        // set parameter 
        self::parameters();

        // my configuration 
        self::myoptions_build();

        // my component to use
        self::use_components();
    }
    /* !-- NO REMOVE */

}
}


?>