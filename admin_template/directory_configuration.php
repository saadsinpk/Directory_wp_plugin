<!-- <?php if (isset($_POST['submit_configuration_form'])) {
            echo "<div class=\"alert alert-primary\" role=\"alert\" style=\"width:100%;background:green;\">
  This is a primary alert�check it out!
</div>";
            include 'script.php';
        }

        ?> -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" integrity="sha512-YWzhKL2whUzgiheMoBFwW8CKV4qpHQAEuvilg9FAn5VJUDwKZZxkJNuGM4XkWuk94WCrrwslk8yWNGmY1EduTA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css"> -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.css">
<!-- <link rel="stylesheet" href="select.css"> -->
<link rel="stylesheet" href="/wp-content/plugins/directory_plugin-master/admin_template/bootstrap.css">
<link rel="stylesheet" href="/wp-content/plugins/directory_plugin-master/admin_template/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome-animation/0.2.1/font-awesome-animation.min.css">

<div class="container my-3">
    <div class="panel panel-default">
        <?php isset($_SESSION['SUCCESS']) ?  $_SESSION['SUCCESS'] : '' ?>
        <div class="panel-body">
            <ul class="nav nav-tabs nav-tabs-simple nav-tabs-simple-bottom" id="googleYelpDataContentTabs" role="tablist">
                <li class="nav-item active">
                    <a class="nav-link active" id="nav2-tab" data-toggle="tab" href="#nav2" role="tab">
                        <b>New Listings Import</b>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="nav3-tab" data-toggle="tab" href="#nav3" role="tab">
                        <b>Configurations</b>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="nav4-tab" data-toggle="tab" href="#nav4" role="tab">
                        <b>Ads Handling</b>
                    </a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane show fade in active" id="nav2" role="tabpanel" aria-labelledby="nav2-tab">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="admin-table">
                                <ul class="nav nav-tabs nav-tabs-simple nav-tabs-simple-bottom" id="listingsSearchContentTabs" role="tablist">
                                    <li class="nav-item active">
                                        <a class="nav-link active" id="listings-search-nearby-layout-tab" data-toggle="tab" href="#listings-search-nearby-layout" role="tab" aria-expanded="true">
                                            <b>Nearby search</b>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="listings-search-text-layout-tab" data-toggle="tab" href="#listings-search-text-layout" role="tab">
                                            <b>Text search</b>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="listings-search-outscraper-layout-tab" data-toggle="tab" href="#listings-search-outscraper-layout" role="tab">
                                            <b>Outscraper</b>
                                        </a>
                                    </li>
                                    <div class="clearfix"></div>
                                </ul>

                                <div class="tab-content" id="listingsSearchTabsContent" style="margin-bottom: 10px">
                                    <div class="tab-pane fade active in" id="listings-search-nearby-layout" role="tabpanel" aria-labelledby="listings-search-nearby-layout-tab">
                                        <form id="placesSearchNearbyForm">
                                            <input type="hidden" id="form-check" value="Nearby_Search">
                                            <div class="row" style="margin-top: 15px">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Keywords</label>
                                                        <input name="nearby_Keywords" id="nearby_Keywords" type="text" class="form-control places-keyword-filter" placeholder="Enter keywords" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Country</label>
                                                        <input name="nearby_Country" id="nearby_Country" type="text" class="country form-control" placeholder="Type a country" value="" />

                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>State</label>
                                                        <input name="nearby_State" id="nearby_State" type="text" class="state form-control" placeholder="Enter a state" />

                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>City</label>
                                                        <input name="nearby_City" id="nearby_City" type="text" class="city form-control" placeholder="Enter a city" />

                                                    </div>
                                                </div>
                                                <div class="clearfix"></div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Limit</label>
                                                        <select class="results-limit form-control" name="nearby_Limit" id="nearby_Limit">
                                                            <option>10</option>
                                                            <option>20</option>
                                                            <option>30</option>
                                                            <option>40</option>
                                                            <option>50</option>
                                                            <option>60</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="clearfix"></div>
                                                <div class="category_main">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Category</label>
                                                            <select class="outscrap_category form-control" id="nearby_category" name="input_category">
                                                                <option value="">Select Category</option>
                                                                <option value="Post" data-value="Post">Post</option>
                                                                <option value="Event" data-value="Event">Event</option>
                                                                <option value="Service" data-value="Service">Service</option>
                                                                <option value="Restaurant" data-value="Restaurant">Restaurant</option>
                                                                <option value="Classified" data-value="Classified">Classified</option>
                                                                <option value="Shop" data-value="Shop">Shop</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Sub Category</label>
                                                            <select class="outscrap_subcategory form-control" id="nearby_subCategory" name="input_subCategory">
                                                                <option value="">Select Sub Category</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="clearfix"></div>
                                                <div class="col-md-6 col-md-offset-6" style="margin-top: 10px;margin-bottom: 10px;">
                                                    <div class="form-group">
                                                        <button id="get-nearby-data" type="submit" class="btn-block btn btn-success reload-places-data">
                                                            <b>Search Location Data</b>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="tab-pane fade" id="listings-search-text-layout" role="tabpanel" aria-labelledby="listings-search-text-layout-tab">
                                        <form id="placesSearchTextForm">
                                            <input type="hidden" id="form-check-1" value="Text_Search">
                                            <div class="row" style="margin-top: 15px">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Keywords</label>
                                                        <input name="text_search_Keywords" id="text_search_Keywords" type="text" class="form-control places-keyword-filter" placeholder="Enter keywords" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Location</label>
                                                        <input name="text_search_Location" id="text_search_Location" type="text" class="form-control places-location-filter" placeholder="Enter a city, state, zip code or an address." value="" />
                                                    </div>
                                                </div>
                                                <div class="clearfix"></div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Limit</label>
                                                        <select class="results-limit form-control" name="text_search_Limit" id="text_search_Limit">
                                                            <option>10</option>
                                                            <option>20</option>
                                                            <option>30</option>
                                                            <option>40</option>
                                                            <option>50</option>
                                                            <option>60</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="clearfix"></div>
                                                <div class="category_main">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Category</label>
                                                            <select class="outscrap_category form-control" id="text_search_category" name="input_category">
                                                                <option value="">Select Category</option>
                                                                <option value="Post" data-value="Post">Post</option>
                                                                <option value="Event" data-value="Event">Event</option>
                                                                <option value="Service" data-value="Service">Service</option>
                                                                <option value="Restaurant" data-value="Restaurant">Restaurant</option>
                                                                <option value="Classified" data-value="Classified">Classified</option>
                                                                <option value="Shop" data-value="Shop">Shop</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Sub Category</label>
                                                            <select class="outscrap_subcategory form-control" id="text_search_subCategory" name="input_subCategory">
                                                                <option value="">Select Sub Category</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="clearfix"></div>
                                                <div class="col-md-6 col-md-offset-6" style="margin-top: 10px;margin-bottom: 10px;">
                                                    <div class="form-group">
                                                        <button id="get-text-search-data" type="submit" class="btn-block btn btn-success reload-places-data">
                                                            <b>Search Location Data</b>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="tab-pane fade in" id="listings-search-outscraper-layout" role="tabpanel" aria-labelledby="listings-search-outscraper-layout-tab">
                                        <div class="tab-content" id="outscraperSearchTabsContent" style="margin-bottom: 10px">
                                            <div class="tab-pane fade in active" id="outscraper-search-finder-layout" role="tabpanel" aria-labelledby="outscraper-search-finder-layout-tab">
                                                <div class="info info-danger">
                                                    <!-- <p> Warning: Using this search method will immediately use your monthly scraping credits as data is immediately pulled. </p> -->
                                                </div>
                                                <!-- OutScrapper Search Form -->
                                                <form id="placesSearchOutscraperForm">
                                                    <input type="hidden" id="form-check-2" value="Outscrape_Search">
                                                    <div class="row" style="margin-top: 15px">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Keywords</label>
                                                                <input type="text" class="form-control places-keyword-filter" placeholder="Enter keywords" id="input_keyword" name="input_keyword" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>State</label>
                                                                <input type="text" class="form-control places-location-filter" placeholder="Enter a state or zip code." value="" id="input_state" name="input_state" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>City</label>
                                                                <input type="text" class="form-control places-location-filter" placeholder="Enter a city or an address." value="" id="input_city" name="input_city" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Limit</label>
                                                                <select class="results-limit form-control" id="input_limit" name="input_limit">
                                                                    <option value="10">10</option>
                                                                    <option value="20">20</option>
                                                                    <option value="50">50</option>
                                                                    <option value="80">80</option>
                                                                    <option value="100">100</option>
                                                                    <option value="150">150</option>
                                                                    <option value="200">200</option>
                                                                    <option value="250">250</option>
                                                                    <option value="300">300</option>
                                                                    <option value="350">350</option>
                                                                    <option value="400">400</option>
                                                                    <option value="450">450</option>
                                                                    <option value="500">500</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="clearfix"></div>
                                                        <div class="category_main">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>Category</label>
                                                                    <select class="outscrap_category form-control" id="outscrap_category" name="input_category">
                                                                        <option value="">Select Category</option>
                                                                        <option value="Post" data-value="Post">Post</option>
                                                                        <option value="Event" data-value="Event">Event</option>
                                                                        <option value="Service" data-value="Service">Service</option>
                                                                        <option value="Restaurant" data-value="Restaurant">Restaurant</option>
                                                                        <option value="Classified" data-value="Classified">Classified</option>
                                                                        <option value="Shop" data-value="Shop">Shop</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>Sub Category</label>
                                                                    <select class="outscrap_subcategory form-control" id="outscrap_subCategory" name="input_subCategory">
                                                                        <option value="">Select Sub Category</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="clearfix"></div>
                                                        <div class="col-md-6 col-md-offset-6" style="margin-top: 10px;margin-bottom: 10px;">
                                                            <div class="form-group">
                                                                <button id="get-search-data" type="submit" class="btn-block btn btn-success reload-places-data">
                                                                    <b>Search Location Data</b>
                                                                </button>

                                                            </div>
                                                        </div>
                                                        <div class="clearfix"></div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div id="map-canvas" style="width: 100%; height: 500px;">

                            </div>
                        </div>
                        <div class="cold-md-12">
                            <div class="admin-table">
                                <div id="listingsLoaderDatatable_wrapper" class="dataTables_wrapper no-footer">
                                    <style>
                                        .odd {
                                            background-color: transparent;
                                            width: 100%;
                                        }

                                        .rounded-circle {
                                            height: 55px;
                                            width: 55px;
                                            border-radius: 50%;
                                            display: inline-block;
                                        }
                                    </style>
                                    <div id="listingsLoaderDatatable_processing" style="display: none;" class="dataTables_processing">
                                        <img src="https://aws1.discourse-cdn.com/sitepoint/original/3X/e/3/e352b26bbfa8b233050087d6cb32667da3ff809c.gif" height="70" alt="">
                                        <h5 class="text-center">loading data..</h5>
                                    </div>
                                    <style>
                                        .pagination li:hover {
                                            cursor: pointer;
                                        }
                                    </style>
                                    <style>
                                        /* CSS Document */

                                        .notice-success-gradient {
                                            background: #67b11c;
                                            padding: 5px 10px 5px 10px;
                                            border: 2px solid rgba(0, 153, 0, 1);
                                            border-radius: 5px;
                                            color: #fff;
                                            font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
                                            font-weight: 700;
                                            /*width:400px;*/
                                        }

                                        .notice-success-gradient {
                                            position: relative;
                                        }

                                        .notice-success-gradient p {
                                            padding-left: 35px;
                                            margin-top: 5px;
                                        }

                                        .notice-success-gradient img {
                                            width: 30px;
                                            height: 30px;
                                            position: absolute;
                                            top: 4px;
                                            left: 10px;
                                        }

                                        .notice-success-gradient {
                                            background: linear-gradient(#67b11c, #437015);
                                        }

                                        .notice-fail-gradient {
                                            background: #e12727;
                                            padding: 5px 10px 5px 10px;
                                            border: 2px solid rgb(157, 61, 10);
                                            border-radius: 5px;
                                            color: #fff;
                                            font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
                                            font-weight: 700;
                                            /*width:400px;*/
                                        }

                                        .notice-fail-gradient {
                                            position: relative;
                                        }

                                        .notice-fail-gradient p {
                                            padding-left: 35px;
                                            margin-top: 5px;
                                        }

                                        .notice-fail-gradient img {
                                            width: 30px;
                                            height: 30px;
                                            position: absolute;
                                            top: 4px;
                                            left: 10px;
                                        }

                                        .notice-fail-gradient {
                                            background: linear-gradient(#67b11c, #437015);
                                        }
                                    </style>
                                    <div id="listing_forms"></div>
                                    <div style="display: none;" id="success" class="text-success">
                                        <div class="notice-success-gradient">
                                            <img src="https://upload.wikimedia.org/wikipedia/commons/d/d2/Pictogram_voting_keep-light-green.svg" alt="tick">
                                            <p>Success! Directory Successfully Listed!</p>
                                        </div>
                                        <p>&nbsp;</p>
                                    </div>
                                    <div style="display: none;" id="failed" class="text-danger">
                                        <div class="notice-fail-gradient">
                                            <img src="https://www.google.com/url?sa=i&url=https%3A%2F%2Fwww.freeiconspng.com%2Fimg%2F7115&psig=AOvVaw1PIjO8-nyAsEZSo1DRUPzp&ust=1672264026859000&source=images&cd=vfe&ved=0CA8QjRxqFwoTCJiyoYvjmvwCFQAAAAAdAAAAABAT" alt="tick">
                                            <p>Failed! Directory Listing Failed!</p>
                                        </div>
                                        <p>&nbsp;</p>
                                    </div>
                                    <h2>Select Number Of Rows</h2>
                                    <div class="form-group">
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
                                    <table id="listingsLoaderDatatable" class="table table-striped dataTable no-footer" aria-describedby="listingsLoaderDatatable_info">
                                        <thead>
                                            <tr>
                                                <th class="order">Company Logo</th>
                                                <th class="order">Company Name</th>
                                                <th class="order">Description</th>
                                                <th class="order">Website</th>
                                                <th class="order">Phone Number</th>
                                                <th class="order">Location</th>
                                                <th class="order">Hours Of Operation</th>
                                                <th class="order">Address</th>
                                                <th class="order">Reviews</th>
                                                <th class="order">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="searchData">

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
                        <!-- <div class="col-md-6 nopad" style="position: sticky; top: 40px">
                <div id="mapView" style="position: relative;">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3620.6630893237834!2d67.00740971551608!3d24.84119278406308!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3eb33dfc94ef2283%3A0xd29b8cc455121f77!2sUnited%20State%20of%20America%20Consulate%20General%20Karachi!5e0!3m2!1sen!2s!4v1671199490570!5m2!1sen!2s" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
                </div> -->
                    </div>
                </div>
                <div class="tab-pane fade" id="nav3" role="tabpanel" aria-labelledby="nav3-tab">
                    <form method="post">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Google API Key</label>
                                <input type="text" class="form-control" name="google_yelp_listings__google_api_key" id="google_yelp_listings__google_api_key" value="<?php echo get_option('google_api_key'); ?>">
                                <small>You must have billing enabled in your account and the Google project should not
                                    have referrer restrictions.</small>
                            </div>
                            <div class="form-group">
                                <label>Yelp API Key</label>
                                <input type="text" class="form-control" name="google_yelp_listings__yelp_api_key" id="google_yelp_listings__yelp_api_key" value="<?php echo get_option('yelp_api_key'); ?>" style="padding-right: 80px;text-overflow: ellipsis;">
                                <a class="" href="https://www.yelp.com/signup" target="_blank"></a>
                                <small>You can find a guide about setting up a Yelp Fusion API key. </small>
                            </div>
                            <div class="form-group">
                                <label>Outscraper API Key</label>
                                <input type="text" class="form-control" name="google_yelp_listings__outscraper_api_key" id="google_yelp_listings__outscraper_api_key" value="<?php echo get_option('outscraper_api_key'); ?>" style="padding-right: 80px;text-overflow: ellipsis;">
                                <small>You can find a guide about setting up an Outscraper API key. </small>
                            </div>
                            <div class="form-group">
                                <label>Default membership level</label>
                                <select class="form-control" name="google_yelp_listings__default_membership_level" id="google_yelp_listings__default_membership_level">
                                    <option value="">Select an option</option>
                                    <option value="Classified Ads Pro">Classified Ads Pro</option>
                                    <option value="MNM Managed Reporting">MNM Managed Reporting</option>
                                    <option selected="" value="Free/Claim">Free/Claim</option>
                                    <option value="General User Account">General User Account</option>
                                    <option value="Admin - Blog Author">Admin - Blog Author</option>
                                    <option value="Basic Listing">Basic Listing</option>
                                    <option value="Featured Listing">Featured Listing</option>
                                    <option value="Premium Listing">Premium Listing</option>
                                </select>
                                <small>Select the default membership level to use for the new listings created with the
                                    generator.</small>
                            </div>
                        </div>
                        <input type="submit" name="submit_configuration_form" class="btn btn-cta btn-success" id="get_Update" value="Submit">
                        <div class="clearfix"></div>
                    </form>
                </div>
                <div class="tab-pane fade" id="nav4" role="tabpanel" aria-labelledby="nav4-tab">
                    <form method="post">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>AD DETAIL</label><small class="text-secondary">Tracking URL/Script</small>
                                <textarea name="ad_text" class="form-control"><?php echo get_option('ad_url'); ?></textarea>
                            </div>
                        </div>
                        <input type="submit" name="submit_ad" class="btn btn-cta btn-success" id="get_Update" value="Submit">
                        <div class="clearfix"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.js"></script>
<script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyBUOugdibLDtzLCHOxQjHiMxH5ER5cwlek&sensor=true" type="text/javascript"></script>

<script>
    jQuery(document).ready(function($) {

        var map;
        var geocoder;
        var marker;
        var people = new Array();
        var latlng;
        var infowindow;

        // $(document).ready(function() {
        //     ViewCustInGoogleMap();
        // });

        // function ViewCustInGoogleMap() {

        //     var mapOptions = {
        //         center: new google.maps.LatLng(11.0168445, 76.9558321),   // Coimbatore = (11.0168445, 76.9558321)
        //         zoom: 7,
        //         mapTypeId: google.maps.MapTypeId.ROADMAP
        //     };
        //     map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);

        //     // Get data from database. It should be like below format or you can alter it.

        //     var data = '[{ "DisplayText": "adcv", "ADDRESS": "Jamiya Nagar Kovaipudur Coimbatore-641042", "LatitudeLongitude": "10.9435131,76.9383790", "MarkerId": "Customer" },{ "DisplayText": "abcd", "ADDRESS": "Coimbatore-641042", "LatitudeLongitude": "11.0168445,76.9558321", "MarkerId": "Customer"}]';

        //     people = JSON.parse(data); 

        //     for (var i = 0; i < people.length; i++) {
        //         setMarker(people[i]);
        //     }

        // }

        // function setMarker(people) {
        //     geocoder = new google.maps.Geocoder();
        //     infowindow = new google.maps.InfoWindow();
        // if ((people["LatitudeLongitude"] == null) || (people["LatitudeLongitude"] == 'null') || (people["LatitudeLongitude"] == '')) {
        //     geocoder.geocode({ 'address': people["Address"] }, function(results, status) {
        //         if (status == google.maps.GeocoderStatus.OK) {
        //             latlng = new google.maps.LatLng(results[0].geometry.location.lat(), results[0].geometry.location.lng());
        //             marker = new google.maps.Marker({
        //                 position: latlng,
        //                 map: map,
        //                 draggable: false,
        //                 html: people["DisplayText"]
        //             });
        //             //marker.setPosition(latlng);
        //             //map.setCenter(latlng);
        //             google.maps.event.addListener(marker, 'click', function(event) {
        //                 infowindow.setContent(this.html);
        //                 infowindow.setPosition(event.latLng);
        //                 infowindow.open(map, this);
        //             });
        //         }
        //         else {
        //             alert(people["DisplayText"] + " -- " + people["Address"] + ". This address couldn't be found");
        //         }
        //     });
        // }
        // else {

        //         var latlngStr = people["LatitudeLongitude"].split(",");
        //         var lat = parseFloat(latlngStr[0]);
        //         var lng = parseFloat(latlngStr[1]);
        //         latlng = new google.maps.LatLng(lat, lng);
        //         marker = new google.maps.Marker({
        //             position: latlng,
        //             map: map,
        //             draggable: false,               // cant drag it
        //             html: people["DisplayText"]    // Content display on marker click
        //             //icon: "images/marker.png"       // Give ur own image
        //         });
        //         //marker.setPosition(latlng);
        //         //map.setCenter(latlng);
        //         google.maps.event.addListener(marker, 'click', function(event) {
        //             infowindow.setContent(this.html);
        //             infowindow.setPosition(event.latLng);
        //             infowindow.open(map, this);
        //         });
        //     // }
        // }

        $(document).ready(function() {
            $('#listingsDatatable').DataTable();
        });
    });
    jQuery(document).ready(function($) {
        $(document).ready(function() {
            $('.select2').select2();
        });
    });
    jQuery(document).ready(function($) {
        $(function() {
            $("li").click(function() {
                // remove classes from all
                $("li").removeClass("active");
                // add class to the one we clicked
                $(this).addClass("active");
            });
        });
    });
</script>

<!-- <script>
    jQuery(document).ready(function($) {
        $('#listings-search-nearby-layout-tab').click(function() {
            $("#placesSearchTextForm")[0].reset();
            $("#placesSearchOutscraperForm")[0].reset();
        });
        $('#listings-search-text-layout-tab').click(function() {
            
            
        });
        $('#listings-search-outscraper-layout-tab').click(function() {
            
            $("#placesSearchNearbyForm")[0].reset();
        });
    })   
        
</script> -->

<script>
    jQuery(document).ready(function($) {
        $(document).ready(function() {


            getPagination('#listingsLoaderDatatable');

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
        }
    `
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