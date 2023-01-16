
<?php 
if (!isset($_GET['post_id'])) {
    wp_redirect( '/directory_listing' );
    exit; 
}
  $msg = "";
  if (isset( $_POST['cpt_nonce_field'] ) && wp_verify_nonce( $_POST['cpt_nonce_field'], 'cpt_nonce_action' ) && !empty($_POST['post_title']) && !empty($_POST['user_email']) && !empty($_POST['post_code'])) {
    $file = $_FILES['quote_file'];
    $attach_id = 0;
    $file_name = sanitize_file_name($file['name']);
    $file_type = $file['type'];
    // first checking if tmp_name is not empty
    if (!empty($file['tmp_name'])) {
        // if not, then try creating a file on disk
        $upload = wp_upload_bits($file_name, null, file_get_contents($file['tmp_name']));

        // if wp does not return a file creation error
        if ($upload['error'] === false) {
            // then you can create an attachment
            $attachment = array(
                'post_mime_type' => $upload['type'],
                'post_title' => $file_name,
                'post_content' => '',
                'post_status' => 'inherit'
            );

            // creating an attachment in db and saving its ID to a variable
            $attach_id = wp_insert_attachment($attachment, $upload['file']);

            // generation of attachment metadata
            $attach_data = wp_generate_attachment_metadata($attach_id, $upload['file']);

            // attaching metadata and creating a thumbnail
            wp_update_attachment_metadata($attach_id, $attach_data);
        }
    }
  // create post object with the form values
    $title = $_POST['post_title'];
    $company_name = $_POST['company_name'];
    $company_email = $_POST['company_email'];
    $user_email = $_POST['user_email'];
    $user_phone = $_POST['user_phone'];
    $user_preferred_day = $_POST['user_preferred_day'];
    $user_preferred_time = $_POST['user_preferred_time'];
    $post_code = $_POST['post_code'];
    // $top_id = $_POST['top_id'];
    $user_message = $_POST['user_message'];
    $additional_replies = $_POST['additional_replies'];
    
    $post = array(
      'post_title' =>  $title,
      'post_status' => 'publish', 
      '_thumbnail_id' => $attach_id,
      'post_type' => 'quote_request_form',
      'meta_input' => array(
          'company_name' => $company_name,
          'company_email' => $company_email,
          'user_email' => $user_email,
          'user_phone' => $user_phone,
          'preferred_reply_day' => $user_preferred_day,
          'preferred_reply_time' => $user_preferred_time,
          'city_or_post_code' => $post_code,
          'message' =>$user_message,
          'get_replies' => $additional_replies
      )
    );

  // insert the post into the database
   $form_id = wp_insert_post($post);   
   $imagePath = "";
   $imageUrl = wp_get_attachment_url($attach_id);   
   if (!empty($imageUrl)) {
       $imagePath = "<a href='".$imageUrl."' >Download File</a>";
   }
   $quote_table = "<table border='1'>";   
   $quote_table .= "<tr>
                        <td>Company Name</td>
                        <td>".$company_name."</td>
                    </tr>
                    <tr>
                        <td>User Name</td>
                        <td>".$title."</td>
                    </tr>
                    <tr>
                        <td>User Email </td>
                        <td>".$user_email."</td>
                    </tr>
                    <tr>
                        <td>User Phone</td>
                        <td>".$user_phone."</td>
                    </tr>
                    <tr>
                        <td>Preferred Reply Day</td>
                        <td>".$user_preferred_day."</td>
                    </tr>
                    <tr>
                        <td>Preferred Reply Time</td>
                        <td>".$user_preferred_time."</td>
                    </tr>
                    <tr>
                        <td>City or Post Code</td>
                        <td>".$post_code."</td>
                    </tr>
                    <tr>
                        <td>Message</td>
                        <td>".$user_message."</td>
                    </tr>
                    <tr>
                        <td>Get Replies</td>
                        <td>".$additional_replies."</td>
                    </tr>
                    <tr>
                        <td>Uploaded File:</td>
                        <td>".$imagePath."</td>
                    </tr>
                    ";
 $quote_table .= "</table>";
       $subject = "Request a Quote Email";
       $headers = array(); // let's be safe
       $headers[] = 'MIME-Version: 1.0';
       $headers[] = 'Content-type: text/html; charset=iso-8859-1';
       $headers[] = 'From: Mountain <no-reply@gmail.com>';
       if (wp_mail( $company_email, $subject, $quote_table, $headers )) {    
            $msg = "Request form submitted successfully.";
       }
  }
  $post_id = $_GET['post_id'];
  $get_post_meta = get_post($post_id);
  // var_dump($get_post_meta);
  $companyName = $get_post_meta->post_title;
  $companyEmail = $get_post_meta->email_address;

  $imageUrl = get_the_post_thumbnail_url($post_id, 'full');
    $image = "";
    if (!empty($imageUrl)) {
      $image = $imageUrl;
    } else {
      $image = plugins_url('assets/images/directory2.png', __FILE__);
    }
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css"
integrity="sha512-YWzhKL2whUzgiheMoBFwW8CKV4qpHQAEuvilg9FAn5VJUDwKZZxkJNuGM4XkWuk94WCrrwslk8yWNGmY1EduTA=="
crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css"
integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous" />
<link rel="stylesheet" href="<?php echo plugin_dir_url(__FILE__);?>/login_assets/css/style.css"/>
<div class="mainpush col-md-10 col-md-push-3 pr-0 pl-0">
<div class="headingquote line-height-lg bold contact-member-title">Request Free Quote</div>
<form method="post" enctype="multipart/form-data">
    <input type="hidden" name="company_email" value="<?php echo $companyEmail; ?>">
