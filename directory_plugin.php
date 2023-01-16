<?php

/**
 * @package
 */
/*
Plugin Name: Directory_Login_Plugin/Register_Plugin
Plugin URI: #
Description: Developed by Sid Techno.
Version: 1.0.0
Author: Sidtehcno
Author URI: https://portal.sidtechno.com
License: GPLv2 or later
Text Domain: directory-login-register-plugin
*/

// LOGIN ACTION/PROCESS// LOGIN // LOGIN // LOGIN 
// LOGIN ACTION/PROCESS// LOGIN // LOGIN // LOGIN 

// PROCESS
// add_action('init', 'process_login');

// function process_login()
// {
//     global $wpdb;
//     if (isset($_POST['login_directory'])) {
//         $username = $_POST['login_username'];
//         $pass = $_POST['login_password'];
//         if (isset($_POST['token'])) {
//             $captcha_token = $_POST['token'];
//         }

//         $userDetails = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}users WHERE user_email='$username'");

//         if (isset($userDetails->user_login)) {

//             $creds = array(
//                 'user_login'    => $userDetails->user_login,
//                 'user_password' => $pass,
//                 'remember'      => true
//             );

//             $user = wp_signon($creds, false);
//             if (is_wp_error($user)) {
//                 // $msg = $user->get_error_message();
//                 $_SESSION['error'] = "Invalid Username/Password";
//             } else {
//                 wp_clear_auth_cookie();
//                 wp_set_current_user($user->ID); // Set the current user detail
//                 wp_set_auth_cookie($user->ID); // Set auth details in cookie
//                 $_SESSION['success'] = "LOGIN SUCCESSFULL";
//             }
//         } else {
//             $_SESSION['error'] = "Invalid Username/Password";
//         }
//     }
// }

// // ACTION
// add_shortcode('directory_login', 'login_page_view');

// function login_page_view()
// {
//     if (!is_admin() and !wp_is_json_request()) {
//         include 'template/login.php';
//         return $html;
//     }
// }

// REGISTER ACTION/PROCESS// REGISTER// REGISTER// REGISTER
// REGISTER ACTION/PROCESS// REGISTER// REGISTER// REGISTER

// PROCESS
add_action('init', 'process_register');

