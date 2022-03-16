<?php
session_start();
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization");
require_once "../config.php";
if($_SERVER["REQUEST_METHOD"] == "POST"){//login   
    header("Access-Control-Allow-Methods: POST");
    $data = json_decode(file_get_contents("php://input"), true);
    if($data!=null){
        $lMail = $data["email"];
        $lPsw = $data["password"];
        $rememberMe = $data["rememberMe"];
    }
    if(empty($lMail) || empty($lPsw) || empty($rememberMe)){
        $lMail = $_POST["Lemail"];
        $lPsw = $_POST["Lpassword"];
        $rememberMe = $_POST["rememberMe"];
        if(empty($lMail) || empty($lPsw)|| empty($rememberMe)){
            http_response_code(400);
            response("Error sending data", false, "");
        }
        elseif (check_password($conn,$lMail,$lPsw)) {
            if ($rememberMe == "true") {
                $_SESSION["email"] = $lMail;
                http_response_code(200);
                response("Login with remember",true,"ok");
            }else {
                http_response_code(200);
                response("Login succeful",true,"ok");
            }
           
        }else {
            http_response_code(400);
            response("Credential error",false,"");
        }
    }
}
elseif($_SERVER["REQUEST_METHOD"] == "PUT"){  //Registration
    header("Access-Control-Allow-Methods: PUT");
    $data = json_decode(file_get_contents("php://input"), true);
    if($data!=null){
        $rUsername = $data["username"];
        $rMail = $data["email"];
        $rPsw = $data["password"];
    }else{
        $rUsername = $_PUT["username"];
        $rMail = $_PUT["email"];
        $rPsw = $_PUT["password"];
    }
    if(empty($rUsername) || empty($rMail) || empty($rPsw)){
        http_response_code(400);
        response("Error sending data", false, "Empty values");
    }
    if(check_not_exist($conn, $rMail)){    //true allora l'utente non esiste allora puó registrarsi
        $stmt = $conn->prepare("INSERT INTO users (username,email,password) VALUES(?,?,?)");
        $stmt->bind_param('sss', $rUsername, $rMail, hash("sha256",$rPsw));
        $stmt->execute();
        $result = $stmt->get_result();
        $_SESSION["email"] = $rMail;
        http_response_code(200);
        response("User created",true,"ok");
    }else{  //false allora l'utente giá esiste
        http_response_code(400);
        response("User already exist", false, "");
    }
}
elseif($_SERVER["REQUEST_METHOD"] == "DELETE"){  //Delete
    header("Access-Control-Allow-Methods: DELETE");
    response("No user can be deleted", false, "");
}
function check_password($conn, $rMail,$rPsw){
    $stmt = $conn->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->bind_param('s', $rMail);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = mysqli_fetch_array($result);
    if ($row["password"] == hash("sha256",$rPsw)) {
        return true;
    }else {
        return false;
    }
}
function check_not_exist($conn, $rMail){
    $stmt = $conn->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->bind_param('s', $rMail);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows === 0){
        return true;
    }else{
        return false;
    }
}

function response($message, $status, $response,$data=""){
    echo json_encode(array("message" => $message, "status" => $status, "response" => $response,"data" => $data));
    exit();
}
?>