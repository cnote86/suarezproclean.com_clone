<?php
// ==================================================
// WhatsApp Cloud API - Contact Form Integration (PHP)
// Location: Landing_Page_Janitor/send-whatsapp.php
// ==================================================
//
// REQUIRED: Replace these 3 values with your real credentials.
// - $token:            Permanent access token from Meta (WhatsApp Cloud API)
// - $phone_number_id:  Your WhatsApp Business phone number ID (NOT the phone number)
// - $owner_phone:      The WhatsApp number to receive the message (international format, digits only)
//                      NOTE: You generally cannot send a message to the SAME number that is tied to $phone_number_id.
//                            Use a different WhatsApp line you control to receive the lead notification.
//
$token           = "PASTE_YOUR_WHATSAPP_CLOUD_API_TOKEN_HERE";
$phone_number_id = "PASTE_YOUR_WHATSAPP_PHONE_NUMBER_ID_HERE";
$owner_phone     = "19493579256"; // e.g. 19495551234 (no +)

// --------- Do not edit below this line unless you know what you’re doing ----------
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
  http_response_code(405);
  echo json_encode(["ok" => false, "error" => "Method Not Allowed"]);
  exit;
}

// Accept JSON (AJAX) or regular form POST
$raw = file_get_contents("php://input");
$data = json_decode($raw, true);
if (!is_array($data)) {
  // Fallback to form-encoded
  $data = $_POST ?? [];
}

// Helper getter
function g($k, $default = "") {
  global $data;
  if (!isset($data[$k])) return $default;
  if (is_bool($data[$k])) return $data[$k];
  return trim((string)$data[$k]);
}

// Collect fields exactly as form names
$name        = g("name");
$company     = g("company");
$phone       = g("phone");
$email       = g("email");
$city        = g("city");
$contactTime = g("contactTime");
$service     = g("service");
$size        = g("size");
$message     = g("message");
$consent     = g("consent");

// Normalize consent (true/"on"/"1")
$consented = false;
if ($consent === true || $consent === "true" || $consent === "on" || $consent === "1" || $consent === 1) {
  $consented = true;
}

// Validate required fields (mirrors your front-end)
$missing = [];
if (strlen($name) < 2)     $missing[] = "name";
if (!preg_match("/\d{7,}/", preg_replace("/\D+/", "", $phone ?? ""))) $missing[] = "phone";
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $missing[] = "email";
if (strlen($city) < 2)     $missing[] = "city";
if (!$service)             $missing[] = "service";
if (!$consented)           $missing[] = "consent";

if (!empty($missing)) {
  http_response_code(400);
  echo json_encode(["ok" => false, "error" => "Invalid or missing fields", "fields" => $missing]);
  exit;
}

// Compose WhatsApp message
$bodyLines = [
  "📩 New Cleaning Quote Request:",
  "",
  "👤 Name: {$name}",
];
if ($company)     $bodyLines[] = "🏢 Company: {$company}";
$bodyLines[] = "📞 Phone: {$phone}";
$bodyLines[] = "✉ Email: {$email}";
$bodyLines[] = "🏙 City: {$city}";
if ($contactTime) $bodyLines[] = "⏰ Preferred Time: {$contactTime}";
$bodyLines[] = "🧹 Service: {$service}";
if ($size)        $bodyLines[] = "📐 Size: {$size}";
if ($message)     $bodyLines[] = "📝 Notes: {$message}";

$body = implode("\n", $bodyLines);

// Send via WhatsApp Cloud API
$url  = "https://graph.facebook.com/v20.0/{$phone_number_id}/messages";
$payload = [
  "messaging_product" => "whatsapp",
  "to"                => $owner_phone, // international format without "+"
  "type"              => "text",
  "text"              => ["body" => $body]
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
  "Authorization: Bearer {$token}",
  "Content-Type: application/json"
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload, JSON_UNESCAPED_UNICODE));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 20);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlErr  = curl_error($ch);
curl_close($ch);

// Relay result
if ($curlErr) {
  http_response_code(502);
  echo json_encode(["ok" => false, "error" => "cURL error", "details" => $curlErr]);
  exit;
}

$resp = json_decode($response, true);
if ($httpCode >= 200 && $httpCode < 300) {
  echo json_encode(["ok" => true, "whatsapp_response" => $resp]);
} else {
  http_response_code($httpCode ?: 500);
  echo json_encode(["ok" => false, "error" => "WhatsApp API error", "details" => $resp ?: $response]);
}