function process_register()
{
    global $wpdb;
    if (isset($_POST['reg_directory'])) {
        // if (isset($_POST['token'])) {
        //     $captcha_token = $_POST['token'];
        // }
        $data = array(
            'user_login' => $_POST['reg_full_name'],
            'user_email' => $_POST['reg_email'],
            'user_pass' => md5($_POST['reg_pass']),
            'user_registered' => date("Y-m-d H:i:s"),
            'display_name' => $_POST['reg_full_name'],
            'user_nicename' => $_POST['reg_email'],
        );
        $table_name = "{$wpdb->prefix}users";
        $userDetails_get_one = $wpdb->get_row(" 
      SELECT {$wpdb->prefix}users.* , {$wpdb->prefix}usermeta.*
      FROM {$wpdb->prefix}users
      INNER JOIN {$wpdb->prefix}usermeta
      ON {$wpdb->prefix}users.id =  {$wpdb->prefix}usermeta.user_id
      WHERE {$wpdb->prefix}users.user_email='" . $_POST['reg_email'] . "'");
        if (!$userDetails_get_one) {
            $result = $wpdb->insert($table_name, $data, $format = null);
            if ($result == 1) {
                $lastid = $wpdb->insert_id;
                $wp_get_user = get_user_by('ID', $lastid);
                $wp_get_user->add_role('free');
                $msg = "Registration successfull.";
                $_SESSION['success'] = $msg;
                $table_package = "{$wpdb->prefix}user_subscriptions_details";
                $user_data = array(
                    'user_id' => $lastid,
                    'customer_name' => $_POST['reg_full_name'],
                    'customer_email' =>  $_POST['reg_email'],
                    'package_type' =>  'free',
                    'item_name' =>  'Free Listing',
                    'item_price' =>  0.00,
                    'item_price_currency' => 'usd',
                    'payment_method' => 'free',
                    'plan_amount' =>  0.00,
                    'plan_amount_currency' => 'usd',
                    'payer_email' =>  $_POST['reg_email'],
                    'created' => date("Y-m-d H:i:s"),
                    'status' => 'active'
                );
                $add_free_package = $wpdb->insert($table_package, $user_data, $format = null);;

                global $wp;
                $url = home_url($wp->request);
                $_redirect = explode("user_dashboard", $url);
                $site_url = $_redirect[0];
                echo "<script> window.location.href='" . $site_url . "/user_dashboard'</script>";
            }
        } else {
            $_SESSION['error'] = "Email already register!";
        }
    }
}

// ACTION
add_shortcode('directory_register', 'register_page_view');

function register_page_view()
{
    if (!is_admin() and !wp_is_json_request()) {
        include 'template/register.php';
        return $html;
    }
}

// FORGET PASSWORD ACTION/PROCESS// FORGET PASSWORD// FORGET PASSWORD
// FORGET PASSWORD ACTION/PROCESS// FORGET PASSWORD// FORGET PASSWORD

// PROCESS
add_action('init', 'Password_Reset');

function Password_Reset()
{
    global $wpdb;
    if (isset($_POST['btnReset'])) {
        $user = $_POST['forget_email'];

        $table_name = "{$wpdb->prefix}users";
        $userDetails = $wpdb->get_row(" 
      SELECT {$wpdb->prefix}users.* , {$wpdb->prefix}usermeta.*
      FROM {$wpdb->prefix}users
      INNER JOIN {$wpdb->prefix}usermeta
      ON {$wpdb->prefix}users.id =  {$wpdb->prefix}usermeta.user_id
      WHERE {$wpdb->prefix}users.user_email='" . $user . "'");

        print_r($userDetails);
        if (!$userDetails) {
            $_SESSION['error'] = 'User with this "' . $user . '" email doesn\'t exist.';
        } else {

            $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
            $randomKey = substr(str_shuffle($permitted_chars), 0, 20);
            $randomPass = substr(str_shuffle($permitted_chars), 0, 10);

            $table = "{$wpdb->prefix}users";
            $data = array(
                'user_activation_key' => $randomKey,
                'user_pass' => md5($randomPass),
            );
            $where = array(
                'user_email' => $userDetails->user_email,
            );
            $update_key = $wpdb->update($table, $data, $where);

            $to = $userDetails->user_email;
            $subject = get_bloginfo("name") . 'Password Reset';
            $message = 'Hi! ' . $userDetails->user_email . ' Your New Password Is:
            <h1>New Password</h1><br>
            <p>' . $randomPass . '</p>
            ';
            wp_mail($to, $subject, $message);
            $_SESSION['success'] = "Password Reset Successfull <a>New Password</a> Has Been Sent To '" . $userDetails->user_email . "'";
        }
    }
}

// ACTION
add_shortcode('forget_reset_password', 'Forget_Password_View');

function Forget_Password_View()
{
    if (!is_admin() and !wp_is_json_request()) {
        include 'template/forget.php';
        return $html;
    }
}

// CUSTOM POST TYPE PROCESS/ACTION

// POST CATEGORY// POST CATEGORY// POST CATEGORY// POST CATEGORY
// POST CATEGORY// POST CATEGORY// POST CATEGORY// POST CATEGORY

function my_custom_post_post_types()
{

    //labels array added inside the function and precedes args array

    $labels = array(
        'name' => _x('Directory Listing', 'post type general name'),
        'singular_name' => _x('Directory Listing', 'post type singular name'),
        'add_new' => _x('Add Directory', 'Post'),
        'add_new_item' => __('Add New Directory'),
        'edit_item' => __('Edit Directory'),
        'new_item' => __('New Directory'),
        'all_items' => __('All Directory'),
        'view_item' => __('View Directory'),
        'search_items' => __('Search Directory'),
        'not_found' => __('No directory listing found'),
        'not_found_in_trash' => __('No directory listing found in the Trash'),
        'parent_item_colon' => '',
        'menu_name' => 'Directory',
    );

    // args array

    $args = array(
        'labels' => $labels,
        'description' => 'Displays posts and their ratings',
        'public' => true,
        'menu_position' => 4,
        'supports' => array('title', 'editor', 'thumbnail',  'comments'),
        'has_archive' => true,
        'menu_icon' => 'dashicons-table-col-before',
        // 'rewrite'      => array( 'slug' => 'directory_listing/%cat-type%/%post%', 'with_front' => false ),
        // 'taxonomies'   => array( 'Post', 'Event', 'Shop', 'Service', 'Restaurant', 'Classified'),
    );

    register_post_type('directory_listing', $args);
}
add_action('init', 'my_custom_post_post_types');

// add_filter( 'post_type_link', 'custom_post_type_link', 10, 2 );
// function custom_post_type_link( $link, $post ) {

//   if ( $post->post_type == 'directory_listing' ) {
  
//     if ( $cats = get_the_terms( $post->ID, 'Post' ) ) {
//       $link = str_replace( '%post%', current($cats)->slug, $link );
//     }
//   }
//   return $link;
// }

// add_filter( 'post_type_link', 'custom_category_type_link', 10, 2 );
// function custom_category_type_link( $link, $post ) {

//   if ( $post->post_type == 'directory_listing' ) {
  
//     if ( $cats = get_the_terms( $post->ID, 'Post' ) ) {
//       $link = str_replace( '%cat-type%', current($cats)->taxonomy, $link );
//     }
//   }
//   return strtolower($link);
// }

// POST CATEGORY TAXONOMY// POST CATEGORY TAXONOMY// POST CATEGORY TAXONOMY
// POST CATEGORY TAXONOMY// POST CATEGORY TAXONOMY// POST CATEGORY TAXONOMY

function my_taxonomies_post_types()
{
    //labels array

    $labels = array(
        'name' => _x('Post Categories', 'taxonomy general name'),
        'singular_name' => _x('Post Category', 'taxonomy singular name'),
        'search_items' => __('Search Post Categories'),
        'all_items' => __('All Post Categories'),
        'parent_item' => __('Parent Post Category'),
        'parent_item_colon' => __('Parent Post Category:'),
        'edit_item' => __('Edit Post Category'),
        'update_item' => __('Update Post Category'),
        'add_new_item' => __('Add New Post Category'),
        'new_item_name' => __('New Post Category'),
        'menu_name' => __(' Post Categories'),
    );

    //args array

    $args = array(
        'labels' => $labels,
        'hierarchical' => true,
        'rewrite' => true,
        'has_archive' => true,
        // 'rewrite' => array( 'slug' => 'Post', 'with_front' => false ),
    );

    register_taxonomy('Post', 'directory_listing', $args);
}

add_action('init', 'my_taxonomies_post_types', 0);

// EVENT CATEGORY TAXONOMY// EVENT CATEGORY TAXONOMY// EVENT CATEGORY TAXONOMY
// EVENT CATEGORY TAXONOMY// EVENT CATEGORY TAXONOMY// EVENT CATEGORY TAXONOMY

function my_taxonomies_event_category()
{
    //labels array

    $labels = array(
        'name' => _x('Event Categories', 'taxonomy general name'),
        'singular_name' => _x('Event Category', 'taxonomy singular name'),
        'search_items' => __('Search Event Categories'),
        'all_items' => __('All Event Categories'),
        'parent_item' => __('Parent Event Category'),
        'parent_item_colon' => __('Parent Event Category:'),
        'edit_item' => __('Edit Event Category'),
        'update_item' => __('Update Event Category'),
        'add_new_item' => __('Add New Event Category'),
        'new_item_name' => __('New Event Category'),
        'menu_name' => __(' Event Categories'),
    );

    //args array

    $args = array(
        'labels' => $labels,
        'hierarchical' => true,
        'rewrite' => true,
    );

    register_taxonomy('Event', 'directory_listing', $args);
}

add_action('init', 'my_taxonomies_event_category', 0);

// SERVICE CATEGORY TAXONOMY// SERVICE CATEGORY TAXONOMY// SERVICE CATEGORY TAXONOMY
// SERVICE CATEGORY TAXONOMY// SERVICE CATEGORY TAXONOMY// SERVICE CATEGORY TAXONOMY

function my_taxonomies_service_category()
{
    //labels array

    $labels = array(
        'name' => _x('Service Categories', 'taxonomy general name'),
        'singular_name' => _x('Service Category', 'taxonomy singular name'),
        'search_items' => __('Search Service Categories'),
        'all_items' => __('All Service Categories'),
        'parent_item' => __('Parent Service Category'),
        'parent_item_colon' => __('Parent Service Category:'),
        'edit_item' => __('Edit Service Category'),
        'update_item' => __('Update Service Category'),
        'add_new_item' => __('Add New Service Category'),
        'new_item_name' => __('New Service Category'),
        'menu_name' => __(' Service Categories'),
    );

    //args array

    $args = array(
        'labels' => $labels,
        'hierarchical' => true,
        'rewrite' => true,
    );

    register_taxonomy('Service', 'directory_listing', $args);
}

add_action('init', 'my_taxonomies_service_category', 0);

// RESTAURANTS CATEGORY TAXONOMY// RESTAURANTS CATEGORY TAXONOMY// RESTAURANTS CATEGORY TAXONOMY
// RESTAURANTS CATEGORY TAXONOMY// RESTAURANTS CATEGORY TAXONOMY// RESTAURANTS CATEGORY TAXONOMY

function my_taxonomies_restaurant_category()
{
    //labels array

    $labels = array(
        'name' => _x('Restaurant Categories', 'taxonomy general name'),
        'singular_name' => _x('Restaurant Category', 'taxonomy singular name'),
        'search_items' => __('Search Restaurant Categories'),
        'all_items' => __('All Restaurant Categories'),
        'parent_item' => __('Parent Restaurant Category'),
        'parent_item_colon' => __('Parent Restaurant Category:'),
        'edit_item' => __('Edit Restaurant Category'),
        'update_item' => __('Update Restaurant Category'),
        'add_new_item' => __('Add New Restaurant Category'),
        'new_item_name' => __('New Restaurant Category'),
        'menu_name' => __(' Restaurant Categories'),
    );

    //args array

    $args = array(
        'labels' => $labels,
        'hierarchical' => true,
        'rewrite' => true,
    );

    register_taxonomy('Restaurant', 'directory_listing', $args);
}

add_action('init', 'my_taxonomies_restaurant_category', 0);

// Classifieds Category Taxonomy// Classifieds Category Taxonomy// Classifieds Category Taxonomy
// Classifieds Category Taxonomy// Classifieds Category Taxonomy// Classifieds Category Taxonomy

function my_taxonomies_classified_category()
{
    //labels array

    $labels = array(
        'name' => _x('Classified Categories', 'taxonomy general name'),
        'singular_name' => _x('Classified Category', 'taxonomy singular name'),
        'search_items' => __('Search Classified Categories'),
        'all_items' => __('All Classified Categories'),
        'parent_item' => __('Parent Classified Category'),
        'parent_item_colon' => __('Parent Classified Category:'),
        'edit_item' => __('Edit Classified Category'),
        'update_item' => __('Update Classified Category'),
        'add_new_item' => __('Add New Classified Category'),
        'new_item_name' => __('New Classified Category'),
        'menu_name' => __(' Classified Categories'),
    );

    //args array

    $args = array(
        'labels' => $labels,
        'hierarchical' => true,
        'rewrite' => true,
    );

    register_taxonomy('Classified', 'directory_listing', $args);
}

add_action('init', 'my_taxonomies_classified_category', 0);


// SHOP CATEGORY// SHOP CATEGORY// SHOP CATEGORY// SHOP CATEGORY// SHOP CATEGORY
// SHOP CATEGORY// SHOP CATEGORY// SHOP CATEGORY// SHOP CATEGORY// SHOP CATEGORY

function my_taxonomies_shop_category()
{
    //labels array

    $labels = array(
        'name' => _x('Shop Categories', 'taxonomy general name'),
        'singular_name' => _x('Shop Category', 'taxonomy singular name'),
        'search_items' => __('Search Shop Categories'),
        'all_items' => __('All Shop Categories'),
        'parent_item' => __('Parent Shop Category'),
        'parent_item_colon' => __('Parent Shop Category:'),
        'edit_item' => __('Edit Shop Category'),
        'update_item' => __('Update Shop Category'),
        'add_new_item' => __('Add New Shop Category'),
        'new_item_name' => __('New Shop Category'),
        'menu_name' => __(' Shop Categories'),
    );

    //args array

    $args = array(
        'labels' => $labels,
        'hierarchical' => true,
        'rewrite' => true,
    );

    register_taxonomy('Shop', 'directory_listing', $args);
}

add_action('init', 'my_taxonomies_shop_category', 0);

// COMPANY DETAILS// COMPANY DETAILS// COMPANY DETAILS// COMPANY DETAILS
// COMPANY DETAILS// COMPANY DETAILS// COMPANY DETAILS// COMPANY DETAILS


// add event date field to events post type
function add_post_meta_boxes()
{
    add_meta_box(
        "post_metadata_postType_post", // div id containing rendered fields
        "Company Details", // section heading displayed as text
        "post_meta_box_postType_post", // callback function to render fields
        "directory_listing", // name of post type on which to render fields
        "normal", // location on the screen
        "core" // placement priority
    );
}
add_action("admin_init", "add_post_meta_boxes");

// save field value
function save_post_meta_boxes()
{
    global $post;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    update_post_meta(isset($post->ID) ? $post->ID : '', "phone", isset($_POST['phone']) ? $_POST['phone'] : '');

    update_post_meta(isset($post->ID) ? $post->ID : '', "year_established", isset($_POST['year_estab']) ? $_POST['year_estab'] : '');

    update_post_meta(isset($post->ID) ? $post->ID : '', "annual_sale", isset($_POST['annual_sale']) ? $_POST['annual_sale'] : '');

    update_post_meta(isset($post->ID) ? $post->ID : '', "no_of_employees", isset($_POST['no_of_employees']) ? $_POST['no_of_employees'] : '');

    update_post_meta(isset($post->ID) ? $post->ID : '', "hours_of_opertaion", isset($_POST['hours_of_opertaion']) ? $_POST['hours_of_opertaion'] : '');

    update_post_meta(isset($post->ID) ? $post->ID : '', "accepted_forms_payments", isset($_POST['accepted_forms_payments']) ? $_POST['accepted_forms_payments'] : '');

    update_post_meta(isset($post->ID) ? $post->ID : '', "credentials", isset($_POST['credentials']) ? $_POST['credentials'] : '');
}

add_action('save_post', 'save_post_meta_boxes');

// callback function to render fields
function post_meta_box_postType_post()
{
    global $post;
    if (isset($post->ID)) {
        $custom = get_post_custom($post->ID);
    }
    if (isset($custom["year_established"][0])) {
        $Year_Establ = $custom["year_established"][0];
    }
    $year_est = isset($Year_Establ) ? $Year_Establ : '';
    if (isset($custom["annual_sale"][0])) {
        $Annual_Sale = $custom["annual_sale"][0];
    }
    $annual = isset($Annual_Sale) ? $Annual_Sale : '';
    if (isset($custom["no_of_employees"][0])) {
        $Employees = $custom["no_of_employees"][0];
    }
    $employee = isset($Employees) ? $Employees : '';
    if (isset($custom["hours_of_opertaion"][0])) {
        $Operation_Hours = $custom["hours_of_opertaion"][0];
    }
    $hours_operation = isset($Operation_Hours) ? $Operation_Hours : '';
    if (isset($custom["accepted_forms_payments"][0])) {
        $Accepted_Payments = $custom["accepted_forms_payments"][0];
    }
    $payment = isset($Accepted_Payments) ? $Accepted_Payments : '';
    if (isset($custom["credentials"][0])) {
        $creds = $custom["credentials"][0];
    }
    $c_creds = isset($creds) ? $creds : '';

    echo "<label style=\"border-bottom: 1px solid;\">Year Established: </label><br><br><input style=\"width:100%;\" type=\"text\" name=\"year_estab\" value=\"" . $year_est . "\" placeholder=\"Year Established\"><br><br>";

    echo "<label style=\"border-bottom: 1px solid;\">Annual Sales: </label><br><br><input style=\"width:100%;\" type=\"text\" name=\"annual_sale\" value=\"" . $annual . "\" placeholder=\"Annual Sales\"><br><br>";

    echo "<label style=\"border-bottom: 1px solid;\">No of Employees: </label><br><br><input style=\"width:100%;\" type=\"text\" name=\"no_of_employees\" value=\"" . $employee . "\" placeholder=\"No of Employees\"><br><br>";

    echo "<label style=\"border-bottom: 1px solid;\">Hours of Operation: </label><br><br><input style=\"width:100%;\" type=\"text\" name=\"hours_of_opertaion\" value=\"" . $hours_operation . "\" placeholder=\"Hours of Operation\"><br><br>";

    echo "<label style=\"border-bottom: 1px solid;\">Accepted Forms of Payments: </label><br><br><input style=\"width:100%;\" type=\"text\" name=\"accepted_forms_payments\" value=\"" . $payment . "\" placeholder=\"Accepted Forms of Payments\"><br><br>";

    echo "<label style=\"border-bottom: 1px solid;\">Credentials: </label><br><br><input style=\"width:100%;\" type=\"text\" name=\"credentials\" value=\"" . $c_creds . "\" placeholder=\"Credentials\"><br><br>";
}


// LISTING TYPE// LISTING TYPE// LISTING TYPE// LISTING TYPE// LISTING TYPE
// LISTING TYPE// LISTING TYPE// LISTING TYPE// LISTING TYPE// LISTING TYPE


// add event date field to events post type
function add_post_listing_type_meta_boxes()
{
    add_meta_box(
        "post_metadata_listing_type_postType_post", // div id containing rendered fields
        "Listing Type", // section heading displayed as text
        "post_meta_box_listing_type", // callback function to render fields
        "directory_listing", // name of post type on which to render fields
        "normal", // location on the screen
        "core" // placement priority
    );
}
add_action("admin_init", "add_post_listing_type_meta_boxes");



// save field value
function save_post_listing_type_meta_boxes()
{
    global $post;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    update_post_meta(isset($post->ID) ? $post->ID : '', "listing_type_selected", isset($_POST['listing_type_selected']) ? $_POST['listing_type_selected'] : '');
}
add_action('save_post', 'save_post_listing_type_meta_boxes');


// callback function to render fields
function post_meta_box_listing_type()
{
    global $post;
    global $wpdb;
    if (isset($post->ID)) {
        $custom = get_post_custom($post->ID);
    }
    if (isset($custom["listing_type_selected"][0])) {
        $listing_type = $custom["listing_type_selected"][0];
    }
    $listing_type_Listing = isset($listing_type) ? $listing_type : '';


    if ($listing_type_Listing == 'POST') {
        echo "<label style=\"border-bottom: 1px solid;\">Select Listing Type: </label><br><br>
        <select name='listing_type_selected'>
            <option selected value='Post'>Post</option>
            <option value='Event'>Event</option>
            <option value='Service'>Service</option>
            <option value='Restaurant'>Restaurant</option>
            <option value='Classified'>Classified</option>
            <option value='Shop'>Shop</option>
        </select>
        <br><br>";
    } else if ($listing_type_Listing == 'Event') {
        echo "<label style=\"border-bottom: 1px solid;\">Select Listing Type: </label><br><br>
        <select name='listing_type_selected'>
            <option value='Post'>Post</option>
            <option selected value='Event'>Event</option>
            <option value='Service'>Service</option>
            <option value='Restaurant'>Restaurant</option>
            <option value='Classified'>Classified</option>
            <option value='Shop'>Shop</option>
        </select>
        <br><br>";
    } else if ($listing_type_Listing == 'Service') {
        echo "<label style=\"border-bottom: 1px solid;\">Select Listing Type: </label><br><br>
        <select name='listing_type_selected'>
            <option value='Post'>Post</option>
            <option value='Event'>Event</option>
            <option selected value='Service'>Service</option>
            <option value='Restaurant'>Restaurant</option>
            <option value='Classified'>Classified</option>
            <option value='Shop'>Shop</option>
        </select>
        <br><br>";
    } else if ($listing_type_Listing == 'Service') {
        echo "<label style=\"border-bottom: 1px solid;\">Select Listing Type: </label><br><br>
        <select name='listing_type_selected'>
            <option value='Post'>Post</option>
            <option value='Event'>Event</option>
            <option value='Service'>Service</option>
            <option selected value='Restaurant'>Restaurant</option>
            <option value='Classified'>Classified</option>
            <option value='Shop'>Shop</option>
        </select>
        <br><br>";
    } else if ($listing_type_Listing == 'Restaurant') {
        echo "<label style=\"border-bottom: 1px solid;\">Select Listing Type: </label><br><br>
        <select name='listing_type_selected'>
            <option value='Post'>Post</option>
            <option value='Event'>Event</option>
            <option value='Service'>Service</option>
            <option value='Restaurant'>Restaurant</option>
            <option selected value='Classified'>Classified</option>
            <option value='Shop'>Shop</option>
        </select>
        <br><br>";
    } else if ($listing_type_Listing == 'Classified') {
        echo "<label style=\"border-bottom: 1px solid;\">Select Listing Type: </label><br><br>
        <select name='listing_type_selected'>
            <option value='Post'>Post</option>
            <option value='Event'>Event</option>
            <option value='Service'>Service</option>
            <option value='Restaurant'>Restaurant</option>
            <option selected value='Classified'>Classified</option>
            <option value='Shop'>Shop</option>
        </select>
        <br><br>";
    } else if ($listing_type_Listing == 'Shop') {
        echo "<label style=\"border-bottom: 1px solid;\">Select Listing Type: </label><br><br>
        <select name='listing_type_selected'>
            <option value='Post'>Post</option>
            <option value='Event'>Event</option>
            <option value='Service'>Service</option>
            <option value='Restaurant'>Restaurant</option>
            <option value='Classified'>Classified</option>
            <option selected value='Shop'>Shop</option>
        </select>
        <br><br>";
    } else {
        echo "<label style=\"border-bottom: 1px solid;\">Select Listing Type: </label><br><br>
        <select name='listing_type_selected'>
            <option value='Post'>Post</option>
            <option value='Event'>Event</option>
            <option value='Service'>Service</option>
            <option value='Restaurant'>Restaurant</option>
            <option value='Classified'>Classified</option>
            <option value='Shop'>Shop</option>
        </select>
        <br><br>";
    }
}

// LISTING ADMIN DATA// LISTING ADMIN DATA// LISTING ADMIN DATA// LISTING ADMIN DATA// LISTING ADMIN DATA
// LISTING ADMIN DATA// LISTING ADMIN DATA// LISTING ADMIN DATA// LISTING ADMIN DATA// LISTING ADMIN DATA


// add event date field to events post type
function add_post_listing_admin_data_meta_boxes()
{
    add_meta_box(
        "post_metadata_listing_admin_data_postType_post", // div id containing rendered fields
        "Listing Admin Data", // section heading displayed as text
        "post_meta_box_listing_admin_data", // callback function to render fields
        "directory_listing", // name of post type on which to render fields
        "normal", // location on the screen
        "core" // placement priority
    );
}
add_action("admin_init", "add_post_listing_admin_data_meta_boxes");



// save field value
function save_post_listing_admin_data_meta_boxes()
{
    global $post;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    update_post_meta(isset($post->ID) ? $post->ID : '', "listing_admin_data", isset($_POST['listing_admin_data']) ? $_POST['listing_admin_data'] : '');
}
add_action('save_post', 'save_post_listing_admin_data_meta_boxes');


// callback function to render fields
function post_meta_box_listing_admin_data()
{
    global $post;
    if (isset($post->ID)) {
        $custom = get_post_custom($post->ID);
    }
    if (isset($custom["listing_admin_data"][0])) {
        $listing_type = $custom["listing_admin_data"][0];
    }
    $listing_admin_data = isset($listing_type) ? $listing_type : '';

    echo "<label style=\"border-bottom: 1px solid;\">Listing Admin Data: </label><br><br>
    <input type='date' name='listing_admin_data' value='$listing_admin_data' />
    <br><br>";
}


add_action('admin_footer', 'generate_url_javascript');


// EVENT FIELDS// EVENT FIELDS// EVENT FIELDS// EVENT FIELDS// EVENT FIELDS
// EVENT FIELDS// EVENT FIELDS// EVENT FIELDS// EVENT FIELDS// EVENT FIELDS

// add event date field to events post type
function add_post_listing_event_fields_meta_boxes()
{
    add_meta_box(
        "post_metadata_listing_event_fields_postType_post", // div id containing rendered event
        "Event Fields", //section heading displayed as text
        "post_meta_box_listing_event_fields", // callback function to render event
        "directory_listing", // name of post type on which to render event
        "normal", // location on the screen
        "core" // placement priority
    );
}
add_action("admin_init", "add_post_listing_event_fields_meta_boxes");



// save field value
function save_post_listing_event_fields_meta_boxes()
{
    global $post;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    update_post_meta(isset($post->ID) ? $post->ID : '', "event_date", isset($_POST['event_date']) ? $_POST['event_date'] : '');
    update_post_meta(isset($post->ID) ? $post->ID : '', "event_end", isset($_POST['event_end']) ? $_POST['event_end'] : '');
    update_post_meta(isset($post->ID) ? $post->ID : '', "event_size", isset($_POST['event_size']) ? $_POST['event_size'] : '');
}
add_action('save_post', 'save_post_listing_event_fields_meta_boxes');


// callback function to render event
function post_meta_box_listing_event_fields()
{
    global $post;
    if (isset($post->ID)) {
        $custom = get_post_custom($post->ID);
    }
    if (isset($custom["event_date"][0])) {
        $date = $custom["event_date"][0];
    }
    $Event_Date = isset($date) ? $date : '';

    if (isset($custom["event_end"][0])) {
        $date_end = $custom["event_end"][0];
    }
    $Event_End = isset($date_end) ? $date_end : '';

    if (isset($custom["event_size"][0])) {
        $size = $custom["event_size"][0];
    }
    $Event_Size = isset($size) ? $size : '';

    echo "<label style=\"border-bottom: 1px solid;\">Event Date: </label><br><br><input style=\"width:100%;\" type=\"date\" name=\"event_date\" value=\"$Event_Date\" placeholder=\"Event Date\"><br><br>";

    echo "<label style=\"border-bottom: 1px solid;\">Event End: </label><br><br><input style=\"width:100%;\" type=\"date\" name=\"event_end\" value=\"$Event_End\" placeholder=\"Event End\"><br><br>";

    echo "<label style=\"border-bottom: 1px solid;\">Size: </label><br><br><input style=\"width:100%;\" type=\"text\" name=\"event_size\" value=\"$Event_Size\" placeholder=\"Event Size\"><br><br>";
}

// SERVICE FIELDS// SERVICE FIELDS// SERVICE FIELDS// SERVICE FIELDS// SERVICE FIELDS
// SERVICE FIELDS// SERVICE FIELDS// SERVICE FIELDS// SERVICE FIELDS// SERVICE FIELDS


// add event date field to events post type
function add_post_listing_service_fields_meta_boxes()
{
    add_meta_box(
        "post_metadata_listing_service_fields_postType_post", // div id containing rendered service
        "Service Fields", //section heading displayed as text
        "post_meta_box_listing_service_fields", // callback function to render service
        "directory_listing", // name of post type on which to render service
        "normal", // location on the screen
        "core" // placement priority
    );
}
add_action("admin_init", "add_post_listing_service_fields_meta_boxes");



// save field value
function save_post_listing_service_fields_meta_boxes()
{
    global $post;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // update_post_meta(isset($post->ID) ? $post->ID : '', "listing_service_fields", isset($_POST['listing_service_fields']) ? $_POST['listing_service_fields'] : '');
}
add_action('save_post', 'save_post_listing_service_fields_meta_boxes');


// callback function to render service
function post_meta_box_listing_service_fields()
{
    global $post;
    if (isset($post->ID)) {
        $custom = get_post_custom($post->ID);
    }

    // if (isset($custom["listing_service_fields"][0])) {
    //     $listing_type = $custom["listing_service_fields"][0];
    // }
    // $listing_fields_data = isset($listing_type) ? $listing_type : '';

    echo "<label style=\"border-bottom: 1px solid;\">None Available</label><br><br><br>";
}


// RESTAURANT FIELDS// RESTAURANT FIELDS// RESTAURANT FIELDS// RESTAURANT FIELDS// RESTAURANT FIELDS// RESTAURANT FIELDS
// RESTAURANT FIELDS// RESTAURANT FIELDS// RESTAURANT FIELDS// RESTAURANT FIELDS// RESTAURANT FIELDS// RESTAURANT FIELDS


// add event date field to events post type
function add_post_listing_restaurant_fields_meta_boxes()
{
    add_meta_box(
        "post_metadata_listing_restaurant_fields_postType_post", // div id containing rendered restaurant
        "Restaurant Fields", //section heading displayed as text
        "post_meta_box_listing_restaurant_fields", // callback function to render restaurant
        "directory_listing", // name of post type on which to render restaurant
        "normal", // location on the screen
        "core" // placement priority
    );
}
add_action("admin_init", "add_post_listing_restaurant_fields_meta_boxes");



// save field value
function save_post_listing_restaurant_fields_meta_boxes()
{
    global $post;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    update_post_meta(isset($post->ID) ? $post->ID : '', "restaurant_area", isset($_POST['restaurant_area']) ? $_POST['restaurant_area'] : '');
    update_post_meta(isset($post->ID) ? $post->ID : '', "restaurant_rooms", isset($_POST['restaurant_rooms']) ? $_POST['restaurant_rooms'] : '');
}
add_action('save_post', 'save_post_listing_restaurant_fields_meta_boxes');


// callback function to render restaurant
function post_meta_box_listing_restaurant_fields()
{
    global $post;
    if (isset($post->ID)) {
        $custom = get_post_custom($post->ID);
    }
    if (isset($custom["restaurant_area"][0])) {
        $area = $custom["restaurant_area"][0];
    }
    $Restaurant_Area = isset($area) ? $area : '';

    if (isset($custom["restaurant_rooms"][0])) {
        $rooms = $custom["restaurant_rooms"][0];
    }
    $Restaurant_Rooms = isset($rooms) ? $rooms : '';

    echo "<label style=\"border-bottom: 1px solid;\">Area: </label><br><br><input style=\"width:100%;\" type=\"text\" name=\"restaurant_area\" value=\"$Restaurant_Area\" placeholder=\"Area\"><br><br>";

    echo "<label style=\"border-bottom: 1px solid;\">Rooms: </label><br><br><input style=\"width:100%;\" type=\"text\" name=\"restaurant_rooms\" value=\"$Restaurant_Rooms\" placeholder=\"Rooms\"><br><br>";
}


// CLASSIFIED FIELDS// CLASSIFIED FIELDS// CLASSIFIED FIELDS// CLASSIFIED FIELDS// CLASSIFIED FIELDS// CLASSIFIED FIELDS
// CLASSIFIED FIELDS// CLASSIFIED FIELDS// CLASSIFIED FIELDS// CLASSIFIED FIELDS// CLASSIFIED FIELDS// CLASSIFIED FIELDS


// add event date field to events post type
function add_post_listing_classified_fields_meta_boxes()
{
    add_meta_box(
        "post_metadata_listing_classified_fields_postType_post", // div id containing rendered classified
        "Classified Fields", //section heading displayed as text
        "post_meta_box_listing_classified_fields", // callback function to render classified
        "directory_listing", // name of post type on which to render classified
        "normal", // location on the screen
        "core" // placement priority
    );
}
add_action("admin_init", "add_post_listing_classified_fields_meta_boxes");



// save field value
function save_post_listing_classified_fields_meta_boxes()
{
    global $post;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    update_post_meta(isset($post->ID) ? $post->ID : '', "classified_condition", isset($_POST['classified_condition']) ? $_POST['classified_condition'] : '');
    update_post_meta(isset($post->ID) ? $post->ID : '', "classified_price", isset($_POST['classified_price']) ? $_POST['classified_price'] : '');
    update_post_meta(isset($post->ID) ? $post->ID : '', "classified_model", isset($_POST['classified_model']) ? $_POST['classified_model'] : '');
    update_post_meta(isset($post->ID) ? $post->ID : '', "classified_model_number", isset($_POST['classified_model_number']) ? $_POST['classified_model_number'] : '');
}
add_action('save_post', 'save_post_listing_classified_fields_meta_boxes');


// callback function to render classified
function post_meta_box_listing_classified_fields()
{
    global $post;
    if (isset($post->ID)) {
        $custom = get_post_custom($post->ID);
    }
    if (isset($custom["classified_condition"][0])) {
        $condition = $custom["classified_condition"][0];
    }
    $Classified_Condition = isset($condition) ? $condition : '';

    if (isset($custom["classified_price"][0])) {
        $price_classified = $custom["classified_price"][0];
    }
    $Classified_Price = isset($price_classified) ? $price_classified : '';

    if (isset($custom["classified_model"][0])) {
        $model = $custom["classified_model"][0];
    }
    $Classified_Model = isset($model) ? $model : '';

    if (isset($custom["classified_model_number"][0])) {
        $model_number = $custom["classified_model_number"][0];
    }
    $Classified_Model_Number = isset($model_number) ? $model_number : '';


    if ($Classified_Condition == 'New(text-domain: listeo_core)') {
        echo "<label style=\"border-bottom: 1px solid;\">Condition(text-domain: listeo_core): </label><br><br>
        <select name='classified_condition'>
            <option selected value='New(text-domain: listeo_core)'>New(text-domain: listeo_core)</option>
            <option value='Used(text-domain: listeo_core)'>Used(text-domain: listeo_core)</option>
        </select>
        <br><br>";
    } else if ($Classified_Condition == 'Used(text-domain: listeo_core)') {
        echo "<label style=\"border-bottom: 1px solid;\">Condition(text-domain: listeo_core): </label><br><br>
        <select name='classified_condition'>
            <option value='New(text-domain: listeo_core)'>New(text-domain: listeo_core)</option>
            <option selected value='Used(text-domain: listeo_core)'>Used(text-domain: listeo_core)</option>
        </select>
        <br><br>";
    } else {
        echo "<label style=\"border-bottom: 1px solid;\">Condition(text-domain: listeo_core): </label><br><br>
        <select name='classified_condition'>
            <option value='New(text-domain: listeo_core)'>New(text-domain: listeo_core)</option>
            <option value='Used(text-domain: listeo_core)'>Used(text-domain: listeo_core)</option>
        </select>
        <br><br>";
    }

    echo "<label style=\"border-bottom: 1px solid;\">Price(text-domain: listeo_core): </label><br><br><input style=\"width:100%;\" type=\"text\" name=\"classified_price\" value=\"$Classified_Price\" placeholder=\"Price(text-domain: listeo_core)\"><br><br>";

    echo "<label style=\"border-bottom: 1px solid;\">Model: </label><br><br><input style=\"width:100%;\" type=\"text\" name=\"classified_model\" value=\"$Classified_Model\" placeholder=\"Model\"><br><br>";

    echo "<label style=\"border-bottom: 1px solid;\">Model Number: </label><br><br><input style=\"width:100%;\" type=\"text\" name=\"classified_model_number\" value=\"$Classified_Model_Number\" placeholder=\"Model Number\"><br><br>";
}

// SHOP FIELDS// SHOP FIELDS// SHOP FIELDS// SHOP FIELDS// SHOP FIELDS// SHOP FIELDS
// SHOP FIELDS// SHOP FIELDS// SHOP FIELDS// SHOP FIELDS// SHOP FIELDS// SHOP FIELDS



// add event date field to events post type
function add_post_listing_shop_fields_meta_boxes()
{
    add_meta_box(
        "post_metadata_listing_shop_fields_postType_post", // div id containing rendered shop
        "Shop Fields", //section heading displayed as text
        "post_meta_box_listing_shop_fields", // callback function to render shop
        "directory_listing", // name of post type on which to render shop
        "normal", // location on the screen
        "core" // placement priority
    );
}
add_action("admin_init", "add_post_listing_shop_fields_meta_boxes");



// save field value
function save_post_listing_shop_fields_meta_boxes()
{
    global $post;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // update_post_meta(isset($post->ID) ? $post->ID : '', "listing_shop_fields", isset($_POST['listing_shop_fields']) ? $_POST['listing_shop_fields'] : '');
}
add_action('save_post', 'save_post_listing_shop_fields_meta_boxes');


// callback function to render shop
function post_meta_box_listing_shop_fields()
{
    global $post;
    if (isset($post->ID)) {
        $custom = get_post_custom($post->ID);
    }
    // if (isset($custom["listing_shop_fields"][0])) {
    //     $listing_type = $custom["listing_shop_fields"][0];
    // }
    // $listing_fields_data = isset($listing_type) ? $listing_type : '';

    echo "<label style=\"border-bottom: 1px solid;\">None Available</label><br><br><br>";
}


// PRICE FIELDS// PRICE FIELDS// PRICE FIELDS// PRICE FIELDS// PRICE FIELDS// PRICE FIELDS
// PRICE FIELDS// PRICE FIELDS// PRICE FIELDS// PRICE FIELDS// PRICE FIELDS// PRICE FIELDS



// add event date field to events post type
function add_post_listing_price_fields_meta_boxes()
{
    add_meta_box(
        "post_metadata_listing_price_fields_postType_post", // div id containing rendered price
        "Price Fields", //section heading displayed as text
        "post_meta_box_listing_price_fields", // callback function to render price
        "directory_listing", // name of post type on which to render price
        "normal", // location on the screen
        "core" // placement priority
    );
}
add_action("admin_init", "add_post_listing_price_fields_meta_boxes");



// save field value
function save_post_listing_price_fields_meta_boxes()
{
    global $post;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    update_post_meta(isset($post->ID) ? $post->ID : '', "maximum_price", isset($_POST['maximum_price']) ? $_POST['maximum_price'] : '');
    update_post_meta(isset($post->ID) ? $post->ID : '', "minimum_price", isset($_POST['minimum_price']) ? $_POST['minimum_price'] : '');
}
add_action('save_post', 'save_post_listing_price_fields_meta_boxes');


// callback function to render price
function post_meta_box_listing_price_fields()
{
    global $post;
    if (isset($post->ID)) {
        $custom = get_post_custom($post->ID);
    }
    if (isset($custom["maximum_price"][0])) {
        $max_price = $custom["maximum_price"][0];
    }
    $Maximum_Price = isset($max_price) ? $max_price : '';

    if (isset($custom["minimum_price"][0])) {
        $min_price = $custom["minimum_price"][0];
    }
    $Minimum_Price = isset($min_price) ? $min_price : '';

    echo "<label style=\"border-bottom: 1px solid;\">Maximum Price Range: </label><br><br><input style=\"width:100%;\" type=\"text\" name=\"maximum_price\" value=\"$Maximum_Price\" placeholder=\"Maximum Price Range\"><br><br>";

    echo "<label style=\"border-bottom: 1px solid;\">Minimum Price Range: </label><br><br><input style=\"width:100%;\" type=\"text\" name=\"minimum_price\" value=\"$Minimum_Price\" placeholder=\"Minimum Price Range\"><br><br>";
}



// CONTACT INFORMATION // CONTACT INFORMATION // CONTACT INFORMATION
// CONTACT INFORMATION // CONTACT INFORMATION // CONTACT INFORMATION


// add event date field to events post type
function add_post_contact_meta_boxes()
{
    add_meta_box(
        "post_metadata_contact_postType_post", // div id containing rendered fields
        "Contact Information", // section heading displayed as text
        "post_meta_box_contact_post", // callback function to render fields
        "directory_listing", // name of post type on which to render fields
        "normal", // location on the screen
        "core" // placement priority
    );
}
add_action("admin_init", "add_post_contact_meta_boxes");


// save field value
function save_post_contact_meta_boxes()
{
    global $post;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    update_post_meta(isset($post->ID) ? $post->ID : '', "company_name", isset($_POST['company_name']) ? $_POST['company_name'] : '');

    update_post_meta(isset($post->ID) ? $post->ID : '', "visit_website", isset($_POST['visit_website']) ? $_POST['visit_website'] : '');

    // SOCIAL
    update_post_meta(isset($post->ID) ? $post->ID : '', "social_fb", isset($_POST['social_fb']) ? $_POST['social_fb'] : '');
    update_post_meta(isset($post->ID) ? $post->ID : '', "social_twitter", isset($_POST['social_twitter']) ? $_POST['social_twitter'] : '');
    update_post_meta(isset($post->ID) ? $post->ID : '', "social_linkedin", isset($_POST['social_linkedin']) ? $_POST['social_linkedin'] : '');
    update_post_meta(isset($post->ID) ? $post->ID : '', "social_youtube", isset($_POST['social_youtube']) ? $_POST['social_youtube'] : '');
    update_post_meta(isset($post->ID) ? $post->ID : '', "social_instagram", isset($_POST['social_instagram']) ? $_POST['social_instagram'] : '');
    update_post_meta(isset($post->ID) ? $post->ID : '', "social_pinterest", isset($_POST['social_pinterest']) ? $_POST['social_pinterest'] : '');
    update_post_meta(isset($post->ID) ? $post->ID : '', "phone", isset($_POST['phone']) ? $_POST['phone'] : '');
    update_post_meta(isset($post->ID) ? $post->ID : '', "address", isset($_POST['address']) ? $_POST['address'] : '');
    update_post_meta(isset($post->ID) ? $post->ID : '', "email_address", isset($_POST['email_address']) ? $_POST['email_address'] : '');
    update_post_meta(isset($post->ID) ? $post->ID : '', "location_address", isset($_POST['location_address']) ? $_POST['location_address'] : '');
}
add_action('save_post', 'save_post_contact_meta_boxes');

// callback function to render fields
function post_meta_box_contact_post()
{
    global $post;
    if (isset($post->ID)) {
        $custom = get_post_custom($post->ID);
    }
    if (isset($custom["company_name"][0])) {
        $Company_Name = $custom["company_name"][0];
    }
    $Company = isset($Company_Name) ? $Company_Name : '';

    if (isset($custom["visit_website"][0])) {
        $Website_Visit = $custom["visit_website"][0];
    }
    $Website = isset($Website_Visit) ? $Website_Visit : '';

    // SOCIAL

    if (isset($custom["social_fb"][0])) {
        $FB = $custom["social_fb"][0];
    }
    $Facebook = isset($FB) ? $FB : '';
    if (isset($custom["social_twitter"][0])) {
        $TW = $custom["social_twitter"][0];
    }
    $Twitter = isset($TW) ? $TW : '';
    if (isset($custom["social_linkedin"][0])) {
        $LI = $custom["social_linkedin"][0];
    }
    $LinkedIn = isset($LI) ? $LI : '';
    if (isset($custom["social_youtube"][0])) {
        $YT = $custom["social_youtube"][0];
    }
    $Youtube = isset($YT) ? $YT : '';
    if (isset($custom["social_instagram"][0])) {
        $INSTA = $custom["social_instagram"][0];
    }
    $Instagram = isset($INSTA) ? $INSTA : '';
    if (isset($custom["social_pinterest"][0])) {
        $Pint = $custom["social_pinterest"][0];
    }
    $Pinterest = isset($Pint) ? $Pint : '';
    if (isset($custom["location_address"][0])) {
        $loc = $custom["location_address"][0];
    }
    $Location = isset($loc) ? $loc : '';
    if (isset($custom["address"][0])) {
        $addr = $custom["address"][0];
    }
    $Address = isset($addr) ? $addr : '';

    if (isset($custom["email_address"][0])) {
        $email = $custom["email_address"][0];
    }
    $Email_Address = isset($email) ? $email : '';

    if (isset($custom["phone"][0])) {
        $Phone = $custom["phone"][0];
    }
    $phone_num = isset($Phone) ? $Phone : '';

    echo "<label style=\"border-bottom: 1px solid;\">Company Name: </label><br><br><input style=\"width:100%;\" type=\"tel\" name=\"company_name\" value=\"" . $Company . "\" placeholder=\"Company Name\"><br><br>";

    echo "<label style=\"border-bottom: 1px solid;\">Visit Website: </label><br><br><input style=\"width:100%;\" type=\"tel\" name=\"visit_website\" value=\"" . $Website . "\" placeholder=\"Visit Website\"><br><br>";

    echo "<label style=\"border-bottom: 1px solid;\">Online Social Profiles: </label><br><br>
    <input style=\"margin:4px;\" type=\"tel\" name=\"social_fb\" value=\"" .
        $Facebook . "\" placeholder=\"Facebook\">
    <input style=\"margin:4px;\" type=\"tel\" name=\"social_twitter\" value=\"" . $Twitter . "\" placeholder=\"Twitter\">
    <input style=\"margin:4px;\" type=\"tel\" name=\"social_linkedin\" value=\"" . $LinkedIn . "\" placeholder=\"LinkedIn\">
    <input style=\"margin:4px;\" type=\"tel\" name=\"social_youtube\" value=\"" . $Youtube . "\" placeholder=\"Youtube\">
    <input style=\"margin:4px;\" type=\"tel\" name=\"social_instagram\" value=\"" . $Instagram . "\" placeholder=\"Instagram\">
    <input style=\"margin:4px;\" type=\"tel\" name=\"social_pinterest\" value=\"" . $Pinterest . "\" placeholder=\"Pinterest\"><br><br>";

    echo "<label style=\"border-bottom: 1px solid;\">Phone Number: </label><br><br><input style=\"width:100%;\" type=\"tel\" name=\"phone\" value=\"" . $phone_num . "\" placeholder=\"Phone\"><br><br>";

    echo "<label style=\"border-bottom: 1px solid;\">Address: </label><br><br><input style=\"width:100%;\" type=\"tel\" name=\"address\" id='address' value=\"" . $Address . "\" placeholder=\"Address\"><br><br>";

    echo "<label style=\"border-bottom: 1px solid;\">Email Address: </label><br><br><input style=\"width:100%;\" type=\"tel\" name=\"email_address\" value=\"" . $Email_Address . "\" placeholder=\"Email\"><br><br>";

    echo "<label style=\"border-bottom: 1px solid;\">Location: </label> <br><br><input style=\"width:100%;\" type=\"tel\" name=\"location_address\" id='location_address' value=\"" . $Location . "\" placeholder=\"Location\"><br><br>";
}

// VERIFIED LISTING// VERIFIED LISTING// VERIFIED LISTING
// VERIFIED LISTING// VERIFIED LISTING// VERIFIED LISTING

// add event date field to events post type
function add_post_verify_meta_boxes()
{
    add_meta_box(
        "post_metadata_verify_postType_post", // div id containing rendered fields
        "Verified Listing", // section heading displayed as text
        "post_meta_box_verify", // callback function to render fields
        "directory_listing", // name of post type on which to render fields
        "normal", // location on the screen
        "core" // placement priority
    );
}
add_action("admin_init", "add_post_verify_meta_boxes");

// save field value
function save_post_verify_meta_boxes()
{
    global $post;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    update_post_meta(isset($post->ID) ? $post->ID : '', "check_box_verify", isset($_POST['check_box_verify']) ? $_POST['check_box_verify'] : '');
}
add_action('save_post', 'save_post_verify_meta_boxes');


// callback function to render fields
function post_meta_box_verify()
{
    global $post;
    if (isset($post->ID)) {
        $custom = get_post_custom($post->ID);
    }
    if (isset($custom["check_box_verify"][0])) {
        $Verify = $custom["check_box_verify"][0];
    }
    $Verify_Listing = isset($Verify) ? $Verify : '';


    if ($Verify_Listing == "Verified") {
        echo "
        <div style='padding: 20px;'>
        <input checked type='checkbox' name='verify_listing' id='checkBox'> Tick the checkbox to mark it as Verified
        <input type='hidden' id='check_box_verify' name='check_box_verify' value='Verified' />
        </div>
        ";
    } else {
        echo "
    <div style='padding: 20px;'>
    <input type='checkbox' name='verify_listing' id='checkBox'> Tick the checkbox to mark it as Verified
    <input type='hidden' id='check_box_verify' name='check_box_verify' value='Unverified' />
    </div>
    ";
    }
}

// Featured Listing// Featured Listing// Featured Listing// Featured Listing// Featured Listing
// Featured Listing// Featured Listing// Featured Listing// Featured Listing// Featured Listing

// add event date field to events post type
function add_post_featured_meta_boxes()
{
    add_meta_box(
        "post_metadata_featured_postType_post", // div id containing rendered fields
        "Featured Listing", // section heading displayed as text
        "post_meta_box_featured", // callback function to render fields
        "directory_listing", // name of post type on which to render fields
        "normal", // location on the screen
        "core" // placement priority
    );
}
add_action("admin_init", "add_post_featured_meta_boxes");



// save field value
function save_post_featured_meta_boxes()
{
    global $post;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    update_post_meta(isset($post->ID) ? $post->ID : '', "check_box_featured", isset($_POST['check_box_featured']) ? $_POST['check_box_featured'] : '');
}
add_action('save_post', 'save_post_featured_meta_boxes');


// callback function to render fields
function post_meta_box_featured()
{
    global $post;
    if (isset($post->ID)) {
        $custom = get_post_custom($post->ID);
    }
    if (isset($custom["check_box_featured"][0])) {
        $Featured = $custom["check_box_featured"][0];
    }
    $Featured_Listing = isset($Featured) ? $Featured : '';


    if ($Featured_Listing == 'Featured') {
        echo "
    <div style='padding: 20px;'>
    <input checked type='checkbox' id='featured_checkbox'> Tick the checkbox to make it Featured
    <input type='hidden' id='check_box_featured' name='check_box_featured' value='Featured' />
    
    </div>
    ";
    } else {
        echo "
        <div style='padding: 20px;'>
        <input type='checkbox' id='featured_checkbox'> Tick the checkbox to make it Featured
        <input type='hidden' id='check_box_featured' name='check_box_featured' value='Not Featured' />
        
        </div>
        ";
    }
}
?>

<?php

add_action('wp_footer', 'generate_url_javascript');
add_action('admin_footer', 'generate_url_javascript');

function generate_url_javascript()
{
?>
    <!-- <script>
        jQuery(function($) {
            $('#address').keyup(function() {
                var address = $('#address').val();
                $.ajax({
                    url: "https://maps.googleapis.com/maps/api/geocode/json?address=" + address + "&key=AIzaSyBUOugdibLDtzLCHOxQjHiMxH5ER5cwlek",
                    type: 'GET',
                    dataType: 'json', // added data type
                    success: function(res) {
                        console.log(res);
                        var lattitude = res['results'][0]['geometry']['location']['lat'];
                        var longitude = res['results'][0]['geometry']['location']['lng'];
                        var location = lattitude + ',' + longitude;
                        $('#location_address').val(location);
                    }
                });
            })
        });
    </script> -->
    <!-- <script>
        jQuery(function($) {
            $('#update_address').keyup(function() {
                var address = $('#update_address').val();
                $.ajax({
                    url: "https://maps.googleapis.com/maps/api/geocode/json?address=" + address + "&key=AIzaSyBUOugdibLDtzLCHOxQjHiMxH5ER5cwlek",
                    type: 'GET',
                    dataType: 'json', // added data type
                    success: function(res) {
                        console.log(res);
                        var lattitude = res['results'][0]['geometry']['location']['lat'];
                        var longitude = res['results'][0]['geometry']['location']['lng'];
                        var location = lattitude + ',' + longitude;
                        $('#update_location').val(location);
                    }
                });
            })
        });
    </script> -->
    <script>
        jQuery(function($) {
            $('#checkBox').change(function() {
                if (this.checked) {
                    $('#check_box_verify').val('Verified');
                } else {
                    $('#check_box_verify').val('Unverified');

                }
            });
        });
    </script>
    <script>
        jQuery(function($) {
            $('#featured_checkbox').change(function() {
                if (this.checked) {
                    $('#check_box_featured').val('Featured');
                } else {
                    $('#check_box_featured').val('Not Featured');

                }
            });
        });
    </script>
<?php
}

// Add Products// Add Products// Add Products// Add Products
// Add Products// Add Products// Add Products// Add Products

// // add event date field to events post type
// function add_post_product_meta_boxes()
// {
//     add_meta_box(
//         "post_metadata_product_postType_post", // div id containing rendered fields
//         "Product Information", // section heading displayed as text
//         "post_meta_box_product_post", // callback function to render fields
//         "directory_listing", // name of post type on which to render fields
//         "normal", // location on the screen
//         "core" // placement priority
//     );
// }
// add_action("admin_init", "add_post_product_meta_boxes");

// // save field value
// function save_post_product_meta_boxes()
// {
//     global $post;
//     if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
//         return;
//     }

//     update_post_meta(isset($post->ID) ? $post->ID : '', "product_name", isset($_POST['product_name']) ? $_POST['product_name'] : '');
//     update_post_meta(isset($post->ID) ? $post->ID : '', "product_price", isset($_POST['product_price']) ? $_POST['product_price'] : '');
//     update_post_meta(isset($post->ID) ? $post->ID : '', "product_description", isset($_POST['product_description']) ? $_POST['product_description'] : '');
// }
// add_action('save_post', 'save_post_product_meta_boxes');

// // callback function to render fields
// function post_meta_box_product_post()
// {
//     global $post;
//     if (isset($post->ID)) {
//         $custom = get_post_custom($post->ID);
//     }
//     if (isset($custom["product_name"][0])) {
//         $ProductName = $custom["product_name"][0];
//     }
//     $ProductName = isset($ProductName) ? $ProductName : '';
//     if (isset($custom["product_price"][0])) {
//         $ProductPrice = $custom["product_price"][0];
//     }
//     $ProductPrice = isset($ProductPrice) ? $ProductPrice : '';
//     if (isset($custom["product_description"][0])) {
//         $ProductDesc = $custom["product_description"][0];
//     }
//     $ProductDesc = isset($ProductDesc) ? $ProductDesc : '';
// 
?>
<?php

//     echo "<label style=\"border-bottom: 1px solid;\">Product Name: </label><br><br><input style=\"width:100%;\" type=\"tel\" name=\"product_name\" value=\"" . $ProductName . "\" placeholder=\"Product Name\"><br><br>";
//     echo "<label style=\"border-bottom: 1px solid;\">Product Price: </label><br><br><input style=\"width:100%;\" type=\"tel\" name=\"product_price\" value=\"" . $ProductPrice . "\" placeholder=\"Product Price\"><br><br>";
//     echo "<label style=\"border-bottom: 1px solid;\">Product Description: </label><br><br><textarea rows=\"4\" cols=\"50\" style=\"width:100%;\" type=\"tel\" name=\"product_description\" placeholder=\"Product Description\">$ProductDesc</textarea><br><br>";
// }

// add_action('init', 'user_data_clean');
// function user_data_clean(){
//     $current_user = wp_get_current_user();
//     global $wpdb;
//     global $table_prefix;

//     if($current_user){
//         $query = $wpdb->get_results("SELECT * FROM {$table_prefix}posts WHERE author_id = $current_user->ID");
//         if(isset($query)){
//             foreach($query as $data){
//                 $data-
//             }
//         }
//     }
// }


// ADD ROLES// ADD ROLES// ADD ROLES// ADD ROLES// ADD ROLES// ADD ROLES
// ADD ROLES// ADD ROLES// ADD ROLES// ADD ROLES// ADD ROLES// ADD ROLES


add_action('init', 'sidtechno_add_roles');
function sidtechno_add_roles()
{
    add_role(
        'free',
        __(
            'Free'
        )
    );
    add_role(
        'basic',
        __(
            'Basic'
        )
    );
    add_role(
        'feature',
        __(
            'Feature'
        )
    );
    add_role(
        'premium',
        __(
            'Premium'
        )
    );
}

// GALLERY IMAGES// GALLERY IMAGES// GALLERY IMAGES// GALLERY IMAGES
// GALLERY IMAGES// GALLERY IMAGES// GALLERY IMAGES// GALLERY IMAGES



add_action('admin_init', 'add_post_gallery_so_14445904');
add_action('save_post', 'update_post_gallery_so_14445904', 10, 2);

/**
 * Add custom Meta Box to Posts post type
 */
function add_post_gallery_so_14445904()
{
    add_meta_box(
        'post_gallery',
        'Gallery Images',
        'post_gallery_options_so_14445904',
        'directory_listing', // here you can set post type name
        'normal',
        'core'
    );
}

/**
 * Print the Meta Box content
 */
function post_gallery_options_so_14445904()
{
    global $post;
    $gallery_data = get_post_meta($post->ID, 'gallery_image', true);

    // Use nonce for verification
    wp_nonce_field(plugin_basename(__FILE__), 'noncename_so_14445904');
?>

    <div id="dynamic_form">
        <div class="col-md-6">
            <p class="text-secondary">Choose Your File. Generate File-URL and Insert into Post.</p>
        </div>
        <div id="field_wrap">
            <?php
            if (isset($gallery_data['image_url'])) {
                for ($i = 0; $i < count($gallery_data['image_url']); $i++) {
            ?>

                    <div class="field_row">

                        <div class="field_left">
                            <div class="form_field">
                                <label>Image URL</label>
                                <input type="text" class="meta_image_url" name="gallery[image_url][]" value="<?php esc_html_e($gallery_data['image_url'][$i]); ?>" />
                            </div>
                        </div>

                        <div class="field_right image_wrap">
                            <img src="<?php esc_html_e($gallery_data['image_url'][$i]); ?>" height="48" width="48" />
                        </div>

                        <div class="field_right">
                            <input class="button" type="button" value="Choose File" onclick="add_image(this)" /><br />
                            <input class="button" type="button" value="Remove" onclick="remove_field(this)" />
                        </div>

                        <div class="clear" />
                    </div>
        </div>
<?php
                } // endif
            } // endforeach
?>
    </div>

    <div style="display:none" id="master-row">
        <div class="field_row">
            <div class="field_left">
                <div class="form_field">
                    <label>Image URL</label>
                    <input class="meta_image_url" value="" type="text" name="gallery[image_url][]" />
                </div>
            </div>
            <div class="field_right image_wrap">
            </div>
            <div class="field_right">
                <input type="button" class="button" value="Choose File" onclick="add_image(this)" />
                <br />
                <input class="button" type="button" value="Remove" onclick="remove_field(this)" />
            </div>
            <div class="clear"></div>
        </div>
    </div>

    <div id="add_field_row">
        <input class="button" type="button" value="Add Image" onclick="add_field_row();" />
    </div>

    </div>
    <style type="text/css">
        .field_left {
            float: left;
        }

        .field_right {
            float: left;
            margin-left: 10px;
        }

        .clear {
            clear: both;
        }

        #dynamic_form {
            width: 580px;
        }

        #dynamic_form input[type=text] {
            width: 300px;
        }

        #dynamic_form .field_row {
            border: 1px solid #999;
            margin-bottom: 10px;
            padding: 10px;
        }

        #dynamic_form label {
            padding: 0 6px;
        }
    </style>

    <script type="text/javascript">
        function add_image(obj) {
            var parent = jQuery(obj).parent().parent('div.field_row');
            var inputField = jQuery(parent).find("input.meta_image_url");

            tb_show('', 'media-upload.php?TB_iframe=true');

            window.send_to_editor = function(html) {
                var url = jQuery(html).find('img').attr('src');
                inputField.val(url);
                jQuery(parent)
                    .find("div.image_wrap")
                    .html('<img src="' + url + '" height="48" width="48" />');

                // inputField.closest('p').prev('.awdMetaImage').html('<img height=120 width=120 src="'+url+'"/><p>URL: '+ url + '</p>'); 

                tb_remove();
            };

            return false;
        }

        function remove_field(obj) {
            var parent = jQuery(obj).parent().parent();
            //console.log(parent)
            parent.remove();
        }

        function add_field_row() {
            var row = jQuery('#master-row').html();
            jQuery(row).appendTo('#field_wrap');
        }
    </script>

