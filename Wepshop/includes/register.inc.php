<?php
echo "123";


if (isset($_POST['reg-submit'] )){              //tjekker om de er kommet til siden ved TILMELD knappen
    
    require 'dbhusers.inc.php';                      //Database adgang


    $fornavn            = $_POST['navn'];
    $firmanavn          = $_POST['Firmanavn'];
    $cvr                = $_POST['cvr'];
    $password           = $_POST['PSW'];
    $passwordrepeat     = $_POST['PSW'];
    $email              = $_POST['email'];
    $telefon            = $_POST['tlf'];
    $town               = $_POST['by'];
    $street             = $_POST['gade'];


    echo $fornavn;
    echo "<br>";
    echo $firmanavn;
    echo "<br>";
    echo $cvr;
    echo "<br>";
    echo $email;
    echo "<br>";
    echo $password;
    echo "<br>";
    echo $telefon;
    echo "<br>";
    echo $town;
    echo "<br>";
    echo $street;


    //Herunder tjekker den indputs for fejl


    //Tjekker felterne for tomme
    if (empty($fornavn) || empty($firmanavn) || empty($password) || empty($passwordrepeat) || empty($email) || empty($telefon) ){
       header("Location: ../register.php?error=emptyfields&fusr=".$fornavn."&email=".$email); //sender dem tilbage hvis felt er tomt og skriver kendte værdier i url
       exit(); //stopper kode hvis de har glemt et felt
    }


    //tjekker om det er en real email og brugernavn
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) && !preg_match("/^[a-åA-Å]*$/", $fornavn)){
        header("Location: ../register.php?error=name&mailfailure"); 
        exit();
    }

    //tjekker om det er en real email ,burde htmlformen klare men dobbelttjek
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)){
        header("Location: ../register.php?error=invalidmail&fusr=".$fornavn."&email=".$email); 
        exit();    
    }

    //tjekker om kodeordene matcher
    elseif($password !== $passwordrepeat){
        header("Location: ../register.php?error=pswmatch&fusr=".$fornavn."&email=".$email); 
        exit();
    }

    else{

        $sql  = "SELECT user_id FROM users WHERE  user_contactname=?";
        $stmt = mysqli_stmt_init($conn);

        if(!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../register.php?error=sqlerror"); 
        exit();    
        }
    
        else{
     
           
            $sql = "INSERT INTO users (user_email, user_tlf, user_town, user_street, user_centername, user_contactname, user_cvr, user_psw) VALUES (?,?,?,?,?,?,?,?)";
            $stmt = mysqli_stmt_init($conn);

                if(!mysqli_stmt_prepare($stmt, $sql)) {
                    header("Location: ../adduser.php?error=sqlerror"); 
                    exit();
                }
                
                else{
                $hashedpassword = password_hash($password, PASSWORD_DEFAULT); //KRYPTERE Kodeord
                mysqli_stmt_bind_param($stmt, "ssssssss", $email, $telefon, $town, $street, $firmanavn, $fornavn, $cvr, $hashedpassword);
                mysqli_stmt_execute($stmt);


                header("Location: ../index.html?signup=succes"); 
                exit();
                }
        


    }
}
mysqli_stmt_close($stmt);
mysqli_close($conn);
}

else{
    header("Location: ../adduser.php?error"); 
    exit();
}


?>
