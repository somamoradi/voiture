<?php 
    /*
    Plugin Name: Voitures
    Plugin URI: #
    Description: Voitures Marques
    Author: Soma Moradi
    Version: 1.0
    Author URI: #
    */

/**
* create and update db
**/
function installer(){
    include('installer.php');
}
register_activation_hook( __file__, 'installer' );

include 'functions.php';

function add_voiture_stylesheet() 
{

    // or
    //Register the script like this for a theme:
     wp_enqueue_script( 'jquery', get_template_directory_uri() . '/interface/javascripts/jquery.min.js', array(), false , true );
    // Register the script like this for a plugin:
    wp_enqueue_script( 'table', plugins_url( '/js/table.js', __FILE__ ), array(), false, true );
    // Register the script like this for a plugin:
    wp_enqueue_script( 'scripts', plugins_url( '/js/scripts.js', __FILE__ ), array( 'jquery'), false, true );
    wp_enqueue_style('table', plugins_url( '/css/table.css', __FILE__ ));
    wp_enqueue_style('style', plugins_url( '/css/style.css', __FILE__ ));
}
add_action('admin_print_styles', 'add_voiture_stylesheet');


if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}


add_action('admin_menu', function() {
    add_options_page( 'voiture Info', 'voiture Info Seetings', 'manage_options', 'voiture-info', 'voiture_info_plugin_page' );
});
 
add_action( 'admin_init', function() {
    register_setting( 'voiture-info-settings', 'isbn' );
});

function voiture_info_plugin_page() {
  ?>
    <div class="container">
      <div class="row">
          <h1><?php echo __('Voitures' , 'text_domain') ; ?></h1>
      </div>
      <div class="row">
          <?php
          global $wpdb;
          $table = $wpdb->prefix."voiture_info";
          $ids = $wpdb->get_results("SELECT * FROM $table"); ?>
          <table id="myTable">
              <?php
              if ($ids) { ?>
                  <thead>
                  <tr>
                    <td><?php echo __('Numero' , 'text_domain') ; ?></td>
                    <td><?php echo __('Nom' , 'text_domain') ; ?></td>
                    <td><?php echo __('Marques' , 'text_domain') ; ?></td>
                  </tr>
                  </thead>

                  <tfoot>
                  <tr>
                    <td><?php echo __('Numero' , 'text_domain') ; ?></td>
                    <td><?php echo __('Nom' , 'text_domain') ; ?></td>
                    <td><?php echo __('Marques' , 'text_domain') ; ?></td> 
                  </tr>
                  </tfoot>

                  <tbody>
                  <?php foreach ($ids as $id) { ?>
                      <tr>
                          <td ><?php echo $id->ID; ?></td>
                          <td ><?php echo $id->post_id; ?></td>
                          <td ><?php echo $id->isbn; ?></td>
                        
                      </tr>
                  <?php }; ?>
                  </tbody>
              <?php }; ?>
          </table>
      </div> <!-- .row -->
    </div> <!-- .container -->
  <?php
}
?> 