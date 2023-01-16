<?php
if (!isset($_GET['post_id'])) {
	wp_redirect('/directory_listing');
	exit;
}
$msg = "";
if (isset($_POST['claimsubmit']) && !empty($_POST['first_name'])) {
	$msg = "Claim form submitted successfully.";
}
$post_id = $_GET['post_id'];
$get_post_meta = get_post($post_id);
$companyName = $get_post_meta->post_title;
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" integrity="sha512-YWzhKL2whUzgiheMoBFwW8CKV4qpHQAEuvilg9FAn5VJUDwKZZxkJNuGM4XkWuk94WCrrwslk8yWNGmY1EduTA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous" />
<link rel="stylesheet" href="<?php echo plugin_dir_url(__FILE__); ?>/login_assets/css/style.css" />
<section class="sectionbody">
	<div class="container col-sm-8">
		<div class="sectionboxcenter claim_main pt-1 pb-4">
			<div class="sectionsuggest1 mt-5 mb-4">
				<form method="post" enctype="multipart/form-data">
					<input type="hidden" name="company_name" value="<?php echo $companyName; ?>">
					<input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
					<div class="row g-0 justify-content-center">
						<div class="section6suggest">
							<div class="card-body p-4 p-lg-1 text-black m-3">
								<div class="filltext"><b>Fill In The Form </b></div>
								<?php
								if (!empty($msg)) {
									echo "<div class='alert alert-success'>" . $msg . "</div>";
								}
								?>
								<div class="sectionsuggest2 mb-3">
									<div class="tagsheading"> <span class="required">*</span>First Name</div>
									<input type="text" required name="first_name" class="borderrounded">
								</div>
								<div class="sectionsuggest2 mb-3">
									<div class="tagsheading"> <span class="required">*</span>Last Name</div>
									<input type="text" required name="last_name" class="borderrounded">
								</div>
								<div class="sectionsuggest2 mb-3">
									<div class="tagsheading"> <span class="required">*</span>Email</div>
									<input type="email" name="email" class="borderrounded">
								</div>
								<div class="sectionsuggest2 mb-3">
									<div class="tagsheading"> <span class="required">*</span>Phone</div>
									<input type="number" name="phone" class="borderrounded">
								</div>
								<div class="sectionsuggest2 mb-3">
									<div class="tagsheading"> <span class="required">*</span>Address</div>
									<input type="text" name="address" class="borderrounded">
								</div>
								<p class="account-tip">
									<img class='bulbimage' src='<?php echo plugins_url('assets/images/bulb.png', __FILE__); ?>' />
									Documents
								</p>
								<div class="sectionsuggest2 mb-3 multiImages">
									<div class="sectionbox pt-2 mb-2">
										<label for="choosefile" class="seephonebtn w-100 my-2">
											<input type="file" id="choosefile" name="mutipleFiles[]" class="" multiple="multiple">Choose File
										</label>
									</div>
									<div class="doorImages" style="display:none;">
									</div>
								</div>
								<div class="form-actions mb-3">
									<a><button type="submit" name="claimsubmit" class="messagesubmit" id="suggest-element-14" fdprocessedid="bou3hp">Submit Message</a></button>
								</div>
							</div>
						</div>
					</div>
				</form>
				<script type="text/javascript">
					jQuery(function($) {
						$('body').on('click', '#choosefile', function(event) {
							var imageDiv = "";
							var filesInput = document.getElementById("choosefile");
							filesInput.addEventListener("change", function(event) {
								var files = event.target.files; //FileList object
								if (files) {
									$(".doorImages").show();
									$(".doorImages").empty();
								}
								for (var i = 0; i < files.length; i++) {
									var file = files[i];
									//Only pics
									if (file.type.match('image')) {
										var picReader = new FileReader();
										picReader.addEventListener("load", function(event) {
											fileName = "";
											var picFile = event.target;
											imageDiv = "<img class='thumbnail' src='" + picFile.result + "'" +
												"title='" + picFile.name + "'/>";
											//sample function 1: add image preview
											$(".doorImages").append(
												` <div class="column">
						                            <a href="javascript:void(0)" class="removeImg"><span  class="closebtn">&times;</span></a>
						                            <input type='hidden'  name='documents[multiple][${i}]'  value='${file.name}'/>
						                            ${imageDiv}
						                            <label>${fileName}</label>
						                         </div>`);
										});
										//Read the image
										picReader.readAsDataURL(file);
									} else if (file.type.match('pdf')) {
										fileName = file.name;
										imageDiv = "<img class='thumbnail' src='<?php echo plugins_url('assets/images/pdf.png', __FILE__); ?>' />";
										//sample function 1: add image preview
										$(".doorImages").append(
											` <div class="column">
					                            <a href="javascript:void(0)" class="removeImg"><span  class="closebtn">&times;</span></a>
					                            <input type='hidden'  name='documents[multiple][${i}]'  value='${file.name}'/>
					                            ${imageDiv}				                            
					                            <label>${fileName}</label>
					                         </div>`);
									} else {
										fileName = file.name;
										imageDiv = "<img class='thumbnail' src='<?php echo plugins_url('assets/images/notepad.png', __FILE__); ?>' />";
										//sample function 1: add image preview
										$(".doorImages").append(
											` <div class="column">
						                            <a href="javascript:void(0)" class="removeImg"><span  class="closebtn">&times;</span></a>
						                            <input type='hidden'  name='documents[multiple][${i}]'  value='${file.name}'/>
						                            ${imageDiv}					                            
						                            <label>${fileName}</label>
						                         </div>`);
									}
								}
							});
						});
						// on remove button click
						$('body').on('click', '.removeImg', function(event) {
							event.preventDefault();
							const button = $(this);
							if (confirm('Are you sure you want to remove this item!')) {
								button.parents(".column").remove();
							}
						});
					});
				</script>
			</div>
		</div>
	</div>
</section>