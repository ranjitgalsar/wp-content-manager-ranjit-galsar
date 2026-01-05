<?php

if(!defined('ABSPATH')){
    exit;
}

class WPCMRG {

    private static $initiated = false;
    private static $promo_blocks_enabled = false;
    public static function init() {
        if(!self::$initiated){
            self::$initiated = true;
            self::init_hooks();
        }
        self::$promo_blocks_enabled = get_option('promo_blocks_enabled', false);
    }

    public static function init_hooks() {
        self::create_promo_block();
        self::admin_settings();
        self::register_shortcode();
    }
    public static function hide_promo_blocks(){
    if(!self::$promo_blocks_enabled)
    {
        remove_menu_page( 'edit.php?post_type=promo_block' );   
    }
    }
    public static function add_meta_boxes() {
        self::create_promo_block_meta_box();
    }


    public static function plugin_activation() {
        // Action plugin activation
    }

    public static function plugin_deactivation() {
        // Action plugin deactivation
    }

    public static function create_promo_block() {
        register_post_type( 'promo_block',
            array(
                'labels' => array(
                    'name' => __( 'Promo Blocks',"wpcmrg" ),
                    'singular_name' => __( 'Promo Block',"wpcmrg" ),
                ),
                'public' => true,
                'has_archive' => true,
                'supports' => array( 'title', 'editor', 'thumbnail' ),
            )
        );
    }

    public static function create_promo_block_meta_box() {
        add_meta_box(
            'promo_block_meta_box',
            __( 'Meta Fields',"wpcmrg" ),
            array( 'WPCMRG', 'promo_block_meta_box_callback' ),
            'promo_block',
            'normal',
            'high'
        );
    }

    public static function promo_block_meta_box_callback( $post ) {
        wp_nonce_field( 'wpcmrg_save_meta_box_data', 'wpcmrg_meta_box_nonce' );
           
          $cta_text = get_post_meta($post->ID, 'cta_text', true);
          echo '<label class="wpcmrg-label" for="cta_text">' . __( 'CTA Text',"wpcmrg" ) . '</label>';
          echo '<input type="text" id="cta_text" name="cta_text" value="' . esc_attr($cta_text) . '" />';
          echo '<br>';
          echo '<br>';
          $cta_url = get_post_meta($post->ID, 'cta_url', true);
          echo '<label class="wpcmrg-label" for="cta_url">' . __( 'CTA URL',"wpcmrg" ) . '</label>';
          echo '<input type="text" id="cta_url" name="cta_url" value="' . esc_attr($cta_url) . '" />';
          echo '<br>';
          echo '<br>';
          $priority = get_post_meta($post->ID, 'priority', true);
          echo '<label class="wpcmrg-label" for="priority">' . __( 'Priority',"wpcmrg" ) . '</label>';  
          echo '<input type="number" id="priority" name="priority" value="' . esc_attr($priority) . '" />';

          $expire_date = get_post_meta($post->ID, 'expire_date', true);
          echo '<br>';
          echo '<br>';
          echo '<label class="wpcmrg-label" for="expire_date">' . __( 'Expire Date',"wpcmrg" ) . '</label>';
          echo '<input type="date" id="expire_date" name="expire_date" value="' . esc_attr($expire_date) . '" />';
    }
    public static function save_promo_block($post_id){
         if ( ! isset( $_POST['wpcmrg_meta_box_nonce'] ) || ! wp_verify_nonce( $_POST['wpcmrg_meta_box_nonce'], 'wpcmrg_save_meta_box_data' ) ) {
        return;
    }
        if(isset($_POST['cta_text'])){
            $cta_text = sanitize_text_field($_POST['cta_text']);
            update_post_meta($post_id, 'cta_text', $cta_text);
        }
        if(isset($_POST['cta_url'])){
            $cta_url = sanitize_url($_POST['cta_url']);
            update_post_meta($post_id, 'cta_url', $cta_url);
        }
        if(isset($_POST['priority'])){
            $priority = intval($_POST['priority']);
            update_post_meta($post_id, 'priority', $priority);
        }
        if(isset($_POST['expire_date'])){
            $expire_date = sanitize_text_field($_POST['expire_date']);
            update_post_meta($post_id, 'expire_date', $expire_date);
        }
    }
    public static function admin_styles() {
            echo '<style>
                .wpcmrg-label{
                    display:block;
                    margin-bottom:5px;
                }
                    .wpcmrg-form{
                    padding:30px;
    }
            </style>';
    }

