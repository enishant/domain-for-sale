<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

/* Maintenance Mode */
$maintenance = false; //  true | false;

/* Force https */
$force_https = false; //  true | false;

/* Google Tag */
$gtag = 'G-XXXXXXXXXX';

/* Domain / Email */
$domain = 'example.com';

/* Email Sender / Receiver */
$from_email = 'sender@example.com';
$to_email   = 'receiver@example.com';
$bcc_email  = '';

/* SMTP Configuration */
$smtp_server     = 'smtp.example.com';
$smtp_username   = 'sender@example.com';
$smtp_password   = 'secret';
$smtp_auth       = true;
$smtp_encryption = 'tls'; // tls | ssl
$smtp_port       = 587;

if($force_https === true && $_SERVER['REQUEST_SCHEME'] === 'http') {
    header('Location: https://' . $domain);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="robots" content="index, follow">
	<meta name="description" content="Domain <?php echo $domain; ?> for sale, buy domains <?php echo $domain; ?>">
	<meta name="keywords" content="Buy domain,domain parking, domain name, domain, domain <?php echo $domain; ?> for sale, <?php echo $domain; ?>, register domain, new domain name, domains for sale, premium domains, website, transfer domain, buy website, domain auction, marketplace, domain brokerage" />
	<title>Domain <?php echo $domain; ?> for sale</title>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Anton&family=Be+Vietnam+Pro:wght@200&family=Georama&family=Passions+Conflict&display=swap" rel="stylesheet" media="print" onload="this.media='all'; this.onload = null">
	<style type="text/css">
		body {
			background: #844;
		}

		.domain-for-sale {
			font-family: 'Passions Conflict', cursive;
			font-weight: 400;
			font-size: 5rem;
			color: #ccc;
			text-align: center;
		}
		
		.domain-name {
			font-family: 'Anton', sans-serif;
			font-weight: 400;
			font-size: 4.5rem;
			color: #ccc;
			text-align: center;
		}

		.make-an-offer {
			font-family: 'Georama', sans-serif;
			font-weight: 400;
			font-size: 2rem;
			color: #ccc;
			text-align: center;
		}

		.make-an-offer-form,
		.submit-offer {
			padding: 15px;
			margin: 15px auto 15px auto;
			text-align: center;
		}

		.make-an-offer p {
			font-family: 'Be Vietnam Pro', sans-serif;
			font-size: 1.5rem;
			font-weight: 700;
		}

		input[type="text"] {
			padding: 5px;
			margin: 5px;
			font-size: 1.2rem;
			font-family: 'Be Vietnam Pro', sans-serif;
			border: 2px solid #fff;
			background: #db4848;
			background: #4E0E0E;
			color: #fff;
			color: #F1A865;
		}

		input[type="text"]::-webkit-input-placeholder { /* Edge */
		  color: #fff;
		}

		input[type="text"]:-ms-input-placeholder { /* Internet Explorer 10-11 */
		  color: #fff;
		}

		input[type="text"]::placeholder {
		  color: #fff;
		}

		input[type="submit"].btn {
			border: 2px solid #fff;
			padding: 5px;
			font-size: 1.2rem;
			width: 150px;
			margin: 0 auto;
			cursor: pointer;
			background: #4E0E0E;
			color: #fff;
		}

		input[type="submit"].btn:hover {
			background: #fff;
			color: #4E0E0E;
			border: 2px solid #4E0E0E;
		}

		input[type="submit"].btn.disabled,
		input[type="submit"].btn.disabled:hover {
    		background: #cfcfcf;
    		color: #a3a3a3;
    		cursor: not-allowed;
		}

		#offer-validate {
			margin: 15px auto;
			color: #fff;
		}
	</style>
    <?php if(isset($gtag) === true && empty($gtag) === false) { ?>
        <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $gtag; ?>"></script>
        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());
        
          gtag('config', '<?php echo $gtag; ?>');
        </script>
    <?php } ?>
</head>
<body>
<?php 
if($maintenance === true) {
    echo '<div class="domain-name">Domain for sale<br> email: ' . $from_email . '</div>';
} else if(isset($_POST['domain-lead']) === true && empty($_POST['domain-lead']) === false) {
    require_once 'vendor/autoload.php';
    
    $mail = new PHPMailer(true);
    
    try {
        //Server settings
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host       = $smtp_server;
        $mail->SMTPAuth   = true;
        $mail->Username   = $smtp_username;
        $mail->Password   = $smtp_password;
        $mail->SMTPSecure = $smtp_encryption;
        $mail->Port       = $smtp_port;
    
        //Recipients
        $mail->setFrom($from_email, $domain);
        $mail->addAddress($to_email);
        if(isset($bcc_email) === true && empty($bcc_email) === false) {
        	$mail->addBCC($bcc_email);
        }
        $mail->addReplyTo($from_email, $domain);
    
        //Content
        $mail->isHTML(true);
        $mail->Subject = 'Lead (' . $domain . ')';
    
        $incomplete_form = false;
        $mail_body = '';
        foreach($_POST as $fk => $field) {
        	if($fk === 'domain-lead') {
        		continue;
        	}
        	if(empty($field) === true) {
        		$incomplete_form = true;
        		break;
        	}
        	
        	if($fk === 'offer' && is_numeric($field) === true) {
        		$mail_body .= '<p>' . $fk . ': ' . number_format($field, 2) . '</p>';
        	} else {
        		$mail_body .= '<p>' . $fk . ': ' . $field . '</p>';
        	}
        }
    
        if($incomplete_form) {
        	echo '<h2 style="color:#fff;">Please complete all fields</h2>';
        } else {
	        $mail->Body    = $mail_body;
	        $mail->send();
	        echo '<h2 style="color:#fff;">Thank You</h2>';
        }
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
} else { ?>
	<div class="domain-for-sale">Domain for Sale</div>
	<div class="domain-name"><?php echo $domain; ?></div>
	<div class="make-an-offer">
		Submit this form with your offered price to get this domain.
		<p>Send offer request with Indian Rupees only</p>
	</div>
	<div class="make-an-offer-form">
		<form action="/" method="POST" onsubmit="return validateForm()">
			<input type="hidden" name="domain-lead" value="domain-for-sale">
			<input type="text" id="offer-name" name="name" placeholder="Your Name">
			<input type="text" id="offer-contact" name="contact" placeholder="Contact Number">
			<input type="text" id="offer-email" name="email" placeholder="E-mail">
			<input type="text" id="offer-offer" name="offer" placeholder="Offered Price">
			<div class="submit-offer">
				<input type="submit" class="btn btn-submit" value="Make an Offer">
				<div id="offer-validate"></div>
			</div>
		</form>
	</div>
	<script type="text/javascript">
		function validateForm() {
		  let name = document.getElementById('offer-name').value;
		  let contact = document.getElementById('offer-contact').value;
		  let email = document.getElementById('offer-email').value;
		  let offer = document.getElementById('offer-offer').value;
		  if (name == '' || contact == '' || email == '' || offer == '') {
		  	document.getElementById('offer-validate').innerHTML = 'All fields are required.';
		    return false;
		  } else if ((/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email)) === false) {
		  	document.getElementById('offer-validate').innerHTML = 'Invalid email address.';
		    return false;
		  } else if(isNaN(offer) === true || isNaN(contact) === true ) {
		  	document.getElementById('offer-validate').innerHTML = 'Required numeric value for contact and offer.';
		    return false;
		  }
		}
	</script>
<?php } ?>
</body>
</html>
