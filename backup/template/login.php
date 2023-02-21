<?php
$html = '';
global $user_ID;
if (is_user_logged_in() && $user_ID != 0 && isset($_GET['pageRefer'])) {
    $redirectPage = $_GET['pageRefer'];
    wp_redirect( '/'.$redirectPage);
}else if (is_user_logged_in() && $user_ID != 0){
  wp_redirect( '/user_dashboard');
}
$html .= '
<link rel="stylesheet" href="'.plugin_dir_url(__FILE__).'/login_assets/css/style.css"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.css" integrity="sha512-FA9cIbtlP61W0PRtX36P6CGRy0vZs0C2Uw26Q1cMmj3xwhftftymr0sj8/YeezDnRwL9wtWw8ZwtCiTDXlXGjQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<link rel="stylesheet" href="'.plugin_dir_url(__FILE__).'/vendor/bootstrap/css/bootstrap.min.css"/>
<section class="sectionbody py-5">
<div class="container col-lg-6">
 <div class="row d-flex justify-content-center align-items-center">
   <div class="sectionboxcenter">
   <div class="sectiontoplogcenter">
     <a href="#" class="memberloginbtn active">Member Login</a>
     
    </div>
     <div class="sectionlog1 mb-3">
        <form method="post" action="">
       <div class="row g-0 justify-content-center">
         <div class="col-md- col-lg-11 d-md-flex align-items-center">
           <div class="card-body p-4 p-lg-1 text-black">
               <div class="d-flex align-items-center mb-3 py-3 mt-2">
               <img src="https://wordpress-896782-3115562.cloudwaysapps.com/wp-content/plugins/directory_plugin-master/template/man.png" class="rounded-circle"  style="background-color:white;">
              </div>
               <div class="sectionlog2 mb-4">
                 <i class="fa-solid fa-envelope"></i>
                 <input type="email" name="login_username" class="form-control-email form-control email-lg" placeholder="Email ID">
               </div>
               <div class="sectionlog2 mb-3">
                 <i class="fa-solid fa-lock"></i>
                 <input type="password" name="login_password" class="form-control-password form-control password-lg" placeholder="Password">
               </div>';
               // $html .='<div class="sectionlog3 d-flex mb-3">
               //   <a class="forget" href="'.wp_lostpassword_url().'">*Forgot Password?</a><span class="resetpassword">Click to Reset Password</span>
               //  </div>';
               $html.='<div class="sectionlog4 pt-1 mb-2 text-center">
                 <button class="loginnowbtn" name="login_directory" type="submit"><b>LOGIN NOW</b></button>
               </div>
           </div>
         </div> 
        </div>
        </form>
     </div>
     <div class="sectionlog5 d-md-flex gap-3">
       <div class="textcenterleft my-2">
         <div class="m-0">
           <a class="boxcenterleft" href="'.get_site_url().'/register">Not a Registered User?<small>Create Free User Account</small></a></div>
       </div>
       
   </div>
   </div>
 </div>
</div>
</section>';


?>