<?php
	session_start();
	if ( isset($_SESSION["email"]) ) {
		header("Location: API/users/privateArea.php");
	}else {
        require_once './googleOauth/google/vendor/autoload.php';
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
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/login.css">
    <script src="js/request.js"></script>
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
<div class="container" id="container">
	<div class="form-container sign-up-container">
		<form onsubmit="register();return false;" id="registration">
			<h1>Crea Account</h1><br>
			<input type="text" placeholder="username" name="username" id="username" required/>
			<input type="email" placeholder="email" name="email" id="email" required/>
			<input type="password" placeholder="Password" name="password" id="password" required/>
			<input class="btn" type="submit" value="Sign Up">
		</form>
	</div>
	<div class="form-container sign-in-container">
		<form onsubmit="login();return false;" id="login">
			<h1>Sign in</h1><br>
			<input type="email" placeholder="Email" name="Lemail" id="Lemail" required/>
			<input type="password" placeholder="Password" name="Lpassword" id="Lpassword" required/>
			<input type="password" placeholder="Confirm password" name="conf_password" id="Lconf_password" required>            
                <input type="checkbox" id="rememberMe" name="rememberMe" checked>
                <label for="rememberMe">Ricordami</label>
            <a href="changePassword.php" style="color: blue;">Password dimenticata?</a>
            <input class="btn" type="submit" value="Sign In">
            <?php 
                if (isset($_GET['code'])) {
                    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
                    $client->setAccessToken($token['access_token']);
                     
                    // get profile info
                    $google_oauth = new Google_Service_Oauth2($client);
                    $google_account_info = $google_oauth->userinfo->get();
                    $_SESSION["email"] =  $google_account_info->email;
                    // $name =  $google_account_info->name;
                    
                    // now you can use this profile info to create account in your website and make user logged in.
                  } else {
                    // echo $client->createAuthUrl();
                    echo "<a class='btn' 
                    style='background-color: white; color: #2bb1ff;'                    
                    href='".$client->createAuthUrl()."'>Google Login</a>";
                  }
            ?>
		</form>
	</div>
	<div class="overlay-container">
		<div class="overlay">
			<div class="overlay-panel overlay-left">
				<h1>Sei già registrato?</h1>
				<p>Inserisci le credenziali e recupera il tuo account</p>
				<button class="btn" id="signIn">Sign In</button>
				
			</div>
			<div class="overlay-panel overlay-right">
				<h1>Registrati!</h1>
				<p>Crea il tuo account </p>
				<button class="btn" id="signUp">Sign Up</button>
			</div>
		</div>
	</div>
</div>
<script src="js/login.js"></script>
</body>
</html>