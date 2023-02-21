<?php

if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}
global $post;
$post_id = $post->ID;

get_header();

$get_post_meta = get_post_meta($post_id, '', true);
$comments = wp_count_comments($post_id);
$total_comments = $comments->total_comments;

$average = directory_comment_rating_get_average_ratings($post_id);
$stars = "";
$count = 0;
for ($i = 1; $i <= $average + 1; $i++) {

  $width = intval($i - $average > 0 ? 20 - (($i - $average) * 20) : 20);

  if (0 === $width) {
    continue;
  }

  $stars .= '<span style="overflow:hidden; width:' . $width . 'px" class="dashicons dashicons-star-filled"></span>';

  if ($i - $average > 0) {
    $stars .= '<span style="overflow:hidden; position:relative; left:-' . $width . 'px;" class="dashicons dashicons-star-empty"></span>';
  }
  $count++;
}
if (empty($stars)) {
  $average = 0;
}
$remainStar = 5 - $count;
for ($i = 0; $i < $remainStar; $i++) {
  $stars .= '<span style="overflow:hidden; position:relative;left:-' . $width . 'px;" class="dashicons dashicons-star-empty"></span>';
}

$cats = get_the_terms( $post->ID, 'Event' );
print_r($cats);


?>

<?php
global $wpdb;
global $table_prefix;
$get_post_author = $post->post_author;

// $check_admin = $wpdb->get_results("
// SELECT {$table_prefix}users.* , {$table_prefix}usermeta.*
// FROM {$table_prefix}users
// INNER JOIN {$table_prefix}usermeta
// ON {$table_prefix}users.ID = {$table_prefix}usermeta.user_id
// WHERE {$table_prefix}users.ID = $get_post_author;");

$check_admin = get_user_by('ID', $get_post_author);

// print_r($get_post_author);

$query = $wpdb->get_row("SELECT * FROM {$table_prefix}user_subscriptions_details WHERE `user_id` = $get_post_author AND `status` = 'active'");
// var_dump($query);

$package = json_encode($query);
$package_data = json_decode($package, true);
// print_r($package_data);
// echo $get_post_author;

?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" integrity="sha512-YWzhKL2whUzgiheMoBFwW8CKV4qpHQAEuvilg9FAn5VJUDwKZZxkJNuGM4XkWuk94WCrrwslk8yWNGmY1EduTA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous" />
<link rel="stylesheet" href="<?php echo plugin_dir_url(__FILE__); ?>/assets/css/style.css" />
<link rel="stylesheet" href="<?php echo plugin_dir_url(__FILE__); ?>/assets/css/lightbox.css" />
<!-- Link Swiper's CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css" />
<!-- GALLERY SLIDER -->
<style>
  .swiper {
    width: 100%;
    height: 100%;
  }

  .swiper-slide {
    text-align: center;
    font-size: 18px;
    background: #fff;

    /* Center slide text vertically */
    display: -webkit-box;
    display: -ms-flexbox;
    display: -webkit-flex;
    display: flex;
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    -webkit-justify-content: center;
    justify-content: center;
    -webkit-box-align: center;
    -ms-flex-align: center;
    -webkit-align-items: center;
    align-items: center;
  }

  .swiper-slide img {
    display: block;
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  .swiper-pagination-bullet-active {
    background: white !important;
  }

  .swiper-slide:before {
    content: "";
    display: block;
    position: absolute;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.4);
  }
</style>
<!-- Swiper -->
<div class="swiper mySwiper">
  <div class="swiper-wrapper">
    <?php

    if (isset($get_post_meta['gallery_image'][0])) {

      $images = unserialize($get_post_meta['gallery_image'][0]);
      if ($images) {


        for ($i = 0; $i < count($images); $i++) {
          // print_r($images[$i]);
          echo '<div class="swiper-slide"><img style="max-height: 320px;" src="https://maps.googleapis.com/maps/api/place/photo?maxwidth=400&photo_reference=' . $images[$i] . '&key=AIzaSyBUOugdibLDtzLCHOxQjHiMxH5ER5cwlek" alt="slider_image"></div>';
        }
      }
    } else {
      echo "<p class='text-light text-center'>No image to display.</p>";
    }
    ?>
  </div>
  <div class="swiper-button-next text-light"></div>
  <div class="swiper-button-prev text-light"></div>
  <div class="swiper-pagination text-light"></div>
