<?php
session_start();
include('includes/config.php');

$email = "";
$name = "";
$error = array();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

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
        echo "<script>alert('You have successfully signed up');</script>";
        echo "<script type='text/javascript'> document.location ='login.php'; </script>";
    } else {
        echo "<script>alert('Something Went Wrong. Please try again');</script>";
    }
}

// // if user click reset password button in forgot password form
// if (isset($_POST['check-email'])) {
//     // getting the post values
//     $email = $_POST['email'];

//     // query for data selection
//     $sql = "SELECT * FROM user WHERE email=:email";
//     $query = $dbh->prepare($sql);
//     $query->bindParam(':email', $email, PDO::PARAM_STR);
//     $query->execute();
//     $user = $query->fetch();

//     if ($query->rowCount() > 0) {
//         $password_code = rand(999999, 111111);
//         $run_query = "UPDATE user SET password_code='$password_code' WHERE email=:email";
//         $success = $dbh->prepare($run_query);
//         $success->execute();

//         if ($success->rowCount() > 0) {
//             $subject = "RESET PASSWORD CODE";
//             $message = "The code for reset password is $password_code";
//             $sender = "From: shassic@cidb.gov.my";
//             if (mail($email, $subject, $message, $sender)) {
//                 $info = "A reset password code has been sent to your email";
//                 $_SESSION['info'] = $info;
//                 $_SESSION['email'] = $email;
//                 header('location: forgot-password-code.php');
//                 exit();
//             } else {
//                 $error['pwcode-error'] = "Sending code FAILED!";
//             }
//         } else {
//             $error['db-error'] = "Something went wrong!";
//         }
//     } else {
//         $error['email'] = "This email address does not exist!";
//     }
// }

// if user click check email 2 button in forgot password form
if (isset($_POST['check-email2'])) {
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
        // $insert_pwcode->bindParam(':password_code', $password_code, PDO::PARAM_INT);
        $insert_pwcode->execute([$password_code, $email]);
        // $insert_pwcode->execute();
        // $row = $insert_pwcode->fetch();

        if ($insert_pwcode->rowCount() > 0) {
            $subject = "RESET PASSWORD CODE";
            $message = "The code for reset password is $password_code";
            $sender = "From: shassic@cidb.gov.my";

            // $mail = new PHPMailer(true);

            // $mail->isSMTP();
            // $mail->SMTPAuth = true;
            // $mail->SMTPSecure = 'ssl';
            // $mail->SMTPDebug = SMTP::DEBUG_SERVER;

            // $mail->Host = 'smtp.example.com';
            // $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            // $mail->Port = 587;

            // $mail->Username = 'you@example.com';
            // $mail->Password = 'password';

            // $mail->setFrom('shassic@cidb.gov.my');
            // $mail->addAddress($email);

            // $mail->isHTML(true);

            // $mail->Subject = $subject;
            // $mail->Body = $message;

            // $mail->send();

            $_SESSION['email'] = $email;
            header('location: forgot-password-code.php');
            // if (mail($email, $subject, $message, $sender)) {
            //     $info = "A reset password code has been sent to your email";
            //     $_SESSION['info'] = $info;
            //     $_SESSION['email'] = $email;
            //     header('location: forgot-password-code.php');
            //     exit();
            // } else {
            //     $error['pwcode-error'] = "Sending code FAILED!";
            // }
        }
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
        $info = "Please create a new password that you don't use on any other site.";
        $_SESSION['info'] = $info;
        header('location: forgot-password-new.php');
        exit();
    } else {
        $error['pwcode-error'] = "You've entered incorrect code!";
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
        $info = "Your password has been changed. You can now login with your new password.";
        $_SESSION['info'] = $info;
        header('location: forgot-password-changed.php');
    } else {
        $error['db-error'] = "Something went wrong!";
    }
}

// if user click login now button in forgot password changed page
if (isset($_POST['login-now'])) {
    header('Location: login.php');
}
