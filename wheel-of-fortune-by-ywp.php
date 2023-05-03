<?php
/*
* Plugin Name:       Wheel Of Fortune WordPress Widget by NextWeb
 * Plugin URI:        https://wheelwidget.com
 * Description:       Easily integrate Wheel of Fortune Widget from wheelwidget.com into your WordPress site with this plugin. Input your Widget ID and choose where you want the widget to appear.
 * Version:           2.0.0
 * Requires at least: 5.9
 * Requires PHP:      7.2
 * Author:            NextWeb Oy
 * Author URI:        https://wheelwidget.com
 * Text Domain:       wheel-of-fortune-by-ywp
*/

define('WHEEL_OF_FORTUNE_BY_YWP_WIDGET_PLUGIN_URL',  plugin_dir_url( __FILE__ ) );
define('WHEEL_OF_FORTUNE_BY_YWP_WIDGET_PLUGIN_VERSION', '2.0.0' );

add_action( 'wp_footer', 'wheel_of_fortune_by_ywp_widget_footer_action' );

function wheel_of_fortune_by_ywp_widget_footer_action(){

    $val = get_option('wheel_of_fortune_by_ywp_widget_option_name');
    $idw =  $val['id-widget'] ?? 0;
    $catsing = $val['exclude-single-archive'] ?? '';

    $include = $val['wheel-include-page'] ?? '';
    $exclude = $val['wheel-exclude-page'] ?? '';

    if($idw>0){
        $code = '<script src="https://files.wheelwidget.com/wheel.js" data-id="'.$idw.'"></script>';
        if($catsing){
            if(is_single() OR is_category() OR is_tag() OR is_archive()){
                return;
            }
        }
if (is_page()){
    $thisPostID = get_the_ID();
    if($exclude){
        if (in_array($thisPostID, $exclude)){return;}
    }
    if (!empty($include)){
        if (in_array($thisPostID, $include)){
//отображать только на этих страницах
            echo $code;
        }
    }else{
        echo $code;
    }

}

    }

}

add_action( 'admin_enqueue_scripts', 'wheel_of_fortune_by_ywp_widget_scripts' );
function wheel_of_fortune_by_ywp_widget_scripts() {

        wp_register_style('slimselectcss', WHEEL_OF_FORTUNE_BY_YWP_WIDGET_PLUGIN_URL . 'assets/slimselect.min.css', array(),
            WHEEL_OF_FORTUNE_BY_YWP_WIDGET_PLUGIN_VERSION);
        wp_enqueue_style('slimselectcss');
        wp_register_script('scripts', WHEEL_OF_FORTUNE_BY_YWP_WIDGET_PLUGIN_URL . 'assets/scripts.js', array(),WHEEL_OF_FORTUNE_BY_YWP_WIDGET_PLUGIN_VERSION);
        wp_enqueue_script('scripts');
        wp_register_script('slimselect', WHEEL_OF_FORTUNE_BY_YWP_WIDGET_PLUGIN_URL . 'assets/slimselect.min.js',array('jquery'), null, true);
        wp_enqueue_script('slimselect');
}



function wheel_of_fortune_by_ywp_widget_plugin_settings(){
    add_settings_field( 'wheel_of_fortune_by_ywp_widget_option_name0', '<div class="ww-title-fun">Widget ID</div><div class="ww-description-fun">You can get your ID from wheelwidget.com</div>', 'wheel_of_fortune_by_ywp_widget_field0', 'wheel_of_fortune_by_ywp_widget', 'wheel_of_fortune_by_ywp_widget_section' );
    add_settings_field( 'wheel_of_fortune_by_ywp_widget_option_name1', '<div class="ww-title-fun">Exclude pages</div><div class="ww-description-fun">Pages where there will be no widget</div>', 'wheel_of_fortune_by_ywp_widget_field1', 'wheel_of_fortune_by_ywp_widget', 'wheel_of_fortune_by_ywp_widget_section' );
    add_settings_field( 'wheel_of_fortune_by_ywp_widget_option_name2', '<div class="ww-title-fun">Include pages</div><div class="ww-description-fun">Pages where the widget will be displayed</div>', 'wheel_of_fortune_by_ywp_widget_field2', 'wheel_of_fortune_by_ywp_widget', 'wheel_of_fortune_by_ywp_widget_section' );
    add_settings_field( 'wheel_of_fortune_by_ywp_widget_option_name3', '<div class="ww-title-fun">Exclude categories and posts</div><div class="ww-description-fun">Managing the display of a widget in a blog</div>', 'wheel_of_fortune_by_ywp_widget_field3', 'wheel_of_fortune_by_ywp_widget', 'wheel_of_fortune_by_ywp_widget_section' );
    add_settings_section( 'wheel_of_fortune_by_ywp_widget_section', '<div class="ww-main-title">General</div>', 'wheel_of_fortune_by_ywp_widget_section_callback', 'wheel_of_fortune_by_ywp_widget' );
    register_setting( 'wheel_of_fortune_by_ywp_widget', 'wheel_of_fortune_by_ywp_widget_option_name' );
}
add_action( 'admin_init', 'wheel_of_fortune_by_ywp_widget_plugin_settings' );