</div>


<div class="sectionbody">
  <div class="container py-4">
    <div class="row">
      <div class="sectionshowing col-lg-12">
        <div class="mainglobal">
          <div class="global_top py-3 px-3">
            <div class="mainmain row">
              <div class="mainmain_left col-lg-10">
                <div class="mainmain_left--top py-2 d-flex align-items-center">
                  <div class="mainmain_left--top-l">
                    <h1 class="globaltext m-0" style="text-transform: capitalize;"><b><?php echo get_the_title(); ?></b></h1>
                  </div>
                  <div class="mainmain_left--top-r d-flex align-items-center w-100">
                    <?php
                    echo $stars;
                    ?>
                    <div class="mainmain_left--top-r-l d-flex gap-1">

                      <span class="ratedtext">Rated <?php echo $average; ?>/5 (<?php echo $total_comments; ?> Reviews)</span>
                      <?php if (get_post_meta($post_id, 'check_box_verify', true) == "Verified") { ?>
                        <img src="<?php echo plugin_dir_url(__FILE__); ?>/assets/images/tick.png" alt="">
                      <?php } ?>
                    </div>

                  </div>
                </div>
                <div class="mainpalletsection d-flex gap-2 py-4">
                  <div class="section_pallet_left">
                    <?php
                    $imageUrl = get_the_post_thumbnail_url($post_id, 'full');
                    $image = "";
                    if (!empty($imageUrl)) {
                      $image = $imageUrl;
                    } else {
                      $image = plugins_url('assets/images/directory2.png', __FILE__);
                    }
                    ?>
                    <img class="primerlogo" src="<?php echo $image; ?>" alt="" style="max-width: 200px;max-height: 175px;" />
                  </div>
                  <div class="pallet_text_right mt-3">
                    <h1 class="pallettext"><b><span style="text-decoration: underline;">Category:</span>
                        <?php
                        $categories = get_the_terms($post_id, 'Post');
                        if (!empty($categories)) {
                          $categories_value = "";
                          foreach ($categories as $category_key => $category_value) {
                            $categories_value .= $category_value->name . ",";
                          }
                          echo trim($categories_value, ",");
                          echo ",";
                        }
                        $categories_Shop = get_the_terms($post_id, 'Shop');
                        if (!empty($categories_Shop)) {
                          $categories_Shop_value = "";
                          foreach ($categories_Shop as $category_Shop_key => $category_Shop_value) {
                            $categories_Shop_value .= $category_Shop_value->name . ",";
                          }
                          echo trim($categories_Shop_value, ",");
                          echo ",";
                        }
                        $categories_Service = get_the_terms($post_id, 'Service');
                        if (!empty($categories_Service)) {
                          $categories_Service_value = "";
                          foreach ($categories_Service as $category_Service_key => $category_Service_value) {
                            $categories_Service_value .= $category_Service_value->name . ",";
                          }
                          echo trim($categories_Service_value, ",");
                          echo ",";
                        }
                        $categories_Event = get_the_terms($post_id, 'Event');
                        if (!empty($categories_Event)) {
                          $categories_Event_value = "";
                          foreach ($categories_Event as $category_Event_key => $category_Event_value) {
                            $categories_Event_value .= $category_Event_value->name . ",";
                          }
                          echo trim($categories_Event_value, ",");
                          echo ",";
                        }
                        $categories_Restaurant = get_the_terms($post_id, 'Restaurant');
                        if (!empty($categories_Restaurant)) {
                          $categories_Restaurant_value = "";
                          foreach ($categories_Restaurant as $category_Restaurant_key => $category_Restaurant_value) {
                            $categories_Restaurant_value .= $category_Restaurant_value->name . ",";
                          }
                          echo trim($categories_Restaurant_value, ",");
                          echo ",";
                        }
                        $categories_Classified = get_the_terms($post_id, 'Classified');
                        if (!empty($categories_Classified)) {
                          $categories_Classified_value = "";
                          foreach ($categories_Classified as $category_Classified_key => $category_Classified_value) {
                            $categories_Classified_value .= $category_Classified_value->name . ",";
                          }
                          echo trim($categories_Classified_value, ",");
                        }
                        ?></b></h1>
                    <h1 class="pallettext"><b><?php echo substr(get_the_content(), 0, 300); ?></b></h1>
                    <?php if (get_post_meta($post_id, 'address', true)) { ?>
                      <h1 class="pallettext">
                        <i class="backcolor fa-solid fa-location-dot"></i>
                        <?php
                        echo get_post_meta($post_id, 'address', true);
                        ?>
                      </h1>
                    <?php } ?>

                    <div class="sectionbox d-flex gap-1 mt-5  ">
                      <a href="<?php echo get_site_url(); ?>/request-quote/?post_id=<?php echo $post_id; ?>" class="seephonebtn d-flex align-items-center" type="button">
                        <b>REQUEST FREE QUOTES</b>
                      </a>
                      <?php if ($check_admin->roles[0] == 'administrator') { ?>
                        <a id="see_phone" class="seephonebtn d-flex align-items-center gap-1" style="color:white;" type="button">
                          <img src="<?php echo plugin_dir_url(__FILE__); ?>/assets/images/phone.png" alt=""><b>See Phone Number</b>
                        </a>
                        <a id="call_phone" style="display: none !important;" href="tel:<?php echo (empty($get_post_meta['phone'][0])) ? "" : $get_post_meta['phone'][0]; ?>" class="seephonebtn d-flex align-items-center gap-1" type="button">
                          <span style="text-decoration: underline;">Call:</span> <b><?php echo (empty($get_post_meta['phone'][0])) ? "" : $get_post_meta['phone'][0]; ?></b>
                        </a>
                      <?php } else if ($package_data['package_type'] == 'basic') { ?>
                        <a id="see_phone" class="seephonebtn d-flex align-items-center gap-1" style="color:white;" type="button">
                          <img src="<?php echo plugin_dir_url(__FILE__); ?>/assets/images/phone.png" alt=""><b>See Phone Number</b>
                        </a>
                        <a id="call_phone" style="display: none !important;" href="javascript:void(0)" class="seephonebtn d-flex align-items-center gap-1" type="button">
                          <span style="text-decoration: underline;"></span> <b class="text-danger">Permission Denied.</b>
                        </a>
                      <?php } else { ?>
                        <a id="see_phone" class="seephonebtn d-flex align-items-center gap-1" style="color:white;" type="button">
                          <img src="<?php echo plugin_dir_url(__FILE__); ?>/assets/images/phone.png" alt=""><b>See Phone Number</b>
                        </a>
                        <a id="call_phone" style="display: none !important;" href="tel:<?php echo (empty($get_post_meta['phone'][0])) ? "" : $get_post_meta['phone'][0]; ?>" class="seephonebtn d-flex align-items-center gap-1" type="button">
                          <span style="text-decoration: underline;">Call:</span> <b><?php echo (empty($get_post_meta['phone'][0])) ? "" : $get_post_meta['phone'][0]; ?></b>
                        </a>
                      <?php } ?>
                      <!--  <a href="#" class="seephonebtn d-flex align-items-center" type="button">
                        <b>WRITE A REVIEW</b>
                      </a> -->
                    </div>
                  </div>
                </div>
              </div>
              <div class="mainverified col-lg-2">
                <div class="verifiedsection p-3">
                  <?php if (get_post_meta($post_id, 'check_box_verify', true) == "Verified") { ?>
                    <img src="<?php echo plugin_dir_url(__FILE__); ?>/assets/images/verifiedpic.png" alt="" class="my-2" />
                    <img src="<?php echo plugin_dir_url(__FILE__); ?>/assets/images/signimg.png" alt="" class="my-2" />
                  <?php } ?>
                  <img src="<?php echo plugin_dir_url(__FILE__); ?>/assets/images/locationimg.png" alt="" class="my-2" />
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="sectionboxcenter">
          <div class="sectionglobal3">
            <?php
            global $post;
            global $wpdb;
            $post_id = $post->ID;

            $check_post_claim = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}claim_listin_table WHERE claim_post='$post_id' && status = 'Approved'");

            ?>
            <?php if ($check_post_claim) {
              $verify_post_author = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}users WHERE user_email = '$check_post_claim->email';");
            ?>
              <?php if ($post->post_author != $verify_post_author->ID) { ?>
                <div class="globaltextactive my-3 d-flex align-items-center justify-content-between">
                  <b style="text-transform: capitalize;">Are You <?php echo get_the_title(); ?>? Claim This Listing To Receive Referrals From This Page</b>
                  <a href="<?php echo get_site_url(); ?>/claim-listing/?post_id=<?php echo $post_id; ?>" class="seephonebtn"><b>Claim Listing</b></a>
                </div>
              <?php } else { ?>
                <div class="globaltextactive my-3 d-flex align-items-center justify-content-between">
                  <span style="font-weight: 500;">MAKE A CONNECTION <span style="font-size: medium;"><?php echo get_the_title(); ?> is accepting messages: </span></span>
                  <a href="https://wordpress-888871-3082106.cloudwaysapps.com/request-quote/?post_id=<?php echo $post_id ?>" class="seephonebtn"><b>Request Free Quotes</b></a>
                </div>
              <?php }
            } else { ?>
              <div class="globaltextactive my-3 d-flex align-items-center justify-content-between">
                <b style="text-transform: capitalize;">Are You <?php echo get_the_title(); ?>? Claim This Listing To Receive Referrals From This Page</b>
                <a href="<?php echo get_site_url(); ?>/claim-listing/?post_id=<?php echo $post_id; ?>" class="seephonebtn"><b>Claim Listing</b></a>
              </div>
            <?php } ?>
          </div>
          <?php
          if (!empty(get_option('ad_url'))) { ?>
            <div class="ads_section">
              <section class="ad">
                <?php echo get_option('ad_url'); ?>
              </section>
            </div>
          <?php } ?>
          <!-- <img src="https://wordpress-888871-3082106.cloudwaysapps.com/wp-content/plugins/directory_plugin-master/template/assets/images/ads1.jpg" > -->
          <div class="sectionglobal4">
            <button class="allglobalsectionbtn allglobalsectionbtn1">Overview</button>
            <button class="allglobalsectionbtn allglobalsectionbtn2">Reviews(<?php echo $total_comments; ?>)</button>
            <button class="allglobalsectionbtn allglobalsectionbtn3">PhotoAlbums(
              <?php
              if (isset($get_post_meta['gallery_image'][0])) {
                // $exists = ';}';
                // if (strpos($get_post_meta['gallery_image'][0], $exists) !== false) {
                //   echo "Word Found!";
                // } else {
                //   echo "Word Not Found!";
                // }
                $images = unserialize($get_post_meta['gallery_image'][0]);
                if ($images) {
                  echo count($images);
                } else {
                  echo "0";
                }
              } else {
                echo '0';
              }
              ?>
              )</button>
          </div>
          <style>
            .comments-title {
              text-transform: capitalize !important;
            }
          </style>
          <div id="allreviewmain" class="mainreviewsection" style="text-transform: capitalize !important;">
            <h2 class="heading1" style="text-transform: capitalize;"><?php echo get_the_title(); ?> Reviews</h2>
            <hr>
            <?php
            comments_template() ?>

          </div>

          <div id="mainimage">
            <div class="container mb-3">
              <div class="gallery">
                <!-- <img src="https://maps.googleapis.com/maps/api/place/photo?maxwidth=400&photo_reference=ARywPAIMRaNaLCB6mUPQpWIGdw7Mt0yII-E2T8FLTKZqiRLZxFVQ7dAuIG_t8kRYnMjhdnK5o59JkB6xzMBV6rKcSGNgPhE3SqS03p5n4WUlNbKHpQTUHN1u91CRGjkybUptKbVEmsZl2NvGng_Lxxk89XcA-GTn8dxp794nIOB_gDJcGZMQ&key=AIzaSyBUOugdibLDtzLCHOxQjHiMxH5ER5cwlek" class="img-fluid ${3|rounded-top,rounded-right,rounded-bottom,rounded-left,rounded-circle,|}" alt=""> -->
                <?php

                if (isset($get_post_meta['gallery_image'][0])) {

                  $images = unserialize($get_post_meta['gallery_image'][0]);
                  if ($images) {


                    for ($i = 0; $i < count($images); $i++) {
                      // print_r($images[$i]);
                      echo '<a href="https://maps.googleapis.com/maps/api/place/photo?maxwidth=400&photo_reference=' . $images[$i] . '&key=AIzaSyBUOugdibLDtzLCHOxQjHiMxH5ER5cwlek" data-lightbox="models" data-title="Caption1">
                    <img style="max-height: 250px;" src="https://maps.googleapis.com/maps/api/place/photo?maxwidth=400&photo_reference=' . $images[$i] . '&key=AIzaSyBUOugdibLDtzLCHOxQjHiMxH5ER5cwlek" alt="product_image">
                    </a>';
                    }
                  }
                } else {
                  echo "<p class='text-light text-center'>No image to display.</p>";
                }
                ?>
              </div>
            </div>
          </div>

          <div id="mainsecondsection">
            <div class="sectionglobal5 p-2">
              <h4 class="abouttext" style="text-transform: capitalize;">About
                <?php

                echo get_the_title();

                ?>
              </h4>
              <p class="premiertext">
                <?php
                if (get_the_content() == 'null') {
                } else {
                  echo get_the_content();
                }

                ?>
              </p>
            </div>
            <div class="sectionglobal6 my-2">
              <h2 class="companytext style-italic py-2 m-0" style="text-transform: capitalize;">Company Details</h2>
              <ul class="list yeartext">
                <?php if ($get_post_meta['year_established'][0] == 'Not Available') { ?>

                <?php } else { ?>
                  <li class="row align-items-center">
                    <div class="contacttextleft col-md-3">Year Established</div>
                    <div class="contacttextright col-md-6">
                      <?php

                      echo (empty($get_post_meta['year_established'][0])) ? "" : $get_post_meta['year_established'][0];
                      ?>
                    </div>
                  </li>
                <?php } ?>
                <?php if ($get_post_meta['no_of_employees'][0] == "Not Available") { ?>

                <?php } else { ?>
                  <li class="row align-items-center">
                    <div class="contacttextleft col-md-3">No of Employees</div>
                    <div class="contacttextright col-md-6"><?php echo (empty($get_post_meta['no_of_employees'][0])) ? "" : $get_post_meta['no_of_employees'][0]; ?></div>
                  </li>
                <?php } ?>

                <?php if ($get_post_meta['hours_of_opertaion'][0] == "Not Available") { ?>

                <?php } else { ?>
                  <li class="row align-items-center" style="line-height: normal;">
                    <div class="contacttextleft col-md-3">Hours of Operation</div>
                    <?php
                    $hours_of_operation =  empty($get_post_meta['hours_of_opertaion'][0]);
                    $hours_explode = explode(',', $get_post_meta['hours_of_opertaion'][0]);

                    // print_r($hours_explode);

                    ?>
                    <div class="contacttextright col-md-6">
                      <?php foreach ($hours_explode as $working_hours) { ?>
                        <?php echo $working_hours . "</br></br>"; ?>
                      <?php } ?>
                    </div>
                  </li>
                <?php }
                if ($get_post_meta['accepted_forms_payments'][0] == "Not Available") { ?>

                <?php } else { ?>
                  <li class="row align-items-center">
                    <div class="contacttextleft col-md-3">Accepted Forms of Payment</div>
                    <div class="contacttextright col-md-6"><?php echo (empty($get_post_meta['accepted_forms_payments'][0])) ? "" : $get_post_meta['accepted_forms_payments'][0]; ?></div>
                  </li>
                <?php }
                if ($get_post_meta['credentials'][0] == "Not Available") { ?>

                <?php } else { ?>
                  <li class="row align-items-center">
                    <div class="contacttextleft col-md-3">Credentials</div>
                    <div class="contacttextright col-md-6"><?php echo (empty($get_post_meta['credentials'][0])) ? "" : $get_post_meta['credentials'][0]; ?></div>
                  </li>
                <?php } ?>
              </ul>

            </div>
            <div class="sectionglobal7">
              <h2 class="contacttext style-italic" style="text-transform: capitalize;">Contact Information</h2>
              <ul class="list yeartext">
                <li class="row align-items-center">
                  <div class="contacttextleft col-md-3">Company Name</div>
                  <div class="contacttextright col-md-6"><?php echo (empty($get_post_meta['company_name'][0])) ? "" : $get_post_meta['company_name'][0]; ?></div>
                </li>
                <?php if ($get_post_meta['visit_website'][0] == "Not Available") { ?>

                <?php } else { ?>
                  <li class="row align-items-center">
                    <div class="contacttextleft col-md-3">Visit Website</div>
                    <?php
                    if (!empty($get_post_meta['visit_website'][0])) {
                      if (!str_contains($get_post_meta['visit_website'][0], 'http')) {
                        $websiteUrl = "http://" . $get_post_meta['visit_website'][0];
                      } else {
                        $websiteUrl = $get_post_meta['visit_website'][0];
                      }
                      $rel = "";
                      if ($check_admin->roles[0] == 'administrator') {
                        $rel = "";
                      } else if ($package_data['package_type'] == 'basic' || $package_data['package_type'] == 'featured') {
                        $rel = 'rel="nofollow"';
                      }
                    ?>
                      <div class="contacttextright col-md-6"><a <?php echo $rel; ?> href="<?php echo $websiteUrl;  ?>" target="_blank" style="color:white;"><?php echo $get_post_meta['visit_website'][0]; ?></a></div>
                    <?php } ?>
                  </li>
                <?php } ?>
                <?php if ($get_post_meta['social_fb'][0] == "Not Available" && $get_post_meta['social_twitter'][0] == "Not Available" && $get_post_meta['social_linkedin'][0] == "Not Available" && $get_post_meta['social_youtube'][0] == "Not Available" && $get_post_meta['social_instagram'][0] == "Not Available" && $get_post_meta['social_pinterest'][0] == "Not Available") { ?>

                <?php } else { ?>
                  <li class="row align-items-center">
                    <div class="contacttextleft col-md-3">Online Social Profiles</div>
                    <div class="contacttextright col-md-6">
                      <?php if ($check_admin->roles[0] == 'administrator') {  ?>
                        <ul class="list social_icon d-flex gap-1 align-items-center">
                          <?php if ($get_post_meta['social_fb'][0] == "Not Available") { ?>

                          <?php } else { ?>
                            <?php if (!empty($get_post_meta['social_fb'][0])) { ?><li><a href="<?php echo $get_post_meta['social_fb'][0]; ?>" class="facebook"><i class="fa-brands fa-facebook-f"></i></a></li>
                          <?php }
                          } ?>

                          <?php if ($get_post_meta['social_twitter'][0] == "Not Available") { ?>

                          <?php } else { ?>
                            <?php if (!empty($get_post_meta['social_twitter'][0])) { ?><li><a href="<?php echo $get_post_meta['social_twitter'][0]; ?>" class="twitter"><i class="fa-brands fa-twitter"></i></a></li>
                          <?php }
                          } ?>

                          <?php if ($get_post_meta['social_linkedin'][0] == "Not Available") { ?>

                          <?php } else { ?>
                            <?php if (!empty($get_post_meta['social_linkedin'][0])) { ?><li><a href="<?php echo $get_post_meta['social_linkedin'][0]; ?>" class="linkedin"><i class="fa-brands fa-linkedin-in"></i></a></li>
                          <?php }
                          } ?>

                          <?php if ($get_post_meta['social_youtube'][0] == "Not Available") { ?>

                          <?php } else { ?>
                            <?php if (!empty($get_post_meta['social_youtube'][0])) { ?><li><a href="<?php echo $get_post_meta['social_youtube'][0]; ?>" class="circle"><i class="fa-brands fa-youtube"></i></a></li>
                          <?php }
                          } ?>

                          <?php if ($get_post_meta['social_instagram'][0] == "Not Available") { ?>

                          <?php } else { ?>
                            <?php if (!empty($get_post_meta['social_instagram'][0])) { ?><li><a href="<?php echo $get_post_meta['social_instagram'][0]; ?>" class="instagram"><i class="fa-brands fa-instagram"></i></a></li>
                          <?php }
                          } ?>

                          <?php if ($get_post_meta['social_pinterest'][0] == "Not Available") { ?>

                          <?php } else { ?>
                            <?php if (!empty($get_post_meta['social_pinterest'][0])) { ?><li><a href="<?php echo $get_post_meta['social_pinterest'][0]; ?>" class="pinterest"><i class="fa-brands fa-pinterest"></i></a></li>
                          <?php }
                          } ?>
                        </ul>
                      <?php } else if ($package_data['package_type'] == 'basic' || $package_data['package_type'] == 'free') { ?>
                        <ul class="list social_icon d-flex gap-1 align-items-center">
                          <li><a href="javascript:void(0)" class="facebook"><i class="fa-brands fa-facebook-f"></i></a></li>
                          <li><a href="javascript:void(0)" class="twitter"><i class="fa-brands fa-twitter"></i></a></li>
                          <li><a href="javascript:void(0)" class="linkedin"><i class="fa-brands fa-linkedin-in"></i></a></li>
                          <li><a href="javascript:void(0)" class="circle"><i class="fa-brands fa-youtube"></i></a></li>
                          <li><a href="javascript:void(0)" class="instagram"><i class="fa-brands fa-instagram"></i></a></li>
                          <li><a href="javascript:void(0)" class="pinterest"><i class="fa-brands fa-pinterest"></i></a></li>
                        </ul>
                      <?php } else { ?>
                        <ul class="list social_icon d-flex gap-1 align-items-center">
                          <?php if ($get_post_meta['social_fb'][0] == "Not Available") { ?>

                          <?php } else { ?>
                            <?php if (!empty($get_post_meta['social_fb'][0])) { ?><li><a href="<?php echo $get_post_meta['social_fb'][0]; ?>" class="facebook"><i class="fa-brands fa-facebook-f"></i></a></li>
                          <?php }
                          } ?>

                          <?php if ($get_post_meta['social_twitter'][0] == "Not Available") { ?>

                          <?php } else { ?>
                            <?php if (!empty($get_post_meta['social_twitter'][0])) { ?><li><a href="<?php echo $get_post_meta['social_twitter'][0]; ?>" class="twitter"><i class="fa-brands fa-twitter"></i></a></li>
                          <?php }
                          } ?>

                          <?php if ($get_post_meta['social_linkedin'][0] == "Not Available") { ?>

                          <?php } else { ?>
                            <?php if (!empty($get_post_meta['social_linkedin'][0])) { ?><li><a href="<?php echo $get_post_meta['social_linkedin'][0]; ?>" class="linkedin"><i class="fa-brands fa-linkedin-in"></i></a></li>
                          <?php }
                          } ?>

                          <?php if ($get_post_meta['social_youtube'][0] == "Not Available") { ?>

                          <?php } else { ?>
                            <?php if (!empty($get_post_meta['social_youtube'][0])) { ?><li><a href="<?php echo $get_post_meta['social_youtube'][0]; ?>" class="circle"><i class="fa-brands fa-youtube"></i></a></li>
                          <?php }
                          } ?>

                          <?php if ($get_post_meta['social_instagram'][0] == "Not Available") { ?>

                          <?php } else { ?>
                            <?php if (!empty($get_post_meta['social_instagram'][0])) { ?><li><a href="<?php echo $get_post_meta['social_instagram'][0]; ?>" class="instagram"><i class="fa-brands fa-instagram"></i></a></li>
                          <?php }
                          } ?>

                          <?php if ($get_post_meta['social_pinterest'][0] == "Not Available") { ?>

                          <?php } else { ?>
                            <?php if (!empty($get_post_meta['social_pinterest'][0])) { ?><li><a href="<?php echo $get_post_meta['social_pinterest'][0]; ?>" class="pinterest"><i class="fa-brands fa-pinterest"></i></a></li>
                          <?php }
                          } ?>
                        </ul>
                      <?php } ?>
                    </div>
                  </li>
                <?php } ?>
                <li class="row align-items-center">
                  <div class="contacttextleft col-md-3">Phone Number</div>
                  <div class="contacttextright col-md-6">
                    <?php if ($check_admin->roles[0] == 'administrator') { ?>
                      <button id="see_phone_btn" class="phonebtn" type="button">
                        <b>See Phone Number</b>
                      </button>
                      <a id="call_phone_btn" style="display: none !important;width: 269.3px;" class="phonebtn" href="tel:<?php echo (empty($get_post_meta['phone'][0])) ? "" : $get_post_meta['phone'][0]; ?>" type="button">
                        <span style="text-decoration: underline;">Call:</span> <b><?php echo (empty($get_post_meta['phone'][0])) ? "" : $get_post_meta['phone'][0]; ?></b>
                      </a>
                    <?php } else if ($package_data['package_type'] == 'basic'  || $package_data['package_type'] == 'free') { ?>
                      <button id="see_phone_btn" class="phonebtn" type="button">
                        <b>See Phone Number</b>
                      </button>
                      <a id="call_phone_btn" style="display: none !important;width: 269.3px;" class="phonebtn" href="javascript:void(0)" type="button">
                        <span style="text-decoration: underline;"></span> <b class="text-danger">Permission Denied.</b>
                      </a>
                    <?php } else { ?>
                      <button id="see_phone_btn" class="phonebtn" type="button">
                        <b>See Phone Number</b>
                      </button>
                      <a id="call_phone_btn" style="display: none !important;width: 269.3px;" class="phonebtn" href="tel:<?php echo (empty($get_post_meta['phone'][0])) ? "" : $get_post_meta['phone'][0]; ?>" type="button">
                        <span style="text-decoration: underline;">Call:</span> <b><?php echo (empty($get_post_meta['phone'][0])) ? "" : $get_post_meta['phone'][0]; ?></b>
                      </a>
                    <?php } ?>
                  </div>
                </li>
                <?php if ($get_post_meta['address'][0] == "Not Available") { ?>

                <?php } else { ?>
                  <li class="row align-items-center">
                    <div class="contacttextleft col-md-3">Location Address</div>
                    <div class="contacttextright col-md-6">
                      <?php echo (empty($get_post_meta['address'][0])) ? "" : $get_post_meta['address'][0]; ?>
                    </div>
                  </li>
                <?php } ?>
                <li class="row align-items-center"></li>
              </ul>
            </div>
          </div>
          <div class="iframe">
            <?php
            if (isset($get_post_meta['location_address'][0])) {
              echo '<iframe src="//maps.google.com/maps?q=' . $get_post_meta['location_address'][0] . '&z=15&output=embed" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>';
            } else {
              echo '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d26361904.449782908!2d-113.76795116780146!3d36.23935029859753!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x54eab584e432360b%3A0x1c3bb99243deb742!2sUnited%20States!5e0!3m2!1sen!2s!4v1672228075325!5m2!1sen!2s" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>';
            }
            ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
<script src="https://www.google.com/recaptcha/api.js?onload=myCallBack&render=explicit" async defer></script>
<script src="<?php echo plugin_dir_url(__FILE__); ?>/assets/js/main.js"></script>
<script src="<?php echo plugin_dir_url(__FILE__); ?>/assets/js/lightbox-plus-jquery.js"></script>
<script>
  $('#see_phone').click(function() {
    $('#see_phone').addClass('hide_phone');
    $('#call_phone').css('display', 'block');
  });
  $('#see_phone_btn').click(function() {
    $('#see_phone_btn').addClass('hide_phone');
    $('#call_phone_btn').css('display', 'block');
  });
</script>
<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>

<!-- Initialize Swiper -->
<script>
  var swiper = new Swiper(".mySwiper", {
    spaceBetween: 30,
    centeredSlides: true,
    autoplay: {
      delay: 2500,
      disableOnInteraction: false,
    },
    pagination: {
      el: ".swiper-pagination",
      clickable: true,
    },
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
  });
</script>
<?php get_footer(); ?>