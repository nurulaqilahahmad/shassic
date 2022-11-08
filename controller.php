<?php
session_start();
include('includes/config.php');

$email = "";
$name = "";
$errors = array();

use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\SMTP;
// use PHPMailer\PHPMailer\Exception; 

require_once 'vendor/autoload.php';
require_once 'phpmailer/src/Exception.php';
require_once 'phpmailer/src/PHPMailer.php';
require_once 'phpmailer/src/SMTP.php';

if (isset($_COOKIE['email']) && isset($_COOKIE['password'])) {
    $email = $_COOKIE['email'];
    $password = $_COOKIE['password'];
} else {
    $email = "";
    $password = "";
}

//if user click login button on login page
if (isset($_POST['login'])) {
   
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM user WHERE email = ?";
    $query = $dbh->prepare($sql);
    $query->bindParam(1, $email);
    $query->execute();

    $result = $query->fetch();
    //$result=mysqli_query($con, $sql);
    if ($result) {
        if ($query->rowCount() > 0) {
            //if(mysqli_num_rows($result)==1){
            $result_fetch = mysqli_fetch_assoc($result);
            if (password_verify($password, $result['password'])) {
                $_SESSION['login'] = $_POST['email'];
                $_SESSION['email'] = $result_fetch['email'];
                if (isset($_POST['remember_me'])) {
                    setcookie('email', $_POST['email'], time() + (60 * 60 * 24));
                    setcookie('password', $_POST['password'], time() + (60 * 60 * 24));
                } else {
                    setcookie('email', '', time() - (60 * 60 * 24));
                    setcookie('password', '', time() - (60 * 60 * 24));
                }
                echo "<script type='text/javascript'> document.location = 'index.php'; </script>";
            } else {
                // echo "<script>alert('Incorrect Email Address or Password');</script>";
                $errors['login-error'] = "ERROR: Incorrect details!";
            }
        }
    }
}

// if user click register button in register form
if (isset($_POST['register'])) {    
    //getting the post values
    $fullname = $_POST['fullname'];
    $username = $_POST['username'];
    $code = $_POST['code'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $hash = password_hash($password, PASSWORD_DEFAULT);

    //Query for data insertion
    $sql = "INSERT INTO user(username, email, password, code, fullname) VALUES(:username, :email, :password, :code, :fullname)";

    $query = $dbh->prepare($sql);
    $query->bindParam(':username', $username, PDO::PARAM_STR);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->bindParam(':password', $hash, PDO::PARAM_STR);
    $query->bindParam(':code', $code, PDO::PARAM_STR);
    $query->bindParam(':fullname', $fullname, PDO::PARAM_STR);
    $query->execute();

    $lastInsertId = $dbh->lastInsertId();
    if ($lastInsertId) {
        $_SESSION['info'] = "You have successfully signed up";
        header('location: login.php');
        // echo "<script>alert('You have successfully signed up');</script>";
        // echo "<script type='text/javascript'> document.location ='login.php'; </script>";
    } else {
        $errors['db-error'] = 'Something Went Wrong. Please try again';
        // echo "<script>alert('Something Went Wrong. Please try again');</script>";
    }
}

// if user click check email button in forgot password form
if (isset($_POST['check-email'])) {
    // getting the post values
    $email = $_POST['email'];

    // query for data selection
    $sql = "SELECT * FROM user WHERE email=:email";
    $result = $dbh->prepare($sql);
    $result->bindParam(':email', $email, PDO::PARAM_STR);
    $result->execute();

    if ($result->rowCount() > 0) {
        $password_code = mt_rand(100000, 999999);
        $insert_pwcode = $dbh->prepare("UPDATE user SET password_code=? WHERE email=?");
        $insert_pwcode->execute([$password_code, $email]);

        if ($insert_pwcode->rowCount() > 0) {
            $subject = "SHASSIC | RESET PASSWORD REQUEST";
            $message = 
            "<h1 class='h4 text-gray-900 mb-4' style='font-weight: bold;'>SHASSIC</h1>
            <p style='text-align:center; font-family:'Poppins', sans-serif;'>You have requested to change password.<br> The code for reset password is <b>$password_code</b></p>
            <p style='font-size:smaller; text-align:center; font-family:'Poppins', sans-serif;'>IMPORTANT: Do not reply to this email</p>";
            try{
                $mail = new PHPMailer(true);

                $mail->isSMTP();
                $mail->SMTPAuth = true;
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    
                $mail->Host = 'smtp.gmail.com';
                $mail->Port = 587;
    
                $mail->Username = 'nrlaqilahahmd@gmail.com';
                $mail->Password = 'lakykidmxxwegolu';
    
                $mail->setFrom('nrlaqilahahmd@gmail.com');
                $mail->addAddress($email);
    
                $mail->isHTML(true);
    
                $mail->Subject = $subject;
                $mail->Body = $message;
    
                $mail->send();
    
                $_SESSION['info'] = "Password code has been sent to your email!";
                $_SESSION['email'] = $email;
                header('location: forgot-password-code.php');
            }
            catch (Exception $e){
                $errors['otp-error'] = $e;
            }
        }
    }
    else {
        $errors['email'] = "ERROR: Email address does not exist!";
    }
}

// if user click check password code button in forgot password code form
if (isset($_POST['check-pwcode'])) {
    // getting post values
    $_SESSION['info'] = "";
    $password_code = $_POST['password_code'];

    // query for data selection
    $sql = "SELECT * FROM user WHERE password_code=?";
    $query = $dbh->prepare($sql);
    $query->bindParam(1, $password_code);
    $query->execute();
    // $pwcode = $query->fetchAll(PDO::FETCH_OBJ);
    // $query->execute([$password_code]);

    if ($query->rowCount() > 0) {
        $result = $query->fetch();
        $email = $result['email'];
        $_SESSION['email'] = $email;
        $_SESSION['info'] = "Please create a new password that are not the same as before";
        header('location: forgot-password-new.php');
        exit();
    } else {
        $errors['pwcode-error'] = "ERROR: Incorrect code!";
    }
}

// if user click change button in forgot password new form
if (isset($_POST['change-password'])) {
    // getting post values
    $_SESSION['info'] = "";
    $password = $_POST['password'];
    $hash = password_hash($password, PASSWORD_DEFAULT);

    $code = 0;
    $email = $_SESSION['email']; // getting this email using session

    //Query for data update
    $sql = "UPDATE user SET password=?, password_code=? WHERE email=?";

    $query = $dbh->prepare($sql);
    // $query->bindParam(':password', $hash, PDO::PARAM_STR);
    // $query->execute();

    $query->execute([$hash, $code, $email]);

    if ($query) {
        $_SESSION['info'] = "Your password has been changed. You can now login with your new password.";
        header('location: forgot-password-changed.php');
    } else {
        $errors['db-error'] = "Something went wrong!";
    }
}

// if user click login now button in forgot password changed page
if (isset($_POST['login-now'])) {
    header('Location: login.php');
}

// if user click save button in add new assessment page
if (isset($_POST['add'])) {
    header('location: index.php');
}