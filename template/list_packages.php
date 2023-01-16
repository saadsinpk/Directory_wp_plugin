<?php 
global $user_ID;
global $wpdb;
$subscription_userId = $subscription_package_type = "";
if (is_user_logged_in() && $user_ID != 0) {

$table_name = "{$wpdb->prefix}user_subscriptions_details";
$subscription_data = $wpdb->get_row( "SELECT * FROM $table_name WHERE `user_id` = '$user_ID' AND `status` = 'active' " );
// var_dump($subscription_data);
if (!empty($subscription_data)) {
  // echo $user_ID;
  $subscription_userId = intval($subscription_data->user_id);
  $subscription_package_type = $subscription_data->package_type;
}

}
$html = '
<script src="https://js.stripe.com/v3/"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<div id="payment-request-button">
  <!-- A Stripe Element will be inserted here. -->
</div>
<link rel="stylesheet" href="'.plugin_dir_url(__FILE__).'/assets/css/style.css" />
<div class="row">
  <div class="col-md-12">
    <table class="froala-table" style="width: 100%;">
      <tbody>
        <tr>
          <td class="hidden-xs" style="width: 8.0994%;">
            <br>
          </td>
          <td style="width: 83.7878%; text-align: center;">
            <p style="text-align: center;">
              <span style="font-size: 18px;">As a <strong>manufacturer</strong>, you may be wondering how you can join&nbsp; <strong>Manufacturers Near Me</strong>. Joining is easy! All you need to do is provide some basic information about your company and we will include you in our directory. <br style="color: rgb(98, 100, 106); font-family: Macan, ;" helvetica="" neue",="" helvetica,="" arial,="" sans-serif;="" font-size:="" 16px;="" font-style:="" normal;="" font-variant-ligatures:="" font-variant-caps:="" font-weight:="" 400;="" letter-spacing:="" orphans:="" 2;="" text-align:="" left;="" text-indent:="" 0px;="" text-transform:="" none;="" white-space:="" widows:="" word-spacing:="" -webkit-text-stroke-width:="" background-color:="" rgb(255,="" 255,="" 255);="" text-decoration-thickness:="" initial;="" text-decoration-style:="" text-decoration-color:="" initial;"="" id="isPasted">
                <br style="color: rgb(98, 100, 106); font-family: Macan, ;" helvetica="" neue",="" helvetica,="" arial,="" sans-serif;="" font-size:="" 16px;="" font-style:="" normal;="" font-variant-ligatures:="" font-variant-caps:="" font-weight:="" 400;="" letter-spacing:="" orphans:="" 2;="" text-align:="" left;="" text-indent:="" 0px;="" text-transform:="" none;="" white-space:="" widows:="" word-spacing:="" -webkit-text-stroke-width:="" background-color:="" rgb(255,="" 255,="" 255);="" text-decoration-thickness:="" initial;="" text-decoration-style:="" text-decoration-color:="" initial;"="">Once you have joined, you will be able to claim your company profile. This is a great opportunity for you to showcase your products and services to our audience of buyers and manufacturers. You can also add photos, videos, and product listings to your profile and much more. <br style="color: rgb(98, 100, 106); font-family: Macan, ;" helvetica="" neue",="" helvetica,="" arial,="" sans-serif;="" font-size:="" 16px;="" font-style:="" normal;="" font-variant-ligatures:="" font-variant-caps:="" font-weight:="" 400;="" letter-spacing:="" orphans:="" 2;="" text-align:="" left;="" text-indent:="" 0px;="" text-transform:="" none;="" white-space:="" widows:="" word-spacing:="" -webkit-text-stroke-width:="" background-color:="" rgb(255,="" 255,="" 255);="" text-decoration-thickness:="" initial;="" text-decoration-style:="" text-decoration-color:="" initial;"="">
                <br style="color: rgb(98, 100, 106); font-family: Macan, ;" helvetica="" neue",="" helvetica,="" arial,="" sans-serif;="" font-size:="" 16px;="" font-style:="" normal;="" font-variant-ligatures:="" font-variant-caps:="" font-weight:="" 400;="" letter-spacing:="" orphans:="" 2;="" text-align:="" left;="" text-indent:="" 0px;="" text-transform:="" none;="" white-space:="" widows:="" word-spacing:="" -webkit-text-stroke-width:="" background-color:="" rgb(255,="" 255,="" 255);="" text-decoration-thickness:="" initial;="" text-decoration-style:="" text-decoration-color:="" initial;"="">If you are not yet a member of <strong>Manufacturers Near Me</strong>, join today and start reaching out to new customers!&nbsp; </span>
            </p>
          </td>
          <td class="hidden-xs" style="width: 8.1128%;">
            <br>
          </td>
        </tr>
      </tbody>
    </table>
    <p>
      <br>
    </p>
    <p>
      <br>
    </p>
    <p></p>
    
    <ul class="pricing_menu">
        <li class="main col-sm-4">
          <span id="link330" class="title"> Basic Listing</span>
          <ul>
            <li class="">
              <span id="link331" class="price"> $49/month</span>
            </li>
            <li class="">
              <span id="link332" class="sub-price"> 3rd Priority In Search Results</span>
            </li>
            <li class="">
              <span id="link333"> Select 1 Categories</span>
            </li>
            <li class="">
              <span id="link335"> Ads on Your Listing</span>
            </li>
            <li class="">
              <span id="link336"> Show Your Address (Location)</span>
            </li>
            <li class="">
              <span id="link337"> X</span>
            </li>
            <li class="">
              <span id="link338"> X</span>
            </li>
            <li class="">
              <span id="link339"> X</span>
            </li>
            <li class="">
              <span id="link340"> X</span>
            </li>
            <li class="">
              <span id="link341"> Upload Directory Listings (1)</span>
            </li>
            <li class="">
              <span id="link342"> X</span>
            </li>
            <li class="">
              <span id="link350"> X</span>
            </li>
            <li class="">
              <span id="link542"> No Follow Backlink</span>
            </li>
            <li class="">';
            if ($subscription_userId == $user_ID && $subscription_package_type == "basic") {
              
    $html.='  <form method="post" action="" class="my_form" >
                <input type="hidden" name="subscription_id" value="'.$subscription_data->stripe_subscription_id.'"/>
                <input type="hidden" name="user_ID" value="'.$subscription_data->user_id.'"/>
                <input type="hidden" name="cancelSubscription" value="true" />
                
                <a href="javascript:void(0)" class="btn btn-success btn-lg vmargin cancelbtn">CANCEL PLAN</a>
              </form>';
            }else{
    $html.=' <form method="post" action="'.get_site_url().'/payment-gateway/"  >
                <input type="hidden" name="package_type" value="basic" />
                <input type="hidden" name="package_name" value="Basic Listing" />
                <input type="hidden" name="package_price" value="49" />
                
                <button class="btn btn-success btn-lg vmargin">SELECT PLAN</button>
              </form>';
            }
            $html .='</li>
          </ul>
        </li>
        <li class="main col-sm-4">
          <span id="link353" class="title"> Featured Listing</span>
          <ul>
            <li class="">
              <span id="link354" class="price"> $99/month</span>
            </li>
            <li class="">
              <span id="link355" class="sub-price"> 2nd Priority In Search Results</span>
            </li>
            <li class="">
              <span id="link356"> Select 5 Categories</span>
            </li>
            <li class="">
              <span id="link358"> No Ads on Your Listing</span>
            </li>
            <li class="">
              <span id="link359"> Show Your Address (Location)</span>
            </li>
            <li class="">
              <span id="link360"> Show Your Phone Number</span>
            </li>
            <li class="">
              <span id="link361"> Show Your Social Links</span>
            </li>
            <li class="">
              <span id="link362"> Show Your Website Link</span>
            </li>
            <li class="">
              <span id="link363"> Receive Customer Reviews</span>
            </li>
            <li class="">
              <span id="link370"> Upload Directory Listings (5)</span>
            </li>
            <li class="">
              <span id="link366"> X</span>
            </li>
            <li class="">
              <span id="link373"> X</span>
            </li>
            <li class="">
              <span id="link545"> No Follow Backlink</span>
            </li>
            <li class="">';
            if ($subscription_userId == $user_ID && $subscription_package_type == "featured") {
              
    $html.='  <form method="post" action="" class="my_form" >
                <input type="hidden" name="subscription_id" value="'.$subscription_data->stripe_subscription_id.'"/>
                <input type="hidden" name="user_ID" value="'.$subscription_data->user_id.'"/>
                <input type="hidden" name="cancelSubscription" value="true" />
                <a href="javascript:void(0)" class="btn btn-success btn-lg vmargin cancelbtn">CANCEL PLAN</a>
              </form>';
            }else{
    $html.=' 
              <form method="post" action="'.get_site_url().'/payment-gateway" >
                <input type="hidden" name="package_type" value="featured" />
                <input type="hidden" name="package_name" value="Featured Listing" />
                <input type="hidden" name="package_price" value="99" />
                <button class="btn btn-success btn-lg vmargin">SELECT PLAN</button>
              </form>';
            }
      $html.='</li>
          </ul>
        </li>
        <li class="main col-sm-4">
          <span id="link376" class="title"> Premium Listing</span>
          <ul>
            <li class="">
              <span id="link377" class="price"> $199/month</span>
            </li>
            <li class="">
              <span id="link378" class="sub-price"> 1st Priority In Search Results</span>
            </li>
            <li class="">
              <span id="link379"> Select 20 Categories</span>
            </li>
            <li class="">
              <span id="link381"> No Ads on Your Listing</span>
            </li>
            <li class="">
              <span id="link382"> Show Your Address (Location)</span>
            </li>
            <li class="">
              <span id="link383"> Show Your Phone Number</span>
            </li>
            <li class="">
              <span id="link384"> Show Your Social Links</span>
            </li>
            <li class="">
              <span id="link385"> Show Your Website Link</span>
            </li>
            <li class="">
              <span id="link386"> Receive Customer Reviews</span>
            </li>
            <li class="">
              <span id="link391"> Upload Directory Listings (20)</span>
            </li>
            <li class="">
              <span id="link394"> Add Multiple Business Locations</span>
            </li>
            <li class="">
              <span id="link395"> Verified Business Badge</span>
            </li>
            <li class="">
              <span id="link539"> Do Follow Backlink</span>
            </li>
            <li class="">';
            if ($subscription_userId == $user_ID && $subscription_package_type == "premium") {
              
    $html.='  <form method="post" action="" class="my_form" >
                <input type="hidden" name="subscription_id" value="'.$subscription_data->stripe_subscription_id.'"/>
                <input type="hidden" name="user_ID" value="'.$subscription_data->user_id.'"/>
                <input type="hidden" name="cancelSubscription" value="true" />
                <a href="javascript:void(0)" class="btn btn-success btn-lg vmargin cancelbtn">CANCEL PLAN</a>
              </form>';
            }else{
    $html.='
              <form method="post" action="'.get_site_url().'/payment-gateway" >
                <input type="hidden" name="package_type" value="premium" />
                <input type="hidden" name="package_name" value="Premium Listing" />
                <input type="hidden" name="package_price" value="199" />
                <button class="btn btn-success btn-lg vmargin">SELECT PLAN</button>
              </form>';           
            }
      $html.='</li>
          </ul>
        </li>
      </ul>
    <p></p>
  </div>
</div>
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
';
if (isset($_GET['subscrID'])) {
  
  $html .= "

    <script >
          Swal.fire(
            'Thanks for your payment.',
            'You clicked the button!',
            'success'
          );
        
    </script>
    
  ";
  
}else if (isset($_GET['selectpackage'])) {
  
  $html .= "

    <script >
          Swal.fire(
            'Please select any package first!',
            'You clicked the button!',
            'success'
          );
        
    </script>
    
  ";
  
}else if (isset($_GET['canceled'])) {
  
  $html .= "

    <script >
          Swal.fire(
            'Your Subscription Canceled.',
            'Successfully!',
            'success'
          );
        
    </script>
    
  ";
  
}



?>