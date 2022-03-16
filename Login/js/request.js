function register(){

    var username = document.getElementById("username").value;
    var email = document.getElementById("email").value;
    var password = document.getElementById("password").value;

    var myHeaders = new Headers();
    myHeaders.append("Content-Type", "application/json");

    var raw = JSON.stringify({
        "username": username,
        "email": email,
        "password": password
    });

    var requestOptions = {
    method: 'PUT',
    headers: myHeaders,
    body: raw,
    redirect: 'follow'
    };

    fetch("http://carminati132.altervista.org/Login/API/users/users.php", requestOptions)
    .then(response => response.text())
    .then(result => {
            console.log(result);
            arr = JSON.parse(result);
            if (arr.status) {
                console.log(arr.response);
                window.location.replace("API/users/privateArea.php");
            }else{
                alert("Email gia esistente. Effettua il login per accedere");
            }
        }
        )
}
function login() 
{
    var email = document.getElementById("Lemail").value;
    var password = document.getElementById("Lpassword").value;
    var rememberMe = document.getElementById("rememberMe").checked;
    if (check_passwords() == true){
        var myHeaders = new Headers();
        myHeaders.append("Content-Type", "application/x-www-form-urlencoded");

        var urlencoded = new URLSearchParams();
        urlencoded.append("Lemail", email);
        urlencoded.append("Lpassword", password);
        urlencoded.append("rememberMe", rememberMe.toString());
        

        var requestOptions = {
        method: 'POST',
        headers: myHeaders,
        body: urlencoded,
        redirect: 'follow'
        };

        fetch("http://carminati132.altervista.org/Login/API/users/users.php", requestOptions)
        .then(response => response.text())
        .then(result => 
            {
                console.log(result);
                arr = JSON.parse(result);
                if (arr.status) {
                    console.log(arr.response);
                    window.location.replace("API/users/privateArea.php");
                }else{
                    alert("errore nell'inserimento delle credenziali")
                }
            })

    }else{
        alert("le password non sono uguali");
        document.getElementById("Lpassword").value = "";
        document.getElementById("Lconf_password").value = "";
    }
}
function changePassword() {
    var email = document.getElementById("email").value;
    var myHeaders = new Headers();

    var requestOptions = {
    method: 'GET',
    headers: myHeaders,
    redirect: 'follow'
    };

    fetch("http://carminati132.altervista.org/Login/API/users/sendEmail.php?email="+email, requestOptions)
    .then(response => response.text())
    .then(result => {
        console.log(result);
        arr = JSON.parse(result); 
        if (arr.status) {
            console.log(arr.response);
            window.location.replace("changedPassword.php");
        }else{
            alert("L'utente non è nel database");
        }

    })
    .catch(error => console.log('error', error));
}
function check_passwords() {
    var password = document.getElementById("Lpassword").value;
    var conf_password = document.getElementById("Lconf_password").value;
    if (password == conf_password) {
        return true
    }else{
        return false
    }
}