function wheel_of_fortune_by_ywp_widget_field0(): void
{
    $val = get_option('wheel_of_fortune_by_ywp_widget_option_name');
    $val =  $val['id-widget'] ?? '';
    ?>

    <input class="ww-input-number" style="width: 275px; padding:10px; border: 1px solid rgba(25, 25, 25, 0.1); border-radius: 10px;box-shadow: 0px 0px 2px rgba(0, 0, 0, 0.1);" type="number" min="0" placeholder="Widget ID" name="wheel_of_fortune_by_ywp_widget_option_name[id-widget]"
           value="<?php echo esc_attr($val) ?>"/>
<?php }

function wheel_of_fortune_by_ywp_widget_field1(){
$val = get_option('wheel_of_fortune_by_ywp_widget_option_name');

if (empty($val['wheel-exclude-page'])) {
    $val['wheel-exclude-page'] = array();
}

?>


<select multiple="multiple" id="wheel-exclude-page" name="wheel_of_fortune_by_ywp_widget_option_name[wheel-exclude-page][]" placeholder="Choose pages">
    <?php

    $pages = get_pages( [
        'sort_order'   => 'ASC',
        'sort_column'  => 'post_title',
        'hierarchical' => 1,
        'child_of'     => 0,
        'parent'       => -1,
        'offset'       => 0,
        'post_type'    => 'page',
        'post_status'  => 'publish',
    ] );
    foreach( $pages as $page ){
       $selected = in_array($page->ID, $val['wheel-exclude-page']) ? ' selected="selected" ' : '';
        echo '<option '.$selected.' value="' .   $page->ID . '">'. esc_html($page->post_title) .'</option>';
    }  ?>
</select>
<?php


}

function wheel_of_fortune_by_ywp_widget_field2(){
    $val = get_option('wheel_of_fortune_by_ywp_widget_option_name');

    if (empty($val['wheel-include-page'])) {
        $val['wheel-include-page'] = array();
    }
    ?>


    <select multiple="multiple" id="wheel-include-page" name="wheel_of_fortune_by_ywp_widget_option_name[wheel-include-page][]" placeholder="Choose pages">
        <?php

        $pages = get_pages( [
            'sort_order'   => 'ASC',
            'sort_column'  => 'post_title',
            'hierarchical' => 1,
            'child_of'     => 0,
            'parent'       => -1,
            'offset'       => 0,
            'post_type'    => 'page',
            'post_status'  => 'publish',
        ] );
        foreach( $pages as $page ){
            $disabled ='';
            if(!empty($val['wheel-exclude-page'])){
                $disabled = in_array($page->ID, $val['wheel-exclude-page']) ? ' disabled="disabled" ' : '';
            }

            $selected = in_array($page->ID, $val['wheel-include-page']) ? ' selected="selected" ' : '';
            echo '<option '.$selected.' value="' .   $page->ID . '" '.$disabled.'>'. esc_html($page->post_title) .'</option>';
        }  ?>
    </select>
    <?php
}

 function wheel_of_fortune_by_ywp_widget_field3()
{
    $val = get_option('wheel_of_fortune_by_ywp_widget_option_name');
    $checked = isset($val['exclude-single-archive']) ? "checked" : "";
    ?>
    <input class="ww-checkbox" name="wheel_of_fortune_by_ywp_widget_option_name[exclude-single-archive]" type="checkbox" value="1" <?php echo $checked; ?>>
<?php }


function wheel_of_fortune_by_ywp_widget_section_callback($options){
    foreach ($options as $name => & $val) {
        if ($name) {
            $val = strip_tags($val);
        }
    }
    return $options;
}
function wheel_of_fortune_by_ywp_widget_options(){
    echo '<div class="ww-header">
    <h1 class="ww-plugin-name"><a href="https://wheelwidget.com" target="_blank" class="ww-logo"></a>WheelWidget <span> Settings</span></h1>
    <div class="ww-header-link"><a href="https://wheelwidget.com/docs" target="_blank" class="ww-documentation">Documentation</a><a href="https://wheelwidget.com/app/register" class="ww-login" target="_blank">Login</a></div>';
    echo '</div>';
    echo '<div class="main-wrapper">';
    echo '<div class="general">';
    echo '<form method="post" action="options.php">';
    settings_fields( 'wheel_of_fortune_by_ywp_widget' );
    do_settings_sections( 'wheel_of_fortune_by_ywp_widget' );
    echo '<div style="display: flex;align-items: center; justify-content:space-between; margin-top: 37px;"><button class="ww-save-changes" type="submit">Save Changes</button><a class="powered-link" href="https://nextweb.fi" target="_blank" style="color: #626262;font-weight: 400;
    font-size: 14px;text-decoration:none">Powered by NextWeb</a></div> ';
    echo '</form>';
    echo '</div>';
    echo '<div class="introducing">';
    echo '<span>Introducing the new Wheel of Fortune!</span>';
    echo '<p>The most customizable, 100% SEO friendly, charged with the power of artificial intelligence. It will literally blow up the conversion rate of your site.</p>';
    echo '<a style="text-decoration:none" href="https://wheelwidget.com" target="_blank">Learn More</a>';
    echo '</div>';
    echo '</div>';
}
function wheel_of_fortune_by_ywp_widget_menu(){
    add_menu_page( 'WheelWidget Settings', 'WheelWidget', 'manage_options', 'wheel_of_fortune_by_ywp_widget_options', 'wheel_of_fortune_by_ywp_widget_options', plugins_url( 'wheel-of-fortunewidget-by-wheelwidget-com/assets/image/menu.png' ) );

}
add_action( 'admin_menu', 'wheel_of_fortune_by_ywp_widget_menu' );



