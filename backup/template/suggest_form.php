<?php 
  $msg = "";
  if (isset( $_POST['cpt_nonce_field'] ) && wp_verify_nonce( $_POST['cpt_nonce_field'], 'cpt_nonce_action' ) && !empty($_POST['post_title'])) {

  // create post object with the form values
    $title = $_POST['post_title'];
    $business_email = $_POST['business_email'];
    $business_phone = $_POST['business_phone'];
    $business_website = $_POST['business_website'];
    $business_note = $_POST['business_note'];
    
    $post = array(
      'post_title' =>  $title,
      'post_status' => 'publish', 
      'post_type' => 'suggest_form',
      'meta_input' => array(
          'business_email' => $business_email,
          'business_phone' => $business_phone,
          'business_website' => $business_website,
          'business_note' => $business_note
      )
    );

  // insert the post into the database
   $post_id = wp_insert_post($post);
    $msg = "Suggest form submitted successfully.";
  }
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css"
integrity="sha512-YWzhKL2whUzgiheMoBFwW8CKV4qpHQAEuvilg9FAn5VJUDwKZZxkJNuGM4XkWuk94WCrrwslk8yWNGmY1EduTA=="
crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css"
integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous" />
<link rel="stylesheet" href="<?php echo plugin_dir_url(__FILE__);?>/login_assets/css/style.css"/>
<section class="sectionbody">
        <div class="container col-sm-7">
       <div class="row d-flex justify-content-center align-items-center">
         <div class="sectionboxcenter">
           <div class="sectionsuggest1 mt-5 mb-4">
              <form method="post">
               <div class="row g-0 justify-content-center">
                 <div class="section6suggest">
                   <div class="card-body p-4 p-lg-1 text-black m-3">
                    <div class="filltext"><b>Fill In The Form to Suggest a Business</b></div>
                        <?php 
                          if (!empty($msg)) {
                            echo "<div class='alert alert-success'>".$msg."</div>";
                          }
                         ?>                      
                       <div class="sectionsuggest2 mb-3">
                        <div class="tagsheading"> <span class="required">*</span> Business Name</div>
                         <input type="text" required name="post_title" class="borderrounded">
                       </div>
                       <div class="sectionsuggest2 mb-3">
                        <div class="tagsheading"> <span class="required">*</span> Business Email</div>
                         <input type="email" name="business_email" class="borderrounded">
                       </div>
                       <div class="sectionsuggest2 mb-3">
                        <div class="tagsheading"> <span class="required">*</span> Business Phone</div>
                         <input type="text" name="business_phone" class="borderrounded">
                       </div>
                       <div class="sectionsuggest2 mb-3">
                        <div class="tagsheading"> <span class="required">*</span> Business Website</div>
                         <input type="text" name="business_website" class="borderrounded">
                       </div>
                       <p class="account-tip">Add Any Notes to Help Our Decision</p>
                        <div class="sectionsuggest2 mb-3">
                          <textarea class="suggestborderbox w-100" name="business_note" id="" cols="30" rows="5"></textarea>
                          </div>
                          <div class="form-actions mb-3">
                          <a><button type="submit" class="messagesubmit" id="suggest-element-14" fdprocessedid="bou3hp">Submit Message</a></button>
                          
                          <?php wp_nonce_field( 'cpt_nonce_action', 'cpt_nonce_field' ); ?>

                        </div>
                   </div>
                 </div> 
                </div>
              </form>
           </div>
           <div class="sectionboxcenter">
            <div class="whattext"><b>What We're Looking For</b></div>
            <ul class="lookingtext">
            <li class="detailtext">A Business that is ready to connect with more customers and clients.</li>
            <li class="detailtext">An established business that has been supplying the United States manufactured goods.</li>
            <li class="detailtext">A business that loves to gain more exposure.</li>
            </ul>
           </div>
         </div>
       </div>
     </div>
   </section>