<?php
/**
 * Plugin Name: My Forms Plugin
 * Plugin URI: http://codemental.com/my-first-plugin
 * Description: The very first plugin that I have ever created.
 * Version: 1.0
 * Author: Code Mental
 * Author URI: CodeMental.com
 */

 add_action( 'the_content', 'my_thank_you_text' );

function my_thank_you_text ( $content ) {
    return $content .= '<p>Thank you for reading A million!</p>';
}

// function wporg_custom_post_type() {
//     register_post_type('flexible_form',
//         array(
//             'labels'      => array(
//                 'name'          => __('Flexibe Forms', 'textdomain'),
//                 'singular_name' => __('Flexible Form', 'textdomain'),
//             ),
//                 'public'      => true,
//                 'has_archive' => true,
//         )
//     );
// }
// add_action('init', 'wporg_custom_post_type');

function create_post_type() {
    register_post_type( 'flexible_form',
        array(
            'labels' => array(
                'name'          => __('Flexibe Forms', 'textdomain'),
                'singular_name' => __('Flexible Form', 'textdomain'),
            ),
            'public' => true,
            'has_archive' => true,
            'supports' => array( 'title', 'editor')
        )
    );
}
add_action( 'init', 'create_post_type' );



function add_post_meta_boxes() {
    // see https://developer.wordpress.org/reference/functions/add_meta_box for a full explanation of each property
    add_meta_box(
        "post_metadata_flexible_fields", // div id containing rendered fields
        "Flexible Fields", // section heading displayed as text
        "post_meta_box_flexible_fields", // callback function to render fields
        "flexible_form", // name of post type on which to render fields
        "normal", // location on the screen
        "low" // placement priority
    );
}
add_action( "admin_init", "add_post_meta_boxes" );

function save_post_meta_boxes(){
    global $post;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( get_post_status( $post->ID ) === 'auto-draft' ) {
        return;
    }
    update_post_meta( $post->ID, "_post_field1", sanitize_text_field( $_POST[ "_post_field1" ] ) );
    update_post_meta( $post->ID, "_post_field2", sanitize_text_field( $_POST[ "_post_field2" ] ) );
    update_post_meta( $post->ID, "_post_field3", sanitize_text_field( $_POST[ "_post_field3" ] ) );


    update_post_meta( $post->ID, "_post_advertising_category", sanitize_text_field( $_POST[ "_post_advertising_category" ] ) );
    update_post_meta( $post->ID, "_post_advertising_html", sanitize_text_field( $_POST[ "_post_advertising_html" ] ) );
}
add_action( 'save_post', 'save_post_meta_boxes' );

function post_meta_box_flexible_fields(){
    global $post;
    $custom = get_post_custom( $post->ID );
    $advertisingCategory = $custom[ "_post_advertising_category" ][ 0 ];
//     $advertisingHtml = $custom[ "_post_advertising_html" ][ 0 ];
//     wp_editor(
//         htmlspecialchars_decode( $advertisingHtml ),
//         '_post_advertising_html',
//         $settings = array(
//             'textarea_name' => '_post_advertising_html',
//         )
//     );
    switch ( $advertisingCategory ) {
        case 'internal':
            $internalSelected = "selected";
            break;
        case 'external':
            $externalSelected = "selected";
            break;
        case 'mixed':
            $mixedSelected = "selected";
            break;
    }

    $field1Selected = $custom[ "_post_field1" ][ 0 ];
    $field2Selected = $custom[ "_post_field2" ][ 0 ];
    $field3Selected = $custom[ "_post_field3" ][ 0 ];


    $html = <<<EOT
        <br/>

        <input type="checkbox" name="_post_field1" value="checked" $field1Selected>
        <label for="_post_field1">Field 1</label>
        <input type="text" name="_post_field1_label" value="Field 1">
        <input type="text" name="_post_field1_priority" value="0">
        <br/>

        <input type="checkbox" name="_post_field2" value="checked" $field2Selected>
        <label for="_post_field1">Field 2</label>
        <input type="text" name="_post_field2_label" value="Field 2">
        <input type="text" name="_post_field2_priority" value="1">

        <br/>
        <input type="checkbox" name="_post_field2" value="True" $field3Selected>
        <label for="_post_field1">Field 3</label>
        <input type="text" name="_post_field3_label" value="Field 3">
         <input type="text" name="_post_field3_priority" value="2">
        <br>
        <select name=\"_post_field2\">
            <option value=\"internal\" $internalSelected>Internal</option>
            <option value=\"external\" $externalSelected>External</option>
            <option value=\"mixed\" $mixedSelected>Mixed</option>
        </select>
        <br>
        <select name=\"_post_field3\">
            <option value=\"internal\" $internalSelected>Internal</option>
            <option value=\"external\" $externalSelected>External</option>
            <option value=\"mixed\" $mixedSelected>Mixed</option>
        </select>

    EOT;
    echo $html;
}