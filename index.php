<?php
/*
Plugin Name: Talk For WEB Review Plugin
Description: Fetch all Google Reviews using cookie
Author: Vinay
Version: 1.0
Tags: Google, reviews, Google Places reviews
Author URI: https://www.talkforweb.com.au/
*/
/*  Copyright 2009-2020   (email : info@talkforweb.com.au)
*/

add_action('admin_menu', 'talforweb_review_link');

function talforweb_review_link()
{

    //create new top-level menu
    add_menu_page('All Review', 'All Review', 'administrator', __FILE__, 'talforweb_review');

    //call register settings function
    add_action('admin_init', 'talforweb_variable_setting');
}

function talforweb_variable_setting()
{
    //register our settings
    register_setting('talforweb-review-settings-group', 'talforweb_place_id');
}

function talforweb_review()
{
    include ('review.php');
}

//create table
function talforweb_create_database_table()
{
    global $wpdb;
    $table = $wpdb->prefix . 'talforweb_review';

    $sql = "CREATE TABLE IF NOT EXISTS " . $table . " ( ";
    $sql .= " `id` int(11) NOT NULL auto_increment,
              `author_name` varchar(255) NOT NULL,
              `author_url` text NOT NULL,
              `profile_photo_url` text NOT NULL,
              `rating` int(1) NOT NULL,
              `relative_time_description` varchar(100) NOT NULL,
              `text` text NOT NULL,
              `time` int(11) NOT NULL,
              `added_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
              `user_id` varchar(100) NOT NULL, ";
    $sql .= "  PRIMARY KEY `id` (`id`) ";
    $sql .= ") ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ; ";
    require_once (ABSPATH . '/wp-admin/includes/upgrade.php');
    dbDelta($sql);
    //add example value
    add_option('talforweb_place_id', '!1m2!1y7715437134510776335!2y10307002471025652395!2m2!2i28!2i10!3e1!4m5!3b1!4b1!5b1!6b1!7b1!5m2!1srnuUXvOCEY7H4-EPpPuuUA!7e81');
    include('function.php');
    talkforweb_add_review();
}

register_activation_hook(__FILE__, 'talforweb_create_database_table');



add_action( 'wp_enqueue_scripts', 'talforweb_scripts' );
function talforweb_scripts(){
     wp_enqueue_style( 'TFW-style', plugins_url( 'css/style.css', __FILE__ ), array(), '1.0.0' );
     wp_enqueue_script( 'jquery' );
     wp_enqueue_script( 'TFW-review-JS', plugins_url( 'js/js.js', __FILE__ ), array(), '1.0.0' );
}

add_action('wp_footer', 'talforweb_footer_code');
function talforweb_footer_code()
{  
    global $wpdb;
    $table = $wpdb->prefix . 'talforweb_review';
    $all_review = $wpdb->get_results("SELECT * FROM $table ORDER BY id DESC LIMIT 20");

?>
  <div class="tfw_container">
    <div class="tfw_logoimg"><img src="<?php echo plugin_dir_url(__FILE__); ?>images/review.png" /></div>
      <?php
    foreach ($all_review as $value)
    { ?>
    <div class="ngrcard"> 
          <div class="authorimg"><img  src="<?php echo $value->profile_photo_url; ?>" /></div>
          <div class="groupc">
            <span class="name"><?php echo $value->author_name; ?></span>
            <span class="rating"><?php for ($i = 1;$i <= $value->rating;$i++) echo '<img src="' . plugin_dir_url(__FILE__) . 'images/star.png" />'; ?> <?php echo $value->relative_time_description; ?></span>
            <span class="text"><?php echo ($value->text != 'N/A') ? $value->text : ''; ?></span>
        </div>    
      </div>    
    <?php
    } ?>
  </div>
<?php


} ?>