<?php
}

/**
 * Print styles and scripts
 */

/**
 * Save post action, process fields
 */
function update_post_gallery_so_14445904($post_id, $post_object)
{
    // Doing revision, exit earlier **can be removed**
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return;

    // Doing revision, exit earlier
    if ('revision' == $post_object->post_type)
        return;

    // Verify authenticity
    if (!wp_verify_nonce(isset($_POST['noncename_so_14445904']) ? $_POST['noncename_so_14445904'] : '', plugin_basename(__FILE__)))
        return;

    // Correct post type
    if ('directory_listing' != $_POST['post_type']) // here you can set post type name
        return;

    if ($_POST['gallery']) {
        // Build array for saving post meta
        $gallery_data = array();
        for ($i = 0; $i < count($_POST['gallery']['image_url']); $i++) {
            if ('' != $_POST['gallery']['image_url'][$i]) {
                $gallery_data['image_url'][]  = $_POST['gallery']['image_url'][$i];
            }
        }

        if ($gallery_data)
            update_post_meta($post_id, 'gallery_image', $gallery_data);
        else
            delete_post_meta($post_id, 'gallery_image');
    }
    // Nothing received, all fields are empty, delete option
    else {
        delete_post_meta($post_id, 'gallery_image');
    }
}

// ACTION

