<?php
$current_user = wp_get_current_user();
$email = $current_user->user_email;
$first_name = get_user_meta($current_user->ID, 'first_name', true);
$last_name = get_user_meta($current_user->ID, 'last_name', true);
$address = get_user_meta($current_user->ID, 'user_address', true);
$contact = get_user_meta($current_user->ID, 'user_contact', true);

$url = admin_url('admin-ajax.php');
global $wpdb;
global $table_prefix;

$terms = get_terms(array(
    'taxonomy' => 'directory_listing_cat',
    'parent'   => 0
));

$term_arr = count($terms);

echo $term_arr;

$terms_shop = get_terms(array(
    'taxonomy' => 'Shop',
    'parent'   => 0
));

print_r($terms_shop);

// $query = get_user_meta($current_user->ID);
// print_r($query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.bundle.min.js"></script>
    <link href="//cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css">
    <script src="//cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Script -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBUOugdibLDtzLCHOxQjHiMxH5ER5cwlek&v=3.exp&libraries=places"></script>

    <script type="text/javascript">
        jQuery(function($) {
            $('#location_address').prop('readonly', true);

            function initialize() {
                var input = document.getElementById("directory_address");
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
</head>

<body>
    <?php
    global $wpdb;
    global $table_prefix;
    $user_id = $current_user->ID;

    $query = $wpdb->get_row("SELECT * FROM {$table_prefix}user_subscriptions_details WHERE user_id = $user_id AND status = 'active'");

    if (isset($query)) {
        $package = wp_json_encode($query);
        $package_data = json_decode($package, true);
    }

    ?>
    <!-- DASHBOARD BODY -->
    <!-- DASHBOARD BODY -->
    <!-- DASHBOARD BODY -->
    <!-- DASHBOARD BODY -->

    <div class="container" id="dashboard_body">
        <div class="main-body">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="main-breadcrumb">
                <ol class="breadcrumb" style="display: flow-root;">
                    <li class="breadcrumb-item active float-left"><a href="javascript:void(0)">Dashboard</a></li>
                    <li class=" float-right"><a class="btn btn-info float-md-right" href="<?php echo wp_logout_url(home_url()); ?>">Logout</a></li>
                </ol>
            </nav>

            <div class="row gutters-sm">
                <div class="col-md-3">
                    <div class="card">
                        <img src="https://cdn3d.iconscout.com/3d/premium/thumb/profile-6073860-4996977.png" class="card-img-top img">
                        <div class="card-body">
                            <center>
                                <h5 class="card-title">My Profile</h5>
                            </center>
                            <center>
                                <form id="profile_details">
                                    <input type="hidden" id="c_user" value="<?php echo $current_user->ID; ?>">
                                    <button type="submit" class="mb-0 btn btn-info text-light" id="edit">Edit Profile</button>
                                </form>
                                <!-- <a href="#" class="btn button">HOVER</a> -->
                            </center>
                        </div>
                    </div>
                </div>
                <?php if ($package_data['package_type'] == "free") { ?>

                <?php } else {
                ?>
                    <div class="col-md-3">
                        <div class="card">
                            <img src="https://cdn3d.iconscout.com/3d/premium/thumb/folder-add-6841960-5604822.png" class="card-img-top img">
                            <div class="card-body">
                                <center>
                                    <h5 class="card-title">Directory</h5>
                                </center>
                                <center>
                                    <?php
                                    global $wpdb;
                                    global $table_prefix;
                                    global $post;
                                    $user = wp_get_current_user();
                                    $Directory = $wpdb->get_results("SELECT * FROM {$table_prefix}posts WHERE post_author = $user->ID AND post_status = 'publish' OR post_status = 'draft';");
                                    $directory_limit = count($Directory);
                                    ?>
                                    <?php if ($directory_limit >= 1 && $package_data['package_type'] == "basic") { ?>
                                        <a href="javascript:void(0)" aria-disabled="true">
                                            <button disabled class="mb-0 btn btn-danger text-light">Limit Reached</button>
                                        </a>
                                    <?php } else if ($directory_limit >= 5 && $package_data['package_type'] == "featured") { ?>
                                        <a href="javascript:void(0)" aria-disabled="true">
                                            <button disabled class="mb-0 btn btn-danger text-light">Limit Reached</button>
                                        </a>
                                    <?php } else if ($directory_limit >= 20 && $package_data['package_type'] == "premium") { ?>
                                        <a href="javascript:void(0)" aria-disabled="true">
                                            <button disabled class="mb-0 btn btn-danger text-light">Limit Reached</button>
                                        </a>
                                    <?php } else { ?>
                                        <a href="javascript:void(0)" id="add_directory">
                                            <button class="mb-0 btn btn-info text-light">Add Directory</button>
                                        </a>
                                    <?php } ?>
                                </center>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card">
                            <img src="https://cdn3d.iconscout.com/3d/premium/thumb/folder-3994326-3307660.png" class="card-img-top img">
                            <div class="card-body">
                                <center>
                                    <h5 class="card-title">My Directories</h5>
                                </center>
                                <center>
                                    <form id="my_directory">
                                        <input type="hidden" id="current_user" value="<?php echo $current_user->ID; ?>">
                                        <button class="mb-0 btn btn-info text-light" id="view_directory">My Directories</button>
                                    </form>
                                </center>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <div class="col-md-3">
                    <div class="card">
                        <img src="https://www.iconeasy.com/icon/png/System/Sticker%20Pack%201/Package.png" class="card-img-top img">
                        <div class="card-body">
                            <center>
                                <h5 class="card-title">Active Package</h5>
                            </center>
                            <center>
                                <button class="btn btn-info text-light" data-toggle="modal" data-target="#exampleModal">Show Details</button>
                            </center>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Package Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <dl>
                                <dt>Package Name</dt>
                                <dd>
                                    <?php if ($package_data['package_type'] == "free") { ?>
                                        FREE PACKAGE
                                    <?php } else { ?>
                                        <?php echo $package_data['item_name'];  ?>
                                    <?php } ?>
                                </dd>
                                <dt>Active Package</dt>
                                <dd>
                                    <?php if ($package_data['package_type'] == "free") { ?>
                                        <span style="font-size: medium;" class="badge badge-danger">FREE</span>
                                    <?php } else if ($package_data['package_type'] == "basic") { ?>
                                        <span style="font-size: medium;" class="badge badge-secondary">BASIC</span>
                                    <?php } else if ($package_data['package_type'] == "featured") { ?>
                                        <span style="font-size: medium;" class="badge badge-info">FEATURED</span>
                                    <?php } else if ($package_data['package_type'] == "premium") { ?>
                                        <span style="font-size: medium;" class="badge badge-warning">PREMIUM</span>
                                    <?php } ?>
                                </dd>
                                <dt>Status</dt>
                                <dd>
                                    <span style="font-size: medium;" class="badge badge-success">Active</span>
                                </dd>
                                <?php if ($package_data['package_type'] == "free") { ?>

                                <?php } else { ?>
                                    <dt>Interval</dt>
                                    <dd>
                                        <?php echo $package_data['plan_interval']; ?>
                                    </dd>
                                    <dt>Purchase Date</dt>
                                    <dd>
                                        <?php echo $package_data['plan_period_start']; ?>
                                    </dd>
                                    <dt>Expiration Date</dt>
                                    <dd>
                                        <?php echo $package_data['plan_period_end']; ?>
                                    </dd>
                                    <dt class="pt-4"></dt>
                                    <dd>
                                        
                                        <form method="post" action="" class="my_form" >
                                            <input type="hidden" name="subscription_id" value="<?php echo $package_data['stripe_subscription_id']; ?>"/>
                                            <input type="hidden" name="user_ID" value="<?php echo $package_data['user_id']; ?>"/>
                                            <input type="hidden" name="cancelSubscription" value="true" />
                                            <a href="javascript:void(0)" class="btn btn-danger cancelbtn">CANCEL PLAN</a>
                                        </form>
                                    </dd>
                                <?php } ?>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- DASHBOARD BODY -->
    <!-- DASHBOARD BODY -->
    <!-- DASHBOARD BODY -->
    <!-- DASHBOARD BODY -->


    <!-- PROFILE BODY -->
    <!-- PROFILE BODY -->
    <!-- PROFILE BODY -->
    <!-- PROFILE BODY -->

    <div class="container" id="profile_body" style="display: none;">
        <div class="main-body">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="main-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a id="bt_dashboard" href="javascript:void(0)">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Profile</li>
                </ol>
            </nav>
            <!-- /Breadcrumb -->

            <div class="row gutters-sm">
                <div class="col-md-12">
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">First Name</h6>
                                </div>
                                <div class="col-sm-9 text-secondary" id="first_name">

                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Last Name</h6>
                                </div>
                                <div class="col-sm-9 text-secondary" id="last_name">

                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Email</h6>
                                </div>
                                <div class="col-sm-9 text-secondary" id="email">

                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Phone</h6>
                                </div>
                                <div class="col-sm-9 text-secondary" id="contact">

                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Address</h6>
                                </div>
                                <div class="col-sm-9 text-secondary" id="address">

                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-8"></div>
                                <div class="col-sm-4 text-secondary">
                                    <input type="button" id="bt_dashboard" class="btn btn-info px-4" value="Back">
                                    <input type="button" id="edit_profile" class="btn btn-danger px-4" value="Edit">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- PROFILE BODY -->
    <!-- PROFILE BODY -->
    <!-- PROFILE BODY -->
    <!-- PROFILE BODY -->

    <!-- EDIT PROFILE BODY -->
    <!-- EDIT PROFILE BODY -->
    <!-- EDIT PROFILE BODY -->
    <!-- EDIT PROFILE BODY -->


    <div>
        <div class="alert alert-success alert-dismissible fade show alert_profile" role="alert">
            <p><i class="fa fa-check-circle"></i>Profile Updated Successfully!</p>
        </div>
    </div>

    <div class="container" id="edit_profile_body" style="display: none;">
        <div class="main-body">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="main-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a id="bt_dashboard" href="javascript:void(0)">Dashboard</a></li>
                    <li class="breadcrumb-item"><a id="back_to_profile" href="javascript:void(0)">Profile</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Profile</li>
                </ol>
            </nav>
            <!-- /Breadcrumb -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <form id="profile_form">
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">First Name</h6>
                                    </div>
                                    <div class="col-sm-9 ">
                                        <input type="text" name="first_name" class="form-control" value="<?php echo $first_name ?>">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Last Name</h6>
                                    </div>
                                    <div class="col-sm-9 ">
                                        <input type="text" name="last_name" class="form-control" value="<?php echo $last_name ?>">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Email</h6>
                                    </div>
                                    <div class="col-sm-9 ">
                                        <input type="text" name="email" class="form-control" value="<?php echo $email; ?>">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Phone</h6>
                                    </div>
                                    <div class="col-sm-9 ">
                                        <input type="text" name="contact" class="form-control" value="<?php echo $contact; ?>">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Address</h6>
                                    </div>
                                    <div class="col-sm-9 ">
                                        <input type="text" name="address" class="form-control" value="<?php echo $address; ?>">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-7"></div>
                                    <div class="col-sm-5 text-secondary">
                                        <input type="submit" class="btn btn-info px-4" value="Save Changes">
                                        <input type="button" id="back_to_profile_btn" class="btn btn-danger px-4" value="Cancel">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- EDIT PROFILE BODY -->
    <!-- EDIT PROFILE BODY -->
    <!-- EDIT PROFILE BODY -->

    <!-- ADD DIRECTORY BODY -->
    <!-- ADD DIRECTORY BODY -->
    <!-- ADD DIRECTORY BODY -->
    <!-- ADD DIRECTORY BODY -->


    <div class="container" id="add_directory_body" style="display: none;">
        <div class="main-body">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="main-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a id="bt_dashboard" href="javascript:void(0)">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Add Directory</li>
                </ol>
            </nav>
            <!-- /Breadcrumb -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <form id="directory_form">
                                <div class="row mb-3">
                                    <div class="col-sm-12 ">
                                        <h6 class="mb-0">Title</h6>
                                        <input required type="text" name="d_title" placeholder="Title" class="form-control">
                                    </div>
                                </div>
                                <hr>
                                <div class="row mb-3">
                                    <div class="col-sm-12 ">
                                        <h6 class="mb-0">Description</h6>
                                        <?php
                                        $args = array(
                                            'tinymce'       => array(
                                                'media_buttons' => false,
                                                'toolbar1'      => 'bold,italic,underline,separator,alignleft,aligncenter,alignright,separator,link,unlink,undo,redo',
                                            ),
                                        );
                                        wp_editor("", "description", $args);
                                        ?>
                                    </div>
                                </div>
                                <hr>
                                <h5 class="mb-10 active" style="text-decoration: underline;font-weight: 800;">Contact Information</h5>
                                <div class="row mb-3">
                                    <div class="col-sm-12 ">
                                        <h6 class="mb-0" style="text-decoration: underline;">Company Name:</h6>
                                        <input required type="text" name="d_company_name" placeholder="Company Name" class="form-control">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-12 ">
                                        <h6 class="mb-0" style="text-decoration: underline;">Visit Website:</h6>
                                        <input type="text" name="d_visit_web" placeholder="Website URL" class="form-control">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-12 ">
                                        <h6 class="mb-0" style="text-decoration: underline;">Online Social Profiles:</h6>
                                        <div class="row mb-3">
                                            <div class="col-sm-3 mt-2">
                                                <input type="text" name="d_Facebook" placeholder="Facebook" class="form-control">
                                            </div>
                                            <div class="col-sm-3 mt-2">
                                                <input type="text" name="d_Twitter" placeholder="Twitter" class="form-control">
                                            </div>
                                            <div class="col-sm-3 mt-2">
                                                <input type="text" name="d_Linkedin" placeholder="LinkedIn" class="form-control">
                                            </div>
                                            <div class="col-sm-3 mt-2">
                                                <input type="text" name="d_Youtube" placeholder="Youtube" class="form-control">
                                            </div>
                                            <div class="col-sm-3 mt-2">
                                                <input type="text" name="d_Instagram" placeholder="Instagram" class="form-control">
                                            </div>
                                            <div class="col-sm-3 mt-2">
                                                <input type="text" name="d_Pinterest" placeholder="Pinterest" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-12 ">
                                        <h6 class="mb-0" style="text-decoration: underline;">Phone Number:</h6>
                                        <input required type="text" name="d_phone" placeholder="Phone Number" class="form-control">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-12 ">
                                        <h6 class="mb-0" style="text-decoration: underline;">Address:</h6>
                                        <input type="text" id="directory_address" name="d_address" placeholder="Address" class="form-control">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-12 ">
                                        <h6 class="mb-0" style="text-decoration: underline;">Location:</h6>
                                        <input readonly type="text" name="d_location" id="location_address" placeholder="Location" class="form-control">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-12 ">
                                        <h6 required class="mb-0" style="text-decoration: underline;">Email Address:</h6>
                                        <input type="text" name="d_email" placeholder="Email Address" class="form-control">
                                    </div>
                                </div>

                                <hr>
                                <h5 class="mb-10 active" style="text-decoration: underline;font-weight: 800;">Company Details</h5>
                                <div class="row mb-3">
                                    <div class="col-sm-12 ">
                                        <h6 class="mb-0" style="text-decoration: underline;">Year Established:</h6>
                                        <input type="text" name="d_year_established" placeholder="Year Established" class="form-control">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-12 ">
                                        <h6 class="mb-0" style="text-decoration: underline;">Annual Sales:</h6>
                                        <input type="text" name="d_annual_sales" placeholder="Annual Sales" class="form-control">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-12 ">
                                        <h6 class="mb-0" style="text-decoration: underline;">No of Employees:</h6>
                                        <input type="text" name="d_employees" placeholder="No of Employees" class="form-control">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-12 ">
                                        <h6 class="mb-0" style="text-decoration: underline;">Hours of Operation:</h6>
                                        <input type="text" name="d_hours_of_operations" placeholder="Working Hours/Days" class="form-control">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-12 ">
                                        <h6 class="mb-0" style="text-decoration: underline;">Accepted Forms of Payment:</h6>
                                        <input type="text" name="d_forms_payment" placeholder="Accepted Forms of Payment" class="form-control">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-12 ">
                                        <h6 class="mb-0" style="text-decoration: underline;">Credentials:</h6>
                                        <input type="text" name="d_creds" placeholder="Credentials" class="form-control">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-sm-12 ">
                                        <h6 class="mb-0" style="text-decoration: underline;">Select Category:</h6>
                                        <select class="js-example-basic-multiple form-control" name="category[]" multiple="multiple" style="width: 100%;">
                                            <?php for ($i = 0; $i < $term_arr; $i++) { ?>
                                                <option value="<?php echo $terms[$i]->term_id; ?>"><?php echo $terms[$i]->name; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-7"></div>
                                    <div class="col-sm-5 text-secondary">
                                        <input type="submit" class="btn btn-info px-4" value="Create Directory">
                                        <input type="button" id="bt_dashboard" class="btn btn-danger px-4" value="Cancel">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div>
        <div class="alert alert-success alert-dismissible fade show alert-add" role="alert">
            <p><i class="fa fa-check-circle"></i>Directory Added Successfully!</p>
        </div>
    </div>
    <div>
        <div class="alert alert-danger alert-dismissible fade show alert-error" role="alert">
            <p><i class="fa fa-check-circle"></i>Directory Listing Failed. Limit Reached!</p>
        </div>
    </div>
    <!-- ADD DIRECTORY BODY -->
    <!-- ADD DIRECTORY BODY -->
    <!-- ADD DIRECTORY BODY -->
    <!-- ADD DIRECTORY BODY -->



    <!-- MY DIRECTORIES -->
    <!-- MY DIRECTORIES -->
    <!-- MY DIRECTORIES -->
    <!-- MY DIRECTORIES -->

    <div class="container" id="my_directory_body" style="display: none;">
        <div class="main-body">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="main-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a id="bt_dashboard" href="javascript:void(0)">Dashboard</a></li>
                    <li class="breadcrumb-item active">My Directories</li>
                </ol>
            </nav>
            <!-- /Breadcrumb -->
            <div class="row gutters-sm">
                <div class="col-md-12">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h2>Select Number Of Rows</h2>
                            <div class="form-group">
                                <style>
                                    .pagination li:hover {
                                        cursor: pointer;
                                    }
                                </style>
                                <!--		Show Numbers Of Rows 		-->
                                <select class="form-control" name="state" id="maxRows">
                                    <option value="5000">Show ALL Rows</option>
                                    <option value="5">5</option>
                                    <option value="10">10</option>
                                    <option value="15">15</option>
                                    <option value="20">20</option>
                                    <option value="50">50</option>
                                    <option value="70">70</option>
                                    <option value="100">100</option>
                                </select>

                            </div>
                            <table id="my_directories">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Status</th>
                                        <th>Comments</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="directory_data">

                                </tbody>
                            </table>
                            <!--		Start Pagination -->
                            <div class='pagination-container'>
                                <nav>
                                    <ul class="pagination">

                                        <li data-page="prev">
                                            <span>
                                                < <span class="sr-only">(current)
                                            </span></span>
                                        </li>
                                        <!--	Here the JS Function Will Add the Rows -->
                                        <li data-page="next" id="prev">
                                            <span> > <span class="sr-only">(current)</span></span>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- MY DIRECTORIES -->
    <!-- MY DIRECTORIES -->
    <!-- MY DIRECTORIES -->
    <!-- MY DIRECTORIES -->


    <!-- DIRECTORY UPDATE BODY -->
    <!-- DIRECTORY UPDATE BODY -->
    <!-- DIRECTORY UPDATE BODY -->
    <!-- DIRECTORY UPDATE BODY -->

    <div class="container" id="update_directory_body" style="display: none;">
        <div class="main-body">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="main-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a id="bt_dashboard" href="javascript:void(0)">Dashboard</a></li>
                    <li class="breadcrumb-item active"><a id="back_to_directory" href="javascript:void(0)">My Directories</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Directory</li>
                </ol>
            </nav>
            <!-- /Breadcrumb -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <form id="update_post_form">
                                <input type="hidden" name="postID_update" id="postID_update">
                                <input type="hidden" name="postSTATUS_update" id="postSTATUS_update">
                                <input type="hidden" name="postNAME_update" id="postNAME_update">
                                <input type="hidden" name="postTYPE_update" id="postTYPE_update">
                                <div class="row mb-3">
                                    <div class="col-sm-12 ">
                                        <h6 class="mb-0">Title</h6>
                                        <input required type="text" name="update_title" id="update_title" placeholder="Title" class="form-control">
                                    </div>
                                </div>
                                <hr>
                                <div class="row mb-3">
                                    <div class="col-sm-12 ">
                                        <h6 class="mb-0">Description</h6>
                                        <textarea id="summernote" name="editordata" class="form-control"></textarea>
                                    </div>
                                </div>
                                <hr>
                                <h5 class="mb-10 active" style="text-decoration: underline;font-weight: 800;">Contact Information</h5>
                                <div class="row mb-3">
                                    <div class="col-sm-12 ">
                                        <h6 class="mb-0" style="text-decoration: underline;">Company Name:</h6>
                                        <input required type="text" name="update_company_name" id="update_company_name" placeholder="Company Name" class="form-control">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-12 ">
                                        <h6 class="mb-0" style="text-decoration: underline;">Visit Website:</h6>
                                        <input type="text" name="update_visit_web" id="update_visit_web" placeholder="Website URL" class="form-control">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-12 ">
                                        <h6 class="mb-0" style="text-decoration: underline;">Online Social Profiles:</h6>
                                        <div class="row mb-3">
                                            <div class="col-sm-3 mt-2">
                                                <input type="text" name="update_Facebook" id="update_Facebook" placeholder="Facebook" class="form-control">
                                            </div>
                                            <div class="col-sm-3 mt-2">
                                                <input type="text" name="update_Twitter" id="update_Twitter" placeholder="Twitter" class="form-control">
                                            </div>
                                            <div class="col-sm-3 mt-2">
                                                <input type="text" name="update_Linkedin" id="update_Linkedin" placeholder="LinkedIn" class="form-control">
                                            </div>
                                            <div class="col-sm-3 mt-2">
                                                <input type="text" name="update_Youtube" id="update_Youtube" placeholder="Youtube" class="form-control">
                                            </div>
                                            <div class="col-sm-3 mt-2">
                                                <input type="text" name="update_Instagram" id="update_Instagram" placeholder="Instagram" class="form-control">
                                            </div>
                                            <div class="col-sm-3 mt-2">
                                                <input type="text" name="update_Pinterest" id="update_Pinterest" placeholder="Pinterest" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-12 ">
                                        <h6 class="mb-0" style="text-decoration: underline;">Phone Number:</h6>
                                        <input required type="text" name="update_phone" id="update_phone" placeholder="Phone Number" class="form-control">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-12 ">
                                        <h6 class="mb-0" style="text-decoration: underline;">Address:</h6>
                                        <input type="text" name="update_address" id="update_address" placeholder="Address" class="form-control">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-12 ">
                                        <h6 required class="mb-0" style="text-decoration: underline;">Email Address:</h6>
                                        <input type="text" name="update_email" id="update_email" placeholder="Email Address" class="form-control">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-12 ">
                                        <h6 class="mb-0" style="text-decoration: underline;">Location:</h6>
                                        <input readonly type="text" name="update_location" id="update_location" placeholder="Location" class="form-control">
                                    </div>
                                </div>
                                <hr>
                                <h5 class="mb-10 active" style="text-decoration: underline;font-weight: 800;">Company Details</h5>
                                <div class="row mb-3">
                                    <div class="col-sm-12 ">
                                        <h6 class="mb-0" style="text-decoration: underline;">Year Established:</h6>
                                        <input type="text" name="update_year_established" id="update_year_established" placeholder="Year Established" class="form-control">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-12 ">
                                        <h6 class="mb-0" style="text-decoration: underline;">Annual Sales:</h6>
                                        <input type="text" name="update_annual_sales" id="update_annual_sales" placeholder="Annual Sales" class="form-control">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-12 ">
                                        <h6 class="mb-0" style="text-decoration: underline;">No of Employees:</h6>
                                        <input type="text" name="update_employees" id="update_employees" placeholder="No of Employees" class="form-control">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-12 ">
                                        <h6 class="mb-0" style="text-decoration: underline;">Hours of Operation:</h6>
                                        <input type="text" name="update_hours_of_operations" id="update_hours_of_operations" placeholder="Working Hours/Days" class="form-control">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-12 ">
                                        <h6 class="mb-0" style="text-decoration: underline;">Accepted Forms of Payment:</h6>
                                        <input type="text" name="update_forms_payment" id="update_forms_payment" placeholder="Accepted Forms of Payment" class="form-control">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-12 ">
                                        <h6 class="mb-0" style="text-decoration: underline;">Credentials:</h6>
                                        <input type="text" name="update_creds" id="update_creds" placeholder="Credentials" class="form-control">
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-7"></div>
                                    <div class="col-sm-5 text-secondary">
                                        <input type="submit" class="btn btn-info px-4" id="get_update" value="Update Directory">
                                        <input type="button" id="bt_dashboard" class="btn btn-danger px-4" value="Cancel">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .alert {
            display: none;
            position: fixed;
            bottom: 0px;
            right: 0px;

        }

        .fa {
            margin-right: .5em;
        }
    </style>
    <!-- Alert Update -->
    <div>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <p><i class="fa fa-check-circle"></i>Directory Updated Successfully!</p>
        </div>
    </div>
    <!-- DIRECTORY UPDATE BODY -->
    <!-- DIRECTORY UPDATE BODY -->
    <!-- DIRECTORY UPDATE BODY -->
    <!-- DIRECTORY UPDATE BODY -->


    <style type="text/css">
        body {
            margin-top: 20px;
            color: #1a202c;
            text-align: left;
            background-color: #e2e8f0;
        }

        h6 {
            text-transform: none !important;
        }

        h5 {
            text-transform: none !important;
        }

        .main-body {
            padding: 15px;
        }

        .card {
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, .1), 0 1px 2px 0 rgba(0, 0, 0, .06);
        }

        .card {
            position: relative;
            display: flex;
            flex-direction: column;
            min-width: 0;
            word-wrap: break-word;
            background-color: #fff;
            background-clip: border-box;
            border: 0 solid rgba(0, 0, 0, .125);
            border-radius: .25rem;
        }

        .card-body {
            flex: 1 1 auto;
            min-height: 1px;
            padding: 1rem;
        }

        .gutters-sm {
            margin-right: -8px;
            margin-left: -8px;
        }

        .gutters-sm>.col,
        .gutters-sm>[class*=col-] {
            padding-right: 8px;
            padding-left: 8px;
        }

        .mb-3,
        .my-3 {
            margin-bottom: 1rem !important;
        }

        .bg-gray-300 {
            background-color: #e2e8f0;
        }

        .h-100 {
            height: 100% !important;
        }

        .shadow-none {
            box-shadow: none !important;
        }

        .breadcrumb {
            margin-left: -7px;
        }
    </style>

    <style type="text/css">
        body {
            background: #f7f7ff;
            margin-top: 20px;
        }

        .card {
            position: relative;
            display: flex;
            flex-direction: column;
            min-width: 0;
            word-wrap: break-word;
            background-color: #fff;
            background-clip: border-box;
            border: 0 solid transparent;
            border-radius: .25rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 6px 0 rgb(218 218 253 / 65%), 0 2px 6px 0 rgb(206 206 238 / 54%);
        }

        .me-2 {
            margin-right: .5rem !important;
        }

        ul.pagination li {
            position: relative;
            display: block;
            padding: 4px 10px;
            color: #0d6efd;
            text-decoration: none;
            background-color: #fff;
            border: 1px solid #dee2e6;
            transition: color .15s ease-in-out, background-color .15s ease-in-out, border-color .15s ease-in-out, box-shadow .15s ease-in-out;
        }

        ul.pagination {
            gap: 1px;
        }

        ul.pagination li:hover,
        ul.pagination li.active {
            background: #007bff;
            color: #fff;
        }
    </style>
    <script>
    jQuery(function($) {
        $(".cancelbtn").click(function(e){
            var form = $(this).parents(".my_form");
            let text = "Are you sure you want to canceled!";
            if (confirm(text) == true) {          
                form.submit();
              } else {
                e.preventDefault();
              }
            });
        });
    </script>
    <!-- SUMMER NOTE -->
    <script>
        jQuery(function($) {
            $('#summernote').summernote({
                tabsize: 2,
                height: 120,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                ]
            });
        });
    </script>
    <!-- BREADCRUMB UPDATE -->
    <script type="text/javascript">
        jQuery(function($) {
            $('#edit').click(function(e) {
                $('#profile_body').css('display', 'block');
                $('#dashboard_body').css('display', 'none');
            });
        });
        jQuery(function($) {
            $('#edit_profile').click(function() {
                $('#profile_body').css('display', 'none');
                $('#edit_profile_body').css('display', 'block');
            });
        });
        jQuery(function($) {
            $('#back_to_profile').click(function() {
                $('#profile_body').css('display', 'block');
                $('#edit_profile_body').css('display', 'none');
            });
        });
        jQuery(function($) {
            $('#back_to_profile_btn').click(function() {
                $('#profile_body').css('display', 'block');
                $('#edit_profile_body').css('display', 'none');
            });
        });
        jQuery(function($) {
            $('#add_directory').click(function() {
                $('#dashboard_body').css('display', 'none');
                $('#add_directory_body').css('display', 'block');
            });
        });
        jQuery(function($) {
            $('#view_directory').click(function() {
                $('#dashboard_body').css('display', 'none');
                $('#my_directory_body').css('display', 'block');
            });
        });
        jQuery(function($) {
            $(document).on('click', '#bt_dashboard', function() {
                $('#dashboard_body').css('display', 'block');
                $('#my_directory_body').css('display', 'none');
                $('#update_directory_body').css('display', 'none');
                $('#profile_body').css('display', 'none');
                $('#edit_profile_body').css('display', 'none');
                $('#add_directory_body').css('display', 'none');
            });
        });
        jQuery(function($) {
            $(document).on('click', '#update_post', function() {
                $('#update_directory_body').css('display', 'block');
                $('#my_directory_body').css('display', 'none');
            });
        });
        jQuery(function($) {
            $(document).on('click', '#back_to_directory', function() {
                $('#update_directory_body').css('display', 'none');
                $('#my_directory_body').css('display', 'block');
            });
        });
    </script>
    <!-- PROFILE UPDATE SCRIPT -->
    <script>
        jQuery(function($) {
            $('#profile_form').submit(function() {
                event.preventDefault();
                var link = "<?php echo admin_url('admin-ajax.php'); ?>"
                var form = $('#profile_form').serialize();
                var formData = new FormData;
                formData.append('action', 'profile_update');
                formData.append('profile_update', form);
                jQuery.ajax({
                    url: link,
                    data: formData,
                    processData: false,
                    contentType: false,
                    type: 'post',
                    success: function(result) {
                        $('#edit_profile_body').css('display', 'none');
                        $('#dashboard_body').css('display', 'block');
                        $(".alert_profile").show('medium');
                        setTimeout(function() {
                            $(".alert_profile").hide('medium');
                        }, 5000);
                    }
                })
            });
        });
    </script>
    <!-- ADD DIRECTORY SCRIPT -->
    <script>
        jQuery(function($) {
            $('#directory_form').submit(function() {
                event.preventDefault();
                var link = "<?php echo admin_url('admin-ajax.php'); ?>"
                var form = $('#directory_form').serialize();
                var desc = tinymce.get('description').getContent();

                var formData = new FormData;
                formData.append('action', 'add_directory');
                formData.append('add_directory', form);
                formData.append('directory_desc', desc);
                jQuery.ajax({
                    url: link,
                    data: formData,
                    processData: false,
                    contentType: false,
                    type: 'post',
                    success: function(result) {
                        console.log(result);
                        if (result.success) {
                            $("#directory_form")[0].reset();
                            $('#add_directory_body').css('display', 'none');
                            $('#dashboard_body').css('display', 'block');
                            $(".alert-add").show('medium');
                            setTimeout(function() {
                                $(".alert-add").hide('medium');
                            }, 5000);
                        } else {
                            $("#directory_form")[0].reset();
                            $('#add_directory_body').css('display', 'none');
                            $('#dashboard_body').css('display', 'block');
                            $(".alert-error").show('medium');
                            setTimeout(function() {
                                $(".alert-error").hide('medium');
                            }, 5000);
                        }
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        $("#directory_form")[0].reset();
                        $('#add_directory_body').css('display', 'none');
                        $('#dashboard_body').css('display', 'block');
                        $(".alert-error").show('medium');
                        setTimeout(function() {
                            $(".alert-error").hide('medium');
                        }, 5000);
                    }
                })
            });
        });
    </script>

    <script>
        function table_sort() {
            const styleSheet = document.createElement('style')
            styleSheet.innerHTML = `
                .order-inactive span {
                    visibility:hidden;
                }
                .order-inactive:hover span {
                    visibility:visible;
                }
                .order-active span {
                    visibility: visible;
                }    `
            document.head.appendChild(styleSheet)

            document.querySelectorAll('th.order').forEach(th_elem => {
                let asc = true
                const span_elem = document.createElement('span')
                span_elem.style = "font-size:0.8rem; margin-left:0.5rem"
                span_elem.innerHTML = "?"
                th_elem.appendChild(span_elem)
                th_elem.classList.add('order-inactive')

                const index = Array.from(th_elem.parentNode.children).indexOf(th_elem)
                th_elem.addEventListener('click', (e) => {
                    document.querySelectorAll('th.order').forEach(elem => {
                        elem.classList.remove('order-active')
                        elem.classList.add('order-inactive')
                    })
                    th_elem.classList.remove('order-inactive')
                    th_elem.classList.add('order-active')

                    if (!asc) {
                        th_elem.querySelector('span').innerHTML = '?'
                    } else {
                        th_elem.querySelector('span').innerHTML = '?'
                    }
                    const arr = Array.from(th_elem.closest("table").querySelectorAll('tbody tr'))
                    arr.sort((a, b) => {
                        const a_val = a.children[index].innerText
                        const b_val = b.children[index].innerText
                        return (asc) ? a_val.localeCompare(b_val) : b_val.localeCompare(a_val)
                    })
                    arr.forEach(elem => {
                        th_elem.closest("table").querySelector("tbody").appendChild(elem)
                    })
                    asc = !asc
                })
            })
        }

        table_sort()
    </script>

    <script>
        $(document).ready(function() {


            getPagination('#my_directories');



            function getPagination(table) {
                var lastPage = 1;

                $('#maxRows')
                    .on('change', function(evt) {
                        lastPage = 1;
                        $('.pagination')
                            .find('li')
                            .slice(1, -1)
                            .remove();
                        var trnum = 0;
                        var maxRows = parseInt($(this).val());

                        if (maxRows == 5000) {
                            $('.pagination').hide();
                        } else {
                            $('.pagination').show();
                        }

                        var totalRows = $(table + ' tbody tr').length; // numbers of rows
                        $(table + ' tr:gt(0)').each(function() {
                            // each TR in  table and not the header
                            trnum++; // Start Counter
                            if (trnum > maxRows) {
                                // if tr number gt maxRows

                                $(this).hide(); // fade it out
                            }
                            if (trnum <= maxRows) {
                                $(this).show();
                            } // else fade in Important in case if it ..
                        }); //  was fade out to fade it in
                        if (totalRows > maxRows) {
                            // if tr total rows gt max rows option
                            var pagenum = Math.ceil(totalRows / maxRows); // ceil total(rows/maxrows) to get ..
                            //	numbers of pages
                            for (var i = 1; i <= pagenum;) {
                                // for each page append pagination li
                                $('.pagination #prev')
                                    .before(
                                        '<li data-page="' +
                                        i +
                                        '">\
                    <span>' +
                                        i++ +
                                        '<span class="sr-only">(current)</span></span>\
			            </li>'
                                    )
                                    .show();
                            } // end for i
                        } // end if row count > max rows
                        $('.pagination [data-page="1"]').addClass('active'); // add active class to the first li
                        $('.pagination li').on('click', function(evt) {
                            // on click each page
                            evt.stopImmediatePropagation();
                            evt.preventDefault();
                            var pageNum = $(this).attr('data-page'); // get it's number

                            var maxRows = parseInt($('#maxRows').val()); // get Max Rows from select option

                            if (pageNum == 'prev') {
                                if (lastPage == 1) {
                                    return;
                                }
                                pageNum = --lastPage;
                            }
                            if (pageNum == 'next') {
                                if (lastPage == $('.pagination li').length - 2) {
                                    return;
                                }
                                pageNum = ++lastPage;
                            }

                            lastPage = pageNum;
                            var trIndex = 0; // reset tr counter
                            $('.pagination li').removeClass('active'); // remove active class from all li
                            $('.pagination [data-page="' + lastPage + '"]').addClass('active'); // add active class to the clicked
                            // $(this).addClass('active');					// add active class to the clicked
                            limitPagging();
                            $(table + ' tr:gt(0)').each(function() {
                                // each tr in table not the header
                                trIndex++; // tr index counter
                                // if tr index gt maxRows*pageNum or lt maxRows*pageNum-maxRows fade if out
                                if (
                                    trIndex > maxRows * pageNum ||
                                    trIndex <= maxRows * pageNum - maxRows
                                ) {
                                    $(this).hide();
                                } else {
                                    $(this).show();
                                } //else fade in
                            }); // end of for each tr in table
                        }); // end of on click pagination list
                        limitPagging();
                    })
                    .val(5)
                    .change();
            }

            function limitPagging() {
                if ($('.pagination li').length > 7) {
                    if ($('.pagination li.active').attr('data-page') <= 3) {
                        $('.pagination li:gt(5)').hide();
                        $('.pagination li:lt(5)').show();
                        $('.pagination [data-page="next"]').show();
                    }
                    if ($('.pagination li.active').attr('data-page') > 3) {
                        $('.pagination li:gt(0)').hide();
                        $('.pagination [data-page="next"]').show();
                        for (let i = (parseInt($('.pagination li.active').attr('data-page')) - 2); i <= (parseInt($('.pagination li.active').attr('data-page')) + 2); i++) {
                            $('.pagination [data-page="' + i + '"]').show();

                        }

                    }
                }
            }

        });
    </script>

    <!-- CATEGORY PERMISSIONS -->

    <?php if ($package_data['package_type'] == "basic") { ?>
        <script>
            // In your Javascript (external.js resource or <script> tag)
            $(document).ready(function() {
                $('.js-example-basic-multiple').select2({
                    maximumSelectionLength: 1,
                    formatSelectionTooBig: function(limit) {

                        return 'You\'re allowed only 1 category for selection';
                    }
                });
            });
        </script>
    <?php } else if ($package_data['package_type'] == "featured") { ?>

        <script>
            // In your Javascript (external.js resource or <script> tag)
            $(document).ready(function() {
                $('.js-example-basic-multiple').select2({
                    maximumSelectionLength: 5,
                    formatSelectionTooBig: function(limit) {

                        return 'You\'re allowed only 5 category for selection';
                    }
                });
            });
        </script>
    <?php } else if ($package_data['package_type'] == "premium") { ?>
        <script>
            // In your Javascript (external.js resource or <script> tag)
            $(document).ready(function() {
                $('.js-example-basic-multiple').select2({
                    maximumSelectionLength: 20,
                    formatSelectionTooBig: function(limit) {

                        return 'You\'re allowed only 20 category for selection';
                    }
                });
            });
        </script>
    <?php } ?>
</body>

</html>