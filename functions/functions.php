<?php


    //clean strng values
    function clean($string)
    {
        return htmlentities($string);
    }
    
    //Redirection
    function redirect($location)
    {
        return header("location:{$location}");
    }
    
    //set session message
    function set_message($msg)
    {
        if(!empty($msg))
        {
            $_SESSION['Message']=$msg;
        }
        else
        {
            $msg="";
        }
    }

    //display message function
    function display_message(){
        if(isset($_SESSION['Message']))
        {
            echo $_SESSION['Message'];
            unset($_SESSION['Message']);
        }
            
    }
    
    //generate token
    function Token_Generator()
    {
        $token=$_SESSION['token']=md5(uniqid(mt_rand(), true));
        return $token;
    }

    //send email function
    function send_email($email, $sub, $msg, $header){
        return mail($email, $sub, $msg, $header);
    }

    //errors function
    function Error_validation($Error){
        return '<div class="alert alert-danger">'.$Error.'</div>';
    }


    //   user valitaion function
    function user_validation(){
        if($_SERVER['REQUEST_METHOD']=='POST'){

            //echo "working fine";

            $FirstName=clean($_POST['FirstName']);
            $LastName=clean($_POST['LastName']);
            $UsernameU=clean($_POST['UserName']);
            $Email=clean($_POST['Email']);
            $Pass=clean($_POST['pass']);
            $CPass=clean($_POST['cpass']);

            //echo $FirstName, $LastName, $Username, $Email, $Pass, $CPass; 

            $Errors = [];
            $Max = 20;
            $Min = 3;

            
            if(strlen($FirstName) < $Min)
            {
                $Errors[] = "Name cannot be less than {$Min} characters.";
            }
            if(strlen($FirstName) > $Max)
            {
                $Errors[] = "Name cannot be more than {$Max} characters.";
            }

            if(strlen($LastName) < $Min){
                $Errors[]="Surname cannot be less than {$Min} characters.";
            } 
            if(strlen($LastName)>$Max){
                $Errors[]="Surname cannot be more than {$Max} characters.";
            }

            if(!preg_match("/^[a-zA-Z, 0-9]*$/", $UsernameU)){
                $Errors[]="Username cannot be accept those characters.";
            }
                
            if(Email_Exists($Email)){
                $Errors[]="Email already registered.";
            }

            if(User_Exists($UsernameU)){
                $Errors[]="Username already registered.";
            }

            if($Pass!=$CPass){
                $Errors[]="Password not matched!";
            }

            if(!empty($Errors)){
                foreach($Errors as $Error){
                    echo Error_validation($Error);
                }
            }
            else {
                if(user_registration($FirstName, $LastName, $UsernameU, $Email, $Pass)){
                    
                    set_message('<p class="bg-success text-center lead">You have succescfully registered please check your activation link!</p>');
                    redirect("index.php");
                }
                else{
                    set_message('<p class="bg-danger text-center lead"> Your account not registered, please try again! </p>');
                    redirect("index.php");
                }
            }
        }
    }

    //email exist
    function Email_Exists($email)
    {
        $sql="select * from users where Email='$email'";
        $result=Query($sql);
        if(fatech_data($result)){
            return true;
        }
        else{
            return false;
        }
    }

    //username exists
    function User_Exists($user)
    {
        $sql="select * from users where UserName='$user'";
        $result=Query($sql);
        if(fatech_data($result)){
            return true;
        }
        else{
            return false;
        }
    }

    //user reg,stration function
    function user_registration($Fname, $Lname, $Uname, $Email, $Pass){
        $FirstName=escape($Fname);
        $LastName=escape($Lname);
        $Username=escape($Uname);
        $Email=escape($Email);
        $Pass=escape($Pass);

        if(Email_Exists($Email)){
            return true;
        }
        else if(User_Exists($Username)){
            return true;
        }
        else{
            $Password=md5($Pass);
            $Validation_code=md5($Username.microtime());

            $sql="insert into users (FisrtName, LastName, UserName, Email, Password, Validation_Code,
            Active) values ('$FirstName', '$LastName', '$Username', '$Email', '$Password', '$Validation_code' , '0')";

            $result=Query($sql);
            confirm($result);
            $subject = "active your account";
            $msg = "please click the link below to active your acount http://localhost/digitusproject/activate.php?Email=$Email&Code=$Validation_code ";
            $header = "From no-reply bbausy@gmail.com";

            send_email($Email, $subject, $msg, $header);
            return true;
        }
    }

    // Activation func
    function activation(){
        if($_SERVER['REQUEST_METHOD']=="GET"){
            $Email=$_GET['Email'];
            $Code=$_GET['Code'];

            $sql= " select * from users where Email='$Email' AND Validation_Code='$Code'";
            $result=Query($sql);
            confirm($result);

            if(fatech_data($result)){
                $sqlquery="update users set Active='1', Validation_Code='0' where Email='$Email' AND Validation_Code='$Code' ";
                $result2=Query($sqlquery);
                confirm($result2); 
                set_message('<p class="bg-success text-center lead"> Your account succesfully activated. </p>');
                redirect('login.php');
            }
            else {
                echo '<p class="bg-danger text-center lead"> Your account not activated. </p>';
            }
        }
    }

    //user login validation
    function login_validation(){

        $Errors=[];

        if($_SERVER['REQUEST_METHOD']=='POST'){
            $UserEmail=clean($_POST['Uemail']);
            $UserPass=clean($_POST['Upass']);
            $Remember=isset($_POST['remember']);
            
            if(empty($UserEmail))
            {
                $Errors[]="Please enter your email";

            }
            if(empty($UserPass))
            {
                $Errors[]="Please enter your password";
            }

            if(!empty($Errors))
            {
                foreach($Errors as $Error){
                    echo Error_validation($Error);                  
                }
            }
            else
            {
                if(user_login($UserEmail, $UserPass, $Remember)){
                    redirect('admin.php');
                }
                else{
                    echo Error_validation("Please enter correct eail and password!");
                }
            }
        }
    }

    // user login function
    function user_login($UEmail, $UPass, $Remember){
        $query="select * from users where Email='$UEmail' AND Active='1'";
        $result=Query($query);

        
        if($row=fatech_data($result))
        {
            $db_pass=$row['Password'];
            if(md5($UPass)==$db_pass)
            {
                if($Remember==true){
                    setcookie('email', $UEmail, time()+86400);
                }
                $_SESSION['EMail']=$UEmail;
                return true;
            }
            else{
                return false;
            }

        }
    }

    //logged in function

    function logged_in(){
        if(isset($_SESSION['Email'])||isset($_COOKIE['email'])){
            return true;
        }
        else{
            return false;
        }
        
    }


    // Recover function

    function recover_password(){
        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            if(isset($_SESSION['token']) && $_POST['token'] == $_SESSION['token']){
                
                $Email=$_POST['Uemail'];

                if(Email_Exists($Email)){
                    $code=md5($Email.microtime());
                    setcookie('temo_code', $code, time()+300);

                    $sql="update users set Validation_Code='$code' where Email='$Email'";
                    Query($sql);

                    $Subject= "Please reset the password";
                    $Message="Please follow on below link to reset the password http://localhost/DigitusProject/code.php?Email='$Email'&Code='$code";
                    $header="bbausy@gmail.com";

                    if(send_email($Email, $Subject, $Message, $header)){
                        echo '<div class="alert alert-success"> Please check your email : </div>';
                    }
                    else
                    {
                        echo Error_validation("We couldn't send an Email");
                    }


                 }
                else{
                    echo Error_validation("Email not found!");
                }
            }
            else
            {
                redirect("index.php");
            }
        }
    }


    // validation code function
    function validation_code(){
        if(isset($_COOKIE['temp_code'])){
           if(!isset($_GET['Email']) && !isset($_GET['Code'])){
               redirect("index.php");
           } 
           else if(empty($_GET['Email']) && empty($_GET['Code'])){
               redirect("index.php");
           }
           else{
               if(isset($_POST['recover-code'])){
                   $Code=$_POST['recover-code'];
                   $Email=$_GET['Email'];

                   $query="select * from users where Validation_Code='$Code' and Email='$Email' ";
                   $result=Query($query);

                   if(fatech_data($result)){
                       setcookie('temp_code', $Code, time()+300);
                       redirect("reset.php?Email=$Email&Code=$Code");
                   }
                   else{
                       echo Error_validation("Query failed.");
                   }
               }
           }
        }
        else{
            set_message('<div class="alert alert-danger">Your code has been expired!</div>');
            redirect("recover.php");
        }
    }


    // reset password func

    function reset_password()
    {
        if(isset($_COOKIE['temp_code']))
        {
            if(isset($_GET['Email']) && isset($_GET['Code']))
            {
                if(isset($_SESSION['token']) && isset($_POST['token']))
                {
                    if($_SESSION['token'] == $_POST['token'])
                    {
                        if($_POST['reset-pass'] === $_POST['reset-c-pass'])
                        {
                            $Password = md5($_POST['reset-pass']);
                            $query2 = "update users set Password='".$Password."', Validation_Code=0 where Email='".$_GET['Email']."'";
                            $result = Query($query2);
                            if($result)
                            {
                                set_message('<div class="alert alert-success"> Your Password Has Been Updated </dvi>');
                                redirect("login.php");
                            }
                            else
                            {
                                set_message('<div class="alert alert-danger"> Something Went Wrong </dvi>');
                            }
                        }
                        else
                        {
                            set_message('<div class="alert alert-danger"> Password Not Matched </dvi>');
                        }
                    }
                }
            }
            else
            {
                set_message('<div class="alert alert-danger> Your Code or Your Email Has Not Matched  </dvi>');
            }
        }
        else
        {
            set_message('<div class="alert alert-danger> Your Time Period Has Been Expired </dvi>');
        }
    }

?>