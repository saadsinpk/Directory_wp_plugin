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
add_action('init', 'process_login');

function process_login()
{
    global $wpdb;
    if (isset($_POST['login_directory'])) {
        $username = $_POST['login_username'];
        $pass = $_POST['login_password'];
        if (isset($_POST['token'])) {
            $captcha_token = $_POST['token'];
        }

        $userDetails = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}users WHERE user_email='$username'");

        if (isset($userDetails->user_login)) {

            $creds = array(
                'user_login'    => $userDetails->user_login,
                'user_password' => $pass,
                'remember'      => true
            );

            $user = wp_signon($creds, false);
            if (is_wp_error($user)) {
                // $msg = $user->get_error_message();
                $_SESSION['error'] = "Invalid Username/Password";
            } else {
                wp_clear_auth_cookie();
                wp_set_current_user($user->ID); // Set the current user detail
                wp_set_auth_cookie($user->ID); // Set auth details in cookie
                $_SESSION['success'] = "LOGIN SUCCESSFULL";
            }
        } else {
            $_SESSION['error'] = "Invalid Username/Password";
        }
    }
}

// ACTION
add_shortcode('directory_login', 'login_page_view');

function login_page_view()
{
    include 'template/login.php';
    return $html;
}

// REGISTER ACTION/PROCESS// REGISTER// REGISTER// REGISTER
// REGISTER ACTION/PROCESS// REGISTER// REGISTER// REGISTER

// PROCESS
add_action('init', 'process_register');

