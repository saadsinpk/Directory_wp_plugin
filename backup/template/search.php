
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css"
integrity="sha512-YWzhKL2whUzgiheMoBFwW8CKV4qpHQAEuvilg9FAn5VJUDwKZZxkJNuGM4XkWuk94WCrrwslk8yWNGmY1EduTA=="
crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css"
integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous" />
<link rel="stylesheet" href="<?php echo plugin_dir_url(__FILE__);?>/assets/css/style.css"/>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBUOugdibLDtzLCHOxQjHiMxH5ER5cwlek&v=3.exp&libraries=places"></script>

<script type="text/javascript">
    jQuery(function($) {
       function initialize() {
               var input = document.getElementById("searchTextField");
               var autocomplete = new google.maps.places.Autocomplete(input);
               autocomplete.addListener("place_changed", function() {
                    var place = autocomplete.getPlace();
                    // $("#latitude").val(place.geometry.location.lat()) ;
                    // $("#longitude").val(place.geometry.location.lng());

                });
       }
       google.maps.event.addDomListener(window, "load", initialize);
       });
</script>
<div class="sectionbody">
        <div class="container">
            <div class="ourheading">
              <div class="directtext">Our directory for USA Manufacturers is the perfect resource for businesses looking to connect with leading American manufacturers. Our comprehensive index includes listings for thousands of companies across a variety of industries, making it easy to find a supplier that meets your specific needs. Whether you're searching for a manufacturer of precision machined components or a supplier of custom-printed packaging, our directory can help you get in touch with the right company quickly and easily.</div>
            </div>
            <form action="<?php echo get_site_url();?>" method="get" class="sectionshowing m-auto col-lg-9">
                <div class="searchsection">
                <div class="searchtext pt-2"><b>SEARCH BY LOCATION</b></div>
                <div class="bordersectionnews">
                <input type="text" class="citytext form-control" name="s"  id="searchTextField" placeholder="Address">                
                <input type="hidden" name="post_type" value="directory_listing">
              </div>
              <div class="buttonsection">
                <button class="searchnowbutton mb-3"><b>SEARCH NOW</b></button>
              </div>
          </form>
</div>
</div>
       