<?php 
global $user_ID;
if (!is_user_logged_in() && $user_ID == 0) {
    wp_redirect( '/login/?pageRefer=list-packages');
}else if (!isset($_POST['package_type']) && !isset($_POST['package_name'])){
	wp_redirect( '/list-packages/?selectpackage');
}
$html = '
    
    <script src="https://js.stripe.com/v3/"></script>
    
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900" rel="stylesheet">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="' . plugin_dir_url(__FILE__) . 'StripeAssets/css/bootstrap.min.css' . '">
    <!-- Responsive styles-->
    <link rel="stylesheet" href="' . plugin_dir_url(__FILE__) . 'StripeAssets/css/demo-style.css' . '">
    <!-- Font awosome -->
    <link rel="stylesheet" href="' . plugin_dir_url(__FILE__) . 'StripeAssets/css/font-awesome.min.css' . '">
  	<style >
  		.bank-transfer-spinner {

    display: inline-block;

    background-color: #23282d;

    opacity: 0.75;

    width: 24px;

    height: 24px;

    border: none;

    border-radius: 100%;

    padding: 0;

    margin: 0 24px;

    position: relative;

}



.bank-transfer-spinner::before {

    content: "";

    position: absolute;

    background-color: #fbfbfc;

    top: 4px;

    left: 4px;

    width: 6px;

    height: 6px;

    border: none;

    border-radius: 100%;

    transform-origin: 8px 8px;

    animation-name: spin;

    animation-duration: 1000ms;

    animation-timing-function: linear;

    animation-iteration-count: infinite;

}
  	</style>
    <section class="pading-bottom-30">
		<div class="bg_area_1">
			<div class="container">
				<div class="row">
					<div class="form-box-size login-form-3 box-shadow">
						<div class="login-form-box-3">
							<div class="form-wrapper">
								<form action="" method="post" name="cardpayment" id="payment-form">
								<input type="hidden" name="package_type" value="'.$_POST['package_type'].'" />
								<input type="hidden" name="amount" value="'.$_POST['package_price'].'" />
								<input type="hidden" name="plan_name" value="Monthly Subscription" />
								<input type="hidden" name="interval" value="month" />
								<input type="hidden" name="user_ID" value="'.$user_ID.'" />';
						$html .= ' <div class="form-group focused">
										<label class="form-label" for="name">Package Name</label>
										<input readonly name="package_name" id="package_name" class="form-input" type="text" value="'.$_POST['package_name'].'" />
										<i class="fa fa-pinterest-p" aria-hidden="true"></i>
									</div>

									<div class="form-group">
										<label class="form-label" for="name">Card Holder Name</label>
										<input name="holdername" id="name" class="form-input" type="text" required />
										<i class="fa fa-user" aria-hidden="true"></i>
									</div>

									<div class="form-group">
										<label class="form-label" for="email">Email</label>
										<input name="email" id="email" class="form-input" type="email" required />
										<i class="fa fa-envelope" aria-hidden="true"></i>
									</div>
									<div class="form-group focused" style="display:none;">
										<label class="form-label" for="email">Package end after</label>
										<select name="interval_count_____" class="form-input">
											<option value="1" >1 month</option>
											<option value="2" >2 months</option>
											<option value="3" >3 months</option>
											<option value="4" >4 months</option>
											<option value="5" >5 months</option>
											<option value="6" >6 months</option>
											<option value="7" >7 months</option>
											<option value="8" >8 months</option>
											<option value="9" >9 months</option>
											<option value="10" >10 months</option>
											<option value="11" >11 months</option>
											<option value="12" >12 months</option>
										</select>
										<i class="fa fa-asterisk" aria-hidden="true"></i>
									</div>
									<div class="form-group focused">										
										 <div id="card-element">
								        <!-- A Stripe Element will be inserted here. -->
								        </div>
												<!-- Used to display form errors. -->
		        						<div id="card-errors" role="alert"></div>
												
									</div>
									
									<div class="form-group">
										<div class="payment-errors"></div>
									</div>

									<div class="button-style">
										<button type="submit" class="button login submit">
											 Paynow ($'.$_POST['package_price'].') <i class="fa fa-arrow-right" aria-hidden="true"> </i>
											<i class="fa fa-spinner fa-spin payProcess" aria-hidden="true" style="display:none;"></i>
										</button>

									</div>

								</form>
								
							</div>

						</div>

					</div>
				</div>
			</div>
		</div>
	</section>

	<script src="' . plugin_dir_url(__FILE__) . 'StripeAssets/js/main.js' . '"></script>	
	
    ';
$html .="
	<script type='text/javascript'>
	jQuery(function($) {
	// Create a Stripe client.
var stripe = Stripe('pk_test_51M9qovGDTXcYyUEX7VSHYBzMuTfdHbItYm4tV3NTGs2qccbGQjpEWKMiWgbnmbHr2dhfNJTE11SKmLij6FOn6Ouj00M6MK0rN0');
  
// Create an instance of Elements.
var elements = stripe.elements();
  
// Custom styling can be passed to options when creating an Element.
// (Note that this demo uses a wider set of styles than the guide below.)
var style = {
    base: {
        color: '#32325d',
        fontFamily: '\"Helvetica Neue\", Helvetica, sans-serif',
        fontSmoothing: 'antialiased',
        fontSize: '16px',
        '::placeholder': {
            color: '#aab7c4'
        }
    },
    invalid: {
        color: '#fa755a',
        iconColor: '#fa755a'
    }
};
  
// Create an instance of the card Element.
var card = elements.create('card', {style: style});
  
// Add an instance of the card Element into the `card-element` <div>.
card.mount('#card-element');
  
// Handle real-time validation errors from the card Element.
card.addEventListener('change', function(event) {
    var displayError = document.getElementById('card-errors');
    if (event.error) {
        displayError.textContent = event.error.message;
    } else {
        displayError.textContent = '';
    }
});
  
// Handle form submission.
var form = document.getElementById('payment-form');
form.addEventListener('submit', function(event) {
    event.preventDefault();
  	$('.payProcess').show();
    stripe.createToken(card).then(function(result) {
        if (result.error) {
        	$('.payProcess').hide();
            // Inform the user if there was an error.
            var errorElement = document.getElementById('card-errors');
            errorElement.textContent = result.error.message;
        } else {
            // Send the token to your server.
            stripeTokenHandler(result.token);
        }
    });
});
}); 
// Submit the form with the token ID.
function stripeTokenHandler(token) {
    // Insert the token ID into the form so it gets submitted to the server
    var form = document.getElementById('payment-form');
    var hiddenInput = document.createElement('input');
    hiddenInput.setAttribute('type', 'hidden');
    hiddenInput.setAttribute('name', 'stripeToken');
    hiddenInput.setAttribute('value', token.id);
    form.appendChild(hiddenInput);
  
    // Submit the form
    form.submit();
}

</script>
";