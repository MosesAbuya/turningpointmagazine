// <?php
// header("Content-Type:application/json");

// /*Call function with these configurations*/
// $env = "sandbox";
// $type = 4;
// $shortcode = '174379'; 
// $key = "LuWEaIrjjCOwE6o7N6mwyl3mchiX2g0LE1qVRVTZk6dGhh8j"; //Put your key here
// $secret = "HBdpE2pMSZjhvSGCrwxWMYrzi65yd76okwcraACT4PF2GuBVXaaPBJA0RRAPvBk9";  //Put your secret here
// $initiatorName = "testapi";
// $initiatorPassword = "Safaricom978!";
// $results_url = "https://mydomain.com/TransactionStatus/result/";
// $timeout_url = "https://mydomain.com/TransactionStatus/queue/";
// $CallBackURL = 'https://www.turningpointmagazine.africa/status.php';

// /*Ensure transaction code is entered*/
// if (!isset($_GET["transactionID"])) {
//     echo json_encode(["error" => "Technical error: Missing transaction ID"]);
//     exit();
// }

// $transactionID = $_GET["transactionID"]; 
// $command = "TransactionStatusQuery";
// $remarks = "Transaction Status Query"; 
// $occasion = "Transaction Status Query";

// /*Generate Access Token*/
// $access_token_url = ($env == "live") ? 
//     "https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials" :
//     "https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials";

// $credentials = base64_encode($key . ':' . $secret);
// $ch = curl_init($access_token_url);
// curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Basic " . $credentials]);
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
// $response = curl_exec($ch);
// curl_close($ch);
// $result = json_decode($response);
// $token = isset($result->{'access_token'}) ? $result->{'access_token'} : null;

// if (!$token) {
//     echo json_encode(["error" => "Failed to retrieve access token"]);
//     exit();
// }

// /*Encrypt Initiator Password*/
// $publicKey = file_get_contents(__DIR__ . "/mpesa_public_cert.cer"); 
// openssl_public_encrypt($initiatorPassword, $encrypted, $publicKey, OPENSSL_PKCS1_PADDING);
// $password = base64_encode($encrypted);

// /*Prepare Request Data*/
// $curl_post_data = [
//     "Initiator" => $initiatorName, 
//     "SecurityCredential" => $password, 
//     "CommandID" => $command, 
//     "TransactionID" => $transactionID, 
//     "PartyA" => $shortcode, 
//     "IdentifierType" => $type, 
//     "ResultURL" => $results_url, 
//     "QueueTimeOutURL" => $timeout_url, 
//     "Remarks" => $remarks, 
//     "Occasion" => $occasion,
// ];

// $endpoint = ($env == "live") ? 
//     "https://api.safaricom.co.ke/mpesa/transactionstatus/v1/query" :
//     "https://sandbox.safaricom.co.ke/mpesa/transactionstatus/v1/query";

// $ch2 = curl_init($endpoint);
// curl_setopt($ch2, CURLOPT_HTTPHEADER, [
//     'Authorization: Bearer '.$token,
//     'Content-Type: application/json'
// ]);
// curl_setopt($ch2, CURLOPT_POST, 1);
// curl_setopt($ch2, CURLOPT_POSTFIELDS, json_encode($curl_post_data));
// curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
// $response = curl_exec($ch2);
// curl_close($ch2);

// $result = json_decode($response);
// $verified = $result->{'ResponseCode'};

// /*Send Transaction Status to callback_url.php*/
// $status = ($verified === "0") ? "complete" : "failed";
// $callback_data = [
//     "transaction_id" => $transactionID,
//     "status" => $status
// ];

// $ch3 = curl_init($callback_url);
// curl_setopt($ch3, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
// curl_setopt($ch3, CURLOPT_POST, 1);
// curl_setopt($ch3, CURLOPT_POSTFIELDS, json_encode($callback_data));
// curl_setopt($ch3, CURLOPT_RETURNTRANSFER, 1);
// $callback_response = curl_exec($ch3);
// curl_close($ch3);

// echo json_encode(["message" => "Transaction status sent to callback", "response" => json_decode($callback_response)]);

// ?>