<div class="mainformlist">
 <?php 
      if (!empty($msg)) {
        echo "<div class='alert alert-success'>".$msg."</div>";
      }
 ?> 
<div class="form-group">
<label>Company Name</label>
<input type="text" readonly name="company_name" placeholder="Enter Name" autocomplete="off" value="<?php echo $companyName; ?>" class="form-control control-group  form-control " id="myform-element-7" fdprocessedid="kx816">
</div>
<div class="form-group">
<input type="text"  name="post_title" placeholder="Enter Name" autocomplete="off" value="" class="form-control control-group  form-control " id="myform-element-7" fdprocessedid="kx816">
</div>
<div class="form-group">
<input type="email" name="user_email" required placeholder="Enter Email (Required)" autocomplete="off" value="" class="form-control control-group  form-control " id="myform-element-8" data-fv-field="user_email" fdprocessedid="q0qo6">
</div>
<div class="form-group">
<input type="text" name="user_phone" placeholder="Enter Phone" autocomplete="off" value="" class="form-control control-group  form-control " id="myform-element-9" fdprocessedid="mfzk9a">
</div>
 <div class="sectionbox pt-2 mb-2">
<label for="choosefile" class="seephonebtn w-100 my-2">
<input type="file" id="choosefile" class="" >Choose File
</label>
</div>
<div class="form-group">
<select name="user_preferred_day" autocomplete="off" class="form-control control-group " id="myform-element-1543" fdprocessedid="p3ctqq">
<option value="">Preferred Reply Day</option>
        <option value="Sunday">Sunday</option>
        <option value="Monday">Monday</option>
        <option value="Tuesday">Tuesday</option>
        <option value="Wednesday">Wednesday</option>
        <option value="Thursday">Thursday</option>
        <option value="Friday">Friday</option>
        <option value="Saturday">Saturday</option>  
