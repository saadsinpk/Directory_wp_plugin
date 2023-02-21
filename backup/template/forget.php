<?php
$html = '';

$html.='
<link rel="stylesheet" href="'.plugin_dir_url(__FILE__).'/login_assets/css/style.css" />
<link rel="stylesheet" href="'.plugin_dir_url(__FILE__).'/login_assets/css/fontawesome.css" />
<link rel="stylesheet" href="'.plugin_dir_url(__FILE__).'/vendor/bootstrap/css/bootstrap.min.css" />
<section class="sectionbody"> 
    <div class="container col-md-8">
   <div class="row d-flex justify-content-center align-items-center">
     <div class="sectionboxcenter">
       <div class="sectionforget1 mt-5 mb-5">
          <form>
         <div class="row g-0 justify-content-center">
           <div class="section6forget">
             <div class="card-body p-4 p-lg-1 text-black">
              <div class="headingtext"><b>Password Retrieval</b></div>
                 <div class="sectionforget2 mb-3">
                   <input type="enteremail" class="roundedboxforget" placeholder="Enter Email Address*">
                 </div>
                 <div>
                  <div id="captcha">
                    <div id="recaptcha2"></div>
                </div>
                 </div>
                    <div class="sectionbox pt-3 mb-1">
                      <button class="getbtn" type="button"><b>GET MY PASSWORD</b></button>
                    </div>
                    <div class="sectionbox pt-4 mb-4">
                        <a href="#" class="backloginbtn"><b>Back to Login Page</b></a>
                        </div>
             </div>
           </div> 
          </div>
          </form>
       </div>
     </div>
   </div>
 </div>
</section>
<script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
<script src="https://www.google.com/recaptcha/api.js?onload=myCallBack&render=explicit" async defer></script>
<script src="main.js"></script>';



?>