    public static function admin_settings() {
        add_options_page(
            'Dynamic Content',
            'Dynamic Content',
            'manage_options',
            'dynamic_content_settings',
            array( 'WPCMRG', 'dynamic_content_settings_callback' )
        );
    }
    public static function dynamic_content_settings_callback() {
         if(isset($_POST["submit"])){
            $enabled = isset($_POST['promo_blocks_enabled']) ? true : false;
            update_option('promo_blocks_enabled', $enabled);
            if(isset($_POST['max_promo_blocks'])){
                update_option('max_promo_blocks', $_POST['max_promo_blocks']);
            }
        }
        $promo_blocks_enabled = get_option('promo_blocks_enabled', false);
        $checked = $promo_blocks_enabled ? 'checked' : '';
        echo '<form method="post" class="wpcmrg-form">';
        echo '<label class="wpcmrg-label" style="margin-right:10px;" for="promo_blocks_enabled">' . __( 'Enable Promo Blocks',"wpcmrg" ) . '</label>';
        echo '<input type="checkbox" id="promo_blocks_enabled" name="promo_blocks_enabled" ' . $checked . ' />';
        echo "</br>";
        echo "</br>";
        echo '<label class="wpcmrg-label" style="margin-right:10px;" >' . __( 'Maximum number of promo blocks to display',"wpcmrg" ) . '</label>';
        echo "<input type='number' id='max_promo_blocks' name='max_promo_blocks' value='" . esc_attr(get_option('max_promo_blocks', 3)) . "' />";
        echo "</br>";
        echo "</br>";
        echo '<label class="wpcmrg-label" style="margin-right:10px;" >' . __( 'Cache TTL (in minutes)',"wpcmrg" ) . '</label>';
        echo "<input type='number' id='cache_ttl' name='cache_ttl' value='" . esc_attr(get_option('cache_ttl', 60)) . "' />";
        echo "</br>";
        echo "</br>";
        echo '<input type="submit" name="submit" value="Save Changes" />';
        echo '</form>';
        echo "<button type='button' id='clear_cache'>Clear Cache</button>";
        echo "<script>
            document.getElementById('clear_cache').addEventListener('click', function(){
                if(confirm('Are you sure you want to clear the cache?')){
                    fetch('" . admin_url('admin-ajax.php') . "', {
                        method: 'POST',
                        body: new URLSearchParams({
                            'action': 'clear_promo_blocks_cache',
                            'nonce': '" . wp_create_nonce('clear_promo_blocks_cache') . "'
                        })
                    }).then(response => response.json()).then(data => {
                        if(data.success){
                            alert('Cache cleared successfully');
                        } else {
                            alert('Error clearing cache');
                        }
                    });
                }
            });
        </script>";
       
    }

    public static function register_shortcode(){
        add_shortcode('dynamic_promo', array('WPCMRG', 'promo_blocks_shortcode'));
    }
    public static function promo_blocks_shortcode(){
        $promo_blocks_enabled = get_option('promo_blocks_enabled', false);
        if(!$promo_blocks_enabled){
            return '';
        }
        $max_promo_blocks = get_option('max_promo_blocks', 3);
        $cache_ttl = get_option('cache_ttl', 60);
        $promo_blocks = self::get_promo_blocks($cache_ttl,$max_promo_blocks);
        ob_start();
        include plugin_dir_path(__FILE__) . 'views/shortcode.php';
        return ob_get_clean();
    }
    public static function get_promo_blocks($cache_ttl,$max_promo_blocks){
        $cache_key = 'promo_blocks_' . $cache_ttl."_".$max_promo_blocks;
        $promo_blocks = get_transient($cache_key);
        if(false === $promo_blocks){
            $today = date('Y-m-d');
            $promo_blocks = get_posts(array(
                'post_type' => 'promo_block',
                'posts_per_page' => $max_promo_blocks,
                'orderby' => 'meta_value_num',
                'meta_key' => 'priority',
                'order' => 'ASC',
                'meta_query' => array(
                    array(
                        'key' => 'expire_date',
                        'value' => $today,
                        'compare' => '>=',
                        'type' => 'DATE'
                    )
                )
            ));
            set_transient($cache_key, $promo_blocks, $cache_ttl * 60);
        }
        return $promo_blocks;
    }

    public static function enque_styles(){
        global $post;
    if ( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'dynamic_promo') ) {
        wp_enqueue_style('wpcmrg-style', plugin_dir_url(__FILE__) . 'frontend/style.css', array(), '1.0.0', 'all');
    }
    }
    public static function clear_promo_blocks_cache(){
        check_ajax_referer('clear_promo_blocks_cache', 'nonce');
        delete_transient('promo_blocks_'.get_option('cache_ttl', 60).'_'.get_option('max_promo_blocks', 3));
        wp_send_json_success(__("Cache cleared successfully","wpcmrg"));
        exit;
    }
    public static function register_rest_routes(){
        register_rest_route('dcm/v1', '/promos', array(
            'methods' => 'GET',
            'callback' => array('WPCMRG', 'get_promo_blocks_rest'),
            'permission_callback' => '__return_true',
        ));
    }
    public static function get_promo_blocks_rest($request){
        $cache_ttl = $request->get_param('cache_ttl');
        $max_promo_blocks = $request->get_param('max_promo_blocks');
        return self::get_promo_blocks($cache_ttl,$max_promo_blocks);
    }
}