<?php

class Messages_For_Your_Users_Theme_Functions {

    function __construct() { }

    public static function  define_theme_functions() {

	   if( ! function_exists( 'setM4yuPhpVar' ) ) {
            function setM4yuPhpVar( $var_name, $var_value ) {
                $data_model = Messages_For_Your_Users_Model::getInstance();
                return $data_model->setM4yuPhpVar( $var_name, $var_value );
            }
        }

    }
} 