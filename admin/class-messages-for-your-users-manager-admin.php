<?php

class Messages_For_Your_Users_Manager_Admin {

    private $version;

    private $data_model;
    
    private $options;

    function __construct( $version, $options, $data_model )
    {

        $this->version = $version;

        $this->options = $options;

        $this->data_model = $data_model;
        
    }

    function init_db_schema() {
        global $wpdb;

        $table_name = $wpdb->prefix . 'm4yu_read';

        $charset_collate = '';

        if ( ! empty( $wpdb->charset ) ) {
            $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}";
        }

        if ( ! empty( $wpdb->collate ) ) {
            $charset_collate .= " COLLATE {$wpdb->collate}";
        }

        $sql = "CREATE TABLE $table_name (
          `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
          `read_post_id` bigint(20) unsigned NOT NULL,
          `reading_user_id` bigint(20) unsigned NOT NULL,
          `ts` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
          PRIMARY KEY (`id`),
          KEY `m4yu_read_post_id` (`read_post_id`),
          KEY `m4yu_reading_user_id` (`reading_user_id`),
          CONSTRAINT m4yu_unique UNIQUE (read_post_id,reading_user_id)
	    ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

        dbDelta( $sql );

        add_option( 'm4yu_db_version', $this->version );

    }

    function register_message_for_your_user_post_type() {

        $labels = array(
            'name'               => __( 'Messages for your Users', 'messages-for-your-users' ),
            'singular_name'      => __( 'Message', 'messages-for-your-users' ),
            'menu_name'          => __( 'Messages', 'messages-for-your-users' ),
            'name_admin_bar'     => __( 'Messages', 'messages-for-your-users' ),
            'add_new'            => __( 'Add New Message', 'messages-for-your-users' ),
            'add_new_item'       => __( 'Add New Message', 'messages-for-your-users' ),
            'new_item'           => __( 'New Message', 'messages-for-your-users' ),
            'edit_item'          => __( 'Edit Message', 'messages-for-your-users' ),
            'view_item'          => __( 'View Message', 'messages-for-your-users' ),
            'all_items'          => __( 'All Messages', 'messages-for-your-users' ),
            'search_items'       => __( 'Search Messages', 'messages-for-your-users' ),
            'parent_item_colon'  => __( 'Parent Messages:', 'messages-for-your-users' ),
            'not_found'          => __( 'No Messages found.', 'messages-for-your-users' ),
            'not_found_in_trash' => __( 'No Messages found in Trash.', 'messages-for-your-users' )
        );

        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array( 'slug' => 'messages-for-your-users' ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'map_meta_cap'       => true,
            'exclude_from_search'=> true,
            'menu_position'      => null,
            'supports'           => array( 'title', 'editor', 'author', )
        );

        register_post_type( 'message4yuser', $args );

    }


    function load_textdomain() {

        load_plugin_textdomain( 'messages-for-your-users', false, dirname( dirname( plugin_basename( __FILE__ ) ) )  . '/langs/' );

    }

    function ajax_set_message_as_read()
    {

        if (!isset($_POST['user_id']) || !isset($_POST['message_id'])) {
            die('0');
        }

        $this->data_model->setMessageAsRead($_POST['message_id'], $_POST['user_id']);

        die('1');

    }

}