<?php
/*
Plugin Name: Arc Slider
Plugin URI: 
Description: Responsive Slider, 
Version: 1.0.0
Author: ismaildev
Author URI: http://www.ismailhossaindev.Com/
Text Domain: arc_slider
Domain Path: /lang
*/

class  ArcSlider{

    public function __construct() 
    {
        add_action('admin_menu', array($this, 'arc_slider_menu'));
        add_action('init', array($this, 'arc_slider_setup'));
        add_action('add_meta_boxes', array($this, 'all_meta_boxes'));
        add_action('admin_enqueue_scripts', array($this, 'wp_admin_styles_scripts'));
        add_action('save_post', array($this, 'arc_slider_metabox_data_save'));
        add_shortcode("arc_slider", array($this, "arc_slider_retrive"));
    }

    function wp_admin_styles_scripts()
    {
        wp_enqueue_style( 'wp-color-picker');
        //
        wp_enqueue_script( 'wp-color-picker');
    }
    public function arc_slider_menu()
    {
        add_menu_page(
            'Arc Slider',
            'Arc Slider',
            'read',
            'arc-slider',
            '',
            'dashicons-feedback',
            40 // Position
        );
    }
    
    public function arc_slider_setup() 
    {
        $sliderLabel = array(
            'name' => _x('Arc Sliders', 'Arc Sliders', 'arc_slider'),
            'singular_name' => _x('Arc Slider', 'Arc Slider', 'arc_slider'),
            'menu_name' => _x('Arc Slider', 'Arc Slider', 'arc_slider'),
            'name_admin_bar' => _x('Arc Slider', 'Arc Slider', 'arc_slider'),
            'add_new' => __('Add New', 'arc_slider'),
            'all_items'  => __( 'Arc Slider', 'arc_slider' ),
            'view_item'           => __( 'View Content', 'arc_slider' ),
            'add_new_item'        => __( 'Add New Content', 'arc_slider' ),
            'edit_item'           => __( 'Edit Content', 'arc_slider' ),
            'update_item'         => __( 'Update Content', 'arc_slider' ),
        );
        $slider = array(
            'labels' => $sliderLabel,
            'description' => 'Arc Slider List custom post type.',
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => 'arc-slider',
            'query_var' => true,
            'rewrite' => array('slug' => 'arc_slider'),
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => false,
            'menu_position' => 20,
            'menu_icon' => 'dashicons-groups',
            'supports' => array('title','thumbnail'),
            'show_in_rest' => true
        );
        register_post_type('arc_slider', $slider);
    }

    public function all_meta_boxes()
    {
        add_meta_box('arc_slider_item', 'Arc Slider Info', array($this, 'arc_slider_meta_boxes'), 'arc_slider', 'normal', 'high');
    }

     public function arc_slider_meta_boxes()
    {
	    $arc_content = get_post_meta( get_the_ID(), 'arc_content', true );
	    $arc_bg_color = get_post_meta( get_the_ID(), 'arc_bg_color', true );
        
        ?>
        <div class="arc_slider_info">
            <p><label for="content">Content</label></p>
            <p>
                <textarea name="arc_content" id="arc_content" style="width: 100%;"><?php echo $arc_content; ?></textarea>    
            </p>

            <p><label for="arc_bg_color">Select Color</label></p>
            <p>
                <input class="color-field" type="text" name="arc_bg_color" id="arc_bg_color" value="<?php echo '#'.$arc_bg_color; ?>"/>
            </p>

            <script>
                (function( $ ) {
                    // Add Color Picker to all inputs that have 'color-field' class
                    $(function() {
                        $('.color-field').wpColorPicker();
                    });
                })( jQuery );
            </script>
            
        </div>
        <?php
    }

    public function arc_slider_metabox_data_save()
    {
        $arc_content = $_POST['arc_content'] ?? null;
        $arc_bg_color = ( isset( $_POST['arc_bg_color'] ) ? sanitize_html_class( $_POST['arc_bg_color'] ) : '' );

        if (empty($arc_content) && empty($arc_bg_color)){
            return get_the_ID();
        }
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE){
            return get_the_ID();
        }
        
        if('page' == $_POST['post_type']) {
            if(!current_user_can('edit_page', get_the_ID())) {
                return get_the_ID();
            }
        } else {
            if(!current_user_can('edit_page', get_the_ID())) {
                    return get_the_ID();
            }
        }

        update_post_meta(get_the_ID(), 'arc_content', esc_attr($arc_content));
        update_post_meta(get_the_ID(), 'arc_bg_color', esc_attr($arc_bg_color));
    }

    public function arc_slider_retrive($attr, $content)
    {
        $atts = shortcode_atts(
            array(

            ),
            $attr
        );
        extract($atts);
        ob_start(); ?>

        <section>Arc SLider</section>

    <?php     
    }

    

}

$arcSlider = new ArcSlider();