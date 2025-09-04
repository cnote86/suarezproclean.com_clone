<?php
// ============================================================
// CONTACT FORM HANDLER (Email + SMS via MetroPCS Gateway)
// ============================================================

// Replace with your real email
$your_email = "luzsuarezj74@gmail.com";

// MetroPCS gateways (SMS & MMS)
$sms_gateway = "19493579256@metropcs.smsmytmo.com";
$mms_gateway = "19493579256@mymetropcs.com";

// Collect form data safely
$name    = isset($_POST['name']) ? strip_tags($_POST['name']) : '';
$email   = isset($_POST['email']) ? strip_tags($_POST['email']) : '';
$company = isset($_POST['company']) ? strip_tags($_POST['company']) : '';
$phone   = isset($_POST['phone']) ? strip_tags($_POST['phone']) : '';
$city    = isset($_POST['city']) ? strip_tags($_POST['city']) : '';
$service = isset($_POST['service']) ? strip_tags($_POST['service']) : '';
$size    = isset($_POST['size']) ? strip_tags($_POST['size']) : '';
$time    = isset($_POST['contactTime']) ? strip_tags($_POST['contactTime']) : '';
$message = isset($_POST['message']) ? strip_tags($_POST['message']) : '';

// Build email subject & body
$subject = "New Contact Form Submission from $name";
$body    = "You have a new contact form submission:\n\n" .
           "Name: $name\n" .
           "Company: $company\n" .
           "Phone: $phone\n" .
           "Email: $email\n" .
           "City: $city\n" .
           "Service: $service\n" .
           "Sq Ft / Rooms: $size\n" .
           "Preferred Time: $time\n\n" .
           "Message:\n$message\n";

// Headers
$headers = "From: no-reply@" . $_SERVER['SERVER_NAME'] . "\r\n";
$headers .= "Reply-To: $email\r\n";

// Send to inbox + SMS/MMS at once
$to = $your_email . ", " . $sms_gateway . ", " . $mms_gateway;

if (mail($to, $subject, $body, $headers)) {
    echo "OK";
} else {
    http_response_code(500);
    echo "There was an error sending your message. Please try again.";
}
?>
