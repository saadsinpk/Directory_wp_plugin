<?php

/**
 * @package
 */
/*
Plugin Name: Directory_Plugin
Plugin URI: #
Description: Developed by Sid Techno.
Version: 1.0.0
Author: Sidtehcno
Author URI: https://portal.sidtechno.com
License: GPLv2 or later
Text Domain: directory-login-register-plugin
*/

add_shortcode('post_meta_shortcode', 'getpostmeta');
function getpostmeta()
{
    echo '<pre>';
    echo count(get_post_meta('5314', ''));
    print_r(get_post_meta('5314', ''));
    echo '</pre>';
}




// DIRECTORY CONFIG// DIRECTORY CONFIG// DIRECTORY CONFIG// DIRECTORY CONFIG
// DIRECTORY CONFIG// DIRECTORY CONFIG// DIRECTORY CONFIG// DIRECTORY CONFIG

function directory_admin_menu()
{
    add_menu_page(
        __('Directory Configuration', 'my-textdomain'),
        __('Directory Configuration', 'my-textdomain'),
        'manage_options',
        'directory-configuration',
        'directory__page_contents',
        'dashicons-schedule',
        3
    );
}

add_action('admin_menu', 'directory_admin_menu');

function directory__page_contents()
{
    include 'admin_template/directory_configuration.php';
}


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
                $('#get-text-search-data').attr('disabled', true);
                $('#get-nearby-data').attr('disabled', true);
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
                    console.log(res);

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
                        $('#get-text-search-data').attr('disabled', false);
                        $('#get-nearby-data').attr('disabled', false);
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

                        var arr_key = [];
                        <?php
                        global $wpdb;
                        $place_check = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}postmeta WHERE meta_key = %s", "golo-google_review_placeid"));
                        for ($m = 0; $m < count($place_check); $m++) {
                        ?>
                            arr_key.push('<?php echo $place_check[$m]->meta_value; ?>');
                        <?php } ?>

                        // console.log(arr_key[0]);
                        // var add_button;
                        for (var index = 0; index < res[0].length; index++) {

                            // if(jQuery.inArray(res[0][index].place_id, arr_key)){
                            //     add_button = '<button disabled id="add_to_list' + index + '" type="submit" class="btn btn-danger" style="display: block;">Already Listed</button>';
                            // }else{
                            //     add_button = '<button name="add_to_list" id="add_to_list' + index + '" type="submit" class="btn btn-success" style="display: block;">Add Listing</button>';
                            // }

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
                            html_append = '<tr><td><p class="logo"><img class="rounded-circle" alt="Company Logo" src="' + res[0][index].logo + '" onerror="this.src=\'https://staging.restaurantsinwisconsin.com/wp-content/plugins/directory-plugin/defaultLogo.png\';" /></td><td class="title">' + res[0][index].owner_title + '</td><td class="description">' + res[0][index].description + '</td> <td><p class="site" style="display:none;">' + res[0][index].site + '</p> <a style="color: blue;" target="_blank" href="' + res[0][index].site + '"><i class="fa fa-globe" aria-hidden="true"></i> Visit</a></td><td class="phone">' + res[0][index].phone + '</td> <td class="nr"><p class="location" style="display:none;">' + res[0][index].location_link + '</p><a style="color: red;" target="_blank" href="' + res[0][index].location_link + '"><i class="fa fa-location-arrow" aria-hidden="true"></i> Go To..</a></td> <td class="working-hours">' + 'Monday: ' + Monday + ' , ' + 'Tuesday: ' + Tuesday + ' , ' + 'Wednesday: ' + Wednesday + ' , ' + 'Thursday: ' + Thursday + ' , ' + 'Friday: ' + Friday + ' , ' + 'Saturday: ' + Saturday + 'Sunday: ' + Sunday + '</td><td class="address">' + res[0][index].full_address + '</td><td class="load_data_main"><div class="load_data_inner"><input type="checkbox" id="review_click_' + index + '" class="review_click" checked name="reviews" value="' + res[0][index].place_id + '" /><label for="review_click_' + index + '">Load Reviews</label></div></td><td><form id="listing_form_' + index + '"  enctype="multipart/form-data"><input hidden type="text" name="logo" id="logo" value="' + res[0][index].logo + '"/><input hidden type="text" name="reviews" class="review_hidden" value="' + res[0][index].place_id + '"/><input hidden type="text" name="company_name" id="company_name" value="' + res[0][index].owner_title + '"/><input hidden type="text" name="description" id="description" value="' + res[0][index].description + '"/><input hidden type="text" name="site" id="site" value="' + res[0][index].site + '"/><input hidden type="text" name="phone" id="phone" value="' + res[0][index].phone + '"/><input hidden type="text" name="location" id="location" value="' + res[0][index].location_link + '"/><input hidden type="text" name="working_hours" id="working_hours" value="' +
                                Monday + ' , ' + Tuesday + ' , ' + Wednesday + ' , ' + Thursday + ' , ' + Friday + ' , ' + Saturday + ' , ' + Sunday +
                                '"/><input hidden type="text" name="address" id="address" value="' + res[0][index].full_address + '"/><input hidden type="text" name="latitude" id="latitude" value="' + res[0][index].latitude + '"/><input hidden type="text" name="time_zone" id="time_zone" value="' + res[0][index].time_zone + '"/><input hidden type="text" name="rating" id="rating" value="' + res[0][index].rating + '"/><input hidden type="text" name="type" id="type" value="' + res[0][index].type + '"/><input hidden type="text" name="range" id="range" value="' + res[0][index].range + '"/><input hidden type="text" name="street" id="street" value="' + res[0][index].street + '"/><input hidden type="text" name="state" id="state" value="' + res[0][index].state + '"/><input hidden type="text" name="street" id="street" value="' + res[0][index].street + '"/><input hidden type="text" name="us_state" id="us_state" value="' + res[0][index].us_state + '"/><input hidden type="text" name="postal_code" id="postal_code" value="' + res[0][index].postal_code + '"/><input hidden type="text" name="menu_link" id="menu_link" value="' + res[0][index].menu_link + '"/><input hidden type="text" name="street" id="street" value="' + res[0][index].street + '"/><input hidden type="text" name="longitude" id="longitude" value="' + res[0][index].longitude + '"/><input hidden type="text" name="country_code" id="country_code" value="' + res[0][index].country_code + '"/><input value="' + nearby_cat + '" type="hidden" name="nearby_value" id="nearby_value"><input value="' + nearby_sub + '" type="hidden" name="nearby_subCategory_value" id="nearby_subCategory_value"/><input value="' + outscrap_cat + '" type="hidden" name="outscrap_value" id="outscrap_value"/><input value="' + outscrap_sub + '" type="hidden" name="outscrap_subCategory_value" id="outscrap_subCategory_value"/><input value="' + text_search_cat + '" type="hidden" name="text_search_value" id="text_search_value"/><input value="' + text_search_sub + '" type="hidden" name="text_search_subCategory_value" id="text_search_subCategory_value"/><input type="hidden" name="nearby_text_value" id="nearby_text_value" value="' + nearby_subCategory_text + '"/><input type="hidden" name="text_search_text_value" id="text_search_text_value" value="' + text_search_subCategory_text + '"/><input type="hidden" name="outscrap_text_value" id="outscrap_text_value" value="' + outscrap_subCategory_text + '"/><input type="hidden" name="texonomy_city" id="texonomy_city" value="' + res[0][index].city + '"/><button name="add_to_list" id="add_to_list' + index + '" type="submit" class="btn btn-success" style="display: block;">Add Listing</button><div id="addlistingLoader" style="display: none !important;display: flex;justify-content: center;" class="dataTables_processing"><img src="https://i.stack.imgur.com/hzk6C.gif" height="140" alt=""></div></form></td></tr>';
                            $('#searchData').append(html_append);

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
                            script.innerHTML = "jQuery('#listing_form_" + index + "').submit(function () {event.preventDefault();$('#addlistingLoader').css('display', 'block');$('#add_to_list').css('display', 'none');var link= ajaxurl ;var form = jQuery('#listing_form_" + index + "').serialize();var formData = new FormData;formData.append('action', 'add_listing');formData.append('add_listing', form);jQuery.ajax({url: link,data: formData,processData: false,contentType: false,type: 'post',success:function(result){console.log(result.data);document.getElementById('failed').style.display = 'block';$('#addlistingLoader').css('display', 'none');$('#add_to_list').css('display', 'block');setTimeout(function(){document.getElementById('failed').style.display = 'none'},5000)}, error: function(result){console.log(result.data);document.getElementById('success').style.display = 'block';$('#addlistingLoader').css('display', 'none');$('#add_to_list').css('display', 'block');setTimeout(function() {document.getElementById('success').style.display = 'none'}, 5000)}});});";
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
    // verify if directory exists

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

    $title = str_replace(array(':', '/\\\\/', '\'', '"', ',', ';', '<', '>', '&', '/', '—', '-', '+', '%', '$', '#', '@', '!', '^', '*', '.', ''), ' ', $str);
    $filter = explode(' ', $title);
    $post_name = '';
    $title_name = '';
    for ($i = 0; $i < count($filter); $i++) {
        $filter_image = stripslashes($filter[$i]);
        $post_name .= $filter_image;
        $title_name .= ' '. $filter_image;
    }

    $place_check = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}postmeta WHERE meta_value = %s", $arr["reviews"]));

    if ($place_check == null) {
        $result_post = $wpdb->insert($table, [
            "post_author" => $user->ID,
            "post_date" => date("Y-m-d H:i:s"),
            "post_date_gmt" => date("Y-m-d H:i:s"),
            "post_modified" => date("Y-m-d H:i:s"),
            "post_modified_gmt" => date("Y-m-d H:i:s"),
            "post_title" => $title_name,
            "post_content" => $arr['description'],
            "post_status" => 'publish',
            "post_name" => strtolower($post_name),
            "post_type" => 'place'
        ]);
        $lastid = $wpdb->insert_id;
        $table_update = $table_prefix . "posts";
        $GUID = get_site_url() . '?post_type=place&p=' . $lastid . '&preview=true';
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
                $url_img = "https://maps.googleapis.com/maps/api/place/photo?maxwidth=1600&photo_reference=" . $res_img['result']['photos'][$i]['photo_reference'] . "&key=AIzaSyBUOugdibLDtzLCHOxQjHiMxH5ER5cwlek";
                $loc = explode('Location:', get_headers($url_img)[5]);
                array_push($image_keys, $loc[1]);
            }
            $img_ids = [];
            for ($j = 0; $j < count($image_keys); $j++) {
                $urlImage = $image_keys[$j];
                $att_id = sid_upload_from_url(str_replace(' ', '', $urlImage));
                array_push($img_ids, $att_id);
            }

            print_r($img_ids);

            $att_id_new = implode('|', $img_ids);

            // add_post_meta


            $place_id = $arr['reviews'];
            //golo-place_author
            add_post_meta($lastid, "golo-place_author", "");
            //golo-place_agent
            add_post_meta($lastid, "golo-place_agent", "");
            //golo-place_identity
            add_post_meta($lastid, "golo-place_identity", $lastid);
            //golo-place_price
            add_post_meta($lastid, "golo-place_price", "");
            //rank_math_internal_links_processed
            add_post_meta($lastid, "rank_math_internal_links_processed", "");
            //rank_math_seo_score
            add_post_meta($lastid, "rank_math_seo_score", "");
            //rank_math_focus_keyword
            add_post_meta($lastid, "rank_math_focus_keyword", $arr["company_name"]);
            //inline_featured_image
            add_post_meta($lastid, "inline_featured_image", "0");
            //golo-place_price_short
            add_post_meta($lastid, "golo-place_price_short", "");
            //golo-place_price_unit
            add_post_meta($lastid, "golo-place_price_unit", "");
            //golo-place_price_range
            add_post_meta($lastid, "golo-place_price_range", "");
            //golo-place_booking_type
            add_post_meta($lastid, "golo-place_booking_type", "");
            //golo-place_booking
            add_post_meta($lastid, "golo-place_booking", "");
            //golo-place_booking_site
            add_post_meta($lastid, "golo-place_booking_site", "");
            //golo-place_booking_2
            add_post_meta($lastid, "golo-place_booking_2", "");
            //golo-place_booking_site_2
            add_post_meta($lastid, "golo-place_booking_site_2", "");
            //golo-place_booking_banner
            add_post_meta($lastid, "golo-place_booking_banner", "");
            //golo-place_booking_banner_url
            add_post_meta($lastid, "golo-place_booking_banner_url", "");
            //golo-place_booking_form
            add_post_meta($lastid, "golo-place_booking_form", "");
            //golo-place_phone
            add_post_meta($lastid, "golo-place_phone", $arr["phone"]);
            //golo-place_phone2
            add_post_meta($lastid, "golo-place_phone2", "");
            //golo-place_email
            add_post_meta($lastid, "golo-place_email", "");
            //golo-place_website
            add_post_meta($lastid, "golo-place_website", $arr["site"]);
            //golo-google_review_placeid
            add_post_meta($lastid, "golo-google_review_placeid", $place_id);
            //golo-place_facebook
            add_post_meta($lastid, "golo-place_facebook", "");
            //golo-place_instagram
            add_post_meta($lastid, "golo-place_instagram", "");
            //golo-place_twitter
            add_post_meta($lastid, "golo-place_twitter", "");
            //golo-additional_detail
            add_post_meta($lastid, "golo-additional_detail", "");
            //golo-additional_detail_icon
            add_post_meta($lastid, "golo-additional_detail_icon", "");
            //golo-additional_detail_name
            add_post_meta($lastid, "golo-additional_detail_name", "");
            //golo-additional_detail_url
            add_post_meta($lastid, "golo-additional_detail_url", "");
            //golo-opening_monday
            add_post_meta($lastid, "golo-opening_monday", "Monday");
            //golo-opening_monday_time
            $workingHours = explode(',', $arr["working_hours"]);
            add_post_meta($lastid, "golo-opening_monday_time", $workingHours[0]);
            //golo-opening_tuesday
            add_post_meta($lastid, "golo-opening_tuesday", "Tuesday");
            //golo-opening_tuesday_time
            add_post_meta($lastid, "golo-opening_tuesday_time", $workingHours[1]);
            //golo-opening_wednesday
            add_post_meta($lastid, "golo-opening_wednesday", "Wednesday");
            //golo-opening_wednesday_time
            add_post_meta($lastid, "golo-opening_wednesday_time", $workingHours[2]);
            //golo-opening_thursday
            add_post_meta($lastid, "golo-opening_thursday", "Thursday");
            //golo-opening_thursday_time
            add_post_meta($lastid, "golo-opening_thursday_time", $workingHours[3]);
            //golo-opening_friday
            add_post_meta($lastid, "golo-opening_friday", "Friday");
            //golo-opening_friday_time
            add_post_meta($lastid, "golo-opening_friday_time", $workingHours[4]);
            //golo-opening_saturday
            add_post_meta($lastid, "golo-opening_saturday", "Saturday");
            //golo-opening_saturday_time
            add_post_meta($lastid, "golo-opening_saturday_time", $workingHours[5]);
            //golo-opening_sunday
            add_post_meta($lastid, "golo-opening_sunday", "Sunday");
            //golo-opening_sunday_time
            add_post_meta($lastid, "golo-opening_sunday_time", $workingHours[6]);
            //golo-yelp_review
            add_post_meta($lastid, "golo-yelp_review", "");
            //golo-yelp_review_title
            add_post_meta($lastid, "golo-yelp_review_title", "");
            //golo-yelp_review_type
            add_post_meta($lastid, "golo-yelp_review_type", "");
            //golo-place_views_count
            add_post_meta($lastid, "golo-place_views_count", "");
            //golo-menu_enable
            add_post_meta($lastid, "golo-menu_enable", "");
            //golo-menu_types
            add_post_meta($lastid, "golo-menu_types", "");
            //golo-menu_types_name
            add_post_meta($lastid, "golo-menu_types_name", "");
            //golo-menu_tab
            add_post_meta($lastid, "golo-menu_tab", "");
            //golo-place_address
            add_post_meta($lastid, "golo-place_address", $arr["street"]);
            //golo-place_zip
            add_post_meta($lastid, "golo-place_zip", $arr["postal_code"]);
            //golo-place_timezone
            add_post_meta($lastid, "golo-place_timezone", $arr["time_zone"]);
            //golo-place_location
            $lat_lon = $arr["latitude"] . ',' . $arr["longitude"];
            $location = array(
                'location' => $lat_lon,
                'address' => $arr["address"]
            );
            add_post_meta($lastid, "golo-place_location", $location);
            //golo-place_featured
            add_post_meta($lastid, "golo-place_featured", "0");
            //golo-place_logged
            add_post_meta($lastid, "golo-place_logged", "");
            //golo-place_images
            add_post_meta($lastid, "golo-place_images", "$att_id_new");
            //golo-place_video_url
            add_post_meta($lastid, "golo-place_video_url", "");
            //golo-place_video_image
            add_post_meta($lastid, "golo-place_video_image", "");
            //golo-faqs_enable
            add_post_meta($lastid, "golo-faqs_enable", "0");
            //golo-faqs_tab
            add_post_meta($lastid, "golo-faqs_tab", "");
            //rank_math_primary_place-type
            add_post_meta($lastid, "rank_math_primary_place-type", "0");
            //rank_math_primary_place-categories
            add_post_meta($lastid, "rank_math_primary_place-categories", "0");
            //rank_math_primary_place-amenities
            add_post_meta($lastid, "rank_math_primary_place-amenities", "0");
            //rank_math_analytic_object_id
            add_post_meta($lastid, "rank_math_analytic_object_id", "");
            //golo-average_rating
            add_post_meta($lastid, "golo-average_rating", $arr["rating"]);
            //rank_math_rich_snippet
            add_post_meta($lastid, "rank_math_rich_snippet", "off");
            //rank_math_schema_Restaurant
            $ttle = ucfirst($arr["category"]);
            $restaurant_schema = array(
                'metadata' => array(
                    'type' => $arr["type"],
                    'shortcode' => '',
                    'isPrimary' => '1',
                    'title' => $ttle,
                    'reviewLocationShortcode' => '[rank_math_rich_snippet]'
                ),
                '@type' => $arr['type'],
                'name' => $arr['company_name'],
                'description' => $arr['description'],
                'telephone' => $arr['phone'],
                'priceRange' => $arr["range"],
                'address' => array(
                    '@type' => 'Postal Address',
                    'streetAddress' => $arr["street"],
                    'addressLocality' => $arr["state"],
                    'addressRegion' => $arr["us_state"],
                    'postalCode' => $arr["postal_code"],
                    'addressCountry' => $arr["country_code"]
                ),
                'geo' => array(
                    '@type' => 'GeoCoordinates',
                    'latitude' => $arr["latitude"],
                    'longitude' => $arr["longitude"]
                ),
                'openingHoursSpecification' => array(
                    array(
                        '@type' => 'openingHoursSpecification',
                        'dayOfWeek' => array(
                            'Monday',
                            'Tuesday',
                            'Wednesday',
                            'Thursday',
                            'Friday',
                        ),
                        'opens' => "",
                        'closes' => ""

                    ),
                    array(
                        '@type' => 'openingHoursSpecification',
                        'dayOfWeek' => array(
                            'Sunday',
                        ),
                        'opens' => "",
                        'closes' => ""
                    )
                ),
                'servesCuisine' => array(
                    'German'
                ),
                'hasMenu' => $arr["menu_link"],
                'image' => array(
                    '@type' => 'ImageObject',
                    'url' => '%post_thumbnail%'
                )
            );
            add_post_meta($lastid, "rank_math_schema_Restaurant", $restaurant_schema);
            //rank_math_shortcode_schema_s-9a2bce3a-d5f9-47e1-987e-0d6b2bfbe9d4
            add_post_meta($lastid, "rank_math_shortcode_schema_s-9a2bce3a-d5f9-47e1-987e-0d6b2bfbe9d4", "");

            $post_id = $lastid;
            $taxonomy = $category;
            wp_set_object_terms($post_id, intval($sub_category), $taxonomy, true);

            // CHECK IF TERM EXISTS CITY/REGION
            $terms = get_terms([
                'taxonomy' => 'place-city',
                'hide_empty' => false,
            ]);
    
            $terms_name = [];
            for ($indx=0; $indx < count($terms); $indx++) { 
                $terms_name[$terms[$indx]->term_id] = $terms[$indx]->name;
            }
    
            if(in_array($arr['texonomy_city'],$terms_name)){
                $term = array_search($arr['texonomy_city'], $terms_name);
                wp_set_object_terms($post_id, intval($term), 'place-city', true);
            }else{
                wp_insert_term($arr['texonomy_city'], 'place-city');
                $last_term_id = $wpdb->insert_id;
                wp_set_object_terms($post_id, intval($last_term_id), 'place-city', true);
            }
            // FEATURE IMAGE WORK


            // only need these if performing outside of admin environment
            require_once(ABSPATH . 'wp-admin/includes/media.php');
            require_once(ABSPATH . 'wp-admin/includes/file.php');
            require_once(ABSPATH . 'wp-admin/includes/image.php');

            // featured image
            $image = '';

            $img = $arr['logo'];

            $filter_img = explode('s44-p-k-no-ns-nd/', $img);

            for ($i = 0; $i < count($filter_img); $i++) {
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

            wp_send_json_success("Listing Added To Draft Successfully.");

            // if ($result_post_meta_rank_math_shortcode_schema > 0) {
            //     wp_send_json_success("Listing Added To Draft Successfully.");
            // } else {
            //     wp_send_json_error("Listing Failed. Please Try Again!");
            // }
        }
    } else {
        wp_send_json_error("Listing Failed.");
        exit();
    }
    // wp_send_json_success("test");
}

