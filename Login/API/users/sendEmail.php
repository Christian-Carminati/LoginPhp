<?php
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    require_once "../config.php";
    if (isset($_GET["email"])) 
    {
        $email = $_GET["email"];
        if (UserInDB($conn,$email)) { 
            $new_password = generateRandomPassword(15);
            sendEmail($email,$new_password);
            changePasswordInDb($conn,$email,$new_password);
            header("Content-Type: application/json");
            http_response_code(200);
            response("An email is sended to your address", true, "ok");
        }else {
            header("Content-Type: application/json");
            http_response_code(400);
            response("User is not in the database", false, "");
        }    
    }else {
        header("Content-Type: application/json");
        http_response_code(400);
        response("Invalid input data", false, "");
    }
}else {
    header("Content-Type: application/json");
    http_response_code(400);
    response("Method not allow", false, "Method allowed: GET");
}
function UserInDb($conn,$email)
{
    $stmt = $conn->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows === 0){
        return false;
    }else{
        return true;
    }
}
function sendEmail($email,$new_password)
{
    $to = $email;
    $subject = 'Recupero password login';
    $message = "La tua nuova password per accedere al sistema di login è : \n".$new_password;
    $headers = array(
        'From' => 'LoginAssistant@gmail.com',
        'Reply-To' => 'LoginAssistant@gmail.com',
        'X-Mailer' => 'PHP/' . phpversion()
    );

    mail($to, $subject, $message, $headers);
}
function generateRandomPassword($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
function changePasswordInDb($conn,$email,$password)
{
    $stmt = $conn->prepare('UPDATE users SET password=? WHERE email=?');
    $stmt->bind_param('ss', hash("sha256",$password),$email);
    $stmt->execute();
}
function response($message, $status, $response){
    echo json_encode(array("message" => $message, "status" => $status, "response" => $response));
    exit();
}
?>