</select>
</div>
<div class="form-group">
    <select name="user_preferred_time" autocomplete="off" class="form-control control-group " id="myform-element-1654">
        <option value="">Preferred Reply Time</option>
        <option value="12:00 am">12:00 am</option>
        <option value="1:00 am">1:00 am</option>
        <option value="2:00 am">2:00 am</option>
        <option value="3:00 am">3:00 am</option>
        <option value="4:00 am">4:00 am</option>
        <option value="5:00 am">5:00 am</option>
        <option value="6:00 am">6:00 am</option>
        <option value="7:00 am">7:00 am</option>
        <option value="8:00 am">8:00 am</option>
        <option value="9:00 am">9:00 am</option>
        <option value="10:00 am">10:00 am</option>
        <option value="11:00 am">11:00 am</option>
        <option value="12:00 pm">12:00 pm</option>
        <option value="1:00 pm">1:00 pm</option>
        <option value="2:00 pm">2:00 pm</option>
        <option value="3:00 pm">3:00  pm</option>
        <option value="4:00 pm">4:00 pm</option>
        <option value="5:00 pm">5:00 pm</option>
        <option value="6:00 pm">6:00 pm</option>
        <option value="7:00 pm">7:00 pm</option>
        <option value="8:00 pm">8:00 pm</option>
        <option value="9:00 pm">9:00 pm</option>
        <option value="10:00 pm">10:00 pm</option>
        <option value="11:00 pm">11:00 pm</option>        
    </select>
</div>
   
            <div class="form-group">
            <span class="input_wrapper">
            <input id="pac-input" required="" fv-notempty-message="Required Field" class="controls google-writen-location form-control pac-target-input" type="maintext" name="post_code" placeholder="City or Post Code (Required)" autocomplete="off" data-fv-field="lead_location" fdprocessedid="rwj0b"></span>
         </div>
          <!--   <div class="form-group mt-3">
                <select id="top-id-select" name="top_id" class="form-control control-group combobox select2-preload" fdprocessedid="r8cnn">
                <option value="">Select an Option</option>
                <option value="Pallet Manufacturers">Pallet Manufacturers</option>
            </select>
            </div> -->
            <div class="form-group">
            <textarea rows="4" name="user_message" placeholder="Write a message here..." autocomplete="off" class="form-control control-group form-control" id="myform-element-15"></textarea>
            </div>
	<div class="control-group form-group">
		<label class="control-label" for="myform-element-10 d-inline-block">Get Replies from more members</label>
		<div class="controls">
			<label class="radio-inline d-inline-block">
				<input id="myform-element-10-0" type="radio" name="additional_replies" autocomplete="off" value="yes" checked="checked"> Yes
			</label>
			<label class="radio-inline d-inline-block">
				<input id="myform-element-10-1" type="radio" name="additional_replies" autocomplete="off" value="no"> No
			</label>
		</div>
	</div>
				<div class="form-group nomargin">
				<div class="checkbox nomargin">
					<label class="bmargin small nopad d-inline-block"> 
						<input type="checkbox" class="consent_history" name="terms" autocomplete="off" value="1" required="required" data-fv-notempty="true" data-fv-notempty-message="You must agree to the GDPR consent terms" data-fv-field="consent_history[1]">
						<span class="required">* </span> I understand that information I enter will be stored and shared with relevant members of the site and that I may be contacted by these members and/or the admin of the website.					</label>
				</div>
                <div class="form-actions">
                <button type="submit" value="Send Message" class="sendbuttonmsg" id="myform-element-22" fdprocessedid="2vb4a">Send Message</button>

            </div>
            <?php wp_nonce_field( 'cpt_nonce_action', 'cpt_nonce_field' ); ?>
</div>

</form>
        </div>
        </div>
<div class="leftquotesection col-md-3 col-md-pull-9">
        <div class="well">
            <a href="<?php echo home_url();?>/directory_listing/<?php echo $get_post_meta->post_name; ?>" title="Energizer">
            <img class="doorsectionimg mb-3" src="<?php echo $image; ?>" alt="">
            </a>
            <div class="form-actions mb-3">
              <button type="submit" class="accountbutton" id="suggest-element-14" fdprocessedid="bou3hp"><a href="<?php echo home_url();?>/directory_listing/<?php echo $get_post_meta->post_name; ?>" class="accountbutton">Back to Directory</a></button>              
            </div>
        </div>
    </div>