add_shortcode('payment_gateway_stripe', 'Stripe_Payment_Form');

function Stripe_Payment_Form()
{
    if (!is_admin() and !wp_is_json_request()) {
        include 'template/stripe.php';
        return $html;
    }
}

add_shortcode('sid_list_packages', 'list_packages');

function list_packages()
{
    if (!is_admin() and !wp_is_json_request()) {
        include 'template/list_packages.php';
        return $html;
    }
}

// DIRECTORY CONFIG// DIRECTORY CONFIG// DIRECTORY CONFIG// DIRECTORY CONFIG
// DIRECTORY CONFIG// DIRECTORY CONFIG// DIRECTORY CONFIG// DIRECTORY CONFIG

function sid_directory_admin_menu()
{
    add_menu_page(
        __('Directory Configuration', 'my-textdomain'),
        __('Directory Configuration', 'my-textdomain'),
        'manage_options',
        'directory-configuration',
        'sid_directory__page_contents',
        'dashicons-schedule',
        3
    );
}

add_action('admin_menu', 'sid_directory_admin_menu');

function sid_directory__page_contents()
{
    include 'admin_template/directory_configuration.php';
}


// add_filter('single_template', 'override_single_template');
// function override_single_template($single_template)
// {
//     global $post;

//     $file = dirname(__FILE__) . '/template/single-directory_listing.php';

