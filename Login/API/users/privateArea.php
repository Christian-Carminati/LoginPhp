<?php

session_start();
$email = $_SESSION["email"];

require_once '../../googleOauth/google/vendor/autoload.php';
  
// init configuration
$clientID = '320035418897-il94k1qkom4ol47l19p1682j7bsdlbv9.apps.googleusercontent.com';
$clientSecret = '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';
$redirectUri = 'http://carminati132.altervista.org/Login/API/users/privateArea.php';
   
// create Client Request to access Google API
$client = new Google_Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->addScope("email");
$client->addScope("profile");
  
// authenticate code from Google OAuth Flow
if (isset($_GET['code'])) {
  $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
  $client->setAccessToken($token['access_token']);
   
  // get profile info
  $google_oauth = new Google_Service_Oauth2($client);
  $google_account_info = $google_oauth->userinfo->get();
  $email =  $google_account_info->email;
  //$name =  $google_account_info->name;
  
  // now you can use this profile info to create account in your website and make user logged in.
} else {
    $email = $_SESSION["email"];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/login.css">
    <title>Area privata</title>
</head>
<body>
    <div class="container" style="width: 540px;">
        <form>
            <h2>Benvenuto <?php echo $email;?></h2>
            <p>I tuoi dati sono salvati in sessione, per tornare al login eseguire il logOut</p>
            <a style="color: blue;" href="http://carminati132.altervista.org/Login/API/users/logOut.php">logOut dal sito</a>
        </form>   
    </div>   
</body>
</html>