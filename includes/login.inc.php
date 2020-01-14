<?php 
if (isset($_POST['login-submit'] )){

    require 'dbhusers.inc.php';                      //Database adgang


    $mailusr    = $_POST['USR'];                //Henter data fra loginside
    $password   = $_POST['PSW'];



    //Tjekker felterne for tomme
    if (empty($mailusr) || empty($password)){
    header("Location: ../login.php?error=emptyfields"); 
    exit(); //stopper kode hvis de har glemt et felt
    }


    else{
        $sql = "SELECT * from users WHERE user_email=?  AND `user_state` = 1;";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $sql)){
        header("Location: ../login.php?error=sqlerror"); 
        exit(); //tjekker sql forbinelse
     }
        else{
            mysqli_stmt_bind_param($stmt, s, $mailusr);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            if($row = mysqli_fetch_assoc($result)) {
            
            $pwdcheck = password_verify($password, $row['user_psw']);

                if($pwdcheck == false){
                    header("Location: ../login.php?error=wrongpwd"); 
                    exit(); 

                }
                elseif($pwdcheck == true){
                    session_start();

                    $_SESSION['userid']       = $row['user_id'];

                    header("Location: ../index.html"); 
                    exit(); 

                }
                
     else{
        header("Location: ../login.php?error=nouser"); 
        exit();    
     }


            }
            else{
                header("Location: ../login.php?error=nouser"); 
                exit();    
            }
        }
    }
}


else{
    header("Location: ../login.php?error"); 
    exit();
}