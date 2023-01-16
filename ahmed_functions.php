<?php
register_deactivation_hook(__FILE__, 'on_deactive');
function on_deactive()
{
    global $wpdb;
    $the_removal_query = "DROP TABLE IF EXISTS `{$wpdb->prefix}claim_listin_table`";
    $wpdb->query($the_removal_query);
}
register_activation_hook(__FILE__, 'on_activate');
function on_activate()
{
    global $wpdb;
    // $set_7_day_time = strtotime('-7 day');
    // update_option('senalite_prod_update', $set_7_day_time);
    $create_table_query = "
           CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}claim_listin_table` (
           `id` int(11) NOT NULL auto_increment,
           `claim_post` int(11) NOT NULL,
           `claim_to_company` varchar(255) DEFAULT NULL,
           `first_name` varchar(255) DEFAULT NULL,
           `last_name` varchar(255) DEFAULT NULL,
           `email` varchar(255) DEFAULT NULL,
           `phone` varchar(255) DEFAULT NULL,           
           `address` text DEFAULT NULL,
           `status` varchar(255) DEFAULT NULL,
           `documents` longtext DEFAULT NULL,
           `update_time` timestamp NOT NULL DEFAULT current_timestamp(),
               PRIMARY KEY  (`id`)
           ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
   ";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($create_table_query);
}
add_filter('archive_template', 'override_archive_template');
function override_archive_template($archive_template)
{
    global $post;
    $file = dirname(__FILE__) . '/template/archive_template_listing.php';

    if (file_exists($file)) $archive_template = $file;

    if ($post->post_type == 'directory_listing') {
        return include $file;
    }
}

add_filter('single_template', 'override_single_template');
function override_single_template($single_template)
{
    global $post;

    $file = dirname(__FILE__) . '/template/single-directory_listing.php';

    if (file_exists($file)) $single_template = $file;

    if ($post->post_type == 'directory_listing') {
        return include $single_template;
    }
}

add_action('wp_enqueue_scripts', 'my_comment_rating_styles');
function my_comment_rating_styles()
{

    wp_register_style('ci-comment-rating-styles', plugins_url('/', __FILE__) . 'assets/style.css');

    wp_enqueue_style('dashicons');
    wp_enqueue_style('ci-comment-rating-styles');
}
// Remove the logout link in comment form
add_filter('comment_form_logged_in', '__return_empty_string');
//Create the rating interface.
add_action('comment_form_logged_in_after', 'my_comment_rating_rating_field');
add_action('comment_form_after_fields', 'my_comment_rating_rating_field');
function my_comment_rating_rating_field()
{
?>
    <label for="rating">Rating<span class="required">*</span></label>
    <fieldset class="comments-rating">
        <span class="rating-container">
            <?php for ($i = 5; $i >= 1; $i--) : ?>
                <input type="radio" id="rating-<?php echo esc_attr($i); ?>" name="rating" value="<?php echo esc_attr($i); ?>" /><label for="rating-<?php echo esc_attr($i); ?>"><?php echo esc_html($i); ?></label>
            <?php endfor; ?>
            <input type="radio" id="rating-0" class="star-cb-clear" name="rating" value="0" /><label for="rating-0">0</label>
        </span>
    </fieldset>
<?php
}

//Save the rating submitted by the user.
add_action('comment_post', 'my_comment_rating_save_comment_rating');
function my_comment_rating_save_comment_rating($comment_id)
{
    if ((isset($_POST['rating'])) && ('' !== $_POST['rating']))
        $rating = intval($_POST['rating']);
    add_comment_meta($comment_id, 'rating', $rating);
}

//Make the rating required.
add_filter('preprocess_comment', 'my_comment_rating_require_rating');
function my_comment_rating_require_rating($commentdata)
{
    if (!is_admin() && (!isset($_POST['rating']) || 0 === intval($_POST['rating'])))
        wp_die(__('Error: You did not add a rating. Hit the Back button on your Web browser and resubmit your comment with a rating.'));
    return $commentdata;
}

//Display the rating on a submitted comment.
add_filter('comment_text', 'my_comment_rating_display_rating');
function my_comment_rating_display_rating($comment_text)
{
    if ($rating = get_comment_meta(get_comment_ID(), 'rating', true)) {
        $stars = '<p class="stars">';
        for ($i = 1; $i <= $rating; $i++) {
            $stars .= '<span class="dashicons dashicons-star-filled"></span>';
        }
        $stars .= '</p>';
        $comment_text = $comment_text . $stars;
        return $comment_text;
    } else {
        return $comment_text;
    }
}

//Get the average rating of a post.
function my_comment_rating_get_average_ratings($id)
{
    $comments = get_approved_comments($id);
    if ($comments) {
        $i = 0;
        $total = 0;
        foreach ($comments as $comment) {
            $rate = get_comment_meta($comment->comment_ID, 'rating', true);
            if (isset($rate) && '' !== $rate) {
                $i++;
                $total += $rate;
            }
        }

        if (0 === $i) {
            return false;
        } else {
            return round($total / $i, 1);
        }
    } else {
        return false;
    }
}

//Display the average rating above the content.
add_filter('the_content', 'my_comment_rating_display_average_rating');
function my_comment_rating_display_average_rating($content)
{

    global $post;

    if (false === my_comment_rating_get_average_ratings($post->ID)) {
        return $content;
    }

    $stars   = '';
    $average = my_comment_rating_get_average_ratings($post->ID);

    for ($i = 1; $i <= $average + 1; $i++) {

        $width = intval($i - $average > 0 ? 20 - (($i - $average) * 20) : 20);

        if (0 === $width) {
            continue;
        }

        $stars .= '<span style="overflow:hidden; width:' . $width . 'px" class="dashicons dashicons-star-filled"></span>';

        if ($i - $average > 0) {
            $stars .= '<span style="overflow:hidden; position:relative; left:-' . $width . 'px;" class="dashicons dashicons-star-empty"></span>';
        }
    }

    $custom_content  = '<p class="average-rating">This post\'s average rating is: ' . $average . ' ' . $stars . '</p>';
    $custom_content .= $content;
    return $custom_content;
}

// function wpdocs_comment_form_defaults( $defaults ) {
//    $defaults['title_reply'] = __( 'Add a Comment' );
//    return $defaults;
//  }
//  add_filter( 'comment_form_defaults', 'wpdocs_comment_form_defaults' );


function sid_suggest_form()
{
    //labels array added inside the function and precedes args array
    $labels = array(
        'name' => _x('Suggest Form', 'post type general name'),
        'singular_name' => _x('Suggest Form', 'post type singular name'),
        'add_new' => _x('Add Suggest', 'Post'),
        'add_new_item' => __('Add New Suggest'),
        'edit_item' => __('Edit Suggest'),
        'new_item' => __('New Suggest'),
        'all_items' => __('All Suggest'),
        'view_item' => __('View Suggest'),
        'search_items' => __('Search Suggest'),
        'not_found' => __('No Suggest Form found'),
        'not_found_in_trash' => __('No Suggest Form found in the Trash'),
        'parent_item_colon' => '',
        'menu_name' => 'Business Suggest',
    );

    // args array
    $args = array(
        'labels' => $labels,
        'description' => 'Displays posts and their ratings',
        'public' => true,
        'menu_position' => 4,
        'supports' => array('title'),
        'has_archive' => true,
        'menu_icon' => 'dashicons-table-col-before',
    );

    register_post_type('suggest_form', $args);
}
add_action('init', 'sid_suggest_form');


function moved_post_data()
{
    add_meta_box('moved_post_data', __('Post Move'), 'move_post_callback', 'suggest_form', 'side');
}
add_action('add_meta_boxes', 'moved_post_data');

add_action('init', 'sid_techno_move_to_directory');
function sid_techno_move_to_directory()
{
    global $wpdb;
    if (isset($_GET['moveto_directory'])) {
        $post_id = $_GET['moveto_directory'];
        $get_post_meta = get_post($post_id);

        // var_dump($get_post_meta);
        $title = $get_post_meta->post_title;
        $business_email = $get_post_meta->business_email;
        $business_phone = $get_post_meta->business_phone;
        $business_website = $get_post_meta->business_website;
        $business_note = $get_post_meta->business_note;

        $post = array(
            'post_title' =>  $title,
            'post_content' => $business_note,
            'post_status' => 'publish',
            'post_type' => 'directory_listing',
            'meta_input' => array(
                'company_name' => $title,
                'phone' => $business_phone,
                'email_address' => $business_email,
                'visit_website' => $business_website
            )
        );

        // insert the post into the database
        $post_moved = wp_insert_post($post);
        if ($post_moved) {
            wp_delete_post($post_id, true);
        }
    }
}

/**
 * Meta box display callback.
 *
 * @param WP_Post $post Current post object.
 */
function move_post_callback($post)
{
    global $post;
    $post_id = $post->ID;
    echo '<div >
           <a id="directory_listing_cat-add-toggle" href="' . admin_url('edit.php?post_type=directory_listing&moveto_directory=' . $post_id . '') . '" class="hide-if-no-js taxonomy-add-new button">Moved to Directory </a>
        </div>';
}


function sid_request_form()
{

    //labels array added inside the function and precedes args array
    $labels = array(
        'name' => _x('Request quote', 'post type general name'),
        'singular_name' => _x('Request quote', 'post type singular name'),
        'edit_item' => __('Edit Quote'),
        'all_items' => __('All Quote'),
        'view_item' => __('View Quote'),
        'search_items' => __('Search Quote'),
        'not_found' => __('No request a quote found'),
        'not_found_in_trash' => __('No request a quote found in the Trash'),
        'parent_item_colon' => '',
        'menu_name' => 'Quotes',
    );

    // args array
    $args = array(
        'labels' => $labels,
        'description' => 'Displays posts and their ratings',
        'public' => true,
        'menu_position' => 4,
        'supports'           => array('title', 'thumbnail'),
        'has_archive' => true,
        'menu_icon' => 'dashicons-table-col-before',
    );

    register_post_type('quote_request_form', $args);
}
add_action('init', 'sid_request_form');

add_action('init', 'sid_claim_listin');
function sid_claim_listin()
{
    global $wpdb;
    if (isset($_POST['claimsubmit'])) {
        $files = $_FILES['mutipleFiles'];
        $documents = "";
        // if ($files) {        
        foreach ($files['name'] as $key => $value) {
            if ($files['name'][$key]) {
                $file_name = sanitize_file_name($files['name'][$key]);
                $file_type = $files['type'][$key];
                // first checking if tmp_name is not empty
                if (!empty($files['tmp_name'][$key])) {
                    // if not, then try creating a file on disk
                    $upload = wp_upload_bits($file_name, null, file_get_contents($files['tmp_name'][$key]));

                    // if wp does not return a file creation error
                    if ($upload['error'] === false) {
                        // then you can create an attachment
                        $attachment = array(
                            'post_mime_type' => $upload['type'],
                            'post_title' => $file_name,
                            'post_content' => '',
                            'post_status' => 'inherit'
                        );

                        // creating an attachment in db and saving its ID to a variable
                        $attach_id = wp_insert_attachment($attachment, $upload['file']);
                        $documents .= $attach_id . ",";
                        // generation of attachment metadata
                        $attach_data = wp_generate_attachment_metadata($attach_id, $upload['file']);

                        // attaching metadata and creating a thumbnail
                        wp_update_attachment_metadata($attach_id, $attach_data);
                    }
                }
            }
        }
        // }
        $documents = trim($documents, ",");
        $company_name = $_POST['company_name'];
        $first_name = $_POST['first_name'];
        $last_name = $_POST["last_name"];
        $email = $_POST["email"];
        $phone = $_POST["phone"];
        $address = $_POST["address"];
        $claim_post_id = $_POST["post_id"];
        $table_name = "{$wpdb->prefix}claim_listin_table";

        $user_data = array(
            'claim_to_company' => $company_name,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'phone' => $phone,
            'address' => $address,
            'documents' => $documents,
            'claim_post' => $claim_post_id,
            'status' => 'Pending'
        );
        $result = $wpdb->insert($table_name, $user_data, $format = null);

        $user = get_user_by('email', $email);
        if (!$user) {
            $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
            $randomKey = substr(str_shuffle($permitted_chars), 0, 20);
            $randomPass = substr(str_shuffle($permitted_chars), 0, 10);
            $data = array(
                'user_login' => $first_name,
                'user_email' => $email,
                'user_pass' => md5($randomPass),
                'user_registered' => date("Y-m-d H:i:s"),
                'display_name' => $first_name,
                'user_nicename' => $email,
            );
            $table_name = "{$wpdb->prefix}users";
            $userDetails_get_one = $wpdb->get_row(" 
          SELECT {$wpdb->prefix}users.* , {$wpdb->prefix}usermeta.*
          FROM {$wpdb->prefix}users
          INNER JOIN {$wpdb->prefix}usermeta
          ON {$wpdb->prefix}users.id =  {$wpdb->prefix}usermeta.user_id
          WHERE {$wpdb->prefix}users.user_email='" . $email . "'");
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
                        'customer_name' => $first_name,
                        'customer_email' =>  $email,
                        'package_type' =>  'free',
                        'item_name' =>  'Free Listing',
                        'item_price' =>  0.00,
                        'item_price_currency' => 'usd',
                        'payment_method' => 'free',
                        'plan_amount' =>  0.00,
                        'plan_amount_currency' => 'usd',
                        'payer_email' =>  $email,
                        'created' => date("Y-m-d H:i:s"),
                        'status' => 'active'
                    );
                    $add_free_package = $wpdb->insert($table_package, $user_data, $format = null);

                    add_user_meta($lastid, 'first_name', $first_name);
                    add_user_meta($lastid, 'last_name', $last_name);
                    add_user_meta($lastid, 'user_address', $address);
                    add_user_meta($lastid, 'user_contact', $phone);
                }
            }
        }
    }
}

function my_handle_attachment($file_handler, $post_id, $set_thu = false)
{
    // check to make sure its a successful upload
    if ($_FILES[$file_handler]['error'] !== UPLOAD_ERR_OK) __return_false();

    require_once(ABSPATH . "wp-admin" . '/includes/image.php');
    require_once(ABSPATH . "wp-admin" . '/includes/file.php');
    require_once(ABSPATH . "wp-admin" . '/includes/media.php');

    $attach_id = media_handle_upload($file_handler, $post_id);
    if (is_numeric($attach_id)) {
        update_post_meta($post_id, '_my_file_upload', $attach_id);
    }
    return $attach_id;
}

function sidtechno_my_admin_menu()
{
    add_menu_page(
        __('Claim Listing', 'my-textdomain'),
        __('Claim Listing', 'my-textdomain'),
        'manage_options',
        'claim-listing-view',
        'my_admin_page_contents',
        'dashicons-schedule',
        4
    );
}
add_action('admin_menu', 'sidtechno_my_admin_menu');
function my_admin_page_contents($arg)
{
    global $wpdb;
    // $db_time = get_option('senalite_prod_update');
    // $last_cron_run = get_option('senalite_last_cron_run');
    // absolute current URI (on single site):

    // $pageSlug =  explode("?", $_GET['page']);


?>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap.min.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.0/css/responsive.bootstrap.min.css" type="text/css" media="screen" />
    <style type="text/css">
        .claim_details_view {
            background: #f3f3f3;
        }

        .documents_main {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 10px;
            /* padding-top: 10px; */
            /*margin-top: 25px;
        padding: 20px;*/
        }

        img.claim_thumbnail {
            /*            width: 100%;*/
            height: 150px;
        }

        .column label {
            margin-top: 6px;
            color: black;
            font-size: 12px;
            text-align: center;
            text-transform: uppercase;
        }

        .claim_details_view table th,
        td {
            padding: 12px;
        }
    </style>

    <h1> <?php esc_html_e('Claim Listing', 'my-plugin-textdomain'); ?> </h1>
    <?php echo do_shortcode('[claim_listing_table]'); ?>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.0/js/responsive.bootstrap.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#example').DataTable();
        });
    </script>
<?php
}


add_shortcode('claim_listing_table', function () {
    global $wpdb;
    $current_page = admin_url(sprintf('admin.php?%s', http_build_query($_GET)));
    $table_name = $wpdb->prefix . 'claim_listin_table';
    $output = "";

    if (isset($_POST['verify_claim'])) {
        $claim_id = $_POST['claim_id'];
        $current_page = admin_url(sprintf('admin.php?%s', http_build_query($_GET)));
        $currentUrl = explode("&", $current_page);

        $table = "{$wpdb->prefix}claim_listin_table";
        $data = array(
            'status' => 'Approved',
        );
        $where = array(
            'id' => $claim_id,
        );
        $update_claim = $wpdb->update($table, $data, $where);

        if ($update_claim) {
            echo "<script> window.location.href='" . $currentUrl[0] . "'</script>";
        }
    }
    if (isset($_POST['decline_claim'])) {
        $claim_id = $_POST['claim_id'];
        $current_page = admin_url(sprintf('admin.php?%s', http_build_query($_GET)));
        $currentUrl = explode("&", $current_page);

        $table = "{$wpdb->prefix}claim_listin_table";
        $data = array(
            'status' => 'Declined',
        );
        $where = array(
            'id' => $claim_id,
        );
        $update_claim = $wpdb->update($table, $data, $where);

        if ($update_claim) {

            echo "<script> window.location.href='" . $currentUrl[0] . "'</script>";
        }
    }

    if (isset($_GET['claim_view_id'])) {
        $claimId = $_GET['claim_view_id'];
        $claim_post_id = $_GET['claim_post_id'];
        $currentUrl = explode("&", $current_page);
        $claim_data = $wpdb->get_row("SELECT * FROM $table_name WHERE `id` = $claimId");
        $documents = explode(",", $claim_data->documents);
        $imageDiv = "";

        $get_claim_users = $wpdb->get_results("
        SELECT {$wpdb->prefix}user_subscriptions_details.* , {$wpdb->prefix}claim_listin_table.*, {$wpdb->prefix}user_subscriptions_details.status as package_status
        FROM {$wpdb->prefix}user_subscriptions_details
        INNER JOIN {$wpdb->prefix}claim_listin_table
        ON {$wpdb->prefix}user_subscriptions_details.customer_email = {$wpdb->prefix}claim_listin_table.email
        WHERE {$wpdb->prefix}claim_listin_table.claim_post = $claim_post_id && {$wpdb->prefix}user_subscriptions_details.status = 'active';
        ");


        if(isset($_POST['assign_directory'])){
            $claim_user_id = $_POST['claim_user_id'];
            $claim_post_id = $_POST['claim_post_id'];
            $post_update = array(
                'ID' => $claim_post_id,
                'post_author' => $claim_user_id,
            );

            wp_update_post($post_update);
            echo "<script> window.location.href='" . $currentUrl[0] . "'</script>";
        }
        // print_r($get_claim_users);

        foreach ($documents as $key => $attach_id) {
            $imageUrl = wp_get_attachment_url($attach_id);
            $fileName = basename(get_attached_file($attach_id));
            $fileTypeArray =  wp_check_filetype($fileName);
            $imageType =  explode("/", $fileTypeArray['type']);
            if ($fileTypeArray['type'] == "application/pdf") {

                $image = "<img class='claim_thumbnail' src='" . plugins_url('template/assets/images/pdf.png', __FILE__) . "' />";
            } else if ($imageType[0] == "image") {
                // $fileName = "";
                $image = "<img class='claim_thumbnail' src='" . $imageUrl . "' />";
            } else {

                $image = "<img class='claim_thumbnail' src='" . plugins_url('template/assets/images/notepad.png', __FILE__) . "' />";
            }

            $imageDiv .= '<div class="column">
                        <a href="' . $imageUrl . '" target="_blank">
                            ' . $image . '
                            <label>' . $fileName . '</label>
                            </a>
                        </div>';
        }
        echo '<div class="wrap"><a href="' . $currentUrl[0] . '" class="page-title-action">Back</a>
        </div>
        <div class="claim_details_view">
            <table border="1" class="claim_table">
                <tr>
                    <th>First Name: </th>
                    <td>' . $claim_data->first_name . '</td>
                    <th>Last Name: </th>
                    <td>' . $claim_data->last_name . '</td>                    
                    <th>Email: </th>                    
                    <td>' . $claim_data->email . '</td>                    
                    <th>Phone: </th>                    
                    <td>' . $claim_data->phone . '</td>                    
                </tr>
                <tr>
                    <th>Company: </th>
                    <td colspan="2">' . $claim_data->claim_to_company . '</td>
                    <th>Address: </th>
                    <td colspan="4">' . $claim_data->address . '</td>
                </tr>
                <tr>
                    <th>Documents: </th>
                    <td colspan="7">
                        <div class="documents_main">
                            ' . $imageDiv . '
                        </div>
                    </td>
                </tr>
                <tr>
                    <th>Approval: </th>';
        if ($claim_data->status == "Approved" || $claim_data->status == "Declined") {
            echo   '<td colspan="3">
                        <div class="documents_main">
                        <button type="button" disabled class="btn btn-primary" >' . $claim_data->status . '</button>
                        </div>
                        </td>';
        } else {
            echo '
                    <td colspan="3">
                        <div class="documents_main">
                            <form id="form-approve" method="post">
                            <input type="hidden" id="claim_id" name="claim_id" value="' . $claimId . '" />
                            <button name="verify_claim" type="submit" class="btn btn-success" >Verify Claim</button>

                            </form>
                            <form id="form-decline" method="post">
                            <input type="hidden" id="claim_id" name="claim_id" value="' . $claimId . '" />
                            <button name="decline_claim" type="submit" class="btn btn-danger">Decline Claim</button>
                            </form>
                        </div>
                    </td>';
        }
        echo '
                </tr>                     
            </table>
        </div>';

        // CLAIM USERS LIST
        echo '
            </br>
            <div class="claim_details_view">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th>Package Type</th>
                            <th>Package Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>';

        foreach ($get_claim_users as $claim_data) {
            echo '
                            <tr>
                            <td>' . $claim_data->first_name . $claim_data->last_name . '</td>
                            <td>' . $claim_data->email . '</td>
                            <td>' . $claim_data->phone . '</td> 
                            <td>' . $claim_data->address . '</td>
                            <td>' . $claim_data->package_type . '</td>
                            <td>' . $claim_data->package_status . '</td>
                            <td>
                                <form method="post">
                                    <input type="hidden" name="claim_user_id" value="' . $claim_data->user_id . '"/>
                                    <input type="hidden" name="claim_post_id" value="' . $claim_data->claim_post . '"/>
                                    <a><button type="submit" class="btn btn-success" name="assign_directory">Assign Direcotry</button></a>
                                </form>
                            </td>
                            </tr>';
        }
        echo '
                    </tbody>
                </table>
            </div>';
    } else {

        $output .= '<div class="claim_table_view">
                <table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                <thead>
                  <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Company Name</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                 <tbody>';
        // this will get the data from your table
        $retrieve_data = $wpdb->get_results("SELECT * FROM $table_name ORDER BY `id` DESC");
        foreach ($retrieve_data as $retrieved_data) {
            $cid = $retrieved_data->id;
            $claim_post = $retrieved_data->claim_post;
            $claim_to_company = $retrieved_data->claim_to_company;
            $first_name = $retrieved_data->first_name;
            $last_name = $retrieved_data->last_name;
            $email = $retrieved_data->email;
            $phone = $retrieved_data->phone;
            $status = $retrieved_data->status;


            $output .= '    <tr>
                    <td>' . $first_name . '</td>
                    <td>' . $last_name . '</td>
                    <td>' . $email . '</td>
                    <td>' . $phone . '</td>
                    <td>' . $claim_to_company . '</td>
                    <td>' . $status . '  </td>
                    <td><a href="' . $current_page . '&claim_view_id=' . $cid . '&claim_post_id=' . $claim_post . '">View Details</a></td>
                  </tr>
                ';
        }
        $output .= '</tbody>
                </table>            
            </div>';
    }

    return $output;
});

// For page redirection:
add_action('template_redirect', function () {
    ob_start();
});

//Get the average rating of a post.
function directory_comment_rating_get_average_ratings($id)
{
    $comments = get_approved_comments($id);

    if ($comments) {
        $i = 0;
        $total = 0;
        foreach ($comments as $comment) {
            $rate = get_comment_meta($comment->comment_ID, 'rating', true);
            if (isset($rate) && '' !== $rate) {
                $i++;
                $total += $rate;
            }
        }

        if (0 === $i) {
            return false;
        } else {
            return round($total / $i, 1);
        }
    } else {
        return false;
    }
}
// Stripe Payment Gataway
add_action('init', 'stripe_payment');
function stripe_payment()
{
    global $wpdb;
    $payment_id = $statusMsg = $api_error = '';
    $ordStatus = 'error';
    // require_once(dirname(__FILE__)."/template/StripeAssets/stripe/Stripe.php");
    // var_dump($_POST);
    // exit;
    // $secret_key  = "sk_test_51M9qovGDTXcYyUEXGNM5q1RS5D5ujdJPFH2qRNvQ9sxj9hzL5UFUUo6VgB2RQc0v6ELwQTAI1HSF3g5wj5xoijKw00D2U2uj6J";
    // Stripe::setApiKey($secret_key);

    // $allData = Stripe_Subscription::all(['limit' => 100]);
    // $data = (array)$allData;
    // var_dump($data);
    if (isset($_POST['stripeToken']) && !empty($_POST['stripeToken'])) {
        require_once(dirname(__FILE__) . "/template/StripeAssets/stripe-php-master/init.php");

        $secret_key  = "sk_test_51M9qovGDTXcYyUEXGNM5q1RS5D5ujdJPFH2qRNvQ9sxj9hzL5UFUUo6VgB2RQc0v6ELwQTAI1HSF3g5wj5xoijKw00D2U2uj6J";

        \Stripe\Stripe::setApiKey($secret_key);

        $description     = "Invoice #" . rand(99999, 999999999);
        $amount_cents    = $_POST['amount'];
        // Define item price and convert to cents 
        $itemPriceCents  = round($amount_cents * 100);
        $currency = "USD";
        $token         = $_POST["stripeToken"];
        $stripe = new \Stripe\StripeClient(
            'sk_test_51M9qovGDTXcYyUEXGNM5q1RS5D5ujdJPFH2qRNvQ9sxj9hzL5UFUUo6VgB2RQc0v6ELwQTAI1HSF3g5wj5xoijKw00D2U2uj6J'
        );

        try {
            // $charge = Stripe_Charge::create(
            //     array(
            //         "amount"         => $itemPriceCents,
            //         "currency"       => "USD",
            //         "source"         => $token,
            //         "description"    => $description
            //     ));

            $package_type    = $_POST['package_type'];
            $package_name    = $_POST['package_name'];
            $holdername      = $_POST['holdername'];
            $email           = $_POST['email'];
            $user_ID           = $_POST['user_ID'];

            $table_name = "{$wpdb->prefix}user_subscriptions_details";
            $subscription_data = $wpdb->get_row("SELECT * FROM $table_name WHERE `user_id` = '$user_ID' AND `status` = 'active' ");
            // var_dump($subscription_data);
            if (!empty($subscription_data)) {
                $userSubscrID = $subscription_data->stripe_subscription_id;
                $cancel_subscription =   $stripe->subscriptions->cancel(
                    $userSubscrID,
                    []
                );
                if ($cancel_subscription) {
                    $cancelDate = date("Y-m-d H:i:s", substr($cancel_subscription->canceled_at, 0, 10));
                    $status = $cancel_subscription->status;

                    $where = array(
                        'user_id' => $user_ID,
                        'stripe_subscription_id' => $userSubscrID
                    );
                    $user_data = array(
                        "cancel_subscription" => $cancelDate,
                        "status" => $status
                    );
                    $table_name = "{$wpdb->prefix}user_subscriptions_details";
                    $wpdb->update($table_name, $user_data, $where);
                }
            }

            // Plan info 
            $planName = $_POST['plan_name'];
            $planPrice = $itemPriceCents;
            $planInterval = $_POST['interval'];
            // $planIntervalCount = $_POST['interval_count'];                
            // Add customer to stripe 
            $customer =  $stripe->customers->create(array(
                'email' => $email,
                'source'  => $token
            ));

            // Create a plan 
            try {
                $plan = $stripe->plans->create(array(
                    "product" => [
                        "name" => $planName
                    ],
                    "amount" => $planPrice,
                    "currency" => $currency,
                    "interval" => $planInterval,
                    "interval_count" => 1
                ));

                $txn_id = $plan["id"];
                $amount     = $plan["amount"];
                $paid_amount = ($amount / 100);
                $currency     = $plan["currency"];
                // $status     = $plan["status"];            
            } catch (Exception $e) {
                $api_error = $e->getMessage();
            }
            if (empty($api_error) && $plan) {
                // Creates a new subscription 
                try {
                    $subscription = $stripe->subscriptions->create(array(
                        "customer" => $customer->id,
                        "items" => array(
                            array(
                                "plan" => $plan->id,
                            ),
                        ),
                    ));
                } catch (Exception $e) {
                    $api_error = $e->getMessage();
                }

                if (empty($api_error) && $subscription) {

                    // Check whether the subscription activation is successful 
                    if ($subscription->status == 'active') {
                        // Subscription info 
                        $subscrID = $subscription->id;
                        $custID = $subscription->customer;
                        $planID = $subscription->plan->id;
                        $planAmount = ($subscription->plan->amount / 100);
                        $planCurrency = $subscription->plan->currency;
                        $planinterval = $subscription->plan->interval;
                        $planIntervalCount = $subscription->plan->interval_count;
                        $created = date("Y-m-d H:i:s", $subscription->created);
                        $current_period_start = date("Y-m-d H:i:s", $subscription->current_period_start);
                        $current_period_end = date("Y-m-d H:i:s", $subscription->current_period_end);
                        $status = $subscription->status;

                        // Check if any transaction data is exists already with the same TXN ID 
                        $table_name = "{$wpdb->prefix}user_subscriptions_details";
                        $subscription_data = $wpdb->get_results("SELECT * FROM $table_name WHERE `stripe_subscription_id` = '$subscrID'");
                        if (empty($subscription_data)) {
                            // Insert transaction data into the database 
                            $user_data = array(
                                "user_id" => $user_ID,
                                'customer_name' => $holdername,
                                'customer_email' => $email,
                                'package_type' => $package_type,
                                'item_name' => $package_name,
                                'item_price' => $amount_cents,
                                'item_price_currency' => $currency,
                                "stripe_subscription_id" => $subscrID,
                                "stripe_customer_id" => $custID,
                                "stripe_plan_id" => $planID,
                                "plan_amount" => $planAmount,
                                "plan_amount_currency" => $planCurrency,
                                "plan_interval" => $planinterval,
                                "plan_interval_count" => $planIntervalCount,
                                "payer_email" => $email,
                                "created" => $created,
                                "plan_period_start" => $current_period_start,
                                "plan_period_end" => $current_period_end,
                                "status" => $status
                            );

                            $subresult = $wpdb->insert($table_name, $user_data, $format = null);

                            // Update subscription id in the users table  
                            if ($subresult) {
                                // $subscription_id = $result->id;  
                                // $where = array('ID' => $user_ID);
                                // $user_data = array("subscription_id" => $subscrID);
                                // $table_name = "{$wpdb->prefix}users";
                                // $result = $wpdb->update($table_name, $user_data, $where);
                                // if ($result) {                                                               
                                wp_redirect('/list-packages/?subscrID=' . $subscrID);
                                exit;
                                // }
                            }
                        }

                        // $ordStatus = 'success'; 
                        // $statusMsg = 'Your Subscription Payment has been Successful!'; 
                    } else {
                        $statusMsg = "Subscription activation failed!";
                    }
                } else {
                    $statusMsg = "Subscription creation failed! " . $api_error;
                }
            } else {
                $statusMsg = "Plan creation failed! " . $api_error;
            }
        } catch (Exception $e) {
            $api_error = $e->getMessage();
        }
    }

    if (isset($_POST['cancelSubscription']) && !empty($_POST['subscription_id'])) {
        require_once(dirname(__FILE__) . "/template/StripeAssets/stripe-php-master/init.php");

        $secret_key  = "sk_test_51M9qovGDTXcYyUEXGNM5q1RS5D5ujdJPFH2qRNvQ9sxj9hzL5UFUUo6VgB2RQc0v6ELwQTAI1HSF3g5wj5xoijKw00D2U2uj6J";

        \Stripe\Stripe::setApiKey($secret_key);

        try {
            $subscrID = $_POST['subscription_id'];
            $user_ID = $_POST['user_ID'];
            $stripe = new \Stripe\StripeClient(
                'sk_test_51M9qovGDTXcYyUEXGNM5q1RS5D5ujdJPFH2qRNvQ9sxj9hzL5UFUUo6VgB2RQc0v6ELwQTAI1HSF3g5wj5xoijKw00D2U2uj6J'
            );
            $cancel_subscription =   $stripe->subscriptions->cancel(
                $subscrID,
                []
            );
            // $cancel_subscription = $stripe->subscriptions->update(
            //       'sub_1MLiLxGDTXcYyUEXiNT4Ou5k',
            //       [
            //         'cancel_at_period_end' => true,
            //       ]
            //     );
            if ($cancel_subscription) {
                $cancelDate = date("Y-m-d H:i:s", substr($cancel_subscription->canceled_at, 0, 10));
                $status = $cancel_subscription->status;

                $where = array(
                    'user_id' => $user_ID,
                    'stripe_subscription_id' => $subscrID
                );
                $user_data = array(
                    "cancel_subscription" => $cancelDate,
                    "status" => $status
                );
                $table_name = "{$wpdb->prefix}user_subscriptions_details";
                $result = $wpdb->update($table_name, $user_data, $where);
                if ($result) {
                    if (isset($_POST['admin_side'])) {
                        $redirectPage = $_POST['admin_side'];
                        wp_redirect($redirectPage);
                    } else {
                        wp_redirect('/list-packages/?canceled');
                    }
                    exit;
                }
            }
        } catch (Exception $e) {
            $api_error = $e->getMessage();
        }
        var_dump($api_error);
    }
}

// User Login process
add_action('init', 'process_login');
function process_login()
{
    global $wpdb;
    if (isset($_POST['login_directory'])) {
        $username = $_POST['login_username'];
        $pass = $_POST['login_password'];
        // if (isset($_POST['token'])) {
        //     $captcha_token = $_POST['token'];
        // }

        $userDetails = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}users WHERE user_email='$username'");

        if (isset($userDetails->user_login)) {

            $creds = array(
                'user_login'    => $userDetails->user_login,
                'user_password' => $pass,
                'remember'      => true
            );

            $user = wp_signon($creds, false);
            if (is_wp_error($user)) {
                $msg = $user->get_error_message();
                $_SESSION['error'] = $msg;
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
    if (!is_admin() and !wp_is_json_request()) {
        include 'template/login.php';
        return $html;
    }
}

// Subscription of packages
function sidtechno_subscription_menu()
{
    add_menu_page(
        __('Packages Subscription', 'my-textdomain'),
        __('Packages Subscription', 'my-textdomain'),
        'manage_options',
        'packages-subscription-view',
        'my_admin_subscription_page_contents',
        'dashicons-schedule',
        4
    );
}
add_action('admin_menu', 'sidtechno_subscription_menu');
function my_admin_subscription_page_contents($arg)
{
    global $wpdb;
    // $db_time = get_option('senalite_prod_update');
    // $last_cron_run = get_option('senalite_last_cron_run');
    // absolute current URI (on single site):

    // $pageSlug =  explode("?", $_GET['page']);


?>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap.min.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.0/css/responsive.bootstrap.min.css" type="text/css" media="screen" />
    <style type="text/css">
        .claim_details_view {
            background: #f3f3f3;
        }

        .documents_main {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 10px;
            /* padding-top: 10px; */
            /*margin-top: 25px;
        padding: 20px;*/
        }

        img.claim_thumbnail {
            /*            width: 100%;*/
            height: 150px;
        }

        .column label {
            margin-top: 6px;
            color: black;
            font-size: 12px;
            text-align: center;
            text-transform: uppercase;
        }

        .claim_details_view table th,
        td {
            padding: 12px;
        }
    </style>

    <h1> <?php esc_html_e('Packages Subscription', 'my-plugin-textdomain'); ?> </h1>
    <?php echo do_shortcode('[packages_subscription_table]'); ?>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.0/js/responsive.bootstrap.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#example').DataTable();
        });
    </script>
<?php
}


add_shortcode('packages_subscription_table', function () {
    global $wpdb;
    $current_page = admin_url(sprintf('admin.php?%s', http_build_query($_GET)));
    $table_name = $wpdb->prefix . 'user_subscriptions_details';
    $output = "";
    if (isset($_GET['claim_view_id'])) {
        $claimId = $_GET['claim_view_id'];
        $currentUrl = explode("&", $current_page);
        $claim_data = $wpdb->get_row("SELECT * FROM $table_name WHERE `id` = $claimId");
        $documents = explode(",", $claim_data->documents);
        $imageDiv = "";
        foreach ($documents as $key => $attach_id) {
            $imageUrl = wp_get_attachment_url($attach_id);
            $fileName = basename(get_attached_file($attach_id));
            $fileTypeArray =  wp_check_filetype($fileName);
            $imageType =  explode("/", $fileTypeArray['type']);
            if ($fileTypeArray['type'] == "application/pdf") {

                $image = "<img class='claim_thumbnail' src='" . plugins_url('template/assets/images/pdf.png', __FILE__) . "' />";
            } else if ($imageType[0] == "image") {
                // $fileName = "";
                $image = "<img class='claim_thumbnail' src='" . $imageUrl . "' />";
            } else {

                $image = "<img class='claim_thumbnail' src='" . plugins_url('template/assets/images/notepad.png', __FILE__) . "' />";
            }

            $imageDiv .= '<div class="column">
                        <a href="' . $imageUrl . '" target="_blank">
                            ' . $image . '
                            <label>' . $fileName . '</label>
                            </a>
                        </div>';
        }
        echo '<div class="wrap"><a href="' . $currentUrl[0] . '" class="page-title-action">Back</a>
        </div>
        <div class="claim_details_view">
            <table border="1" class="claim_table">
                <tr>
                    <th>First Name: </th>
                    <td>' . $claim_data->first_name . '</td>
                    <th>Last Name: </th>
                    <td>' . $claim_data->last_name . '</td>                    
                    <th>Email: </th>                    
                    <td>' . $claim_data->email . '</td>                    
                    <th>Phone: </th>                    
                    <td>' . $claim_data->phone . '</td>                    
                </tr>
                <tr>
                    <th>Company: </th>
                    <td colspan="2">' . $claim_data->claim_to_company . '</td>
                    <th>Address: </th>
                    <td colspan="4">' . $claim_data->address . '</td>
                </tr>
                <tr>
                    <th>Documents: </th>
                    <td colspan="7">
                        <div class="documents_main">
                            ' . $imageDiv . '
                        </div>
                    </td>
                </tr>                    
            </table>
        </div>';
    } else {

        $output .= '<div class="claim_table_view">
                <table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                <thead>
                  <tr>
                    <th>User Name</th>                    
                    <th>Card Holder</th>                    
                    <th>Holder Email</th>                    
                    <th>Package</th>
                    <th>Package Price</th>
                    <th>Period Start</th>
                    <th>Period End</th>
                    <th>Canceled Date</th>
                    <th>status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                 <tbody>';
        // this will get the data from your table
        $retrieve_data = $wpdb->get_results("SELECT * FROM $table_name ORDER BY `id` DESC");
        foreach ($retrieve_data as $retrieved_data) {
            $subid = $retrieved_data->id;
            $stripe_subscription_id = $retrieved_data->stripe_subscription_id;
            $user_ID = $retrieved_data->user_id;
            $table_name = "{$wpdb->prefix}users";
            $user_data2 = $wpdb->get_row("SELECT `user_login` FROM $table_name WHERE `ID` = '$user_ID' ");
            $user_name = $user_data2->user_login;
            $customer_name = $retrieved_data->customer_name;
            $customer_email = $retrieved_data->customer_email;
            $item_name = $retrieved_data->item_name;
            $item_price = $retrieved_data->item_price;
            $plan_period_start = $retrieved_data->plan_period_start;
            $plan_period_end = $retrieved_data->plan_period_end;
            $cancel_subscription = $retrieved_data->cancel_subscription;
            $cancelBtn = false;
            if (empty($cancel_subscription)) {
                $cancel_subscription = "----";
                $cancelBtn = true;
            }
            $status = $retrieved_data->status;

            $output .= '    <tr>
                    <td>' . $user_name . '</td>
                    <td>' . $customer_name . '</td>
                    <td>' . $customer_email . '</td>
                    <td>' . $item_name . '</td>
                    <td>$' . $item_price . '</td>
                    <td>' . $plan_period_start . '</td>
                    <td>' . $plan_period_end . '</td>
                    <td>' . $cancel_subscription . '</td>
                    <td>' . $status . '</td>
                    <td> ';
            if ($cancelBtn) {

                $output .= '  <form method="post" action="" id="cancel_form">
                        <input type="hidden" name="subscription_id" value="' . $stripe_subscription_id . '"/>
                        <input type="hidden" name="user_ID" value="' . $user_ID . '"/>
                        <input type="hidden" name="admin_side" value="' . $current_page . '"/>
                        <input type="hidden" name="cancelSubscription" value="true"/>
                        
                        <button   class="btn btn-danger  cancelBtn" >Cancel</button>
                      </form>';
            }
            $output .= '</td>
                  </tr>
                ';
        }
        $output .= '</tbody>
                </table>            
            </div>
            <script>
            jQuery(function($) {
                $(".cancelBtn").click(function(e){
                    var form = document.getElementById("cancel_form");
                    let text = "Are you sure you want to canceled!";
                    if (confirm(text) == true) {
                        form.submit();
                      } else {
                        e.preventDefault();
                      }
                    });
                });
            </script>';
    }

    return $output;
});

add_action('admin_footer', 'generate_addres_javascript');

function generate_addres_javascript()
{
?>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBUOugdibLDtzLCHOxQjHiMxH5ER5cwlek&v=3.exp&libraries=places"></script>

    <script type="text/javascript">
        jQuery(function($) {
            $('#location_address').prop('readonly', true);

            function initialize() {
                var input = document.getElementById("address");
                var autocomplete = new google.maps.places.Autocomplete(input);
                autocomplete.addListener("place_changed", function() {
                    var place = autocomplete.getPlace();
                    lattitude = place.geometry.location.lat();
                    longitude = place.geometry.location.lng();
                    var location = lattitude + ',' + longitude;
                    $('#location_address').val(location);

                });
            }
            google.maps.event.addDomListener(window, "load", initialize);
        });
    </script>

<?php
}

?>