//     if (file_exists($file)) $single_template = $file;

//     if ($post->post_type == 'directory_listing') {
//         return $single_template;
//     }
// }

// AJAX SCRAPING// AJAX SCRAPING// AJAX SCRAPING// AJAX SCRAPING// AJAX SCRAPING// AJAX SCRAPING
// AJAX SCRAPING// AJAX SCRAPING// AJAX SCRAPING// AJAX SCRAPING// AJAX SCRAPING// AJAX SCRAPING
// AJAX SCRAPING// AJAX SCRAPING// AJAX SCRAPING// AJAX SCRAPING// AJAX SCRAPING// AJAX SCRAPING


add_action('admin_footer', 'my_action_javascript');

function my_action_javascript()
{
?>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            var map;
            var geocoder;
            var marker;
            var people = new Array();
            var latlng;
            var infowindow;
            $('#placesSearchOutscraperForm, #placesSearchNearbyForm, #placesSearchTextForm').submit(function(e) {

                $('#get-search-data').attr('disabled', true);
                $('#listingsLoaderDatatable_processing').css('display', 'block');
                $('#add_to_list').prop('disabled', true);
                // FORM CHECK
                var form1 = $('#form-check').val();
                var form2 = $('#form-check-1').val();
                var form3 = $('#form-check-2').val();
                var keyword = $('#input_keyword').val();
                var state = $('#input_state').val();
                var city = $('#input_city').val();
                var limit = $('#input_limit').find(":selected").val();
                var category = $('.outscrap_category').find(":selected").val();
                var sub_category = $('.outscrap_subcategory').find(":selected").val();
                // new start

                // Nearby Category
                var nearby_category = $('#nearby_category').find(":selected").val();
                var nearby_subCategory = $('#nearby_subCategory').find(":selected").val();
                var nearby_subCategory_text = $('#nearby_subCategory').find(":selected").text();
                // Text Search Category
                var text_search_category = $('#text_search_category').find(":selected").val();
                var text_search_subCategory = $('#text_search_subCategory').find(":selected").val();
                var text_search_subCategory_text = $('#text_search_subCategory').find(":selected").text();
                // Outscrap Category
                var outscrap_category = $('#outscrap_category').find(":selected").val();
                var outscrap_subCategory = $('#outscrap_subCategory').find(":selected").val();
                var outscrap_subCategory_text = $('#outscrap_subCategory').find(":selected").text();

                // new end
                var n_keyword = $('#nearby_Keywords').val();
                var n_country = $('#nearby_Country').val();
                var n_state = $('#nearby_State').val();
                var n_city = $('#nearby_City').val();
                var n_limit = $('#nearby_Limit').val();
                var t_keyword = $('#text_search_Keywords').val();
                var t_location = $('#text_search_Location').val();
                var t_limit = $('#text_search_Limit').find(":selected").val();
                var existing_record = $('#existing_check').find(":selected").val();
                var auto_create = $('#auto_create_check').find(":selected").val();
                e.preventDefault();
                var data = {
                    'action': 'my_action',
                    'key': keyword ? keyword : "none",
                    'state': state ? state : "none",
                    'city': city ? city : "none",
                    'limit': limit ? limit : "none",
                    'category': category ? category : "none",
                    'sub_category': sub_category ? sub_category : "none",
                    'nearby_category': nearby_category ? nearby_category : "none", //new
                    'nearby_subCategory': nearby_subCategory ? nearby_subCategory : "none", //new
                    'outscrap_category': outscrap_category ? outscrap_category : "none", //new
                    'outscrap_subCategory': outscrap_subCategory ? outscrap_subCategory : "none", //new
                    'text_search_category': text_search_category ? text_search_category : "none", //new
                    'text_search_subCategory': text_search_subCategory ? text_search_subCategory : "none", //new
                    'nearby_subCategory_text': nearby_subCategory_text ? nearby_subCategory_text : "none", //new
                    'outscrap_subCategory_text': outscrap_subCategory_text ? outscrap_subCategory_text : "none", //new
                    'text_search_subCategory_text': text_search_subCategory_text ? text_search_subCategory_text : "none", //new
                    'nearby_key': n_keyword ? n_keyword : "none",
                    'nearby_coutry': n_country ? n_country : "none",
                    'nearby_state': n_state ? n_state : "none",
                    'nearby_city': n_city ? n_city : "none",
                    'nearby_limit': n_limit ? n_limit : "none",
                    'text_search_key': t_keyword ? t_keyword : "none",
                    'text_search_location': t_location ? t_location : "none",
                    'text_search_Limit': t_limit ? t_limit : "none",
                    'form1': form1 ? form1 : "none",
                    'form2': form2 ? form2 : "none",
                    'form3': form3 ? form3 : "none",
                    'ajax': true
                };

                jQuery.post(ajaxurl, data, function(response) {
                    var res = JSON.parse(response);

                    nearby_cat = nearby_category ? nearby_category : "none";
                    nearby_sub = nearby_subCategory ? nearby_subCategory : "none";
                    outscrap_cat = outscrap_category ? outscrap_category : "none";
                    outscrap_sub = outscrap_subCategory ? outscrap_subCategory : "none";
                    text_search_cat = text_search_category ? text_search_category : "none";
                    text_search_sub = text_search_subCategory ? text_search_subCategory : "none";

                    nearby_subCategory_text = nearby_subCategory_text ? nearby_subCategory_text : "none";
                    text_search_subCategory_text = text_search_subCategory_text ? text_search_subCategory_text : "none";
                    outscrap_subCategory_text = outscrap_subCategory_text ? outscrap_subCategory_text : "none";

                    $("#placesSearchNearbyForm")[0].reset();
                    $("#placesSearchTextForm")[0].reset();
                    $("#placesSearchOutscraperForm")[0].reset();
                    var Monday;
                    var Tuesday;
                    var Wednesday;
                    var Thursday;
                    var Friday;
                    var Saturday;
                    var Sunday;

                    if (res != null) {
                        $('#get-search-data').attr('disabled', false);
                        $('#listingsLoaderDatatable_processing').css('display', 'none');
                        $('.odd').css('display', 'none');
                        $('#add_to_list').prop('disabled', false);

                        lat = res.city_lat;
                        lng = res.city_lng;
                        zoomis = 10;

                        var map = new google.maps.Map(document.getElementById('map-canvas'), {
                            zoom: zoomis,
                            center: new google.maps.LatLng(lat, lng),
                            mapTypeId: google.maps.MapTypeId.ROADMAP
                        });

                        for (var index = 0; index < res[0].length; index++) {

                            if (res[0][index].working_hours != null) {
                                Monday = res[0][index].working_hours.Monday ? res[0][index].working_hours.Monday : 'None';
                                Tuesday = res[0][index].working_hours.Tuesday ? res[0][index].working_hours.Tuesday : 'None';
                                Wednesday = res[0][index].working_hours.Wednesday ? res[0][index].working_hours.Wednesday : 'None';
                                Thursday = res[0][index].working_hours.Thursday ? res[0][index].working_hours.Thursday : 'None';
                                Friday = res[0][index].working_hours.Friday ? res[0][index].working_hours.Friday : 'None';
                                Saturday = res[0][index].working_hours.Saturday ? res[0][index].working_hours.Saturday : 'None';
                                Sunday = res[0][index].working_hours.Sunday ? res[0][index].working_hours.Sunday : 'None';
                            } else {
                                Monday = 'None';
                                Tuesday = 'None';
                                Wednesday = 'None';
                                Thursday = 'None';
                                Friday = 'None';
                                Saturday = 'None';
                                Sunday = 'None';
                            }
                            // console.log(res[0][index].reviews);
                            // console.log(res[0][index].reviews_link);
                            // console.log(res[0][index].reviews_id);

                            $('#listing_forms').append('<div id="form' + index + '"></div>')
                            $('#searchData').append('<tr id="data_row_' + index + '" class="text-center"><td><p class="logo" style="display:none;">' + res[0][index].logo + '</p><img class="rounded-circle" alt="Company Logo" src="' + res[0][index].logo + '" /></td> <td class="title">' + res[0][index].owner_title + '</td><td class="description">' + res[0][index].description + '</td> <td><p class="site" style="display:none;">' + res[0][index].site + '</p> <a style="color: blue;" target="_blank" href="' + res[0][index].site + '"><i class="fa fa-globe" aria-hidden="true"></i> Visit</a></td><td class="phone">' + res[0][index].phone + '</td> <td class="nr"><p class="location" style="display:none;">' + res[0][index].location_link + '</p><a style="color: red;" target="_blank" href="' + res[0][index].location_link + '"><i class="fa fa-location-arrow" aria-hidden="true"></i> Go To..</a></td> <td class="working-hours">' + 'Monday: ' + Monday + ' , ' + 'Tuesday: ' + Tuesday + ' , ' + 'Wednesday: ' + Wednesday + ' , ' + 'Thursday: ' + Thursday + ' , ' + 'Friday: ' + Friday + ' , ' + 'Saturday: ' + Saturday + 'Sunday: ' + Sunday + '</td><td class="address">' + res[0][index].full_address + '</td><td class="load_data_main"><div class="load_data_inner"><input type="checkbox" id="review_click_' + index + '" class="review_click" checked name="reviews" value="' + res[0][index].place_id + '" /><label for="review_click_' + index + '">Load Reviews</label></div></td><td><form id="listing_form_' + index + '"  enctype="multipart/form-data"><input hidden type="text" name="logo" id="logo" value="' + res[0][index].logo + '"/><input hidden type="text" name="reviews" class="review_hidden" value="' + res[0][index].place_id + '"/><input hidden type="text" name="company_name" id="company_name" value="' + res[0][index].owner_title + '"/><input hidden type="text" name="description" id="description" value="' + res[0][index].description + '"/><input hidden type="text" name="site" id="site" value="' + res[0][index].site + '"/><input hidden type="text" name="phone" id="phone" value="' + res[0][index].phone + '"/><input hidden type="text" name="location" id="location" value="' + res[0][index].location_link + '"/><input hidden type="text" name="working_hours" id="working_hours" value="' +
                                'Monday: ' + Monday + ' , ' + 'Tuesday: ' + Tuesday + ' , ' + 'Wednesday: ' + Wednesday + ' , ' + 'Thursday: ' + Thursday + ' , ' + 'Friday: ' + Friday + ' , ' + 'Saturday: ' + Saturday + ' , ' + 'Sunday: ' + Sunday +
                                '"/><input hidden type="text" name="address" id="address" value="' + res[0][index].full_address + '"/><input hidden type="text" name="latitude" id="latitude" value="' + res[0][index].latitude + '"/><input hidden type="text" name="longitude" id="longitude" value="' + res[0][index].longitude + '"/><input hidden type="text" name="country_code" id="country_code" value="' + res[0][index].country_code + '"/><input value="' + nearby_cat + '" type="hidden" name="nearby_value" id="nearby_value"><input value="' + nearby_sub + '" type="hidden" name="nearby_subCategory_value" id="nearby_subCategory_value"/><input value="' + outscrap_cat + '" type="hidden" name="outscrap_value" id="outscrap_value"/><input value="' + outscrap_sub + '" type="hidden" name="outscrap_subCategory_value" id="outscrap_subCategory_value"/><input value="' + text_search_cat + '" type="hidden" name="text_search_value" id="text_search_value"/><input value="' + text_search_sub + '" type="hidden" name="text_search_subCategory_value" id="text_search_subCategory_value"/><input type="hidden" name="nearby_text_value" id="nearby_text_value" value="' + nearby_subCategory_text + '"/><input type="hidden" name="text_search_text_value" id="text_search_text_value" value="' + text_search_subCategory_text + '"/><input type="hidden" name="outscrap_text_value" id="outscrap_text_value" value="' + outscrap_subCategory_text + '"/><button name="add_to_list" id="add_to_list' + index + '" type="submit" class="btn btn-success">Add Listing</button></form></td></tr>');

                            geocoder = new google.maps.Geocoder();
                            infowindow = new google.maps.InfoWindow();

                            var lat = parseFloat(res[0][index].latitude);
                            var lng = parseFloat(res[0][index].longitude);
                            var detail = "<div class='marker_main'><div class='marker_inner'><strong>Company Name</strong>: " + res[0][index].owner_title + "</div><div class='marker_inner'><strong>Website</strong>: " + res[0][index].site + "</div><div class='marker_inner'><strong>Phone Number</strong>: " + res[0][index].phone + "</div><div class='marker_inner'><strong>Address</strong>: " + res[0][index].full_address + "</div></div>";

                            latlng = new google.maps.LatLng(lat, lng);
                            marker = new google.maps.Marker({
                                position: latlng,
                                map: map,
                                draggable: false, // cant drag it
                                html: detail // Content display on marker click

                            });

                            google.maps.event.addListener(marker, 'click', function(event) {
                                infowindow.setContent(this.html);
                                infowindow.setPosition(event.latLng);
                                infowindow.open(map, this);
                            });
                        }
                        $(`#searchData tr `).hide();
                        for (let j = 0; j < 5; j++) {
                            $(`#searchData tr:nth-child(${j})`).show();
                        }
                        for (var index = 0; index < res[0].length; index++) {
                            var script = document.createElement("script");
                            script.innerHTML = "jQuery('#listing_form_" + index + "').submit(function () {event.preventDefault();var link= ajaxurl ;var form = jQuery('#listing_form_" + index + "').serialize();var formData = new FormData;formData.append('action', 'add_listing');formData.append('add_listing', form);jQuery.ajax({url: link,data: formData,processData: false,contentType: false,type: 'post',success:function(result){document.getElementById('success').style.display = 'block';setTimeout(function(){document.getElementById('success').style.display = 'none'},5000)}, error: function(result){document.getElementById('failed').style.display = 'block';setTimeout(function(){document.getElementById('failed').style.display = 'none'},5000)}});});";
                            document.head.appendChild(script);
                        }

                    }
                    $('.review_click').change(function() {
                        var thisValue = $(this).val();
                        if (this.checked) {
                            $(this).parents(".load_data_main").next("td").find(".review_hidden").val(thisValue);
                        } else {
                            $(this).parents(".load_data_main").next("td").find(".review_hidden").val("");
                        }
                    });
                });
            });

            $(".outscrap_category").change(function() {
                var thisValue = $(this).find(':selected').attr("data-value");
                var data = {
                    'action': 'sub_category',
                    'category': thisValue,
                    'ajax': true
                };
                sub_category_section = $(this).parents(".category_main").find(".outscrap_subcategory");
                sub_category_section.empty();
                sub_category_section.append(`<option value="">Select Sub Category</option>`);
                jQuery.post(ajaxurl, data, function(response) {
                    var res = JSON.parse(response);
                    // console.log(res.length);
                    for (var index = 0; index < res.length; index++) {
                        sub_category_section.append(`<option value="${res[index].term_id}">${res[index].name}</option>`);
                    }
                });
            });

        });
    </script>
<?php
}

// PROCESS SCRAPING// PROCESS SCRAPING// PROCESS SCRAPING// PROCESS SCRAPING// PROCESS SCRAPING
// PROCESS SCRAPING// PROCESS SCRAPING// PROCESS SCRAPING// PROCESS SCRAPING// PROCESS SCRAPING
// PROCESS SCRAPING// PROCESS SCRAPING// PROCESS SCRAPING// PROCESS SCRAPING// PROCESS SCRAPING

require(plugin_dir_path(__FILE__) . 'admin_template/vendor/autoload.php');

