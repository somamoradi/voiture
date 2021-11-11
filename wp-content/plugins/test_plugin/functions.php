<?php 
/**
* Create Custom Post type : Voitures
**/
function create_voitures()
{
   $labels = array(
    'name'               => _x( 'voitures', 'post type general name', 'text_domain' ),
    'singular_name'      => _x( 'voiture', 'post type singular name', 'text_domain' ),
    'menu_name'          => _x( 'voitures', 'admin menu', 'text_domain' ),
    'name_admin_bar'     => _x( 'voitures', 'add new on admin bar', 'text_domain' ),
    'add_new'            => _x( 'Add New', 'voitures', 'text_domain' ),
    'add_new_item'       => __( 'Add New voitures', 'text_domain' ),
    'new_item'           => __( 'New voitures', 'text_domain' ),
    'edit_item'          => __( 'Edit voitures', 'text_domain' ),
    'view_item'          => __( 'View voitures', 'text_domain' ),
    'all_items'          => __( 'All voitures', 'text_domain' ),
    'search_items'       => __( 'Search voitures', 'text_domain' ),
    'not_found'          => __( 'No voitures found.', 'text_domain' ),
    'not_found_in_trash' => __( 'No voitures found in Trash.', 'text_domain' )
    );
   $supports = array(
        'title',
        'editor',
        'thumbnail',
        'comments',
        'revisions',
    );

       $args = array(
        'labels'             => $labels,
        'supports'             => $supports,
        'capability_type'      => 'post',
        'description'        => __( 'Description.', 'Add New voitures' ),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'voitures' ),
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 3,
        'menu_icon'          =>'dashicons-car',
        'taxonomies'         => array('marque'),

    );
        register_post_type( 'voitures', $args );
     }
     add_action( 'init', 'create_voitures' );




    function save_voitures_custom_fields(){
         global $post;

         if ( $post )
         {
           update_post_meta($post->ID, "short_description", @$_POST["short_description"]);
           update_post_meta($post->ID, "price", @$_POST["price"]);
           update_post_meta($post->ID, "length", @$_POST["length"]);
           update_post_meta($post->ID,'voitures_ship_lead_days',@$_POST['ship_lead_days']);
           update_post_meta($post->ID,'commision_broker',@$_POST['commision_broker']);
          }
        }
    add_action( 'save_post', 'save_voitures_custom_fields' );

    // add marque taxonomy
    add_action( 'init', 'marque', 1 );
    function marque() {
    $labels = array(
        'name'              => _x( 'marque', 'taxonomy general name' ),
        'singular_name'     => _x( 'marque', 'taxonomy singular name' ),
        'search_items'      => __( 'Search marque' ),
        'all_items'         => __( 'All marque' ),
        'parent_item'       => __( 'Parent marque' ),
        'parent_item_colon' => __( 'Parent marque:' ),
        'edit_item'         => __( 'Edit marque' ),
        'update_item'       => __( 'Update marque' ),
        'add_new_item'      => __( 'Add New marque' ),
        'new_item_name'     => __( 'New marque Name' ),
        'menu_name'         => __( 'marque' ),
      );

       $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'rewrite'           => array( 'slug' => 'marque' ),
    );
    register_taxonomy( 'marque', array( 'voitures' ), $args );
    } 


/**
 * Save the metabox data
 */
function wpt_save_voitures_meta( $post_id, $post ) {
    // Return if the user doesn't have edit permissions.
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return $post_id;
    }

    // Verify this came from the our screen and with proper authorization,
    // because save_post can be triggered at other times.
    if ( ! isset( $_POST['isbn'] ) || ! wp_verify_nonce( $_POST['book_fields'], basename(__FILE__) ) ) {
        return $post_id;
    }
    // Now that we're authenticated, time to save the data.
    // This sanitizes the data from the field and saves it into an array $voitures_meta.
    $voitures_meta['isbn'] = esc_textarea( $_POST['isbn'] );
    // Cycle through the $voitures_meta array.
    // Note, in this example we just have one item, but this is helpful if you have multiple.
    foreach ( $voitures_meta as $key => $value ) :
        // Don't store custom data twice
        if ( 'revision' === $post->post_type ) {
            return;
        }
        if ( get_post_meta( $post_id, $key, false ) ) {
            // If the custom field already has a value, update it.

            update_post_meta( $post_id, $key, $value );

        } else {
            // If the custom field doesn't have a value, add it.
            add_post_meta( $post_id, $key, $value);

        }
        if ( ! $value ) {
            // Delete the meta key if there's no value
            delete_post_meta( $post_id, $key );
        }
    endforeach;

 
    global $wpdb;
    // starts output buffering
    ob_start();
    if ( isset( $_POST['isbn'] ) ){
        $table = $wpdb->prefix."voitures_info";
        $post_id = get_the_ID();
        $isbn = strip_tags($_POST["isbn"], "");


        // search query from db
        $exist_row = $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT post_id FROM " . $wpdb->prefix . "voitures_info
                    WHERE post_id = %d",
                    $post_id
                )
            );
            // update db if record exist
            if ( $exist_row == $post_id ) {
               $wpdb->update( 
                    $table, 
                    array( 
                        'post_id' => $post_id,
                        'isbn' => $isbn
                    ),
                    array( 'post_id' => $post_id )
                );
            // insert into db if record not exist
            }else{
              $wpdb->insert( 
                    $table, 
                    array( 
                        'post_id' => $post_id,
                        'isbn' => $isbn
                    )
                );  
            } 
    }; 
}
add_action( 'save_post', 'wpt_save_voitures_meta', 1, 2 );
?>