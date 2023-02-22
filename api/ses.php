<?php
//session_start();

include '_user.php';
if (!file_exists($_SERVER['DOCUMENT_ROOT'] . $_SERVER['REQUEST_URI'])) {
    // Set the HTTP response status code to 404 (Not Found)
    http_response_code(404);
    // Redirect to the error page
    header('Location: /404.php');
    exit();
}


//print_r($_SERVER["REQUEST_METHOD"]);
$path = pathinfo(basename($_SERVER['PHP_SELF']), PATHINFO_FILENAME); 

if(isset($_SESSION["login"])){
    if ($path == 'home'){
        
        if(!isset($_SESSION['user']) ){
            header('Location: /login.php');
            exit();
        }
        if($_SESSION["auth"] == '1'){
            //echo 'user';
            header('Location: /user.php');
            exit();
        }     
    }
    if ($path == 'avr'){
        
        if(!isset($_SESSION['user']) ){
            header('Location: /login.php');
            exit();
        }
        if($_SESSION["auth"] == '1'){
            //echo 'user';
            header('Location: /user.php');
            exit();
        }     
    }
    if($path == 'login') {
        header('Location: /');
        exit(); 
       
    }
    if($path =='user'){
        if(!isset($_SESSION['user']) ){
            header('Location: /login.php');
            exit();
        }

    }

   
}else{        
    if($path == 'login'){
        if ($_SERVER["REQUEST_METHOD"] == 'POST'){
            $uname = $_POST["uname"];
            $pass = $_POST["psw"];
           
            $stmt = $db->prepare('SELECT * FROM users WHERE email = :email and password = :password;');
            $stmt->bindParam(':email', $uname);
            $stmt->bindParam(':password', $pass);
            $stmt->execute();
            $result = $stmt->fetchAll();
    
            $rescount = count($result);
            if ($rescount == 0){
                $worng = '<div class="alert"><span class="closebtn" onclick="this.parentElement.style.display="none";">&times;</span>Login Failed Wrong Password or Username!</div>';
    
            }else{
                
                $auth = $result[0]['auth'];
                $_SESSION["user"] = $result[0];
                $_SESSION["login"] = true;
              
                
                if($auth == 0){
                    //echo 'admin';
                    $_SESSION["auth"] = $auth;
                    $_SESSION["login"] == true;
                    header('Location: /home.php');
                    exit();
    
                    
                }else{
                    //echo 'user';
                    $_SESSION["auth"] = $auth;
                    $_SESSION["login"] == true;
                    header('Location: /user.php');
                    exit();
                }  
            }
        } 
    }
    if($path =='user'){
        if(!isset($_SESSION['user']) ){
            header('Location: /login.php');
            exit();
        }
    }
    if($path =='home' or $path == 'avr'){
        if(!isset($_SESSION['user']) ){
            header('Location: /login.php');
            exit();
        }
    }
}






