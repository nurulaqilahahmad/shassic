<?php
session_start();
include('includes/config.php');

$email = "";
$name = "";
$infos = array();
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
        $_SESSION['info'] = "You have successfully signed up. ";
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
            try {
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
            } catch (Exception $e) {
                $errors['otp-error'] = $e;
            }
        }
    } else {
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

// if user click add button in add new assessment page
if (isset($_POST['add'])) {
    //getting the post values
    $assessor_id = $_POST['assessor_id'];
    $assessor_name = $_POST['assessor_name'];
    $assessee_name = $_POST['assessee_name'];
    $project_name = $_POST['project_name'];
    $project_date = $_POST['project_date'];
    $project_location = $_POST['project_location'];
    $project_picture = $_FILES['project_picture']['name'];

    // get the image extension
    $extension = substr($project_picture, strlen($project_picture) - 4, strlen($project_picture));
    // allowed extensions
    $allowed_extensions = array(".jpg", "jpeg", ".png", ".gif");
    // Validation for allowed extensions .in_array() function searches an array for a specific value.
    if (!in_array($extension, $allowed_extensions)) {
        echo "<script>alert('Invalid format. Only jpg / jpeg/ png /gif format allowed');</script>";
    } else {
        //rename the image file
        $imgnewfile = md5($imgfile) . time() . $extension;
        // Code for move image into directory
        move_uploaded_file($_FILES["project_picture"]["tmp_name"], "img/project-image/" . $imgnewfile);

        //Query for data insertion
        $sql = "INSERT INTO assessment(assessor_id, assessor_name, assessee_name, project_name, project_date, project_location, project_picture) VALUES 
                    (:assessor_id, :assessor_name, :assessee_name, :project_name, :project_date, :project_location, :imgnewfile)";

        $query = $dbh->prepare($sql);
        $query->bindParam(':assessor_id', $assessor_id, PDO::PARAM_STR);
        $query->bindParam(':assessor_name', $assessor_name, PDO::PARAM_STR);
        $query->bindParam(':assessee_name', $assessee_name, PDO::PARAM_STR);
        $query->bindParam(':project_name', $project_name, PDO::PARAM_STR);
        $query->bindParam(':project_date', $project_date, PDO::PARAM_STR);
        $query->bindParam(':project_location', $project_location, PDO::PARAM_STR);
        $query->bindParam(':imgnewfile', $imgnewfile, PDO::PARAM_STR);
        $query->execute();

        $lastInsertId = $dbh->lastInsertId();
        if ($lastInsertId) {
            $assessee_id = $lastInsertId;
            $con = "INSERT INTO workplace_inspection_subscore(assessment_id) VALUES(:assessee_id)";
            $insert_subscore = $dbh->prepare($con);
            $insert_subscore->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
            $insert_subscore->execute();
            $_SESSION['info'] = "You have successfully added a new assessment";
            header("location: assessment-component.php?assessee_id=" . $assessee_id);
        }
    }
}

// if user click update button in edit assessment page
if (isset($_POST['update'])) {
    //getting the post values
    $assessor_id = $_POST['assessor_id'];
    $assessor_name = $_POST['assessor_name'];
    $assessee_id = $_POST['assessee_id'];
    $assessee_name = $_POST['assessee_name'];
    $project_name = $_POST['project_name'];
    $project_date = $_POST['project_date'];
    $project_location = $_POST['project_location'];
    $project_picture = $_POST['project_image'];

    // query for data selection
    $sql = "SELECT * FROM assessment WHERE assessee_id=:assessee_id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_OBJ);

    if ($query->rowCount() > 0) {
        //query for updation
        $con = "UPDATE assessment SET assessor_id=:assessor_id, assessor_name=:assessor_name, assessee_name=:assessee_name, project_name=:project_name, project_date=:project_date, project_location=:project_location, project_picture=:project_picture WHERE assessee_id=:assessee_id";
        $update = $dbh->prepare($con);
        $update->bindParam(':assessor_id', $assessor_id, PDO::PARAM_STR);
        $update->bindParam(':assessor_name', $assessor_name, PDO::PARAM_STR);
        $update->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
        $update->bindParam(':assessee_name', $assessee_name, PDO::PARAM_STR);
        $update->bindParam(':project_name', $project_name, PDO::PARAM_STR);
        $update->bindParam(':project_date', $project_date, PDO::PARAM_STR);
        $update->bindParam(':project_location', $project_location, PDO::PARAM_STR);
        $update->bindParam(':project_picture', $project_picture, PDO::PARAM_STR);
        $update->execute();

        $_SESSION['info'] = "Updated successfully";
        header("location: assessment-component.php?assessee_id=" . $assessee_id);
    }
}

// if user click update-from-history button in edit assessment page
if (isset($_POST['update-from-history'])) {
    //getting the post values
    $assessor_id = $_POST['assessor_id'];
    $assessor_name = $_POST['assessor_name'];
    $assessee_id = $_POST['assessee_id'];
    $assessee_name = $_POST['assessee_name'];
    $project_name = $_POST['project_name'];
    $project_date = $_POST['project_date'];
    $project_location = $_POST['project_location'];
    $project_picture = $_POST['project_picture'];

    // query for data selection
    $sql = "SELECT * FROM assessment WHERE assessee_id=:assessee_id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_OBJ);

    if ($query->rowCount() > 0) {
        //query for updation
        $con = "UPDATE assessment SET assessor_id=:assessor_id, assessor_name=:assessor_name, assessee_name=:assessee_name, project_name=:project_name, project_date=:project_date, project_location=:project_location, project_picture=:project_picture WHERE assessee_id=:assessee_id";
        $update = $dbh->prepare($con);
        $update->bindParam(':assessor_id', $assessor_id, PDO::PARAM_STR);
        $update->bindParam(':assessor_name', $assessor_name, PDO::PARAM_STR);
        $update->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
        $update->bindParam(':assessee_name', $assessee_name, PDO::PARAM_STR);
        $update->bindParam(':project_name', $project_name, PDO::PARAM_STR);
        $update->bindParam(':project_date', $project_date, PDO::PARAM_STR);
        $update->bindParam(':project_location', $project_location, PDO::PARAM_STR);
        $update->bindParam(':project_picture', $project_picture, PDO::PARAM_STR);
        $update->execute();

        $_SESSION['info'] = "Updated successfully";
        header("location: assessment-component.php?assessee_id=" . $assessee_id);
    }
}

//if user click save-document-check button in assessment document check page
if (isset($_POST['save-document-check'])) {
    //getting the post value
    $assessee_id = $_POST['assessee_id'];
    $document_check_percentage = $_POST['document_check_percentage'];

    // query for data selection
    $sql = "SELECT * FROM assessment WHERE assessee_id=:assessee_id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_OBJ);

    if ($query->rowCount() > 0) {
        //query for updation
        $con = "UPDATE assessment SET assessee_id=:assessee_id, document_check_percentage=:document_check_percentage WHERE assessee_id=:assessee_id";
        $update = $dbh->prepare($con);
        $update->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
        $update->bindParam(':document_check_percentage', $document_check_percentage, PDO::PARAM_STR);
        $update->execute();

        $_SESSION['info'] = "Updated successfully";
        header("location: assessment-component.php?assessee_id=" . $assessee_id);
    }
}

//if user click save-workplace-inspection-high-risk button in assessment workplace inspection page
if (isset($_POST['save-workplace-inspection-high-risk'])) {
    //getting the post values
    $assessee_id = $_POST['assessee_id'];
    $high_risk_score = $_POST['high_risk_score'];

    // query for data selection
    $sql = "SELECT * FROM workplace_inspection_subscore WHERE assessment_id=:assessee_id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);

    if ($query->rowCount() > 0) {
        foreach ($results as $result) {
            //query for updation
            $con = "UPDATE workplace_inspection_subscore SET high_risk_score=:high_risk_score WHERE assessment_id=:assessee_id";
            $update = $dbh->prepare($con);
            $update->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
            $update->bindParam(':high_risk_score', $high_risk_score, PDO::PARAM_STR);
            $update->execute();

            if ($update) {
                //query for data selection
                $conn = "SELECT * FROM assessment WHERE assessee_id=:assessee_id";
                $query1 = $dbh->prepare($conn);
                $query1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                $query1->execute();
                $result1 = $query1->fetchAll(PDO::FETCH_OBJ);

                if ($query1->rowCount() > 0) {
                    $conn1 = "UPDATE assessment SET workplace_inspection_percentage=:high_risk_score+'$result->general_score' WHERE assessee_id=:assessee_id";
                    $update1 = $dbh->prepare($conn1);
                    $update1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                    $update1->bindParam(':high_risk_score', $high_risk_score, PDO::PARAM_STR);
                    $update1->execute();
                }
            }
        }


        $infos['high-risk-update-success'] = "Updated successfully";
        // header("location: assessment-workplace-inspection.php?assessee_id=" . $assessee_id . "&info=" . $info);
    }
}

//the workplace inspection total score is changed every time the sub score is updated

