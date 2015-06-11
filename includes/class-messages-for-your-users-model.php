<?php
class Messages_For_Your_Users_Model {

    private static $_instance = null;

    private $m4yuphp_vars; // variable to share dynamic data with plugin

    private function __construct() {
        $this->m4yuphp_vars = array();
    }
    private function  __clone() { }

    public static function getInstance() {
        if( !is_object(self::$_instance) )
            self::$_instance = new Messages_For_Your_Users_Model();
        return self::$_instance;
    }

    public function checkMessageForUser( $user_id ){

        if( ! current_user_can( 'subscriber' ) ){
            return false;
        }

        $messages_read = $this->getIdsMessagesReadByUser( $user_id );

        $args = array(
            'posts_per_page' => 1,
            'orderby'        => 'date',
            'order'          => 'DESC',
            'post_status'    => 'publish',
            'post_type'      => 'message4yuser',
            'post__not_in' => $messages_read
        );

        $messages = get_posts( $args );

        if( is_array( $messages ) && ! empty( $messages ) ){
            return reset($messages);
        }

        return false;

    }

    public function setMessageAsRead( $message_id, $user_id ) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'm4yu_read';
        return $wpdb->insert(
            $table_name,
            array(
                'read_post_id' => $message_id,
                'reading_user_id' => $user_id
            ),
            array(
                '%d',
                '%d'
            )
        );
    }

    public function getIdsMessagesReadByUser( $user_id ) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'm4yu_read';
        $sql = $wpdb->prepare(
            'select read_post_id as message_id from ' . $table_name . ' where reading_user_id = %d',
            $user_id );
        $res = array();
        $query_res = $wpdb->get_results( $sql );
        foreach( $query_res as $message ){
            $res[] = $message->message_id;
        }
        return $res;
    }

    public function setM4yuPhpVar( $var_name, $var_value ){
        $this->m4yuphp_vars[$var_name] = $var_value;
    }

    public function getM4yuPhpVar( $var_name = '' ){
        if( empty( $var_name ) ){
            return '';
        }

        $res = $this->m4yuphp_vars;

        $hunt = explode( '.', $var_name );

        foreach ( $hunt as $finder ) {
            if( isset( $res[$finder] ) ){
                $res = $res[$finder];
            }else{
                return '';
            }
        }

        return $res;

    }

} 