add_action('wp_ajax_my_action', 'sid_my_action');
function sid_my_action()
{
    $form_nearby = $_POST['form1'];
    $form_text = $_POST['form2'];
    $form_outscrape = $_POST['form3'];
    $nearby_key = $_POST['nearby_key'];
    $nearby_coutry = $_POST['nearby_coutry'];
    $nearby_state = $_POST['nearby_state'];
    $nearby_city = $_POST['nearby_city'];
    $nearby_limit = $_POST['nearby_limit'];
    $text_search_key = $_POST['text_search_key'];
    $text_search_location = $_POST['text_search_location'];
    $text_search_limit = $_POST['text_search_Limit'];
    $key = $_POST['key'];
    $state = $_POST['state'];
    $city = $_POST['city'];
    $limit = $_POST['limit'];
    $category = $_POST['category'];
    $sub_category = $_POST['sub_category'];

    // New 
    $nearby_category = $_POST['nearby_category'];

    if ($_POST['nearby_subCategory_text'] != "Select Sub Category") {
        $nearby_subCategory = $_POST['nearby_subCategory_text'];
    } else {
        $nearby_subCategory = "none";
    }

    $text_search_category = $_POST['text_search_category'];

    if ($_POST['text_search_subCategory_text'] != "Select Sub Category") {
        $text_search_subCategory = $_POST['text_search_subCategory_text'];
    } else {
        $text_search_subCategory = "none";
    }

    $outscrap_category = $_POST['outscrap_category'];

    if ($_POST['outscrap_subCategory_text'] != "Select Sub Category") {
        $outscrap_subCategory = $_POST['outscrap_subCategory_text'];
    } else {
        $outscrap_subCategory = "none";
    }

    if ($nearby_key != 'none') {
        $search_nearby_coutry = str_replace(" ", "%20", $nearby_coutry);
        $search_nearby_state = str_replace(" ", "%20", $nearby_state);
        $search_nearby_city = str_replace(" ", "%20", $nearby_city);
        if (!empty($search_nearby_coutry) && !empty($search_nearby_state) && !empty($search_nearby_city)) {
            $address = $search_nearby_coutry . "," . $search_nearby_state . "," . $search_nearby_city;
        } else {
            if (empty($search_nearby_coutry)) {
                $address = $search_nearby_state;
            } else if (empty($search_nearby_state)) {
                $address = $search_nearby_city;
            } else {
                $address = $search_nearby_coutry;
            }
        }
    }

    if ($text_search_key != 'none') {
        $search_text_search_location = str_replace(" ", "%20", $text_search_location);
        if (!empty($search_text_search_location)) {
            $address = $search_text_search_location;
        }
    }

    if ($key != 'none') {
        $search_city = str_replace(" ", "%20", $city);
        $search_state = str_replace(" ", "%20", $state);
        if (!empty($search_city) && !empty($search_state)) {
            $address = $search_city . "," . $search_state;
        } else {
            if (empty($search_city)) {
                $address = $search_state;
            } else {
                $address = $search_city;
            }
        }
    }
    // geocoding api url
    $url = "https://maps.google.com/maps/api/geocode/json?key=AIzaSyBUOugdibLDtzLCHOxQjHiMxH5ER5cwlek&address=" . $address;
    // send api request
    $geocode = file_get_contents($url);
    $json = json_decode($geocode);
    $data['city_lat'] = $json->results[0]->geometry->location->lat;
    $data['city_lng'] = $json->results[0]->geometry->location->lng;

    if ($nearby_key != 'none') {
        $client = new OutscraperClient('Z29vZ2xlLW9hdXRoMnwxMDQ0NzQwNzkzMjMxNTM2MDIyODB8NDMyZWRiOThkNw');
        // With Limit
        $results = $client->google_maps_search([$nearby_key . ' ' . $nearby_category . ' ' . $nearby_subCategory . ' ' . $nearby_state . ' ' . $nearby_city . ' ' . $nearby_coutry], limit: $nearby_limit, language: 'en');

        $results = array_merge($data, $results);

        print_r(wp_json_encode($results));
    } else
    if ($text_search_key != 'none') {
        $client = new OutscraperClient('Z29vZ2xlLW9hdXRoMnwxMDQ0NzQwNzkzMjMxNTM2MDIyODB8NDMyZWRiOThkNw');
        // With Limit
        $results = $client->google_maps_search([$text_search_key . ' ' . $text_search_category . ' ' . $text_search_subCategory . ' ' . $text_search_location], limit: $text_search_limit, language: 'en');
        $results = array_merge($data, $results);
        print_r(wp_json_encode($results));
    } else
    if ($key != 'none') {
        $client = new OutscraperClient('Z29vZ2xlLW9hdXRoMnwxMDQ0NzQwNzkzMjMxNTM2MDIyODB8NDMyZWRiOThkNw');
        // With Limit
        $results = $client->google_maps_search([$key . ' ' . $outscrap_category . ' ' . $outscrap_subCategory . ' ' . $state . ' ' . $city], limit: $limit, language: 'en');

        // var_dump($results);
        // exit;
        $results = array_merge($data, $results);
        print_r(wp_json_encode($results));
    }

    wp_die();
}

add_action('wp_ajax_sub_category', 'sid_sub_category');
function sid_sub_category()
{
    $taxonomy = $_POST['category'];

    $terms = get_terms([
        'taxonomy' => $taxonomy,
        'hide_empty' => false,
    ]);

    print_r(wp_json_encode($terms));
    wp_die();
}




// Add Listing// Add Listing// Add Listing// Add Listing// Add Listing
// Add Listing// Add Listing// Add Listing// Add Listing// Add Listing

add_action('wp_ajax_add_listing', 'ajax_insert_data');

function ajax_insert_data()
{
    $arr = [];
    wp_parse_str($_POST['add_listing'], $arr);
    // $loc = $arr['location'];
    // $whatIWant = substr($loc, strpos($loc, "@") + 1);
    // $variable = substr($whatIWant, 0, strpos($whatIWant, "/"));
    // $location = explode(',', $variable);
    // echo "<pre>";
    // print_r($location);

    // exit();
    global $wpdb;
    global $table_prefix;
    $table = $table_prefix . 'posts';
    $table_meta = $table_prefix . 'postmeta';
    $user = wp_get_current_user();

    // New

    $category = '';
    if ($arr['nearby_value'] != "none") {
        $category = $arr['nearby_value'];
    } else if ($arr['outscrap_value'] != "none") {
        $category = $arr['outscrap_value'];
    } else if ($arr['text_search_value'] != "none") {
        $category = $arr['text_search_value'];
    }

    $sub_category = '';
    if ($arr['nearby_subCategory_value'] != "none") {
        $sub_category = $arr['nearby_subCategory_value'];
    } else if ($arr['outscrap_subCategory_value'] != "none") {
        $sub_category = $arr['outscrap_subCategory_value'];
    } else if ($arr['text_search_subCategory_value'] != "none") {
        $sub_category = $arr['text_search_subCategory_value'];
    }

    // NEW SUBS

    $filter_category = '';
    if ($_POST['nearby_text_value'] != "Select Sub Category") {
        $filter_category .= str_replace(' ', '-', $_POST['nearby_text_value']);
    } else if ($_POST['text_search_text_value'] != "Select Sub Category") {
        $filter_category .= str_replace(' ', '-', $_POST['text_search_text_value']);
    } else if ($_POST['outscrap_text_value'] != "Select Sub Category") {
        $filter_category .= str_replace(' ', '-', $_POST['outscrap_text_value']);
    }

    $str = $arr['company_name'];

    $title = str_replace(array('/\\\\/','\'', '"', ',', ';', '<', '>', '&', '/','—','-','+','%','$','#','@','!','^','*','.',''), ' ', $str);
    $filter = explode(' ', $title);
    $company = '';
    for ($i = 0; $i < count($filter); $i++) {
        $filter_image = stripslashes($filter[$i]);
        $company .= $filter_image;
    }

    $query = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}term_taxonomy WHERE term_id='$sub_category';");

    $post_name = $arr['country_code'] . '-' . $category . '-' . $query->taxonomy . '-' . $company;

    $result_post = $wpdb->insert($table, [
        "post_author" => $user->ID,
        "post_date" => date("Y-m-d H:i:s"),
        "post_date_gmt" => date("Y-m-d H:i:s"),
        "post_modified" => date("Y-m-d H:i:s"),
        "post_modified_gmt" => date("Y-m-d H:i:s"),
        "post_title" => $arr['company_name'],
        "post_content" => $arr['description'],
        "post_status" => 'publish',
        "post_name" => $post_name,
        "post_type" => 'directory_listing'
    ]);
    $lastid = $wpdb->insert_id;
    $table_update = $table_prefix . "posts";
    $GUID = get_site_url() . '?post_type=directory_listing&p=' . $lastid . '&preview=true';
    $data = array(
        'guid' => $GUID
    );
    $where = array(
        'ID' => $lastid,
    );
    $update_key = $wpdb->update($table_update, $data, $where);
    if ($result_post) {
        if (!empty($arr['reviews'])) {
            $place_id = $arr['reviews'];
            $url = "https://maps.googleapis.com/maps/api/place/details/json?key=AIzaSyBUOugdibLDtzLCHOxQjHiMxH5ER5cwlek&placeid=" . $place_id;

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $result = curl_exec($ch);
            $res        = json_decode($result, true);
            print_r($res);
            $reviews    = $res['result']['reviews'];

            foreach ($reviews as $key => $value) {

                $author_name = $value['author_name'];
                $comment = $value['text'];
                $rating = $value['rating'];

                $time = date("Y-m-d H:i:s", substr($value['time'], 0, 10));

                $data = array(
                    'comment_post_ID' => $lastid,
                    'comment_author' => $author_name,
                    'comment_author_email' => 'example@example.com',
                    'comment_author_url' => $arr['site'],
                    'comment_content' => $comment,
                    'user_id' => $user->ID,
                    'comment_date' => $time,
                    'comment_approved' => 1,
                    'comment_type' => 'custom-comment-class'
                );
                $comment_id = wp_insert_comment($data);

                // HERE inserting the rating (an integer from 1 to 5)
                update_comment_meta($comment_id, 'rating', $rating);
            }
        }

        $place_id = $arr['reviews'];
        $url = "https://maps.googleapis.com/maps/api/place/details/json?key=AIzaSyBUOugdibLDtzLCHOxQjHiMxH5ER5cwlek&placeid=" . $place_id;

        $gallery_photo = curl_init();
        curl_setopt($gallery_photo, CURLOPT_URL, $url);
        curl_setopt($gallery_photo, CURLOPT_RETURNTRANSFER, 1);
        $gallery_result = curl_exec($gallery_photo);
        $res_img        = json_decode($gallery_result, true);

        $img = $res_img['result']['photos'];
        $image_keys = [];

        for ($i = 0; $i < count($img); $i++) {
            array_push($image_keys, $res_img['result']['photos'][$i]['photo_reference']);
        }

        print_r($image_keys);

        $result_post_meta_1 = $wpdb->insert($table_meta, [
            "post_id" => $lastid,
            "meta_key" => 'company_name',
            "meta_value" => $arr['company_name']
        ]);
        $result_post_meta_2 = $wpdb->insert($table_meta, [
            "post_id" => $lastid,
            "meta_key" => 'visit_website',
            "meta_value" => $arr['site']
        ]);
        $result_post_meta_3 = $wpdb->insert($table_meta, [
            "post_id" => $lastid,
            "meta_key" => 'phone',
            "meta_value" => $arr['phone']
        ]);

        $result_post_meta_4 = $wpdb->insert($table_meta, [
            "post_id" => $lastid,
            "meta_key" => 'location_address',
            "meta_value" => $arr['latitude'] . ',' . $arr['longitude'],
        ]);
        $result_post_meta_5 = $wpdb->insert($table_meta, [
            "post_id" => $lastid,
            "meta_key" => 'hours_of_opertaion',
            "meta_value" => $arr['working_hours']
        ]);
        $result_post_meta_6 = $wpdb->insert($table_meta, [
            "post_id" => $lastid,
            "meta_key" => 'address',
            "meta_value" => $arr['address']
        ]);
        // NEW
        $result_post_meta_7 = $wpdb->insert($table_meta, [
            "post_id" => $lastid,
            "meta_key" => 'gallery_image',
            "meta_value" => serialize($image_keys)
        ]);

        // EMPTY DATA CODE

        $result_post_meta_9 = $wpdb->insert($table_meta, [
            "post_id" => $lastid,
            "meta_key" => 'annual_sale',
            "meta_value" => 'Not Available'
        ]);
        $result_post_meta_10 = $wpdb->insert($table_meta, [
            "post_id" => $lastid,
            "meta_key" => 'no_of_employees',
            "meta_value" => 'Not Available'
        ]);
        $result_post_meta_11 = $wpdb->insert($table_meta, [
            "post_id" => $lastid,
            "meta_key" => 'accepted_forms_payments',
            "meta_value" => 'Not Available'
        ]);
        $result_post_meta_12 = $wpdb->insert($table_meta, [
            "post_id" => $lastid,
            "meta_key" => 'credentials',
            "meta_value" => 'Not Available'
        ]);
        // SOCIAL
        $result_post_meta_13 = $wpdb->insert($table_meta, [
            "post_id" => $lastid,
            "meta_key" => 'social_fb',
            "meta_value" => 'Not Available'
        ]);
        $result_post_meta_14 = $wpdb->insert($table_meta, [
            "post_id" => $lastid,
            "meta_key" => 'social_twitter',
            "meta_value" => 'Not Available'
        ]);
        $result_post_meta_15 = $wpdb->insert($table_meta, [
            "post_id" => $lastid,
            "meta_key" => 'social_linkedin',
            "meta_value" => 'Not Available'
        ]);
        $result_post_meta_16 = $wpdb->insert($table_meta, [
            "post_id" => $lastid,
            "meta_key" => 'social_youtube',
            "meta_value" => 'Not Available'
        ]);
        $result_post_meta_17 = $wpdb->insert($table_meta, [
            "post_id" => $lastid,
            "meta_key" => 'social_instagram',
            "meta_value" => 'Not Available'
        ]);
        $result_post_meta_18 = $wpdb->insert($table_meta, [
            "post_id" => $lastid,
            "meta_key" => 'social_pinterest',
            "meta_value" => 'Not Available'
        ]);
        $result_post_meta_19 = $wpdb->insert($table_meta, [
            "post_id" => $lastid,
            "meta_key" => 'email_address',
            "meta_value" => 'Not Available'
        ]);
        $result_post_meta_20 = $wpdb->insert($table_meta, [
            "post_id" => $lastid,
            "meta_key" => 'year_established',
            "meta_value" => 'Not Available'
        ]);

        $post_id = $lastid;
        $taxonomy = $category;
        wp_set_object_terms($post_id, intval($sub_category), $taxonomy, true);

        // FEATURE IMAGE WORK

        // only need these if performing outside of admin environment
        require_once(ABSPATH . 'wp-admin/includes/media.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/image.php');

        // featured image
        $image = '';

        $img = $arr['logo'];

        $filter_img = explode('s44-p-k-no-ns-nd/',$img);

        for ($i=0; $i < count($filter_img); $i++) { 
            $image .= $filter_img[$i];
        }

        // magic sideload image returns an HTML image, not an ID
        $media = media_sideload_image($image, $lastid);

        // therefore we must find it so we can set it as featured ID
        if (!empty($media) && !is_wp_error($media)) {
            $args = array(
                'post_type' => 'attachment',
                'posts_per_page' => -1,
                'post_status' => 'any',
                'post_parent' => $lastid
            );

            // reference new image to set as featured
            $attachments = get_posts($args);

            if (isset($attachments) && is_array($attachments)) {
                foreach ($attachments as $attachment) {
                    // grab source of full size images (so no 300x150 nonsense in path)
                    $image = wp_get_attachment_image_src($attachment->ID, 'full');
                    // determine if in the $media image we created, the string of the URL exists
                    if (strpos($media, $image[0]) !== false) {
                        // if so, we found our image. set it as thumbnail
                        set_post_thumbnail($lastid, $attachment->ID);
                        // only want one image
                        break;
                    }
                }
            }
        }


        if ($result_post_meta_1 > 0 && $result_post_meta_2 > 0 && $result_post_meta_3 > 0 && $result_post_meta_4 > 0 && $result_post_meta_5 > 0 && $result_post_meta_6 > 0 && $result_post_meta_7 > 0 && $result_post_meta_8 > 0) {
            wp_send_json_success("Listing Added To Draft Successfully.");
        } else {
            wp_send_json_error("Listing Failed. Please Try Again!");
        }
    }
    // wp_send_json_success("test");
}

add_shortcode('user_dashboard', 'user_dashboard_view');

function user_dashboard_view()
{
    if (!is_admin() and !wp_is_json_request()) {
        include 'template/user_dashboard.php';
        $current_user = wp_get_current_user();
        // print_r($current_user);
        global $wp;
        $url = home_url($wp->request);
        $_redirect = explode("user_dashboard", $url);
        $site_url = $_redirect[0];
        if ($current_user->ID < 1) {
            echo "<script> window.location.href='" . $site_url . "/login'</script>";
        }
    }
}
add_action('wp_ajax_add_directory', 'ajax_add_directory');