function process_register()
{
    global $wpdb;
    if (isset($_POST['reg_directory'])) {
        if (isset($_POST['token'])) {
            $captcha_token = $_POST['token'];
        }
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
                $wp_get_user->add_role('subscribe');
                $msg = "Registration successfull.";
                $_SESSION['success'] = $msg;
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
    include 'template/register.php';
    return $html;
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
            $randomKey = $userDetails->ID . '_' . substr(str_shuffle($permitted_chars), 0, 20);
            $randomPass = $userDetails->ID . '_' . substr(str_shuffle($permitted_chars), 0, 10);

            $table = "{$wpdb->prefix}users";
            $data = array(
                'user_activation_key' => $randomKey,
                'user_pass' => $randomPass,
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
    include 'template/forget.php';
    return $html;
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
    );

    register_post_type('post_category', $args);
}
add_action('init', 'my_custom_post_post_types');

function my_taxonomies_post_types()
{
    //labels array

    $labels = array(
        'name' => _x('Categories', 'taxonomy general name'),
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
    );

    register_taxonomy('post_category', 'post_category', $args);
}

add_action('init', 'my_taxonomies_post_types', 0);

// COMPANY DETAILS// COMPANY DETAILS// COMPANY DETAILS// COMPANY DETAILS
// COMPANY DETAILS// COMPANY DETAILS// COMPANY DETAILS// COMPANY DETAILS


// add event date field to events post type
function add_post_meta_boxes()
{
    add_meta_box(
        "post_metadata_postType_post", // div id containing rendered fields
        "Company Details", // section heading displayed as text
        "post_meta_box_postType_post", // callback function to render fields
        "post_category", // name of post type on which to render fields
        "side", // location on the screen
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

// CONTACT INFORMATION // CONTACT INFORMATION // CONTACT INFORMATION
// CONTACT INFORMATION // CONTACT INFORMATION // CONTACT INFORMATION


// add event date field to events post type
function add_post_contact_meta_boxes()
{
    add_meta_box(
        "post_metadata_contact_postType_post", // div id containing rendered fields
        "Contact Information", // section heading displayed as text
        "post_meta_box_contact_post", // callback function to render fields
        "post_category", // name of post type on which to render fields
        "side", // location on the screen
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
    update_post_meta(isset($post->ID) ? $post->ID : '', "social_phsinc", isset($_POST['social_phsinc']) ? $_POST['social_phsinc'] : '');

    update_post_meta(isset($post->ID) ? $post->ID : '', "phone", isset($_POST['phone']) ? $_POST['phone'] : '');

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
    if (isset($custom["social_phsinc"][0])) {
        $Phs = $custom["social_phsinc"][0];
    }
    $Phsinc = isset($Phs) ? $Phs : '';
    if (isset($custom["location_address"][0])) {
        $loc = $custom["location_address"][0];
    }
    $Location = isset($loc) ? $loc : '';

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
    <input style=\"margin:4px;\" type=\"tel\" name=\"social_pinterest\" value=\"" . $Pinterest . "\" placeholder=\"Pinterest\">
    <input style=\"margin:4px;\" type=\"tel\" name=\"social_phsinc\" value=\"" . $Phsinc . "\" placeholder=\"Phsinc\"><br><br>";

    echo "<label style=\"border-bottom: 1px solid;\">Phone Number: </label><br><br><input style=\"width:100%;\" type=\"tel\" name=\"phone\" value=\"" . $phone_num . "\" placeholder=\"Phone\"><br><br>";

    echo "<label style=\"border-bottom: 1px solid;\">Location: </label><br><br><input style=\"width:100%;\" type=\"tel\" name=\"location_address\" value=\"" . $Location . "\" placeholder=\"Location\"><br><br>";
}

// Add Products// Add Products// Add Products// Add Products
// Add Products// Add Products// Add Products// Add Products

// add event date field to events post type
function add_post_product_meta_boxes()
{
    add_meta_box(
        "post_metadata_product_postType_post", // div id containing rendered fields
        "Product Information", // section heading displayed as text
        "post_meta_box_product_post", // callback function to render fields
        "post_category", // name of post type on which to render fields
        "side", // location on the screen
        "core" // placement priority
    );
}
add_action("admin_init", "add_post_product_meta_boxes");

// save field value
function save_post_product_meta_boxes()
{
    global $post;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    update_post_meta(isset($post->ID) ? $post->ID : '', "product_name", isset($_POST['product_name']) ? $_POST['product_name'] : '');
    update_post_meta(isset($post->ID) ? $post->ID : '', "product_price", isset($_POST['product_price']) ? $_POST['product_price'] : '');
    update_post_meta(isset($post->ID) ? $post->ID : '', "product_description", isset($_POST['product_description']) ? $_POST['product_description'] : '');
}
add_action('save_post', 'save_post_product_meta_boxes');

// callback function to render fields
function post_meta_box_product_post()
{
    global $post;
    if (isset($post->ID)) {
        $custom = get_post_custom($post->ID);
    }
    if (isset($custom["product_name"][0])) {
        $ProductName = $custom["product_name"][0];
    }
    $ProductName = isset($ProductName) ? $ProductName : '';
    if (isset($custom["product_price"][0])) {
        $ProductPrice = $custom["product_price"][0];
    }
    $ProductPrice = isset($ProductPrice) ? $ProductPrice : '';
    if (isset($custom["product_description"][0])) {
        $ProductDesc = $custom["product_description"][0];
    }
    $ProductDesc = isset($ProductDesc) ? $ProductDesc : '';
?>
<?php

    echo "<label style=\"border-bottom: 1px solid;\">Product Name: </label><br><br><input style=\"width:100%;\" type=\"tel\" name=\"product_name\" value=\"" . $ProductName . "\" placeholder=\"Product Name\"><br><br>";
    echo "<label style=\"border-bottom: 1px solid;\">Product Price: </label><br><br><input style=\"width:100%;\" type=\"tel\" name=\"product_price\" value=\"" . $ProductPrice . "\" placeholder=\"Product Price\"><br><br>";
    echo "<label style=\"border-bottom: 1px solid;\">Product Description: </label><br><br><textarea rows=\"4\" cols=\"50\" style=\"width:100%;\" type=\"tel\" name=\"product_description\" placeholder=\"Product Description\">$ProductDesc</textarea><br><br>";
}

// PRODUCT IMAGES// PRODUCT IMAGES// PRODUCT IMAGES// PRODUCT IMAGES
// PRODUCT IMAGES// PRODUCT IMAGES// PRODUCT IMAGES// PRODUCT IMAGES



add_action('admin_init', 'add_post_gallery_so_14445904');
add_action('admin_head-post.php', 'print_scripts_so_14445904');
add_action('admin_head-post-new.php', 'print_scripts_so_14445904');
add_action('save_post', 'update_post_gallery_so_14445904', 10, 2);

/**
 * Add custom Meta Box to Posts post type
 */
function add_post_gallery_so_14445904()
{
    add_meta_box(
        'post_gallery',
        'Product Image Uploader',
        'post_gallery_options_so_14445904',
        'post_category', // here you can set post type name
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
    $gallery_data = get_post_meta($post->ID, 'product_image', true);

    // Use nonce for verification
    wp_nonce_field(plugin_basename(__FILE__), 'noncename_so_14445904');
?>

    <div id="dynamic_form">

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
        <input class="button" type="button" value="Add Field" onclick="add_field_row();" />
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
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

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
function print_scripts_so_14445904()
{
    // Check for correct post_type
    global $post;
    if ('post_category' != $post->post_type) // here you can set post type name
        return;
?>
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
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

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
    if ('post_category' != $_POST['post_type']) // here you can set post type name
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
            update_post_meta($post_id, 'product_image', $gallery_data);
        else
            delete_post_meta($post_id, 'product_image');
    }
    // Nothing received, all fields are empty, delete option
    else {
        delete_post_meta($post_id, 'product_image');
    }
}

// STRIP PAYMENT GATEWAY// STRIP PAYMENT GATEWAY// STRIP PAYMENT GATEWAY
// STRIP PAYMENT GATEWAY// STRIP PAYMENT GATEWAY// STRIP PAYMENT GATEWAY


function Stripe_Payment_Method()
{
}
// PROCESS

add_action('admin_init', 'Stripe_Process');

function Stripe_Process()
{
    if (isset($_POST['btnSubscribe'])) {
        wp_redirect('create.php');
    }
}

// ACTION
add_shortcode('payment_gateway_stripe', 'Stripe_Payment_Form');

function Stripe_Payment_Form()
{
    include 'template/stripe.php';
    return $html;
}

function sid_directory_admin_menu() {
    add_menu_page(
        __( 'Directory Configuration', 'my-textdomain' ),
        __( 'Directory Configuration', 'my-textdomain' ),
        'manage_options',
        'directory-configuration',
        'sid_directory__page_contents',
        'dashicons-schedule',
        3
    );
}

add_action( 'admin_menu', 'sid_directory_admin_menu' );

function sid_directory__page_contents() {
    include 'admin_template/directory_configuration.php';
}

