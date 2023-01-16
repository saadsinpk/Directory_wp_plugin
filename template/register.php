<?php
$html = '';
$html.='
<link rel="stylesheet" href="'.plugin_dir_url(__FILE__).'login_assets/css/style.css" />
<link rel="stylesheet" href="'.plugin_dir_url(__FILE__).'login_assets/css/fontawesome.css" />
<link rel="stylesheet" href="'.plugin_dir_url(__FILE__).'vendor/bootstrap/css/bootstrap.min.css" />
<section class="sectionbody py-5">
    <div class="container col-lg-7">
   <div class="row d-flex justify-content-center align-items-center">
     <div class="sectionboxcenter">
      
       <div class="sectionreg1 mb-3">
          <form method="post">
         <div class="row g-0 justify-content-center">
           <div class="section6reg">
             <div class="card-body-section p-4 p-lg-1 text-black">
              <div class="createtext"><b>Create New Account</b></div>
              <div class="sectionreg2 mb-3">
                    <input name="reg_full_name" type="text" class="roundedbox" placeholder="Full Name*" fdprocessedid="zizlls">
                  </div>
                  <div class="sectionreg3 mb-3">
                    <input name="reg_email" type="email" class="roundedbox" placeholder="Email*" fdprocessedid="x2897q">
                  </div>
                  <div class="sectionpassword mb-3">
                    <input name="reg_pass" type="password" class="roundedbox" placeholder="Password*" fdprocessedid="xw2y4m">
                  </div>
                <div class="sectionreg4">
                  <input type="checkbox" class="checktext"><b> *By Creating an account,you agree to our teams & conditions</b><br>
                  <input type="checkbox" class="checktext"><b> *I understand that personal information I enter on this website will be stored and used to contact me in the future. I can change or remove this information at any time.</b><br>
                  <input type="checkbox" class="checktext"><b> *I understand that if I make any purchases on this website billing information will be securely stored with a 3rd party payment processor. I can change or remove this information at any time.</b>
                </div>
             </div>
           </div> 
          </div>
          <div class="sectionreg5">
           <div class="sectionbox pt-1 mb-2">
             <button class="profilebtn" name="reg_directory" type="submit"><b>CREATE MY PROFILE</b></button>
           </div>
        </div>
          </form>
       </div>
     </div>
   </div>
 </div>
</section>';
?>