function ajax_add_directory()
{
    // wp_send_json_success("TEST");
    $arr = [];
    wp_parse_str($_POST['add_directory'], $arr);
    // echo "<pre>";
    // print_r($arr);
    // echo "</pre><pre>";
    // echo $_POST['directory_desc'];
    $filter_1 = str_replace("<p>", "", $_POST['directory_desc']);
    $filter = str_replace("</p>", "", $filter_1);
    global $wpdb;
    global $table_prefix;
    $user = wp_get_current_user();

    global $post;

    $query = $wpdb->get_row("SELECT * FROM {$table_prefix}user_subscriptions_details WHERE user_id = $user->ID AND status = 'active'");

    if (isset($query)) {
        $package = wp_json_encode($query);
        $package_data = json_decode($package, true);
    }

    $Directory = $wpdb->get_results("SELECT * FROM {$table_prefix}posts WHERE post_author = $user->ID AND post_status = 'publish' OR post_status = 'draft';");
    $directory_limit = count($Directory);

    if ($directory_limit >= 1 && $package_data['package_type'] == "basic") {
        wp_send_json_error("Directory Listing Failed. Limit Reached!");
        exit();
        return false;
    } else if ($directory_limit >= 5 && $package_data['package_type'] == "featured") {
        wp_send_json_error("Directory Listing Failed. Limit Reached!");
        exit();
        return false;
    } else if ($directory_limit >= 20 && $package_data['package_type'] == "premium") {
        wp_send_json_error("Directory Listing Failed. Limit Reached!");
        exit();
        return false;
    } else {
    }
    $post_table = $table_prefix . 'posts';

    $result_post = $wpdb->insert($post_table, [
        "post_author" => $user->ID,
        "post_date" => date("Y-m-d H:i:s"),
        "post_date_gmt" => date("Y-m-d H:i:s"),
        "post_modified" => date("Y-m-d H:i:s"),
        "post_modified_gmt" => date("Y-m-d H:i:s"),
        "post_title" => $arr['d_title'],
        "post_content" => $filter,
        "post_status" => 'draft',
        "post_name" => 'testing-directory',
        "post_type" => 'directory_listing'
    ]);
    $lastid = $wpdb->insert_id;
    $table_update = $table_prefix . "posts";
    $table_meta = $table_prefix . 'postmeta';
    $GUID = get_site_url() . '?post_type=directory_listing&p=' . $lastid . '&preview=true';
    $data = array(
        'guid' => $GUID
    );
    $where = array(
        'ID' => $lastid,
    );
    $update_key = $wpdb->update($table_update, $data, $where);

    if ($result_post) {
        $result_post_meta_1 = $wpdb->insert($table_meta, [
            "post_id" => $lastid,
            "meta_key" => 'company_name',
            "meta_value" => $arr['d_company_name']
        ]);
        $result_post_meta_2 = $wpdb->insert($table_meta, [
            "post_id" => $lastid,
            "meta_key" => 'visit_website',
            "meta_value" => $arr['d_visit_web']
        ]);
        $result_post_meta_3 = $wpdb->insert($table_meta, [
            "post_id" => $lastid,
            "meta_key" => 'phone',
            "meta_value" => $arr['d_phone']
        ]);

        $result_post_meta_4 = $wpdb->insert($table_meta, [
            "post_id" => $lastid,
            "meta_key" => 'location_address',
            "meta_value" => $arr['d_location']
        ]);
        $result_post_meta_5 = $wpdb->insert($table_meta, [
            "post_id" => $lastid,
            "meta_key" => 'hours_of_opertaion',
            "meta_value" => $arr['d_hours_of_operations']
        ]);
        $result_post_meta_6 = $wpdb->insert($table_meta, [
            "post_id" => $lastid,
            "meta_key" => 'address',
            "meta_value" => $arr['d_address']
        ]);
        // awdwadwadadawdadawda
        $result_post_meta_7 = $wpdb->insert($table_meta, [
            "post_id" => $lastid,
            "meta_key" => 'email_address',
            "meta_value" => $arr['d_email']
        ]);
        $result_post_meta_8 = $wpdb->insert($table_meta, [
            "post_id" => $lastid,
            "meta_key" => 'year_established',
            "meta_value" => $arr['d_year_established']
        ]);
        $result_post_meta_9 = $wpdb->insert($table_meta, [
            "post_id" => $lastid,
            "meta_key" => 'annual_sale',
            "meta_value" => $arr['d_annual_sales']
        ]);

        $result_post_meta_10 = $wpdb->insert($table_meta, [
            "post_id" => $lastid,
            "meta_key" => 'no_of_employees',
            "meta_value" => $arr['d_employees']
        ]);
        $result_post_meta_11 = $wpdb->insert($table_meta, [
            "post_id" => $lastid,
            "meta_key" => 'accepted_forms_payments',
            "meta_value" => $arr['d_forms_payment']
        ]);
        $result_post_meta_12 = $wpdb->insert($table_meta, [
            "post_id" => $lastid,
            "meta_key" => 'credentials',
            "meta_value" => $arr['d_creds']
        ]);
        // SOCIAL
        $result_post_meta_13 = $wpdb->insert($table_meta, [
            "post_id" => $lastid,
            "meta_key" => 'social_fb',
            "meta_value" => $arr['d_Facebook']
        ]);
        $result_post_meta_14 = $wpdb->insert($table_meta, [
            "post_id" => $lastid,
            "meta_key" => 'social_twitter',
            "meta_value" => $arr['d_Twitter']
        ]);
        $result_post_meta_15 = $wpdb->insert($table_meta, [
            "post_id" => $lastid,
            "meta_key" => 'social_linkedin',
            "meta_value" => $arr['d_Linkedin']
        ]);
        $result_post_meta_16 = $wpdb->insert($table_meta, [
            "post_id" => $lastid,
            "meta_key" => 'social_youtube',
            "meta_value" => $arr['d_Youtube']
        ]);
        $result_post_meta_17 = $wpdb->insert($table_meta, [
            "post_id" => $lastid,
            "meta_key" => 'social_instagram',
            "meta_value" => $arr['d_Instagram']
        ]);
        $result_post_meta_18 = $wpdb->insert($table_meta, [
            "post_id" => $lastid,
            "meta_key" => 'social_pinterest',
            "meta_value" => $arr['d_Pinterest']
        ]);

        $user_id = $user->ID;

        $query = $wpdb->get_row("SELECT * FROM {$table_prefix}user_subscriptions_details WHERE user_id = $user_id");

        if ($query->package_type == 'featured' || $query->package_type == 'premium') {
            $result_post_meta_19 = $wpdb->insert($table_meta, [
                "post_id" => $lastid,
                "meta_key" => 'check_box_verify',
                "meta_value" => 'Verified'
            ]);
            $result_post_meta_20 = $wpdb->insert($table_meta, [
                "post_id" => $lastid,
                "meta_key" => 'check_box_featured',
                "meta_value" => 'Featured'
            ]);
        }

        $post_id = $lastid;
        $taxonomy = 'Post';
        foreach ($arr['category'] as $cat_data) {
            wp_set_object_terms($post_id, intval($cat_data), $taxonomy, true);
        }

        wp_send_json_success("Directory Listed Successfully.");
    } else {
        wp_send_json_error("Directory Listing Failed. Please Try Again.");
    }
}

// AJAX UPDATE POST// AJAX UPDATE POST// AJAX UPDATE POST
// AJAX UPDATE POST// AJAX UPDATE POST// AJAX UPDATE POST
add_action('wp_footer', 'my_ajax_without_file');

function my_ajax_without_file()
{ ?>

    <script type="text/javascript">
        jQuery(document).ready(function($) {
            jQuery(document).on("click", ".post_update", function(e) {
                e.preventDefault();
                var id = jQuery(this).siblings('#postID').val();
                ajaxurl = '<?php echo admin_url('admin-ajax.php') ?>'; // get ajaxurl
                var data = {
                    'action': 'frontend_action_without_file', // your action name 
                    'ID': id
                };
                e.preventDefault();
                jQuery.ajax({
                    url: ajaxurl, // this will point to admin-ajax.php
                    type: 'POST',
                    data: data,
                    success: function(response) {
                        var ck = JSON.parse(response);
                        console.log(ck);
                        $('#postID_update').val(id);
                        for (let index = 0; index < ck.length; index++) {
                            if (ck[index].meta_key == "company_name") {
                                jQuery('#update_company_name').val(ck[index].meta_value);
                            }
                            if (ck[index] == "post_title") {
                                jQuery('#update_title').val(ck[index].post_title);
                            }
                            if (ck[index].meta_key == "visit_website") {
                                jQuery('#update_visit_web').val(ck[index].meta_value);
                            }
                            if (ck[index].meta_key == "social_fb") {
                                jQuery('#update_Facebook').val(ck[index].meta_value);
                            }
                            if (ck[index].meta_key == "social_twitter") {
                                jQuery('#update_Twitter').val(ck[index].meta_value);
                            }
                            if (ck[index].meta_key == "social_linkedin") {
                                jQuery('#update_Linkedin').val(ck[index].meta_value);
                            }
                            if (ck[index].meta_key == "social_youtube") {
                                jQuery('#update_Youtube').val(ck[index].meta_value);
                            }
                            if (ck[index].meta_key == "social_instagram") {
                                jQuery('#update_Instagram').val(ck[index].meta_value);
                            }
                            if (ck[index].meta_key == "social_pinterest") {
                                jQuery('#update_Pinterest').val(ck[index].meta_value);
                            }
                            if (ck[index].meta_key == "phone") {
                                jQuery('#update_phone').val(ck[index].meta_value);
                            }
                            if (ck[index].meta_key == "address") {
                                jQuery('#update_address').val(ck[index].meta_value);
                            }
                            if (ck[index].meta_key == "email_address") {
                                jQuery('#update_email').val(ck[index].meta_value);
                            }
                            if (ck[index].meta_key == "location_address") {
                                jQuery('#update_location').val(ck[index].meta_value);
                            }
                            if (ck[index].meta_key == "year_established") {
                                jQuery('#update_year_established').val(ck[index].meta_value);
                            }
                            if (ck[index].meta_key == "annual_sale") {
                                jQuery('#update_annual_sales').val(ck[index].meta_value);
                            }
                            if (ck[index].meta_key == "no_of_employees") {
                                jQuery('#update_employees').val(ck[index].meta_value);
                            }
                            if (ck[index].meta_key == "hours_of_opertaion") {
                                jQuery('#update_hours_of_operations').val(ck[index].meta_value);
                            }
                            if (ck[index].meta_key == "accepted_forms_payments") {
                                jQuery('#update_forms_payment').val(ck[index].meta_value);
                            }
                            if (ck[index].meta_key == "credentials") {
                                jQuery('#update_creds').val(ck[index].meta_value);
                            }

                            jQuery('#postSTATUS_update').val(ck[index].post_status);
                            jQuery('#postNAME_update').val(ck[index].post_name);
                            jQuery('#postTYPE_update').val(ck[index].post_type);

                            jQuery('#update_title').val(ck[index].post_title);
                            jQuery(".summernote").summernote("code", ck[index].post_content);
                            jQuery('.note-editable p').text(ck[index].post_content);
                            jQuery('.note-editable p').css('color', '#666');
                        }
                    }
                });
            });
        });
    </script>
<?php
}

add_action("wp_ajax_frontend_action_without_file", "frontend_action_without_file");
add_action("wp_ajax_nopriv_frontend_action_without_file", "frontend_action_without_file");

