<?php

class Messages_For_Your_Users_Manager_Public {

    private $version;

    private $data_model;

    private $options;

    private $js_configuration;

    function __construct( $version, $options, $data_model )
    {

        $this->version = $version;

        $this->options = $options;

        $this->data_model = $data_model;

        $this->js_configuration = array();

        if(WP_DEBUG == false) {
            $this->js_configuration['js_path'] = 'js/prod/';
            $this->js_configuration['js_extension'] = $this->version . '.min.js';
        }else{
            $this->js_configuration['js_path'] = 'js/';
            $this->js_configuration['js_extension'] = 'js';
        }

    }

    public function register_scripts() {
        wp_register_script('m4yu-js', plugins_url($this->js_configuration['js_path'] . 'messages-for-your-users.' . $this->js_configuration['js_extension'], __FILE__), array( 'jquery' ) );
        wp_localize_script( 'm4yu-js', 'm4yu', array(
            'url' => admin_url() . 'admin-ajax.php',
        ) );
    }

    public function enqueue_scripts($hook) {
        wp_enqueue_script('m4yu-js');
    }

    function print_message_to_the_user() {

        $message_for_user = $this->data_model->checkMessageForUser( get_current_user_id() );

        if( $message_for_user ){

            global $post;

            $post = $message_for_user;

            setup_postdata($post);

            $output = file_get_contents( $this->get_template_message() );

            $output = str_replace( '#m4yu_message_id#', $post->ID, $output );

            $output = str_replace( '#m4yu_user_id#', get_current_user_id(), $output );

            $output = str_replace( '#m4yu_title#', $post->post_title, $output );

            $message_content = apply_filters( 'the_content', $post->post_content );

            $output = str_replace( '#m4yu_content#', $message_content, $output );

            wp_reset_postdata();

            echo $output;

        }

    }

    function get_template_message(){

        //default file provide with plugin
        $file_path_default = dirname(__FILE__) . '/partials/to-user-modal-message.php';

        $custom_template_folder = get_template_directory();

        // build an array of templates so in the future we can foresee multiple way/rules to create a custom template
        $check_templates = array();

        $check_templates[] = $custom_template_folder . '/' . 'm4yu_message.php';

        foreach( $check_templates as $file_path ){

            if( file_exists( $file_path ) ){

                return $file_path;

            }

        }

        return $file_path_default;

    }

    function create_shortcode_m4yuphp( $atts ){

        add_shortcode( 'm4yuphp', array( $this, 'render_m4yuphp_shortcode') );

    }

    public function render_m4yuphp_shortcode( $atts ){

        if( ! isset( $atts['var'] ) ) {
            return '';
        }

        return $this->data_model->getM4yuPhpVar( $atts['var'] );

    }


}