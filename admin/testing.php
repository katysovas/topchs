
<script src="js/jquery-1.9.1.js"></script>
    <script type="text/javascript" src="https://js.stripe.com/v2/"></script> 
    <script type="text/javascript">    
     $(document).ready(function(){ 
		// Get the values:
        var error = false;
		var ccNum = "4465420320906159"; 
        var cvcNum = "505";
        var expMonth = "07";
        var expYear = "2019";
		// Validate the number:
		if (!Stripe.card.validateCardNumber(ccNum)) {
			error = true;
		}
		// Validate the expiration:
		if (!Stripe.card.validateExpiry(expMonth, expYear)) {
			error = true;	
		}
		// Validate other form elements, if needed!

		// Check for errors:
		if (!error){
			// Get the Stripe token:
			Stripe.card.createToken({
				number: ccNum,
				exp_month: expMonth,
				exp_year: expYear
			}, stripeResponseHandler);
		  } 
     });     
function stripeResponseHandler(status, response) {
	if (response.error) {
		reportError(response.error.message);
	} 
    else { // No errors, submit the form:
	  	  
	  var token = response['id'];
	  alert(token);
	}
}     
</script>
<body>
 <?php  
// Check for a form submission:

		if (isset($errors) && !empty($errors) && is_array($errors)) {
			echo '<div class="alert alert-error">';
			foreach ($errors as $e) {
				echo $e;
			}
			echo '</div>';
		}
echo '<script type="text/javascript">Stripe.setPublishableKey("pk_live_bAWFPBkZrDuPLkUPHOzI1TKM");</script>';
?>