function sid_upload_from_url($url, $title = null)
{
    require_once(ABSPATH . "/wp-load.php");
    require_once(ABSPATH . "/wp-admin/includes/image.php");
    require_once(ABSPATH . "/wp-admin/includes/file.php");
    require_once(ABSPATH . "/wp-admin/includes/media.php");

    // Download url to a temp file
    $tmp = download_url($url);
    if (is_wp_error($tmp)) return false;

    // Get the filename and extension ("photo.png" => "photo", "png")
    $filename = pathinfo($url, PATHINFO_FILENAME);
    $extension = pathinfo($url, PATHINFO_EXTENSION);

    // An extension is required or else WordPress will reject the upload
    if (!$extension) {
        // Look up mime type, example: "/photo.png" -> "image/png"
        $mime = mime_content_type($tmp);
        $mime = is_string($mime) ? sanitize_mime_type($mime) : false;

        // Only allow certain mime types because mime types do not always end in a valid extension (see the .doc example below)
        $mime_extensions = array(
            // mime_type         => extension (no period)
            'text/plain'         => 'txt',
            'text/csv'           => 'csv',
            'application/msword' => 'doc',
            'image/jpg'          => 'jpg',
            'image/jpeg'         => 'jpeg',
            'image/gif'          => 'gif',
            'image/png'          => 'png',
            'video/mp4'          => 'mp4',
        );

        if (isset($mime_extensions[$mime])) {
            // Use the mapped extension
            $extension = $mime_extensions[$mime];
        } else {
            // Could not identify extension
            @unlink($tmp);
            return false;
        }
    }

    // Upload by "sideloading": "the same way as an uploaded file is handled by media_handle_upload"
    $args = array(
        'name' => "$filename.$extension",
        'tmp_name' => $tmp,
    );

    // Do the upload
    $attachment_id = media_handle_sideload($args, 0, $title);

    // echo "<pre>";
    // print_r($attachment_id);
    // echo "</pre>";
    // echo "<br>";
    // echo $extension;
    // echo 'testsss';
    // Cleanup temp file
    @unlink($tmp);

    // Error uploading
    if (is_wp_error($attachment_id)) return false;

    // Success, return attachment ID (int)
    return (int) $attachment_id;
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
        // if (isset($_POST['google_yelp_listings__default_membership_level'])) {
        //     $default_membership_level = $_POST['google_yelp_listings__default_membership_level'];
        //     update_option("default_membership_level", "$default_membership_level");
        // }
    }
}