function frontend_action_without_file()
{
    $data = json_encode($_POST);
    $decode = json_decode($data, true);
    // print_r($decode['ID']);

    global $wpdb;
    global $table_prefix;
    $table_update = $table_prefix . "posts";
    $table_meta = $table_prefix . 'postmeta';
    $post_id = $decode['ID'];
    $getResults = $wpdb->get_results("SELECT $table_update.* , $table_meta.*
    FROM $table_update
    INNER JOIN $table_meta
    ON $table_update.ID = $table_meta.post_id
    WHERE $table_meta.post_id = '$post_id'");

    $query_posts = $wpdb->get_row($wpdb->prepare("SELECT * FROM $wpdb->posts WHERE ID = %d", $decode['ID']));
    $query_post_meta = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->postmeta WHERE post_id = " . $decode['ID'] . " "));

    // print_r(wp_json_encode($query_post_meta));
    print_r(wp_json_encode($getResults));

    wp_die();
}


// AJAX UPDATE POST DATA// AJAX UPDATE POST DATA// AJAX UPDATE POST DATA
// AJAX UPDATE POST DATA// AJAX UPDATE POST DATA// AJAX UPDATE POST DATA

add_action('wp_footer', 'ajax_update_directory');

function ajax_update_directory()
{ ?>

    <script type="text/javascript">
        jQuery(document).ready(function($) {
            jQuery(document).on("click", "#get_update", function(e) {
                e.preventDefault();
                ajaxurl = '<?php echo admin_url('admin-ajax.php') ?>'; // get ajaxurl
                var postID_update = $('#postID_update').val();
                var postSTATUS_update = $('#postSTATUS_update').val();
                var postNAME_update = $('#postNAME_update').val();
                var postTYPE_update = $('#postTYPE_update').val();
                var update_title = $('#update_title').val();
                var update_desc = $('.note-editable').text();
                var update_company_name = $('#update_company_name').val();
                var update_visit_web = $('#update_visit_web').val();
                var update_Facebook = $('#update_Facebook').val();
                var update_Twitter = $('#update_Twitter').val();
                var update_Linkedin = $('#update_Linkedin').val();
                var update_Youtube = $('#update_Youtube').val();
                var update_Instagram = $('#update_Instagram').val();
                var update_Pinterest = $('#update_Pinterest').val();
                var update_phone = $('#update_phone').val();
                var update_address = $('#update_address').val();
                var update_email = $('#update_email').val();
                var update_location = $('#update_location').val();
                var update_year_established = $('#update_year_established').val();
                var update_annual_sales = $('#update_annual_sales').val();
                var update_employees = $('#update_employees').val();
                var update_hours_of_operations = $('#update_hours_of_operations').val();
                var update_forms_payment = $('#update_forms_payment').val();
                var update_creds = $('#update_creds').val();
                var data = {
                    'action': 'frontend_action_update_directory', // your action name 
                    'ID': postID_update,
                    'update_title': update_title,
                    'update_desc': update_desc,
                    'update_company_name': update_company_name,
                    'update_visit_web': update_visit_web,
                    'update_Facebook': update_Facebook,
                    'update_Twitter': update_Twitter,
                    'update_Linkedin': update_Linkedin,
                    'update_Youtube': update_Youtube,
                    'update_Instagram': update_Instagram,
                    'update_Pinterest': update_Pinterest,
                    'update_phone': update_phone,
                    'update_address': update_address,
                    'update_email': update_email,
                    'update_location': update_location,
                    'update_year_established': update_year_established,
                    'update_annual_sales': update_annual_sales,
                    'update_employees': update_employees,
                    'update_hours_of_operations': update_hours_of_operations,
                    'update_forms_payment': update_forms_payment,
                    'update_creds': update_creds,
                    'postSTATUS_update': postSTATUS_update,
                    'postNAME_update': postNAME_update,
                    'postTYPE_update': postTYPE_update,
                };
                e.preventDefault();
                jQuery.ajax({
                    url: ajaxurl, // this will point to admin-ajax.php
                    type: 'POST',
                    data: data,
                    success: function(response) {
                        $(".alert").show('medium');
                        setTimeout(function() {
                            $(".alert").hide('medium');
                        }, 5000);
                        $('#update_post_form')[0].reset();
                        $('#update_directory_body').css('display', 'none');
                        $('#my_directory_body').css('display', 'block');
                    }
                });
            });
        });
    </script>
<?php
}

add_action("wp_ajax_frontend_action_update_directory", "frontend_action_update_directory");
add_action("wp_ajax_nopriv_frontend_action_update_directory", "frontend_action_update_directory");

function frontend_action_update_directory()
{
    $data = json_encode($_POST);
    $decode = json_decode($data, true);
    // echo "<pre>";
    // print_r($decode);

    global $wpdb;
    global $table_prefix;
    $table_update = $table_prefix . "posts";
    $table_meta = $table_prefix . "postmeta";
    $user = wp_get_current_user();
    $post_id = $decode['ID'];

    // POST UPDATE DATA
    $data = array(
        "post_author" => $user->ID,
        "post_date" => date("Y-m-d H:i:s"),
        "post_date_gmt" => date("Y-m-d H:i:s"),
        "post_modified" => date("Y-m-d H:i:s"),
        "post_modified_gmt" => date("Y-m-d H:i:s"),
        "post_title" => $decode['update_title'],
        "post_content" => $decode['update_desc'],
        "post_status" => $decode['postSTATUS_update'],
        "post_name" => $decode['postNAME_update'],
        "post_type" => $decode['postTYPE_update'],
    );
    $where = array(
        'ID' => $decode['ID'],
    );
    $update_key = $wpdb->update($table_update, $data, $where);

    // Company Name

    $where_meta_company = array(
        'post_id' => $decode['ID'],
        "meta_key" => 'company_name',
    );
    $result_post_meta_1 = $wpdb->update($table_meta, [
        "meta_value" => $decode['update_company_name']
    ], $where_meta_company);

    // Visit Website

    $where_meta_website = array(
        'post_id' => $decode['ID'],
        "meta_key" => 'visit_website',
    );
    $result_post_meta_2 = $wpdb->update($table_meta, [
        "meta_value" => $decode['update_visit_web']
    ], $where_meta_website);

    // Contact Number

    $where_meta_phone = array(
        'post_id' => $decode['ID'],
        "meta_key" => 'phone',
    );
    $result_post_meta_3 = $wpdb->update($table_meta, [
        "meta_value" => $decode['update_phone']
    ], $where_meta_phone);

    // Location Address

    $where_meta_location = array(
        'post_id' => $decode['ID'],
        "meta_key" => 'location_address',
    );
    $result_post_meta_4 = $wpdb->update($table_meta, [
        "meta_value" => $decode['update_location']
    ], $where_meta_location);

    // Working Hours

    $where_meta_working_hours = array(
        'post_id' => $decode['ID'],
        "meta_key" => 'hours_of_opertaion',
    );

    $result_post_meta_5 = $wpdb->update($table_meta, [
        "meta_value" => $decode['update_hours_of_operations']
    ], $where_meta_working_hours);

    // Address

    $where_meta_address = array(
        'post_id' => $decode['ID'],
        "meta_key" => 'address',
    );

    $result_post_meta_6 = $wpdb->update($table_meta, [
        "meta_value" => $decode['update_address']
    ], $where_meta_address);

    // Email Address

    $where_meta_email_address = array(
        'post_id' => $decode['ID'],
        "meta_key" => 'email_address',
    );

    $result_post_meta_7 = $wpdb->update($table_meta, [
        "meta_value" => $decode['update_email']
    ], $where_meta_email_address);

    // Year Established

    $where_meta_year_established = array(
        'post_id' => $decode['ID'],
        "meta_key" => 'year_established',
    );

    $result_post_meta_8 = $wpdb->update($table_meta, [
        "meta_value" => $decode['update_year_established']
    ], $where_meta_year_established);

    // Annual Sales

    $where_meta_annual_sale = array(
        'post_id' => $decode['ID'],
        "meta_key" => 'annual_sale',
    );

    $result_post_meta_9 = $wpdb->update($table_meta, [
        "meta_value" => $decode['update_annual_sales']
    ], $where_meta_annual_sale);

    // Employees

    $where_meta_no_of_employees = array(
        'post_id' => $decode['ID'],
        "meta_key" => 'no_of_employees',
    );

    $result_post_meta_10 = $wpdb->update($table_meta, [
        "meta_value" => $decode['update_employees']
    ], $where_meta_no_of_employees);

    // Accepted Forms Payment

    $where_meta_accepted_forms_payments = array(
        'post_id' => $decode['ID'],
        "meta_key" => 'accepted_forms_payments',
    );

    $result_post_meta_11 = $wpdb->update($table_meta, [
        "meta_value" => $decode['update_forms_payment']
    ], $where_meta_accepted_forms_payments);

    // Credentials

    $where_meta_credentials = array(
        'post_id' => $decode['ID'],
        "meta_key" => 'credentials',
    );

    $result_post_meta_12 = $wpdb->update($table_meta, [
        "meta_value" => $decode['update_creds']
    ], $where_meta_credentials);

    // Social Facebook

    $where_meta_social_fb = array(
        'post_id' => $decode['ID'],
        "meta_key" => 'social_fb',
    );

    $result_post_meta_13 = $wpdb->update($table_meta, [
        "meta_value" => $decode['update_Facebook']
    ], $where_meta_social_fb);

    // Social Twitter

    $where_meta_social_twitter = array(
        'post_id' => $decode['ID'],
        "meta_key" => 'social_twitter',
    );

    $result_post_meta_14 = $wpdb->update($table_meta, [
        "meta_value" => $decode['update_Twitter']
    ], $where_meta_social_twitter);

    // Social LinkedIn

    $where_meta_social_linkedin = array(
        'post_id' => $decode['ID'],
        "meta_key" => 'social_linkedin',
    );

    $result_post_meta_15 = $wpdb->update($table_meta, [
        "meta_value" => $decode['update_Linkedin']
    ], $where_meta_social_linkedin);

    // Social Youtube

    $where_meta_social_youtube = array(
        'post_id' => $decode['ID'],
        "meta_key" => 'social_youtube',
    );

    $result_post_meta_16 = $wpdb->update($table_meta, [
        "meta_value" => $decode['update_Youtube']
    ], $where_meta_social_youtube);

    // Social Instagram

    $where_meta_social_instagram = array(
        'post_id' => $decode['ID'],
        "meta_key" => 'social_instagram',
    );

    $result_post_meta_17 = $wpdb->update($table_meta, [
        "meta_value" => $decode['update_Instagram']
    ], $where_meta_social_instagram);

    // Social Pinterest

    $where_meta_social_pinterest = array(
        'post_id' => $decode['ID'],
        "meta_key" => 'social_pinterest',
    );

    $result_post_meta_18 = $wpdb->update($table_meta, [
        "meta_value" => $decode['update_Pinterest']
    ], $where_meta_social_pinterest);


    if ($result_post_meta_1 || $result_post_meta_2 || $result_post_meta_3 || $result_post_meta_4 || $result_post_meta_5 || $result_post_meta_6 || $result_post_meta_7 || $result_post_meta_8 || $result_post_meta_9 || $result_post_meta_10 || $result_post_meta_11 || $result_post_meta_12 || $result_post_meta_13 || $result_post_meta_14 || $result_post_meta_15 || $result_post_meta_16 || $result_post_meta_17 || $result_post_meta_18 ||  $update_key) {
        echo "Directory Updated Successfully.";
    } else {
        echo "Directory Update Failed. Please Try Again.";
    }

    wp_die();
}


// USER PROFILE// USER PROFILE// USER PROFILE// USER PROFILE// USER PROFILE
// USER PROFILE// USER PROFILE// USER PROFILE// USER PROFILE// USER PROFILE


add_action('wp_ajax_profile_update', 'ajax_profile_update');

function ajax_profile_update()
{
    // wp_send_json_success("TEST");
    $arr = [];
    wp_parse_str($_POST['profile_update'], $arr);
    // echo "<pre>";
    // print_r($arr);
    global $wpdb;
    global $table_prefix;
    $user_table = $table_prefix . 'users';
    $user_meta_table = $table_prefix . 'usermeta';
    $user = wp_get_current_user();
    $user_id = $user->ID;

    if (!empty($arr['email'])) {
        wp_update_user(array('ID' => $user_id, 'user_email' => $arr['email']));
        echo "EMAIL UPDATED";
    } else {
        echo "EMAIL FAIL";
    }

    // ADDRESS
    if (!empty($arr['address'])) {
        update_user_meta($user_id, 'user_address',  $arr['address']);
        echo "ADDRESS UPDATED";
    } else {
        echo "ADDRESS FAIL";
    }

    // FIRS NAME
    if (!empty($arr['first_name'])) {
        update_user_meta($user_id, 'first_name',  $arr['first_name']);
        echo "ADDRESS UPDATED";
    } else {
        echo "ADDRESS FAIL";
    }

    // LAST NAME
    if (!empty($arr['last_name'])) {
        update_user_meta($user_id, 'last_name',  $arr['last_name']);
        echo "ADDRESS UPDATED";
    } else {
        echo "ADDRESS FAIL";
    }

    // CONTACT
    if (!empty($arr['contact'])) {
        update_user_meta($user_id, 'user_contact',  $arr['contact']);
        echo "ADDRESS UPDATED";
    } else {
        echo "ADDRESS FAIL";
    }


    wp_die();
}


add_action('show_user_profile', 'crf_show_extra_profile_fields');
add_action('edit_user_profile', 'crf_show_extra_profile_fields');
add_action("user_new_form", "crf_show_extra_profile_fields");
function crf_show_extra_profile_fields($user)
{
    $u_address = get_the_author_meta('user_address', isset($user->ID) ? $user->ID : '');
?>
    <h3><?php esc_html_e('Personal Information', 'crf'); ?></h3>

    <table class="form-table">
        <tr>
            <th><label for="user_address"><?php esc_html_e('Address', 'crf'); ?></label></th>
            <td>
                <input type="text" id="user_address" name="user_address" value="<?php echo esc_attr($u_address); ?>" class="regular-text" />
            </td>
        </tr>
    </table>
    <?php
    $contact = get_the_author_meta('user_contact', isset($user->ID) ? $user->ID : '');
    ?>
    <table class="form-table">
        <tr>
            <th><label for="user_contact"><?php esc_html_e('Contact Number', 'crf'); ?></label></th>
            <td>
                <input type="text" id="user_contact" name="user_contact" value="<?php echo esc_attr($contact); ?>" class="regular-text" />
            </td>
        </tr>
    </table>
<?php
}

add_action('user_profile_update_errors', 'crf_user_profile_update_errors', 10, 3);
function crf_user_profile_update_errors($errors, $update, $user)
{
    if (empty($_POST['user_address'])) {
        $errors->add('user_address_error', __('<strong>ERROR</strong>: Please enter your address.', 'crf'));
    }
    if (empty($_POST['user_contact'])) {
        $errors->add('user_contact_error', __('<strong>ERROR</strong>: Please enter your contact number.', 'crf'));
    }
}


add_action('personal_options_update', 'crf_update_profile_fields');
add_action('edit_user_profile_update', 'crf_update_profile_fields');

function crf_update_profile_fields($user_id)
{
    if (!current_user_can('edit_user', $user_id)) {
        return false;
    }

    if (!empty($_POST['user_address'])) {
        update_user_meta($user_id, 'user_address',  $_POST['user_address']);
    }
    if (!empty($_POST['user_contact'])) {
        update_user_meta($user_id, 'user_contact',  $_POST['user_contact']);
    }
}

// PROFILE DATA AJAX// PROFILE DATA AJAX// PROFILE DATA AJAX// PROFILE DATA AJAX
// PROFILE DATA AJAX// PROFILE DATA AJAX// PROFILE DATA AJAX// PROFILE DATA AJAX

add_action('wp_footer', 'profile_data_javascript');

function profile_data_javascript()
{
?>
    <script>
        jQuery(document).ready(function($) {
            $('#profile_details #edit').click(function(e) {
                var id = jQuery(this).siblings('#c_user').val();

                ajaxurl = '<?php echo admin_url('admin-ajax.php') ?>'; // get ajaxurl
                var data = {
                    'action': 'profile_update_action', // your action name 
                    'ID': id
                };
                e.preventDefault();
                jQuery.ajax({
                    url: ajaxurl, // this will point to admin-ajax.php
                    type: 'POST',
                    data: data,
                    success: function(response) {
                        var ck = JSON.parse(response);
                        console.log(ck.fname);
                        $('#first_name').text(ck.fname);
                        $('#last_name').text(ck.lname);
                        $('#contact').text(ck.contact);
                        $('#address').text(ck.address);
                        $('#email').text(ck.email);
                    }
                });
            });
        });
    </script>
<?php
}



add_action("wp_ajax_profile_update_action", "profile_update_action");
add_action("wp_ajax_nopriv_profile_update_action", "profile_update_action");

function profile_update_action()
{
    $data = json_encode($_POST);
    $decode = json_decode($data, true);
    // print_r($decode['ID']);

    $current_user = wp_get_current_user();
    $email = $current_user->user_email;
    $first_name = get_user_meta($current_user->ID, 'first_name', true);
    $last_name = get_user_meta($current_user->ID, 'last_name', true);
    $address = get_user_meta($current_user->ID, 'user_address', true);
    $contact = get_user_meta($current_user->ID, 'user_contact', true);

    $userDATA = array(
        'email' => $email,
        'fname' => $first_name,
        'lname' => $last_name,
        'address' => $address,
        'contact' => $contact
    );
    // $Directory[0]->ID
    print_r(wp_json_encode($userDATA));

    wp_die();
}


// GET DIRECTORY AJAX// GET DIRECTORY AJAX// GET DIRECTORY AJAX// GET DIRECTORY AJAX
// GET DIRECTORY AJAX// GET DIRECTORY AJAX// GET DIRECTORY AJAX// GET DIRECTORY AJAX


add_action('wp_footer', 'directory_data_javascript');

function directory_data_javascript()
{
?>
    <script>
        jQuery(document).ready(function($) {
            $('#my_directory #view_directory').click(function(e) {
                var id = jQuery(this).siblings('#current_user').val();

                ajaxurl = '<?php echo admin_url('admin-ajax.php') ?>'; // get ajaxurl
                var data = {
                    'action': 'my_directory_action', // your action name 
                    'ID': id
                };
                e.preventDefault();
                jQuery.ajax({
                    url: ajaxurl, // this will point to admin-ajax.php
                    type: 'POST',
                    data: data,
                    success: function(response) {
                        var ck = JSON.parse(response);
                        // console.log(ck);
                        for (let index = 0; index < ck.length; index++) {
                            var c_name = ck[index].post_id;
                            $('#directory_data').append('<tr><td>' + ck[index].post_title + '</td><td>' + ck[index].post_status + '</td><td>' + ck[index].comment_count + '</td><td><span style="color: gray;">Last Modified:</span> <br>' + ck[index].post_modified + '</td><td><div class="row"><div class="col-md-5"><a href="phpurl" target="_blank" class="btn btn-primary">View</a></div>                <div class="col-md-7"><form class="post_id_form" id="post_id_form' + ck[index].ID + '"><input type="hidden" name="postID" id="postID" value="' + ck[index].ID + '"><button type="submit" id="update_post" name="update_post" class="btn btn-success post_update">Update</button></form></div></div></td></tr>')
                        }
                        $("#directory_data tr").hide();
                        for (let i = 0; i < 5; i++) {
                            $(`#directory_data tr:nth-child(${i})`).show();
                        }
                    }
                });
            });
        });
    </script>
<?php
}


add_action("wp_ajax_my_directory_action", "my_directory_action");
add_action("wp_ajax_nopriv_my_directory_action", "my_directory_action");

function my_directory_action()
{
    $data = json_encode($_POST);
    $decode = json_decode($data, true);
    // print_r($decode['ID']);

    global $wpdb;
    global $table_prefix;
    global $post;
    $user = wp_get_current_user();
    $Directory = $wpdb->get_results("SELECT * FROM {$table_prefix}posts WHERE post_author = $user->ID AND post_status = 'publish' OR post_status = 'draft';");

    $arr_data = [];

    foreach ($Directory as $data) {
        $post_id = $data->ID;
        $company_name = get_post_meta($post_id, 'company_name', true);
        $contact = get_post_meta($post_id, 'phone', true);
        $url = get_site_url() . '?post_type=directory_listing&p=' . $post_id . '&preview=true';

        $dd = array(
            'post_id' => $post_id,
            'url' => $url,
            'contact' => $contact,
            'company' => $company_name
        );

        array_push($Directory, $dd);
        // array_push($arr_data,$contact);

    }

    $merged_data = array_merge($Directory, $arr_data);
    // print_r($merged_data);

    // echo gettype($Directory);
    print_r(wp_json_encode($Directory));

    wp_die();
}

add_action('admin_init', 'add_option_ad');
function add_option_ad()
{
    if (isset($_POST['submit_ad'])) {
        $text = stripslashes($_POST['ad_text']);
        return update_option("ad_url", "$text");
    }
}

add_action('admin_init', 'add_api_option_ad');
function add_api_option_ad()
{
    if (isset($_POST['submit_configuration_form'])) {

        // $my_options = array(
        //     'google_api_key' => $_POST['google_api_key'],
        //     'yelp_api_key' => $_POST['yelp_api_key'],
        //     'outscraper_api_key' => $_POST['outscraper_api_key'],
        //     'default_membership_level' => $_POST['default_membership_level'],
        // );
        // return update_option('test_value',$my_options);

        if (isset($_POST['google_yelp_listings__google_api_key'])) {
            $google_api_key = $_POST['google_yelp_listings__google_api_key'];
            update_option("google_api_key", "$google_api_key");
        }
        if (isset($_POST['google_yelp_listings__yelp_api_key'])) {
            $yelp_api_key = $_POST['google_yelp_listings__yelp_api_key'];
            update_option("yelp_api_key", "$yelp_api_key");
        }
        if (isset($_POST['google_yelp_listings__outscraper_api_key'])) {
            $outscraper_api_key = $_POST['google_yelp_listings__outscraper_api_key'];
            update_option("outscraper_api_key", "$outscraper_api_key");
        }
        if (isset($_POST['google_yelp_listings__default_membership_level'])) {
            $default_membership_level = $_POST['google_yelp_listings__default_membership_level'];
            update_option("default_membership_level", "$default_membership_level");
        }
    }
}

include 'saad_functions.php';
include 'ahmed_functions.php';
