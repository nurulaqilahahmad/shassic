<?php
session_start();
include 'includes/config.php';
error_reporting(0);

$email = "";
$name = "";
$infos = array();
$errors = array();

use LDAP\Result;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

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

// //if user click login button on login page
// if (isset($_POST['login'])) {

//     $email = $_POST['email'];
//     $password = $_POST['password'];

//     $sql = "SELECT * FROM user WHERE email = ?";
//     $query = $dbh->prepare($sql);
//     $query->bindParam(1, $email);
//     $query->execute();

//     $result = $query->fetch();
//     //$result=mysqli_query($con, $sql);
//     if ($result) {
//         if ($query->rowCount() > 0) {
//             //if(mysqli_num_rows($result)==1){
//             // $result_fetch = mysqli_fetch_assoc($result);
//             if (password_verify($password, $result['password'])) {
//                 $_SESSION['login'] = $_POST['email'];
//                 // $_SESSION['email'] = $result_fetch['email'];
//                 // if (isset($_POST['remember_me'])) {
//                 //     setcookie('email', $_POST['email'], time() + (60 * 60 * 24));
//                 //     setcookie('password', $_POST['password'], time() + (60 * 60 * 24));
//                 // } else {
//                 //     setcookie('email', '', time() - (60 * 60 * 24));
//                 //     setcookie('password', '', time() - (60 * 60 * 24));
//                 // }
//                 echo "<script type='text/javascript'> document.location = 'index.php'; </script>";
//             } else {
//                 // echo "<script>alert('Incorrect Email Address or Password');</script>";
//                 $errors['login-error'] = "ERROR: Incorrect details!";
//             }
//         }
//     }
// }

// If user click login button on login page
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM user WHERE email=:email";
    $query = $dbh->prepare($sql);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetch();

    if ($query->rowCount() > 0) {
        if (password_verify($password, $results['password'])) {
            $_SESSION['login'] = $_POST['email'];
            echo "<script type='text/javascript'> document.location = 'landing.php'; </script>";
        } else {
            $errors['login-error'] = "ERROR: Incorrect details!";
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

    //validate password strength
    $uppercase = preg_match('@[A-Z]@', $password);
    $lowercase = preg_match('@[a-z]@', $password);
    $number    = preg_match('@[0-9]@', $password);
    $specialChars = preg_match('@[^\w]@', $password);

    if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
        $errors['weak-password'] = 'Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character';
    } else {
        $options = array("cost" => 4);
        $password = password_hash($password, PASSWORD_BCRYPT, $options);

        $picture = $_FILES['picture']['name'];

        // get the image extension
        $extension = substr($picture, strlen($picture) - 4, strlen($picture));
        // allowed extensions
        $allowed_extensions = array(".jpg", "jpeg", ".png", ".gif");
        // Validation for allowed extensions .in_array() function searches an array for a specific value.
        if (!in_array($extension, $allowed_extensions)) {
            echo "<script>alert('Invalid format. Only jpg / jpeg/ png /gif format allowed');</script>";
        } else {
            //rename the image file
            $profpicture = md5($picture) . time() . $extension;
            // Code for move image into directory
            move_uploaded_file($_FILES["picture"]["tmp_name"], "img/profile-picture/" . $profpicture);

            $regex = '/^[0-9]{6}-[0-9]{2}-[0-9]{4}$/';

            if (preg_match($regex, $code)) {
                //Query for data insertion
                $sql = "INSERT INTO user(username, email, password, code, fullname, picture) VALUES(:username, :email, :password, :code, :fullname, :profpicture)";

                $query = $dbh->prepare($sql);
                $query->bindParam(':username', $username, PDO::PARAM_STR);
                $query->bindParam(':email', $email, PDO::PARAM_STR);
                $query->bindParam(':password', $password, PDO::PARAM_STR);
                $query->bindParam(':code', $code, PDO::PARAM_STR);
                $query->bindParam(':fullname', $fullname, PDO::PARAM_STR);
                $query->bindParam(':profpicture', $profpicture, PDO::PARAM_STR);
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
            } else {
                $errors['db-error'] = 'IC Invalid';
            }
        }
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

        header('location: forgot-password-new.php');
        exit();
    } else {
        $errors['pwcode-error'] = "ERROR: Incorrect code!";
    }
}

// if user click change button in forgot password new form
if (isset($_POST['change-password'])) {
    // getting post values
    $password = $_POST['password'];
    $old_password = $_POST['old_password'];
    $hash = password_hash($password, PASSWORD_DEFAULT);

    //validate password strength
    $uppercase = preg_match('@[A-Z]@', $password);
    $lowercase = preg_match('@[a-z]@', $password);
    $number    = preg_match('@[0-9]@', $password);
    $specialChars = preg_match('@[^\w]@', $password);

    if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
        $errors['weak-password'] = 'Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character';
    } else {
        $code = 0;
        $email = $_SESSION['email']; // getting this email using session

        if (password_verify($password, $old_password)) {
            $errors['password-error'] = "New password cannot same with old password!";
        } else {
            //Query for data update
            $sql = "UPDATE user SET password=?, password_code=? WHERE email=?";
            $query = $dbh->prepare($sql);
            $query->execute([$hash, $code, $email]);

            if ($query) {
                header('location: forgot-password-changed.php');
            } else {
                $errors['db-error'] = "Something went wrong!";
            }
        }
    }
}

// if user click login now button in forgot password changed page
if (isset($_POST['login-now'])) {
    header('Location: login.php');
}

//If user click edit-profile button in index page
if (isset($_POST['update_profile'])) {
    //getting the post values
    $id = $_POST['id'];
    $username = $_POST['username'];
    $fullname = $_POST['fullname'];
    $old_picture = $_POST['old_picture'];

    if (isset($_FILES['picture']['name'])  && !empty($_FILES['picture']['name'])) {
        $image_name = $_FILES['picture']['name'];
        $image_tmp_name = $_FILES['picture']['tmp_name'];
        $image_error = $_FILES['picture']['error'];

        if ($image_error === 0) {
            // $image_extension = pathinfo($image_name, PATHINFO_EXTENSION);
            // $image_extension_to_lc = strtolower($image_extension);
            $image_extension_to_lc = substr($image_name, strlen($image_name) - 4, strlen($image_name));

            $allowed_extensions = array(".jpg", "jpeg", ".png", ".gif");
            if (!in_array($image_extension_to_lc, $allowed_extensions)) {
                echo "<script>alert('Invalid format. Only jpg / jpeg/ png /gif format allowed');</script>";
            } else {
                $picture = md5($image_name) . time() . $image_extension_to_lc;
                $img_upload_path = 'img/profile-picture/' . $picture;
                // Delete old profile picture
                $old_pp_des = "img/profile-picture/$old_picture";
                if (unlink($old_pp_des)) {
                    // just deleted
                    move_uploaded_file($image_tmp_name, $img_upload_path);
                } else {
                    // error or already deleted
                    move_uploaded_file($image_tmp_name, $img_upload_path);
                }

                // query for data selection
                $sql = "SELECT * FROM user WHERE id=:id";
                $query = $dbh->prepare($sql);
                $query->bindParam(':id', $id, PDO::PARAM_STR);
                $query->execute();
                $result = $query->fetchAll(PDO::FETCH_OBJ);

                if ($query->rowCount() > 0) {
                    //query for updation
                    $con = "UPDATE user SET username=:username, fullname=:fullname, picture=:picture WHERE id=:id";
                    $update = $dbh->prepare($con);
                    $update->bindParam(':id', $id, PDO::PARAM_STR);
                    $update->bindParam(':username', $username, PDO::PARAM_STR);
                    $update->bindParam(':fullname', $fullname, PDO::PARAM_STR);
                    $update->bindParam(':picture', $picture, PDO::PARAM_STR);
                    $update->execute();

                    if ($update) {
                        $infos['edit-profile-success'] = "Updated successfully";
                    } else {
                        $errors['edit-profile-fail'] = "Something went wrong";
                    }
                }
            }
        }
    } else {
        // query for data selection
        $sql = "SELECT * FROM user WHERE id=:id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':id', $id, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_OBJ);

        if ($query->rowCount() > 0) {
            //query for updation
            $con = "UPDATE user SET username=:username, fullname=:fullname WHERE id=:id";
            $update = $dbh->prepare($con);
            $update->bindParam(':id', $id, PDO::PARAM_STR);
            $update->bindParam(':username', $username, PDO::PARAM_STR);
            $update->bindParam(':fullname', $fullname, PDO::PARAM_STR);
            $update->execute();

            if ($update) {
                $infos['edit-profile-success'] = "Updated successfully";
            } else {
                $errors['edit-profile-fail'] = "Something went wrong";
            }
        }
    }
}

// if (isset($_FILES['picture']['name']))

//     // query for data selection
//     $sql = "SELECT * FROM user WHERE id=:id";
// $query = $dbh->prepare($sql);
// $query->bindParam(':id', $id, PDO::PARAM_STR);
// $query->execute();
// $result = $query->fetchAll(PDO::FETCH_OBJ);

// if ($query->rowCount() > 0) {
//     //query for updation
//     $con = "UPDATE user SET username=:username, fullname=:fullname WHERE id=:id";
//     $update = $dbh->prepare($con);
//     $update->bindParam(':id', $id, PDO::PARAM_STR);
//     $update->bindParam(':username', $username, PDO::PARAM_STR);
//     $update->bindParam(':fullname', $fullname, PDO::PARAM_STR);
//     $update->execute();

//     $infos['edit-profile'] = "Updated successfully";
// }

//If user click edit_password button in edit password page
if (isset($_POST['edit_password'])) {
    $email = $_POST['email'];
    $op = $_POST['op'];
    $np = $_POST['np'];
    $c_np = $_POST['c_np'];

    // $sql = "SELECT password from user where id=:id AND password=:op";
    $sql = "SELECT email, password from user where email=:email";
    $query = $dbh->prepare($sql);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetch();

    if ((password_verify($op, $result['password'])) && !empty($op)) {
        if ($np == '') {
            $error[] = "New Password is required.";
            $errors[] = "New Password is required.";
        }
        if ($c_np == '') {
            $error[] = 'Confirmation password is required.';
            $errors[] = 'Confirmation password is required.';
        }
        if ($np != $c_np) {
            $error[] = 'The confirmation password does not match.';
            $errors[] = 'The confirmation password does not match.';
        }
        if (!isset($error)) {
            $options = array("cost" => 4);
            $np = password_hash($np, PASSWORD_BCRYPT, $options);

            $sql = "UPDATE user SET password=? WHERE email=?";
            $query = $dbh->prepare($sql);
            $query->execute([$np, $email]);

            if ($query) {
                $infos['edit-password'] = "Your password has been changed successfully";
            } else {
                header("Location: edit-password.php?wrong=Fail to change the password, please try again");
            }
        }
    } else {
        // $error[] = 'Old Password does not match.';
        $errors[] = 'Old Password does not match.';
    }

    if (isset($error)) {

        foreach ($error as $error) {
            // echo '<p class="errmsg">' . $error . '</p>';
        }
    }
}

// if user click verify-password in edit email verify password page
if (isset($_POST['verify-password'])) {
    //getting the post values
    $id = $_POST['id'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    //Query for data selection
    $sql = "SELECT * from user where id=:id AND email=:email";
    $query = $dbh->prepare($sql);
    $query->bindParam(':id', $id, PDO::PARAM_STR);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetch();


    if (password_verify($password, $result['password'])) {
        header('location: edit-email-change-email.php');
    } else {
        $errors['verify-password-fail'] = "Invalid password";
    }
}

//if user click update-email button in edit email change email page
if (isset($_POST['update-email'])) {
    //getting the post values
    $id = $_POST['id'];
    $old_email = $_POST['old_email'];
    $email = $_POST['email'];

    if ($old_email != $email) {
        //Query for data selection
        $sql = "SELECT * from user where id=:id AND email=:old_email";
        $query = $dbh->prepare($sql);
        $query->bindParam(':id', $id, PDO::PARAM_STR);
        $query->bindParam(':old_email', $old_email, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetch();

        if ($query->rowCount() > 0) {
            $email_code = mt_rand(100000, 999999);
            //query for updation
            $con = "UPDATE user SET temp_email=:email, email_code=:email_code WHERE id=:id AND email=:old_email";
            $update = $dbh->prepare($con);
            $update->bindParam(':id', $id, PDO::PARAM_STR);
            $update->bindParam(':old_email', $old_email, PDO::PARAM_STR);
            $update->bindParam(':email', $email, PDO::PARAM_STR);
            $update->bindParam(':email_code', $email_code, PDO::PARAM_STR);
            $update->execute();

            if ($update) {
                $subject = "SHASSIC | CHANGE EMAIL REQUEST";
                $message =
                    "<h1 class='h4 text-gray-900 mb-4' style='font-weight: bold;'>SHASSIC</h1>
            <p style='text-align:center; font-family:'Poppins', sans-serif;'>You have requested to change email.<br> The code for change email is <b>$email_code</b></p>
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

                    // $_SESSION['info'] = "Code has been sent to your email!";
                    // $_SESSION['email'] = $email;
                    header('location: edit-email-code.php');
                } catch (Exception $e) {
                    $errors['otp-error'] = $e;
                }
            } else {
                $errors['change-email-fail'] = "Something went wrong";
            }
        }
    } else {
        $errors['same-email-error'] = "Email cannot be the same";
    }
}

if (isset($_POST['check-emailcode'])) {
    $id = $_POST['id'];
    $email = $_POST['email'];
    $temp_email = $_POST['temp_email'];
    $email_code = $_POST['email_code'];

    //Query for data selection
    $sql = "SELECT * from user where id=:id AND email=:email AND temp_email=:temp_email";
    $query = $dbh->prepare($sql);
    $query->bindParam(':id', $id, PDO::PARAM_STR);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->bindParam(':temp_email', $temp_email, PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetch();

    if ($query->rowCount() > 0) {
        if ($email_code == $result['email_code']) {
            $email = $temp_email;
            $temp_email = '';
            $email_code = 0;
            //query for updation
            $con = "UPDATE user SET email=:email, temp_email=:temp_email, email_code=:email_code WHERE id=:id";
            $update = $dbh->prepare($con);
            $update->bindParam(':id', $id, PDO::PARAM_STR);
            $update->bindParam(':email', $email, PDO::PARAM_STR);
            $update->bindParam(':temp_email', $temp_email, PDO::PARAM_STR);
            $update->bindParam(':email_code', $email_code, PDO::PARAM_STR);
            $update->execute();

            if ($update) {
                $_SESSION['info'] = 'Updated successfully';
                $_SESSION['login'] = $email;
                header('location: edit-profile.php');
            } else {
                $errors['update-email-fail'] = 'Something went wrong';
            }
        } else {
            $errors['email-code-invalid'] = 'ERROR: Incorrect code!';
        }
    }
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
        $imgnewfile = md5($project_picture) . time() . $extension;
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
            $con = "INSERT INTO document_check_subscore(assessment_id) VALUES(:assessee_id);
                    INSERT INTO workplace_inspection_subscore(assessment_id) VALUES(:assessee_id);
                    INSERT INTO personnel_interview_subscore(assessment_id) VALUES(:assessee_id);";
            $insert_subscore = $dbh->prepare($con);
            $insert_subscore->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
            $insert_subscore->execute();

            if ($insert_subscore && insertDocumentCheck($assessee_id) && insertWorkplaceInspection($assessee_id) && insertPersonnelInterviewManagerialSupervisory($assessee_id) && insertPersonnelInterviewWorker($assessee_id)) {
                $_SESSION['info'] = "You have successfully added a new assessment";
                header("location: assessment-component.php?assessee_id=" . $assessee_id);
            } else {
                $errors['add-new-assessment-fail'] = "Something went wrong";
            }
        }
    }
}

function insertDocumentCheck($lastInsertId)
{
    if (!isset($_SESSION)) {
        session_start();
    }
    include 'includes/config.php';

    $assessee_id = $lastInsertId;

    //query for data selection - document check
    $sql = "SELECT * FROM document_check_checklist";
    $query = $dbh->prepare($sql);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);
    $cnt = 1;
    if ($query->rowCount() > 0) {
        foreach ($results as $result) {
            //query for data insertion - document check
            $document_check_checklist_id = $result->id;
            $conn = "INSERT INTO document_check_assessment(assessment_id, document_check_checklist_id) VALUES(:assessee_id, :document_check_checklist_id);";
            $insert_document_check = $dbh->prepare($conn);
            $insert_document_check->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
            $insert_document_check->bindParam(':document_check_checklist_id', $document_check_checklist_id, PDO::PARAM_STR);
            $insert_document_check->execute();
        }
    }

    if ($insert_document_check) {
        return true;
    } else {
        return false;
    }
}

function insertWorkplaceInspection($lastInsertId)
{
    if (!isset($_SESSION)) {
        session_start();
    }
    include 'includes/config.php';

    $assessee_id = $lastInsertId;

    //query for data selection - workplace inspection (general)
    $conn1 = "SELECT * FROM workplace_inspection_checklist WHERE item_id BETWEEN 1 AND 3";
    $query1 = $dbh->prepare($conn1);
    $query1->execute();
    $checklists = $query1->fetchAll(PDO::FETCH_OBJ);
    $cnt = 1;
    if ($query1->rowCount() > 0) {
        foreach ($checklists as $checklist) {
            //query for data insertion - workplace inspection (general)
            $workplace_inspection_checklist_id = $checklist->id;
            $conn2 = "INSERT INTO workplace_inspection_assessment(assessment_id, workplace_inspection_checklist_id) VALUES(:assessee_id, :workplace_inspection_checklist_id);";
            $insert_workplace_inspection_general = $dbh->prepare($conn2);
            $insert_workplace_inspection_general->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
            $insert_workplace_inspection_general->bindParam(':workplace_inspection_checklist_id', $workplace_inspection_checklist_id, PDO::PARAM_STR);
            $insert_workplace_inspection_general->execute();
        }
    }


    //query for data selection - workplace inspection (construction work)
    $conn3 = "SELECT * FROM workplace_inspection_checklist WHERE item_id BETWEEN 4 AND 7";
    $query2 = $dbh->prepare($conn3);
    $query2->execute();
    $checklists1 = $query2->fetchAll(PDO::FETCH_OBJ);
    $cnt = 1;
    if ($query2->rowCount() > 0) {
        foreach ($checklists1 as $checklist1) {
            //query for data insertion - workplace inspection (construction work)
            $workplace_inspection_checklist_id = $checklist1->id;
            $conn4 = "INSERT INTO workplace_inspection_high_risk_1(assessment_id, workplace_inspection_checklist_id) VALUES(:assessee_id, :workplace_inspection_checklist_id);
                      INSERT INTO workplace_inspection_high_risk_2(assessment_id, workplace_inspection_checklist_id) VALUES(:assessee_id, :workplace_inspection_checklist_id);
                      INSERT INTO workplace_inspection_high_risk_3(assessment_id, workplace_inspection_checklist_id) VALUES(:assessee_id, :workplace_inspection_checklist_id);";
            $insert_workplace_inspection_highrisk = $dbh->prepare($conn4);
            $insert_workplace_inspection_highrisk->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
            $insert_workplace_inspection_highrisk->bindParam(':workplace_inspection_checklist_id', $workplace_inspection_checklist_id, PDO::PARAM_STR);
            $insert_workplace_inspection_highrisk->execute();
        }
    }

    if ($insert_workplace_inspection_general && $insert_workplace_inspection_highrisk) {
        return true;
    } else {
        return false;
    }
}

function insertPersonnelInterviewManagerialSupervisory($lastInsertId)
{
    if (!isset($_SESSION)) {
        session_start();
    }
    include 'includes/config.php';

    $assessee_id = $lastInsertId;

    //query for data selection - personnel interview (managerial)
    $conn5 = "SELECT * FROM personnel_interview_checklist WHERE category_id = 1";
    $query3 = $dbh->prepare($conn5);
    $query3->execute();
    $checklists2 = $query3->fetchAll(PDO::FETCH_OBJ);
    $cnt = 1;
    if ($query3->rowCount() > 0) {
        foreach ($checklists2 as $checklist2) {
            //query for data insertion - personnel interview (managerial)
            $personnel_interview_checklist_id = $checklist2->id;
            $conn6 = "INSERT INTO personnel_interview_managerial(assessment_id, personnel_interview_checklist_id) VALUES(:assessee_id, :personnel_interview_checklist_id)";
            $insert_personnel_interview_managerial = $dbh->prepare($conn6);
            $insert_personnel_interview_managerial->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
            $insert_personnel_interview_managerial->bindParam(':personnel_interview_checklist_id', $personnel_interview_checklist_id, PDO::PARAM_STR);
            $insert_personnel_interview_managerial->execute();
        }
    }


    //query for data selection - personnel interview (supervisory)
    $conn7 = "SELECT * FROM personnel_interview_checklist WHERE category_id = 2";
    $query4 = $dbh->prepare($conn7);
    $query4->execute();
    $checklists3 = $query4->fetchAll(PDO::FETCH_OBJ);
    $cnt = 1;
    if ($query4->rowCount() > 0) {
        foreach ($checklists3 as $checklist3) {
            //query for data insertion - personnel interview (supervisory)
            $personnel_interview_checklist_id = $checklist3->id;
            $conn8 = "INSERT INTO personnel_interview_supervisory_1(assessment_id, personnel_interview_checklist_id) VALUES(:assessee_id, :personnel_interview_checklist_id);
                      INSERT INTO personnel_interview_supervisory_2(assessment_id, personnel_interview_checklist_id) VALUES(:assessee_id, :personnel_interview_checklist_id);
                      INSERT INTO personnel_interview_supervisory_3(assessment_id, personnel_interview_checklist_id) VALUES(:assessee_id, :personnel_interview_checklist_id);";
            $insert_personnel_interview_supervisory = $dbh->prepare($conn8);
            $insert_personnel_interview_supervisory->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
            $insert_personnel_interview_supervisory->bindParam(':personnel_interview_checklist_id', $personnel_interview_checklist_id, PDO::PARAM_STR);
            $insert_personnel_interview_supervisory->execute();
        }
    }

    if ($insert_personnel_interview_managerial && $insert_personnel_interview_supervisory) {
        return true;
    } else {
        return false;
    }
}

function insertPersonnelInterviewWorker($lastInsertId)
{
    if (!isset($_SESSION)) {
        session_start();
    }
    include 'includes/config.php';

    $assessee_id = $lastInsertId;

    //query for data selection - personnel interview (worker)
    $conn9 = "SELECT * FROM personnel_interview_checklist WHERE category_id = 3";
    $query5 = $dbh->prepare($conn9);
    $query5->execute();
    $checklists4 = $query5->fetchAll(PDO::FETCH_OBJ);
    $cnt = 1;
    if ($query5->rowCount() > 0) {
        foreach ($checklists4 as $checklist4) {
            //query for data insertion - personnel interview (worker)
            $personnel_interview_checklist_id = $checklist4->id;
            $conn10 = "INSERT INTO personnel_interview_worker_1(assessment_id, personnel_interview_checklist_id) VALUES(:assessee_id, :personnel_interview_checklist_id);
                   INSERT INTO personnel_interview_worker_2(assessment_id, personnel_interview_checklist_id) VALUES(:assessee_id, :personnel_interview_checklist_id);
                   INSERT INTO personnel_interview_worker_3(assessment_id, personnel_interview_checklist_id) VALUES(:assessee_id, :personnel_interview_checklist_id);
                   INSERT INTO personnel_interview_worker_4(assessment_id, personnel_interview_checklist_id) VALUES(:assessee_id, :personnel_interview_checklist_id);
                   INSERT INTO personnel_interview_worker_5(assessment_id, personnel_interview_checklist_id) VALUES(:assessee_id, :personnel_interview_checklist_id);
                   INSERT INTO personnel_interview_worker_6(assessment_id, personnel_interview_checklist_id) VALUES(:assessee_id, :personnel_interview_checklist_id);
                   INSERT INTO personnel_interview_worker_7(assessment_id, personnel_interview_checklist_id) VALUES(:assessee_id, :personnel_interview_checklist_id);
                   INSERT INTO personnel_interview_worker_8(assessment_id, personnel_interview_checklist_id) VALUES(:assessee_id, :personnel_interview_checklist_id);
                   INSERT INTO personnel_interview_worker_9(assessment_id, personnel_interview_checklist_id) VALUES(:assessee_id, :personnel_interview_checklist_id);";
            $insert_personnel_interview_worker = $dbh->prepare($conn10);
            $insert_personnel_interview_worker->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
            $insert_personnel_interview_worker->bindParam(':personnel_interview_checklist_id', $personnel_interview_checklist_id, PDO::PARAM_STR);
            $insert_personnel_interview_worker->execute();
        }
    }

    if ($insert_personnel_interview_worker) {
        return true;
    } else {
        return false;
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
    $old_project_picture = $_POST['old_project_picture'];

    if (isset($_FILES['project_picture']['name'])  && !empty($_FILES['project_picture']['name'])) {
        $project_image_name = $_FILES['project_picture']['name'];
        $project_image_tmp_name = $_FILES['project_picture']['tmp_name'];
        $project_image_error = $_FILES['project_picture']['error'];

        if ($project_image_error === 0) {
            // $project_image_extension = pathinfo($project_image_name, PATHINFO_EXTENSION);
            $project_image_extension_to_lc = substr($project_image_name, strlen($project_image_name) - 4, strlen($project_image_name));

            $allowed_extensions = array(".jpg", "jpeg", ".png", ".gif");
            if (in_array($project_image_extension_to_lc, $allowed_extensions)) {
                $project_picture = md5($project_image_extension_to_lc) . time() . $project_image_extension_to_lc;
                $img_upload_path = 'img/project-image/' . $project_picture;
                // Delete old profile pic
                $old_pp_des = "img/project-image/$old_project_picture";
                if (unlink($old_pp_des)) {
                    // just deleted
                    move_uploaded_file($project_image_tmp_name, $img_upload_path);
                } else {
                    // error or already deleted
                    move_uploaded_file($project_image_tmp_name, $img_upload_path);
                }

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

                    if ($update) {
                        $infos['edit-assessment-success'] = "Updated successfully";
                    } else {
                        $errors['edit-assessment-fail'] = "Something went wrong";
                    }
                    // header("location: assessment-component.php?assessee_id=" . $assessee_id);
                }
            }
        }
    } else {
        // query for data selection
        $sql = "SELECT * FROM assessment WHERE assessee_id=:assessee_id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_OBJ);

        if ($query->rowCount() > 0) {
            //query for updation
            $con = "UPDATE assessment SET assessor_id=:assessor_id, assessor_name=:assessor_name, assessee_name=:assessee_name, project_name=:project_name, project_date=:project_date, project_location=:project_location WHERE assessee_id=:assessee_id";
            $update = $dbh->prepare($con);
            $update->bindParam(':assessor_id', $assessor_id, PDO::PARAM_STR);
            $update->bindParam(':assessor_name', $assessor_name, PDO::PARAM_STR);
            $update->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
            $update->bindParam(':assessee_name', $assessee_name, PDO::PARAM_STR);
            $update->bindParam(':project_name', $project_name, PDO::PARAM_STR);
            $update->bindParam(':project_date', $project_date, PDO::PARAM_STR);
            $update->bindParam(':project_location', $project_location, PDO::PARAM_STR);
            $update->execute();

            if ($update) {
                $infos['edit-assessment-success'] = "Updated successfully";
            } else {
                $errors['edit-assessment-fail'] = "Something went wrong";
            }
            // header("location: assessment-component.php?assessee_id=" . $assessee_id);
        }
    }
}

// if user click update-from-history button in edit assessment from history page
if (isset($_POST['update-from-history'])) {
    //getting the post values
    $assessor_id = $_POST['assessor_id'];
    $assessor_name = $_POST['assessor_name'];
    $assessee_id = $_POST['assessee_id'];
    $assessee_name = $_POST['assessee_name'];
    $project_name = $_POST['project_name'];
    $project_date = $_POST['project_date'];
    $project_location = $_POST['project_location'];
    $old_project_picture = $_POST['old_project_picture'];

    if (isset($_FILES['project_picture']['name'])  && !empty($_FILES['project_picture']['name'])) {
        $project_image_name = $_FILES['project_picture']['name'];
        $project_image_tmp_name = $_FILES['project_picture']['tmp_name'];
        $project_image_error = $_FILES['project_picture']['error'];

        if ($project_image_error === 0) {
            // $project_image_extension = pathinfo($project_image_name, PATHINFO_EXTENSION);
            // $project_image_extension_to_lc = strtolower($project_image_extension);
            $project_image_extension_to_lc = substr($project_image_name, strlen($project_image_name) - 4, strlen($project_image_name));

            $allowed_extensions = array(".jpg", "jpeg", ".png", ".gif");
            if (!in_array($project_image_extension_to_lc, $allowed_extensions)) {
                echo "<script>alert('Invalid format. Only jpg / jpeg/ png /gif format allowed');</script>";
            } else {
                $project_picture = md5($project_image_name) . time() . $project_image_extension_to_lc;
                $img_upload_path = 'img/project-image/' . $project_picture;
                // Delete old project picture
                $old_pp_des = "img/project-image/$old_project_picture";
                if (unlink($old_pp_des)) {
                    // just deleted
                    move_uploaded_file($project_image_tmp_name, $img_upload_path);
                } else {
                    // error or already deleted
                    move_uploaded_file($project_image_tmp_name, $img_upload_path);
                }

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

                    if ($update) {
                        $infos['update-from-history-success'] = "Updated successfully";
                    } else {
                        $errors['update-from-history-fail'] = "Something went wrong";
                    }
                    // header("location: assessment-component.php?assessee_id=" . $assessee_id);
                }
            }
        }
    } else {
        // query for data selection
        $sql = "SELECT * FROM assessment WHERE assessee_id=:assessee_id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_OBJ);

        if ($query->rowCount() > 0) {
            //query for updation
            $con = "UPDATE assessment SET assessor_id=:assessor_id, assessor_name=:assessor_name, assessee_name=:assessee_name, project_name=:project_name, project_date=:project_date, project_location=:project_location WHERE assessee_id=:assessee_id";
            $update = $dbh->prepare($con);
            $update->bindParam(':assessor_id', $assessor_id, PDO::PARAM_STR);
            $update->bindParam(':assessor_name', $assessor_name, PDO::PARAM_STR);
            $update->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
            $update->bindParam(':assessee_name', $assessee_name, PDO::PARAM_STR);
            $update->bindParam(':project_name', $project_name, PDO::PARAM_STR);
            $update->bindParam(':project_date', $project_date, PDO::PARAM_STR);
            $update->bindParam(':project_location', $project_location, PDO::PARAM_STR);
            $update->execute();

            if ($update) {
                $infos['update-from-history-success'] = "Updated successfully";
            } else {
                $errors['update-from-history-fail'] = "Something went wrong";
            }
            // header("location: assessment-component.php?assessee_id=" . $assessee_id);
        }
    }
}

//if user click save-document-check button in assessment document check page
if (isset($_POST['save-document-check'])) {
    //getting the post value
    $assessee_id = $_POST['assessee_id'];
    $doc_check_c_score = $_POST['doc_check_c_score'];
    $doc_check_nc_score = $_POST['doc_check_nc_score'];
    $doc_check_na_score = $_POST['doc_check_na_score'];
    $document_check_percentage = $_POST['document_check_percentage'];

    // query for data selection - document_check_subscore
    $sql = "SELECT * FROM document_check_subscore WHERE assessment_id=:assessee_id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);

    if ($query->rowCount() > 0) {
        foreach ($results as $result) {
            //query for updation
            $con = "UPDATE document_check_subscore SET doc_check_c_score=:doc_check_c_score, doc_check_nc_score=:doc_check_nc_score, doc_check_na_score=:doc_check_na_score WHERE assessment_id=:assessee_id";
            $update = $dbh->prepare($con);
            $update->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
            $update->bindParam(':doc_check_c_score', $doc_check_c_score, PDO::PARAM_STR);
            $update->bindParam(':doc_check_nc_score', $doc_check_nc_score, PDO::PARAM_STR);
            $update->bindParam(':doc_check_na_score', $doc_check_na_score, PDO::PARAM_STR);
            $update->execute();

            if ($update) {
                // query for data selection
                $sql = "SELECT * FROM assessment WHERE assessee_id=:assessee_id";
                $query = $dbh->prepare($sql);
                $query->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                $query->execute();
                $result1 = $query->fetchAll(PDO::FETCH_OBJ);

                if ($query->rowCount() > 0) {
                    foreach ($result1 as $results1) {
                        $con = "UPDATE assessment SET document_check_percentage=:document_check_percentage, total_percentage=('$results1->workplace_inspection_percentage'+'$results1->personnel_interview_percentage'+ :document_check_percentage) WHERE assessee_id='$result->assessment_id'";
                        $update = $dbh->prepare($con);
                        $update->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                        $update->bindParam(':document_check_percentage', $document_check_percentage, PDO::PARAM_STR);
                        $update->execute();
                    }
                }
            }
        }
    }

    foreach ($_POST['document_check_checklist_id'] as $document_check_checklist_id) {
        if (isset($_POST['doccheck_' . $document_check_checklist_id])) {
            $doccheck = $_POST['doccheck_' . $document_check_checklist_id];
            $doc_check = implode(', ', $doccheck);
        } else {
            $doc_check = "";
        }
        if (isset($_POST['remarks_' . $document_check_checklist_id])) {
            $remarks = $_POST['remarks_' . $document_check_checklist_id];
        } else {
            $remarks = '';
        }

        // query for data selection - document_check_assessment
        $sql1 = "SELECT * FROM document_check_assessment WHERE document_check_checklist_id=:document_check_checklist_id AND assessment_id=:assessee_id";
        $query1 = $dbh->prepare($sql1);
        $query1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
        $query1->bindParam(':document_check_checklist_id', $document_check_checklist_id, PDO::PARAM_STR);
        $query1->execute();
        $results1 = $query1->fetchAll(PDO::FETCH_OBJ);

        if ($query1->rowCount() > 0) {
            foreach ($results1 as $result1) {
                //query for updation - document_check_assessment
                $con1 = "UPDATE document_check_assessment SET status=:doc_check, remarks=:remarks WHERE document_check_checklist_id=:document_check_checklist_id AND assessment_id=:assessee_id";
                $update1 = $dbh->prepare($con1);
                $update1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                $update1->bindParam(':doc_check', $doc_check, PDO::PARAM_STR);
                $update1->bindParam(':remarks', $remarks, PDO::PARAM_STR);
                $update1->bindParam(':document_check_checklist_id', $document_check_checklist_id, PDO::PARAM_STR);
                $update1->execute();

                if ($update1) {
                    $infos['document-check-update-success'] = "Updated successfully";
                } else {
                    $errors['document-check-update-fail'] = "Something went wrong";
                }
            }
        }
    }
}

//if user click save-document-check-from-history button in assessment document check from history page
if (isset($_POST['save-document-check-from-history'])) {
    //getting the post value
    $assessee_id = $_POST['assessee_id'];
    $doc_check_c_score = $_POST['doc_check_c_score'];
    $doc_check_nc_score = $_POST['doc_check_nc_score'];
    $doc_check_na_score = $_POST['doc_check_na_score'];
    $document_check_percentage = $_POST['document_check_percentage'];

    // query for data selection - document_check_subscore
    $sql = "SELECT * FROM document_check_subscore WHERE assessment_id=:assessee_id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);

    if ($query->rowCount() > 0) {
        foreach ($results as $result) {
            //query for updation
            $con = "UPDATE document_check_subscore SET doc_check_c_score=:doc_check_c_score, doc_check_nc_score=:doc_check_nc_score, doc_check_na_score=:doc_check_na_score WHERE assessment_id=:assessee_id";
            $update = $dbh->prepare($con);
            $update->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
            $update->bindParam(':doc_check_c_score', $doc_check_c_score, PDO::PARAM_STR);
            $update->bindParam(':doc_check_nc_score', $doc_check_nc_score, PDO::PARAM_STR);
            $update->bindParam(':doc_check_na_score', $doc_check_na_score, PDO::PARAM_STR);
            $update->execute();

            if ($update) {
                // query for data selection
                $sql = "SELECT * FROM assessment WHERE assessee_id=:assessee_id";
                $query = $dbh->prepare($sql);
                $query->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                $query->execute();
                $result1 = $query->fetchAll(PDO::FETCH_OBJ);

                if ($query->rowCount() > 0) {
                    foreach ($result1 as $results1) {
                        $con = "UPDATE assessment SET document_check_percentage=:document_check_percentage, total_percentage=('$results1->workplace_inspection_percentage'+'$results1->personnel_interview_percentage'+ :document_check_percentage) WHERE assessee_id='$result->assessment_id'";
                        $update = $dbh->prepare($con);
                        $update->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                        $update->bindParam(':document_check_percentage', $document_check_percentage, PDO::PARAM_STR);
                        $update->execute();
                    }
                }
            }
        }
    }

    foreach ($_POST['document_check_checklist_id'] as $document_check_checklist_id) {
        if (isset($_POST['doccheck_' . $document_check_checklist_id])) {
            $doccheck = $_POST['doccheck_' . $document_check_checklist_id];
            $doc_check = implode(', ', $doccheck);
        } else {
            $doc_check = "";
        }
        if (isset($_POST['remarks_' . $document_check_checklist_id])) {
            $remarks = $_POST['remarks_' . $document_check_checklist_id];
        } else {
            $remarks = '';
        }

        // query for data selection - document_check_assessment
        $sql1 = "SELECT * FROM document_check_assessment WHERE document_check_checklist_id=:document_check_checklist_id AND assessment_id=:assessee_id";
        $query1 = $dbh->prepare($sql1);
        $query1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
        $query1->bindParam(':document_check_checklist_id', $document_check_checklist_id, PDO::PARAM_STR);
        $query1->execute();
        $results1 = $query1->fetchAll(PDO::FETCH_OBJ);

        if ($query1->rowCount() > 0) {
            foreach ($results1 as $result1) {
                //query for updation - document_check_assessment
                $con1 = "UPDATE document_check_assessment SET status=:doc_check, remarks=:remarks WHERE document_check_checklist_id=:document_check_checklist_id AND assessment_id=:assessee_id";
                $update1 = $dbh->prepare($con1);
                $update1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                $update1->bindParam(':doc_check', $doc_check, PDO::PARAM_STR);
                $update1->bindParam(':remarks', $remarks, PDO::PARAM_STR);
                $update1->bindParam(':document_check_checklist_id', $document_check_checklist_id, PDO::PARAM_STR);
                $update1->execute();

                if ($update1) {
                    $infos['document-check-update-success'] = "Updated successfully";
                } else {
                    $errors['document-check-update-fail'] = "Something went wrong";
                }
            }
        }
    }
}

//if user click save-workplace-inspection-general button in assessment workplace inspection page
if (isset($_POST['save-workplace-inspection-general'])) {
    //getting the post value
    $assessee_id = $_POST['assessee_id'];
    $general_c_score = $_POST['general_c_score'];
    $general_nc_score = $_POST['general_nc_score'];
    $general_na_score = $_POST['general_na_score'];

    // query for data selection - workplace_inspection_subscore
    $sql = "SELECT * FROM workplace_inspection_subscore WHERE assessment_id=:assessee_id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);

    if ($query->rowCount() > 0) {
        foreach ($results as $result) {
            //query for updation
            $con = "UPDATE workplace_inspection_subscore SET general_c_score=:general_c_score, general_nc_score=:general_nc_score, general_na_score=:general_na_score WHERE assessment_id=:assessee_id";
            $update = $dbh->prepare($con);
            $update->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
            $update->bindParam(':general_c_score', $general_c_score, PDO::PARAM_STR);
            $update->bindParam(':general_nc_score', $general_nc_score, PDO::PARAM_STR);
            $update->bindParam(':general_na_score', $general_na_score, PDO::PARAM_STR);
            $update->execute();

            if ($update) {
                //query for data selection
                $conn = "SELECT * FROM assessment WHERE assessee_id=:assessee_id";
                $query1 = $dbh->prepare($conn);
                $query1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                $query1->execute();
                $result1 = $query1->fetchAll(PDO::FETCH_OBJ);

                if ($query1->rowCount() > 0) {
                    foreach ($result1 as $results1) {
                        $conn1 = "UPDATE assessment SET total_percentage=(((:general_c_score + '$result->high_risk_c_score') / (72 - (:general_na_score + '$result->high_risk_na_score')) * 60)+'$results1->document_check_percentage'+'$results1->personnel_interview_percentage'), 
                        workplace_inspection_percentage=((:general_c_score + '$result->high_risk_c_score') / (72 - (:general_na_score + '$result->high_risk_na_score')) * 60) WHERE assessee_id=:assessee_id";
                        $update1 = $dbh->prepare($conn1);
                        $update1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                        $update1->bindParam(':general_c_score', $general_c_score, PDO::PARAM_STR);
                        $update1->bindParam(':general_na_score', $general_na_score, PDO::PARAM_STR);
                        $update1->execute();
                    }
                }
            }
        }
    }

    foreach ($_POST['workplace_inspection_checklist_id'] as $workplace_inspection_checklist_id) {
        if (isset($_POST['workinsp_' . $workplace_inspection_checklist_id])) {
            $workinsp = $_POST['workinsp_' . $workplace_inspection_checklist_id];
            $work_insp = implode(', ', $workinsp);
        } else {
            $work_insp = "";
        }
        if (isset($_POST['remarks_' . $workplace_inspection_checklist_id])) {
            $remarks = $_POST['remarks_' . $workplace_inspection_checklist_id];
        } else {
            $remarks = '';
        }

        // query for data selection - workplace_inspection_assessment
        $sql1 = "SELECT * FROM workplace_inspection_assessment WHERE workplace_inspection_checklist_id=:workplace_inspection_checklist_id AND assessment_id=:assessee_id";
        $query1 = $dbh->prepare($sql1);
        $query1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
        $query1->bindParam(':workplace_inspection_checklist_id', $workplace_inspection_checklist_id, PDO::PARAM_STR);
        $query1->execute();
        $results1 = $query1->fetchAll(PDO::FETCH_OBJ);

        if ($query1->rowCount() > 0) {
            foreach ($results1 as $result1) {
                //query for updation - workplace_inspection_assessment
                $con1 = "UPDATE workplace_inspection_assessment SET status=:work_insp, remarks=:remarks WHERE workplace_inspection_checklist_id=:workplace_inspection_checklist_id AND assessment_id=:assessee_id";
                $update1 = $dbh->prepare($con1);
                $update1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                $update1->bindParam(':work_insp', $work_insp, PDO::PARAM_STR);
                $update1->bindParam(':remarks', $remarks, PDO::PARAM_STR);
                $update1->bindParam(':workplace_inspection_checklist_id', $workplace_inspection_checklist_id, PDO::PARAM_STR);
                $update1->execute();

                if ($update1) {
                    $infos['workplace-inspection-general-update-success'] = "Updated successfully";
                } else {
                    $errors['workplace-inspection-general-update-fail'] = "Something went wrong";
                }
            }
            // header("location: assessment-workplace-inspection.php?assessee_id=" . $assessee_id . "&info=" . $info);
        }
    }
}

//if user click save-workplace-inspection-general-from-history button in assessment workplace inspection from history page
if (isset($_POST['save-workplace-inspection-general-from-history'])) {
    //getting the post value
    $assessee_id = $_POST['assessee_id'];
    $general_c_score = $_POST['general_c_score'];
    $general_nc_score = $_POST['general_nc_score'];
    $general_na_score = $_POST['general_na_score'];

    // query for data selection - workplace_inspection_subscore
    $sql = "SELECT * FROM workplace_inspection_subscore WHERE assessment_id=:assessee_id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);

    if ($query->rowCount() > 0) {
        foreach ($results as $result) {
            //query for updation
            $con = "UPDATE workplace_inspection_subscore SET general_c_score=:general_c_score, general_nc_score=:general_nc_score, general_na_score=:general_na_score WHERE assessment_id=:assessee_id";
            $update = $dbh->prepare($con);
            $update->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
            $update->bindParam(':general_c_score', $general_c_score, PDO::PARAM_STR);
            $update->bindParam(':general_nc_score', $general_nc_score, PDO::PARAM_STR);
            $update->bindParam(':general_na_score', $general_na_score, PDO::PARAM_STR);
            $update->execute();

            if ($update) {
                //query for data selection
                $conn = "SELECT * FROM assessment WHERE assessee_id=:assessee_id";
                $query1 = $dbh->prepare($conn);
                $query1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                $query1->execute();
                $result1 = $query1->fetchAll(PDO::FETCH_OBJ);

                if ($query1->rowCount() > 0) {
                    foreach ($result1 as $results1) {
                        $conn1 = "UPDATE assessment SET total_percentage=(((:general_c_score + '$result->high_risk_c_score') / (72 - (:general_na_score + '$result->high_risk_na_score')) * 60)+'$results1->document_check_percentage'+'$results1->personnel_interview_percentage'), 
                        workplace_inspection_percentage=((:general_c_score + '$result->high_risk_c_score') / (72 - (:general_na_score + '$result->high_risk_na_score')) * 60) WHERE assessee_id=:assessee_id";
                        $update1 = $dbh->prepare($conn1);
                        $update1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                        $update1->bindParam(':general_c_score', $general_c_score, PDO::PARAM_STR);
                        $update1->bindParam(':general_na_score', $general_na_score, PDO::PARAM_STR);
                        $update1->execute();
                    }
                }
            }
        }
    }

    foreach ($_POST['workplace_inspection_checklist_id'] as $workplace_inspection_checklist_id) {
        if (isset($_POST['workinsp_' . $workplace_inspection_checklist_id])) {
            $workinsp = $_POST['workinsp_' . $workplace_inspection_checklist_id];
            $work_insp = implode(', ', $workinsp);
        } else {
            $work_insp = "";
        }
        if (isset($_POST['remarks_' . $workplace_inspection_checklist_id])) {
            $remarks = $_POST['remarks_' . $workplace_inspection_checklist_id];
        } else {
            $remarks = '';
        }

        // query for data selection - workplace_inspection_assessment
        $sql1 = "SELECT * FROM workplace_inspection_assessment WHERE workplace_inspection_checklist_id=:workplace_inspection_checklist_id AND assessment_id=:assessee_id";
        $query1 = $dbh->prepare($sql1);
        $query1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
        $query1->bindParam(':workplace_inspection_checklist_id', $workplace_inspection_checklist_id, PDO::PARAM_STR);
        $query1->execute();
        $results1 = $query1->fetchAll(PDO::FETCH_OBJ);

        if ($query1->rowCount() > 0) {
            foreach ($results1 as $result1) {
                //query for updation - workplace_inspection_assessment
                $con1 = "UPDATE workplace_inspection_assessment SET status=:work_insp, remarks=:remarks WHERE workplace_inspection_checklist_id=:workplace_inspection_checklist_id AND assessment_id=:assessee_id";
                $update1 = $dbh->prepare($con1);
                $update1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                $update1->bindParam(':work_insp', $work_insp, PDO::PARAM_STR);
                $update1->bindParam(':remarks', $remarks, PDO::PARAM_STR);
                $update1->bindParam(':workplace_inspection_checklist_id', $workplace_inspection_checklist_id, PDO::PARAM_STR);
                $update1->execute();

                if ($update1) {
                    $infos['workplace-inspection-general-update-success'] = "Updated successfully";
                } else {
                    $errors['workplace-inspection-general-update-fail'] = "Something went wrong";
                }
            }
            // header("location: assessment-workplace-inspection.php?assessee_id=" . $assessee_id . "&info=" . $info);
        }
    }
}

//if user click save-workplace-inspection-high-risk button in assessment workplace inspection page
if (isset($_POST['save-workplace-inspection-high-risk'])) {
    //getting the post values
    $assessee_id = $_POST['assessee_id'];
    $high_risk_c_score = $_POST['high_risk_c_score'];
    $high_risk_nc_score = $_POST['high_risk_nc_score'];
    $high_risk_na_score = $_POST['high_risk_na_score'];

    // query for data selection - workplace_inspection_subscore
    $sql = "SELECT * FROM workplace_inspection_subscore WHERE assessment_id=:assessee_id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);

    if ($query->rowCount() > 0) {
        foreach ($results as $result) {
            //query for updation
            $con = "UPDATE workplace_inspection_subscore SET high_risk_c_score=:high_risk_c_score, high_risk_nc_score=:high_risk_nc_score, high_risk_na_score=:high_risk_na_score WHERE assessment_id=:assessee_id";
            $update = $dbh->prepare($con);
            $update->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
            $update->bindParam(':high_risk_c_score', $high_risk_c_score, PDO::PARAM_STR);
            $update->bindParam(':high_risk_nc_score', $high_risk_nc_score, PDO::PARAM_STR);
            $update->bindParam(':high_risk_na_score', $high_risk_na_score, PDO::PARAM_STR);
            $update->execute();

            if ($update) {
                //query for data selection
                $conn = "SELECT * FROM assessment WHERE assessee_id=:assessee_id";
                $query1 = $dbh->prepare($conn);
                $query1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                $query1->execute();
                $result1 = $query1->fetchAll(PDO::FETCH_OBJ);

                if ($query1->rowCount() > 0) {
                    foreach ($result1 as $results1) {
                        $conn1 = "UPDATE assessment SET total_percentage=(((:high_risk_c_score + '$result->general_c_score') / (72 - (:high_risk_na_score + '$result->general_na_score')) * 60)+'$results1->document_check_percentage'+'$results1->personnel_interview_percentage'), 
                        workplace_inspection_percentage=((:high_risk_c_score + '$result->general_c_score') / (72 - (:high_risk_na_score + '$result->general_na_score')) * 60) WHERE assessee_id=:assessee_id";
                        $update1 = $dbh->prepare($conn1);
                        $update1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                        $update1->bindParam(':high_risk_c_score', $high_risk_c_score, PDO::PARAM_STR);
                        $update1->bindParam(':high_risk_na_score', $high_risk_na_score, PDO::PARAM_STR);
                        $update1->execute();
                    }
                }
            }
        }
    }

    foreach ($_POST['workplace_inspection_checklist_id'] as $workplace_inspection_checklist_id) {
        if (isset($_POST['highrisk1_' . $workplace_inspection_checklist_id])) {
            $highrisk1 = $_POST['highrisk1_' . $workplace_inspection_checklist_id];
            $high_risk_1 = implode(', ', $highrisk1);
        } else {
            $high_risk_1 = "";
        }
        if (isset($_POST['highrisk2_' . $workplace_inspection_checklist_id])) {
            $highrisk2 = $_POST['highrisk2_' . $workplace_inspection_checklist_id];
            $high_risk_2 = implode(', ', $highrisk2);
        } else {
            $high_risk_2 = "";
        }
        if (isset($_POST['highrisk3_' . $workplace_inspection_checklist_id])) {
            $highrisk3 = $_POST['highrisk3_' . $workplace_inspection_checklist_id];
            $high_risk_3 = implode(', ', $highrisk3);
        } else {
            $high_risk_3 = "";
        }
        if (isset($_POST['remarks_' . $workplace_inspection_checklist_id])) {
            $remarks = $_POST['remarks_' . $workplace_inspection_checklist_id];
        } else {
            $remarks = '';
        }

        // query for data selection - workplace_inspection_high_risk_1
        $sql1 = "SELECT * FROM workplace_inspection_high_risk_1 WHERE workplace_inspection_checklist_id=:workplace_inspection_checklist_id AND assessment_id=:assessee_id";
        $query1 = $dbh->prepare($sql1);
        $query1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
        $query1->bindParam(':workplace_inspection_checklist_id', $workplace_inspection_checklist_id, PDO::PARAM_STR);
        $query1->execute();
        $results1 = $query1->fetchAll(PDO::FETCH_OBJ);

        if ($query1->rowCount() > 0) {
            foreach ($results1 as $result1) {
                //query for updation - workplace_inspection_high_risk_1
                $con1 = "UPDATE workplace_inspection_high_risk_1 SET status=:high_risk_1 WHERE workplace_inspection_checklist_id=:workplace_inspection_checklist_id AND assessment_id=:assessee_id";
                $update1 = $dbh->prepare($con1);
                $update1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                $update1->bindParam(':high_risk_1', $high_risk_1, PDO::PARAM_STR);
                $update1->bindParam(':workplace_inspection_checklist_id', $workplace_inspection_checklist_id, PDO::PARAM_STR);
                $update1->execute();
            }
        }

        // query for data selection - workplace_inspection_high_risk_2
        $sql2 = "SELECT * FROM workplace_inspection_high_risk_2 WHERE workplace_inspection_checklist_id=:workplace_inspection_checklist_id AND assessment_id=:assessee_id";
        $query2 = $dbh->prepare($sql2);
        $query2->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
        $query2->bindParam(':workplace_inspection_checklist_id', $workplace_inspection_checklist_id, PDO::PARAM_STR);
        $query2->execute();
        $results2 = $query2->fetchAll(PDO::FETCH_OBJ);

        if ($query2->rowCount() > 0) {
            foreach ($results2 as $result2) {
                //query for updation - workplace_inspection_high_risk_2
                $con2 = "UPDATE workplace_inspection_high_risk_2 SET status=:high_risk_2 WHERE workplace_inspection_checklist_id=:workplace_inspection_checklist_id AND assessment_id=:assessee_id";
                // $con1 = "UPDATE workplace_inspection_high_risk_1 SET status=? WHERE assessment_id=:assessee_id AND workplace_inspection_checklist_id=?";
                $update2 = $dbh->prepare($con2);
                $update2->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                $update2->bindParam(':high_risk_2', $high_risk_2, PDO::PARAM_STR);
                $update2->bindParam(':workplace_inspection_checklist_id', $workplace_inspection_checklist_id, PDO::PARAM_STR);
                $update2->execute();
            }
        }

        // query for data selection - workplace_inspection_high_risk_3
        $sql3 = "SELECT * FROM workplace_inspection_high_risk_3 WHERE workplace_inspection_checklist_id=:workplace_inspection_checklist_id AND assessment_id=:assessee_id";
        $query3 = $dbh->prepare($sql3);
        $query3->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
        $query3->bindParam(':workplace_inspection_checklist_id', $workplace_inspection_checklist_id, PDO::PARAM_STR);
        $query3->execute();
        $results3 = $query3->fetchAll(PDO::FETCH_OBJ);

        if ($query3->rowCount() > 0) {
            foreach ($results3 as $result3) {
                //query for updation - workplace_inspection_high_risk_3
                $con3 = "UPDATE workplace_inspection_high_risk_3 SET status=:high_risk_3, remarks=:remarks WHERE workplace_inspection_checklist_id=:workplace_inspection_checklist_id AND assessment_id=:assessee_id";
                $update3 = $dbh->prepare($con3);
                $update3->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                $update3->bindParam(':high_risk_3', $high_risk_3, PDO::PARAM_STR);
                $update3->bindParam(':remarks', $remarks, PDO::PARAM_STR);
                $update3->bindParam(':workplace_inspection_checklist_id', $workplace_inspection_checklist_id, PDO::PARAM_STR);
                $update3->execute();
            }
        }

        if ($update1 || $update2 || $update3) {
            $infos['workplace-insepection-high-risk-success'] = "Updated successfully";
        } else {
            $errors['workplace-insepection-high-risk-fail'] = "Something went wrong";
        }
    }
}

//if user click save-workplace-inspection-high-risk-from-history button in assessment workplace inspection from history page
if (isset($_POST['save-workplace-inspection-high-risk-from-history'])) {
    //getting the post values
    $assessee_id = $_POST['assessee_id'];
    $high_risk_c_score = $_POST['high_risk_c_score'];
    $high_risk_nc_score = $_POST['high_risk_nc_score'];
    $high_risk_na_score = $_POST['high_risk_na_score'];

    // query for data selection - workplace_inspection_subscore
    $sql = "SELECT * FROM workplace_inspection_subscore WHERE assessment_id=:assessee_id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);

    if ($query->rowCount() > 0) {
        foreach ($results as $result) {
            //query for updation
            $con = "UPDATE workplace_inspection_subscore SET high_risk_c_score=:high_risk_c_score, high_risk_nc_score=:high_risk_nc_score, high_risk_na_score=:high_risk_na_score WHERE assessment_id=:assessee_id";
            $update = $dbh->prepare($con);
            $update->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
            $update->bindParam(':high_risk_c_score', $high_risk_c_score, PDO::PARAM_STR);
            $update->bindParam(':high_risk_nc_score', $high_risk_nc_score, PDO::PARAM_STR);
            $update->bindParam(':high_risk_na_score', $high_risk_na_score, PDO::PARAM_STR);
            $update->execute();

            if ($update) {
                //query for data selection
                $conn = "SELECT * FROM assessment WHERE assessee_id=:assessee_id";
                $query1 = $dbh->prepare($conn);
                $query1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                $query1->execute();
                $result1 = $query1->fetchAll(PDO::FETCH_OBJ);

                if ($query1->rowCount() > 0) {
                    foreach ($result1 as $results1) {
                        $conn1 = "UPDATE assessment SET total_percentage=(((:high_risk_c_score + '$result->general_c_score') / (72 - (:high_risk_na_score + '$result->general_na_score')) * 60)+'$results1->document_check_percentage'+'$results1->personnel_interview_percentage'), 
                        workplace_inspection_percentage=((:high_risk_c_score + '$result->general_c_score') / (72 - (:high_risk_na_score + '$result->general_na_score')) * 60) WHERE assessee_id=:assessee_id";
                        $update1 = $dbh->prepare($conn1);
                        $update1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                        $update1->bindParam(':high_risk_c_score', $high_risk_c_score, PDO::PARAM_STR);
                        $update1->bindParam(':high_risk_na_score', $high_risk_na_score, PDO::PARAM_STR);
                        $update1->execute();
                    }
                }
            }
        }
    }

    foreach ($_POST['workplace_inspection_checklist_id'] as $workplace_inspection_checklist_id) {
        if (isset($_POST['highrisk1_' . $workplace_inspection_checklist_id])) {
            $highrisk1 = $_POST['highrisk1_' . $workplace_inspection_checklist_id];
            $high_risk_1 = implode(', ', $highrisk1);
        } else {
            $high_risk_1 = "";
        }
        if (isset($_POST['highrisk2_' . $workplace_inspection_checklist_id])) {
            $highrisk2 = $_POST['highrisk2_' . $workplace_inspection_checklist_id];
            $high_risk_2 = implode(', ', $highrisk2);
        } else {
            $high_risk_2 = "";
        }
        if (isset($_POST['highrisk3_' . $workplace_inspection_checklist_id])) {
            $highrisk3 = $_POST['highrisk3_' . $workplace_inspection_checklist_id];
            $high_risk_3 = implode(', ', $highrisk3);
        } else {
            $high_risk_3 = "";
        }
        if (isset($_POST['remarks_' . $workplace_inspection_checklist_id])) {
            $remarks = $_POST['remarks_' . $workplace_inspection_checklist_id];
        } else {
            $remarks = '';
        }

        // query for data selection - workplace_inspection_high_risk_1
        $sql1 = "SELECT * FROM workplace_inspection_high_risk_1 WHERE workplace_inspection_checklist_id=:workplace_inspection_checklist_id AND assessment_id=:assessee_id";
        $query1 = $dbh->prepare($sql1);
        $query1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
        $query1->bindParam(':workplace_inspection_checklist_id', $workplace_inspection_checklist_id, PDO::PARAM_STR);
        $query1->execute();
        $results1 = $query1->fetchAll(PDO::FETCH_OBJ);

        if ($query1->rowCount() > 0) {
            foreach ($results1 as $result1) {
                //query for updation - workplace_inspection_high_risk_1
                $con1 = "UPDATE workplace_inspection_high_risk_1 SET status=:high_risk_1 WHERE workplace_inspection_checklist_id=:workplace_inspection_checklist_id AND assessment_id=:assessee_id";
                $update1 = $dbh->prepare($con1);
                $update1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                $update1->bindParam(':high_risk_1', $high_risk_1, PDO::PARAM_STR);
                $update1->bindParam(':workplace_inspection_checklist_id', $workplace_inspection_checklist_id, PDO::PARAM_STR);
                $update1->execute();
            }
        }

        // query for data selection - workplace_inspection_high_risk_2
        $sql2 = "SELECT * FROM workplace_inspection_high_risk_2 WHERE workplace_inspection_checklist_id=:workplace_inspection_checklist_id AND assessment_id=:assessee_id";
        $query2 = $dbh->prepare($sql2);
        $query2->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
        $query2->bindParam(':workplace_inspection_checklist_id', $workplace_inspection_checklist_id, PDO::PARAM_STR);
        $query2->execute();
        $results2 = $query2->fetchAll(PDO::FETCH_OBJ);

        if ($query2->rowCount() > 0) {
            foreach ($results2 as $result2) {
                //query for updation - workplace_inspection_high_risk_2
                $con2 = "UPDATE workplace_inspection_high_risk_2 SET status=:high_risk_2 WHERE workplace_inspection_checklist_id=:workplace_inspection_checklist_id AND assessment_id=:assessee_id";
                // $con1 = "UPDATE workplace_inspection_high_risk_1 SET status=? WHERE assessment_id=:assessee_id AND workplace_inspection_checklist_id=?";
                $update2 = $dbh->prepare($con2);
                $update2->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                $update2->bindParam(':high_risk_2', $high_risk_2, PDO::PARAM_STR);
                $update2->bindParam(':workplace_inspection_checklist_id', $workplace_inspection_checklist_id, PDO::PARAM_STR);
                $update2->execute();
            }
        }

        // query for data selection - workplace_inspection_high_risk_3
        $sql3 = "SELECT * FROM workplace_inspection_high_risk_3 WHERE workplace_inspection_checklist_id=:workplace_inspection_checklist_id AND assessment_id=:assessee_id";
        $query3 = $dbh->prepare($sql3);
        $query3->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
        $query3->bindParam(':workplace_inspection_checklist_id', $workplace_inspection_checklist_id, PDO::PARAM_STR);
        $query3->execute();
        $results3 = $query3->fetchAll(PDO::FETCH_OBJ);

        if ($query3->rowCount() > 0) {
            foreach ($results3 as $result3) {
                //query for updation - workplace_inspection_high_risk_3
                $con3 = "UPDATE workplace_inspection_high_risk_3 SET status=:high_risk_3, remarks=:remarks WHERE workplace_inspection_checklist_id=:workplace_inspection_checklist_id AND assessment_id=:assessee_id";
                $update3 = $dbh->prepare($con3);
                $update3->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                $update3->bindParam(':high_risk_3', $high_risk_3, PDO::PARAM_STR);
                $update3->bindParam(':remarks', $remarks, PDO::PARAM_STR);
                $update3->bindParam(':workplace_inspection_checklist_id', $workplace_inspection_checklist_id, PDO::PARAM_STR);
                $update3->execute();
            }
        }

        if ($update1 || $update2 || $update3) {
            $infos['workplace-insepection-high-risk-success'] = "Updated successfully";
        } else {
            $errors['workplace-insepection-high-risk-fail'] = "Something went wrong";
        }
    }
}

//if user click save-personnel-interview-managerial button in assessment personnel interview page
if (isset($_POST['save-personnel-interview-managerial'])) {
    //getting the post value
    $assessee_id = $_POST['assessee_id'];
    $managerial_c_score = $_POST['managerial_c_score'];
    $managerial_nc_score = $_POST['managerial_nc_score'];
    $managerial_na_score = $_POST['managerial_na_score'];

    // query for data selection - personnel_interview_subscore
    $sql = "SELECT * FROM personnel_interview_subscore WHERE assessment_id=:assessee_id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);

    if ($query->rowCount() > 0) {
        foreach ($results as $result) {
            //query for updation
            $con = "UPDATE personnel_interview_subscore SET managerial_c_score=:managerial_c_score, managerial_nc_score=:managerial_nc_score, managerial_na_score=:managerial_na_score WHERE assessment_id=:assessee_id";
            $update = $dbh->prepare($con);
            $update->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
            $update->bindParam(':managerial_c_score', $managerial_c_score, PDO::PARAM_STR);
            $update->bindParam(':managerial_nc_score', $managerial_nc_score, PDO::PARAM_STR);
            $update->bindParam(':managerial_na_score', $managerial_na_score, PDO::PARAM_STR);
            $update->execute();

            if ($update) {
                //query for data selection
                $conn = "SELECT * FROM assessment WHERE assessee_id=:assessee_id";
                $query1 = $dbh->prepare($conn);
                $query1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                $query1->execute();
                $result1 = $query1->fetchAll(PDO::FETCH_OBJ);

                if ($query1->rowCount() > 0) {
                    foreach ($result1 as $results1) {
                        $conn1 = "UPDATE assessment SET total_percentage=(((:managerial_c_score+'$result->supervisory_c_score'+'$result->worker_1_c_score'+'$result->worker_2_c_score'+'$result->worker_3_c_score'+'$result->worker_4_c_score'+'$result->worker_5_c_score'+'$result->worker_6_c_score'+'$result->worker_7_c_score'+'$result->worker_8_c_score'+'$result->worker_9_c_score') / (186 - (:managerial_na_score+'$result->supervisory_na_score'+'$result->worker_1_na_score'+'$result->worker_2_na_score'+'$result->worker_3_na_score'+'$result->worker_4_na_score'+'$result->worker_5_na_score'+'$result->worker_6_na_score'+'$result->worker_7_na_score'+'$result->worker_8_na_score'+'$result->worker_9_na_score')) * 20)+'$results1->document_check_percentage'+'$results1->workplace_inspection_percentage'), 
                        personnel_interview_percentage=((:managerial_c_score+'$result->supervisory_c_score'+'$result->worker_1_c_score'+'$result->worker_2_c_score'+'$result->worker_3_c_score'+'$result->worker_4_c_score'+'$result->worker_5_c_score'+'$result->worker_6_c_score'+'$result->worker_7_c_score'+'$result->worker_8_c_score'+'$result->worker_9_c_score') / (186 - (:managerial_na_score+'$result->supervisory_na_score'+'$result->worker_1_na_score'+'$result->worker_2_na_score'+'$result->worker_3_na_score'+'$result->worker_4_na_score'+'$result->worker_5_na_score'+'$result->worker_6_na_score'+'$result->worker_7_na_score'+'$result->worker_8_na_score'+'$result->worker_9_na_score')) * 20) WHERE assessee_id=:assessee_id";
                        $update1 = $dbh->prepare($conn1);
                        $update1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                        $update1->bindParam(':managerial_c_score', $managerial_c_score, PDO::PARAM_STR);
                        $update1->bindParam(':managerial_na_score', $managerial_na_score, PDO::PARAM_STR);
                        $update1->execute();
                    }
                }
            }
        }
    }

    foreach ($_POST['personnel_interview_checklist_id'] as $personnel_interview_checklist_id) {
        if (isset($_POST['managerial_' . $personnel_interview_checklist_id])) {
            $_managerial = $_POST['managerial_' . $personnel_interview_checklist_id];
            $managerial = implode(', ', $_managerial);
        } else {
            $managerial = '';
        }
        if (isset($_POST['remarks_' . $personnel_interview_checklist_id])) {
            $remarks = $_POST['remarks_' . $personnel_interview_checklist_id];
        } else {
            $remarks = '';
        }

        // query for data selection - personnel_interview_managerial
        $sql1 = "SELECT * FROM personnel_interview_managerial WHERE personnel_interview_checklist_id=:personnel_interview_checklist_id AND assessment_id=:assessee_id";
        $query1 = $dbh->prepare($sql1);
        $query1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
        $query1->bindParam(':personnel_interview_checklist_id', $personnel_interview_checklist_id, PDO::PARAM_STR);
        $query1->execute();
        $results1 = $query1->fetchAll(PDO::FETCH_OBJ);

        if ($query1->rowCount() > 0) {
            foreach ($results1 as $result1) {
                //query for updation - workplace_inspection_high_risk_1
                $con1 = "UPDATE personnel_interview_managerial SET status=:managerial, remarks=:remarks WHERE personnel_interview_checklist_id=:personnel_interview_checklist_id AND assessment_id=:assessee_id";
                $update1 = $dbh->prepare($con1);
                $update1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                $update1->bindParam(':managerial', $managerial, PDO::PARAM_STR);
                $update1->bindParam(':remarks', $remarks, PDO::PARAM_STR);
                $update1->bindParam(':personnel_interview_checklist_id', $personnel_interview_checklist_id, PDO::PARAM_STR);
                $update1->execute();

                if ($update1) {
                    $infos['personnel-interview-managerial-update-success'] = "Updated successfully";
                } else {
                    $errors['personnel-interview-managerial-update-fail'] = "Something went wrong";
                }
            }
            // header("location: assessment-workplace-inspection.php?assessee_id=" . $assessee_id . "&info=" . $info);
        }
    }
}

//if user click save-personnel-interview-managerial-from-history button in assessment personnel interview from history page
if (isset($_POST['save-personnel-interview-managerial-from-history'])) {
    //getting the post value
    $assessee_id = $_POST['assessee_id'];
    $managerial_c_score = $_POST['managerial_c_score'];
    $managerial_nc_score = $_POST['managerial_nc_score'];
    $managerial_na_score = $_POST['managerial_na_score'];

    // query for data selection - personnel_interview_subscore
    $sql = "SELECT * FROM personnel_interview_subscore WHERE assessment_id=:assessee_id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);

    if ($query->rowCount() > 0) {
        foreach ($results as $result) {
            //query for updation
            $con = "UPDATE personnel_interview_subscore SET managerial_c_score=:managerial_c_score, managerial_nc_score=:managerial_nc_score, managerial_na_score=:managerial_na_score WHERE assessment_id=:assessee_id";
            $update = $dbh->prepare($con);
            $update->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
            $update->bindParam(':managerial_c_score', $managerial_c_score, PDO::PARAM_STR);
            $update->bindParam(':managerial_nc_score', $managerial_nc_score, PDO::PARAM_STR);
            $update->bindParam(':managerial_na_score', $managerial_na_score, PDO::PARAM_STR);
            $update->execute();

            if ($update) {
                //query for data selection
                $conn = "SELECT * FROM assessment WHERE assessee_id=:assessee_id";
                $query1 = $dbh->prepare($conn);
                $query1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                $query1->execute();
                $result1 = $query1->fetchAll(PDO::FETCH_OBJ);

                if ($query1->rowCount() > 0) {
                    foreach ($result1 as $results1) {
                        $conn1 = "UPDATE assessment SET total_percentage=(((:managerial_c_score+'$result->supervisory_c_score'+'$result->worker_1_c_score'+'$result->worker_2_c_score'+'$result->worker_3_c_score'+'$result->worker_4_c_score'+'$result->worker_5_c_score'+'$result->worker_6_c_score'+'$result->worker_7_c_score'+'$result->worker_8_c_score'+'$result->worker_9_c_score') / (186 - (:managerial_na_score+'$result->supervisory_na_score'+'$result->worker_1_na_score'+'$result->worker_2_na_score'+'$result->worker_3_na_score'+'$result->worker_4_na_score'+'$result->worker_5_na_score'+'$result->worker_6_na_score'+'$result->worker_7_na_score'+'$result->worker_8_na_score'+'$result->worker_9_na_score')) * 20)+'$results1->document_check_percentage'+'$results1->workplace_inspection_percentage'), 
                        personnel_interview_percentage=((:managerial_c_score+'$result->supervisory_c_score'+'$result->worker_1_c_score'+'$result->worker_2_c_score'+'$result->worker_3_c_score'+'$result->worker_4_c_score'+'$result->worker_5_c_score'+'$result->worker_6_c_score'+'$result->worker_7_c_score'+'$result->worker_8_c_score'+'$result->worker_9_c_score') / (186 - (:managerial_na_score+'$result->supervisory_na_score'+'$result->worker_1_na_score'+'$result->worker_2_na_score'+'$result->worker_3_na_score'+'$result->worker_4_na_score'+'$result->worker_5_na_score'+'$result->worker_6_na_score'+'$result->worker_7_na_score'+'$result->worker_8_na_score'+'$result->worker_9_na_score')) * 20) WHERE assessee_id=:assessee_id";
                        $update1 = $dbh->prepare($conn1);
                        $update1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                        $update1->bindParam(':managerial_c_score', $managerial_c_score, PDO::PARAM_STR);
                        $update1->bindParam(':managerial_na_score', $managerial_na_score, PDO::PARAM_STR);
                        $update1->execute();
                    }
                }
            }
        }
    }

    foreach ($_POST['personnel_interview_checklist_id'] as $personnel_interview_checklist_id) {
        if (isset($_POST['managerial_' . $personnel_interview_checklist_id])) {
            $_managerial = $_POST['managerial_' . $personnel_interview_checklist_id];
            $managerial = implode(', ', $_managerial);
        } else {
            $managerial = '';
        }
        if (isset($_POST['remarks_' . $personnel_interview_checklist_id])) {
            $remarks = $_POST['remarks_' . $personnel_interview_checklist_id];
        } else {
            $remarks = '';
        }

        // query for data selection - personnel_interview_managerial
        $sql1 = "SELECT * FROM personnel_interview_managerial WHERE personnel_interview_checklist_id=:personnel_interview_checklist_id AND assessment_id=:assessee_id";
        $query1 = $dbh->prepare($sql1);
        $query1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
        $query1->bindParam(':personnel_interview_checklist_id', $personnel_interview_checklist_id, PDO::PARAM_STR);
        $query1->execute();
        $results1 = $query1->fetchAll(PDO::FETCH_OBJ);

        if ($query1->rowCount() > 0) {
            foreach ($results1 as $result1) {
                //query for updation - workplace_inspection_high_risk_1
                $con1 = "UPDATE personnel_interview_managerial SET status=:managerial, remarks=:remarks WHERE personnel_interview_checklist_id=:personnel_interview_checklist_id AND assessment_id=:assessee_id";
                $update1 = $dbh->prepare($con1);
                $update1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                $update1->bindParam(':managerial', $managerial, PDO::PARAM_STR);
                $update1->bindParam(':remarks', $remarks, PDO::PARAM_STR);
                $update1->bindParam(':personnel_interview_checklist_id', $personnel_interview_checklist_id, PDO::PARAM_STR);
                $update1->execute();

                if ($update1) {
                    $infos['personnel-interview-managerial-update-success'] = "Updated successfully";
                } else {
                    $errors['personnel-interview-managerial-update-fail'] = "Something went wrong";
                }
            }
            // header("location: assessment-workplace-inspection.php?assessee_id=" . $assessee_id . "&info=" . $info);
        }
    }
}

//if user click save-personnel-interview-supervisory button in assessment personnel interview page
if (isset($_POST['save-personnel-interview-supervisory'])) {
    //getting the post values
    $assessee_id = $_POST['assessee_id'];
    $supervisory_c_score = $_POST['supervisory_c_score'];
    $supervisory_nc_score = $_POST['supervisory_nc_score'];
    $supervisory_na_score = $_POST['supervisory_na_score'];

    // query for data selection - personnel_interview_subscore
    $sql = "SELECT * FROM personnel_interview_subscore WHERE assessment_id=:assessee_id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);

    if ($query->rowCount() > 0) {
        foreach ($results as $result) {
            //query for updation
            $con = "UPDATE personnel_interview_subscore SET supervisory_c_score=:supervisory_c_score, supervisory_nc_score=:supervisory_nc_score, supervisory_na_score=:supervisory_na_score WHERE assessment_id=:assessee_id";
            $update = $dbh->prepare($con);
            $update->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
            $update->bindParam(':supervisory_c_score', $supervisory_c_score, PDO::PARAM_STR);
            $update->bindParam(':supervisory_nc_score', $supervisory_nc_score, PDO::PARAM_STR);
            $update->bindParam(':supervisory_na_score', $supervisory_na_score, PDO::PARAM_STR);
            $update->execute();

            if ($update) {
                //query for data selection
                $conn = "SELECT * FROM assessment WHERE assessee_id=:assessee_id";
                $query1 = $dbh->prepare($conn);
                $query1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                $query1->execute();
                $result1 = $query1->fetchAll(PDO::FETCH_OBJ);

                if ($query1->rowCount() > 0) {
                    foreach ($result1 as $results1) {
                        $conn1 = "UPDATE assessment SET total_percentage=((('$result->managerial_c_score'+:supervisory_c_score+'$result->worker_1_c_score'+'$result->worker_2_c_score'+'$result->worker_3_c_score'+'$result->worker_4_c_score'+'$result->worker_5_c_score'+'$result->worker_6_c_score'+'$result->worker_7_c_score'+'$result->worker_8_c_score'+'$result->worker_9_c_score') / (186 - ('$result->managerial_na_score'+:supervisory_na_score+'$result->worker_1_na_score'+'$result->worker_2_na_score'+'$result->worker_3_na_score'+'$result->worker_4_na_score'+'$result->worker_5_na_score'+'$result->worker_6_na_score'+'$result->worker_7_na_score'+'$result->worker_8_na_score'+'$result->worker_9_na_score')) * 20)+'$results1->document_check_percentage'+'$results1->workplace_inspection_percentage'), 
                        personnel_interview_percentage=(('$result->managerial_c_score'+:supervisory_c_score+'$result->worker_1_c_score'+'$result->worker_2_c_score'+'$result->worker_3_c_score'+'$result->worker_4_c_score'+'$result->worker_5_c_score'+'$result->worker_6_c_score'+'$result->worker_7_c_score'+'$result->worker_8_c_score'+'$result->worker_9_c_score') / (186 - ('$result->managerial_na_score'+:supervisory_na_score+'$result->worker_1_na_score'+'$result->worker_2_na_score'+'$result->worker_3_na_score'+'$result->worker_4_na_score'+'$result->worker_5_na_score'+'$result->worker_6_na_score'+'$result->worker_7_na_score'+'$result->worker_8_na_score'+'$result->worker_9_na_score')) * 20) WHERE assessee_id=:assessee_id";
                        $update1 = $dbh->prepare($conn1);
                        $update1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                        $update1->bindParam(':supervisory_c_score', $supervisory_c_score, PDO::PARAM_STR);
                        $update1->bindParam(':supervisory_na_score', $supervisory_na_score, PDO::PARAM_STR);
                        $update1->execute();
                    }
                }
            }
        }
    }

    foreach ($_POST['personnel_interview_checklist_id'] as $personnel_interview_checklist_id) {
        if (isset($_POST['supervisory1_' . $personnel_interview_checklist_id])) {
            $supervisory1 = $_POST['supervisory1_' . $personnel_interview_checklist_id];
            $supervisory_1 = implode(', ', $supervisory1);
        } else {
            $supervisory_1 = "";
        }
        if (isset($_POST['supervisory2_' . $personnel_interview_checklist_id])) {
            $supervisory2 = $_POST['supervisory2_' . $personnel_interview_checklist_id];
            $supervisory_2 = implode(', ', $supervisory2);
        } else {
            $supervisory_2 = "";
        }
        if (isset($_POST['supervisory3_' . $personnel_interview_checklist_id])) {
            $supervisory3 = $_POST['supervisory3_' . $personnel_interview_checklist_id];
            $supervisory_3 = implode(', ', $supervisory3);
        } else {
            $supervisory_3 = "";
        }
        if (isset($_POST['remarks_' . $personnel_interview_checklist_id])) {
            $remarks = $_POST['remarks_' . $personnel_interview_checklist_id];
        } else {
            $remarks = '';
        }

        // query for data selection - personnel_interview_supervisory_1
        $sql1 = "SELECT * FROM personnel_interview_supervisory_1 WHERE personnel_interview_checklist_id=:personnel_interview_checklist_id AND assessment_id=:assessee_id";
        $query1 = $dbh->prepare($sql1);
        $query1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
        $query1->bindParam(':personnel_interview_checklist_id', $personnel_interview_checklist_id, PDO::PARAM_STR);
        $query1->execute();
        $results1 = $query1->fetchAll(PDO::FETCH_OBJ);

        if ($query1->rowCount() > 0) {
            foreach ($results1 as $result1) {
                //query for updation - personnel_interview_supervisory_1
                $con1 = "UPDATE personnel_interview_supervisory_1 SET status=:supervisory_1 WHERE personnel_interview_checklist_id=:personnel_interview_checklist_id AND assessment_id=:assessee_id";
                $update1 = $dbh->prepare($con1);
                $update1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                $update1->bindParam(':supervisory_1', $supervisory_1, PDO::PARAM_STR);
                $update1->bindParam(':personnel_interview_checklist_id', $personnel_interview_checklist_id, PDO::PARAM_STR);
                $update1->execute();
            }
        }

        // query for data selection - personnel_interview_supervisory_2
        $sql2 = "SELECT * FROM personnel_interview_supervisory_2 WHERE personnel_interview_checklist_id=:personnel_interview_checklist_id AND assessment_id=:assessee_id";
        $query2 = $dbh->prepare($sql2);
        $query2->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
        $query2->bindParam(':personnel_interview_checklist_id', $personnel_interview_checklist_id, PDO::PARAM_STR);
        $query2->execute();
        $results2 = $query2->fetchAll(PDO::FETCH_OBJ);

        if ($query2->rowCount() > 0) {
            foreach ($results2 as $result2) {
                //query for updation - personnel_interview_supervisory_2
                $con2 = "UPDATE personnel_interview_supervisory_2 SET status=:supervisory_2 WHERE personnel_interview_checklist_id=:personnel_interview_checklist_id AND assessment_id=:assessee_id";
                $update2 = $dbh->prepare($con2);
                $update2->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                $update2->bindParam(':supervisory_2', $supervisory_2, PDO::PARAM_STR);
                $update2->bindParam(':personnel_interview_checklist_id', $personnel_interview_checklist_id, PDO::PARAM_STR);
                $update2->execute();
            }
        }

        // query for data selection - personnel_interview_supervisory_3
        $sql3 = "SELECT * FROM personnel_interview_supervisory_3 WHERE personnel_interview_checklist_id=:personnel_interview_checklist_id AND assessment_id=:assessee_id";
        $query3 = $dbh->prepare($sql3);
        $query3->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
        $query3->bindParam(':personnel_interview_checklist_id', $personnel_interview_checklist_id, PDO::PARAM_STR);
        $query3->execute();
        $results3 = $query3->fetchAll(PDO::FETCH_OBJ);

        if ($query3->rowCount() > 0) {
            foreach ($results3 as $result3) {
                //query for updation - personnel_interview_supervisory_3
                $con3 = "UPDATE personnel_interview_supervisory_3 SET status=:supervisory_3, remarks=:remarks WHERE personnel_interview_checklist_id=:personnel_interview_checklist_id AND assessment_id=:assessee_id";
                $update3 = $dbh->prepare($con3);
                $update3->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                $update3->bindParam(':supervisory_3', $supervisory_3, PDO::PARAM_STR);
                $update3->bindParam(':remarks', $remarks, PDO::PARAM_STR);
                $update3->bindParam(':personnel_interview_checklist_id', $personnel_interview_checklist_id, PDO::PARAM_STR);
                $update3->execute();
            }
        }

        if ($update1 || $update2 || $update3) {
            $infos['personnel-interview-supervisory-update-success'] = "Updated successfully";
        } else {
            $errors['personnel-interview-supervisory-update-fail'] = "Something went wrong";
        }
    }
}

//if user click save-personnel-interview-supervisory-from-history button in assessment personnel interview from history page
if (isset($_POST['save-personnel-interview-supervisory-from-history'])) {
    //getting the post values
    $assessee_id = $_POST['assessee_id'];
    $supervisory_c_score = $_POST['supervisory_c_score'];
    $supervisory_nc_score = $_POST['supervisory_nc_score'];
    $supervisory_na_score = $_POST['supervisory_na_score'];

    // query for data selection - personnel_interview_subscore
    $sql = "SELECT * FROM personnel_interview_subscore WHERE assessment_id=:assessee_id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);

    if ($query->rowCount() > 0) {
        foreach ($results as $result) {
            //query for updation
            $con = "UPDATE personnel_interview_subscore SET supervisory_c_score=:supervisory_c_score, supervisory_nc_score=:supervisory_nc_score, supervisory_na_score=:supervisory_na_score WHERE assessment_id=:assessee_id";
            $update = $dbh->prepare($con);
            $update->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
            $update->bindParam(':supervisory_c_score', $supervisory_c_score, PDO::PARAM_STR);
            $update->bindParam(':supervisory_nc_score', $supervisory_nc_score, PDO::PARAM_STR);
            $update->bindParam(':supervisory_na_score', $supervisory_na_score, PDO::PARAM_STR);
            $update->execute();

            if ($update) {
                //query for data selection
                $conn = "SELECT * FROM assessment WHERE assessee_id=:assessee_id";
                $query1 = $dbh->prepare($conn);
                $query1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                $query1->execute();
                $result1 = $query1->fetchAll(PDO::FETCH_OBJ);

                if ($query1->rowCount() > 0) {
                    foreach ($result1 as $results1) {
                        $conn1 = "UPDATE assessment SET total_percentage=((('$result->managerial_c_score'+:supervisory_c_score+'$result->worker_1_c_score'+'$result->worker_2_c_score'+'$result->worker_3_c_score'+'$result->worker_4_c_score'+'$result->worker_5_c_score'+'$result->worker_6_c_score'+'$result->worker_7_c_score'+'$result->worker_8_c_score'+'$result->worker_9_c_score') / (186 - ('$result->managerial_na_score'+:supervisory_na_score+'$result->worker_1_na_score'+'$result->worker_2_na_score'+'$result->worker_3_na_score'+'$result->worker_4_na_score'+'$result->worker_5_na_score'+'$result->worker_6_na_score'+'$result->worker_7_na_score'+'$result->worker_8_na_score'+'$result->worker_9_na_score')) * 20)+'$results1->document_check_percentage'+'$results1->workplace_inspection_percentage'), 
                        personnel_interview_percentage=(('$result->managerial_c_score'+:supervisory_c_score+'$result->worker_1_c_score'+'$result->worker_2_c_score'+'$result->worker_3_c_score'+'$result->worker_4_c_score'+'$result->worker_5_c_score'+'$result->worker_6_c_score'+'$result->worker_7_c_score'+'$result->worker_8_c_score'+'$result->worker_9_c_score') / (186 - ('$result->managerial_na_score'+:supervisory_na_score+'$result->worker_1_na_score'+'$result->worker_2_na_score'+'$result->worker_3_na_score'+'$result->worker_4_na_score'+'$result->worker_5_na_score'+'$result->worker_6_na_score'+'$result->worker_7_na_score'+'$result->worker_8_na_score'+'$result->worker_9_na_score')) * 20) WHERE assessee_id=:assessee_id";
                        $update1 = $dbh->prepare($conn1);
                        $update1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                        $update1->bindParam(':supervisory_c_score', $supervisory_c_score, PDO::PARAM_STR);
                        $update1->bindParam(':supervisory_na_score', $supervisory_na_score, PDO::PARAM_STR);
                        $update1->execute();
                    }
                }
            }
        }
    }

    foreach ($_POST['personnel_interview_checklist_id'] as $personnel_interview_checklist_id) {
        if (isset($_POST['supervisory1_' . $personnel_interview_checklist_id])) {
            $supervisory1 = $_POST['supervisory1_' . $personnel_interview_checklist_id];
            $supervisory_1 = implode(', ', $supervisory1);
        } else {
            $supervisory_1 = "";
        }
        if (isset($_POST['supervisory2_' . $personnel_interview_checklist_id])) {
            $supervisory2 = $_POST['supervisory2_' . $personnel_interview_checklist_id];
            $supervisory_2 = implode(', ', $supervisory2);
        } else {
            $supervisory_2 = "";
        }
        if (isset($_POST['supervisory3_' . $personnel_interview_checklist_id])) {
            $supervisory3 = $_POST['supervisory3_' . $personnel_interview_checklist_id];
            $supervisory_3 = implode(', ', $supervisory3);
        } else {
            $supervisory_3 = "";
        }
        if (isset($_POST['remarks_' . $personnel_interview_checklist_id])) {
            $remarks = $_POST['remarks_' . $personnel_interview_checklist_id];
        } else {
            $remarks = '';
        }

        // query for data selection - personnel_interview_supervisory_1
        $sql1 = "SELECT * FROM personnel_interview_supervisory_1 WHERE personnel_interview_checklist_id=:personnel_interview_checklist_id AND assessment_id=:assessee_id";
        $query1 = $dbh->prepare($sql1);
        $query1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
        $query1->bindParam(':personnel_interview_checklist_id', $personnel_interview_checklist_id, PDO::PARAM_STR);
        $query1->execute();
        $results1 = $query1->fetchAll(PDO::FETCH_OBJ);

        if ($query1->rowCount() > 0) {
            foreach ($results1 as $result1) {
                //query for updation - personnel_interview_supervisory_1
                $con1 = "UPDATE personnel_interview_supervisory_1 SET status=:supervisory_1 WHERE personnel_interview_checklist_id=:personnel_interview_checklist_id AND assessment_id=:assessee_id";
                $update1 = $dbh->prepare($con1);
                $update1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                $update1->bindParam(':supervisory_1', $supervisory_1, PDO::PARAM_STR);
                $update1->bindParam(':personnel_interview_checklist_id', $personnel_interview_checklist_id, PDO::PARAM_STR);
                $update1->execute();
            }
        }

        // query for data selection - personnel_interview_supervisory_2
        $sql2 = "SELECT * FROM personnel_interview_supervisory_2 WHERE personnel_interview_checklist_id=:personnel_interview_checklist_id AND assessment_id=:assessee_id";
        $query2 = $dbh->prepare($sql2);
        $query2->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
        $query2->bindParam(':personnel_interview_checklist_id', $personnel_interview_checklist_id, PDO::PARAM_STR);
        $query2->execute();
        $results2 = $query2->fetchAll(PDO::FETCH_OBJ);

        if ($query2->rowCount() > 0) {
            foreach ($results2 as $result2) {
                //query for updation - personnel_interview_supervisory_2
                $con2 = "UPDATE personnel_interview_supervisory_2 SET status=:supervisory_2 WHERE personnel_interview_checklist_id=:personnel_interview_checklist_id AND assessment_id=:assessee_id";
                $update2 = $dbh->prepare($con2);
                $update2->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                $update2->bindParam(':supervisory_2', $supervisory_2, PDO::PARAM_STR);
                $update2->bindParam(':personnel_interview_checklist_id', $personnel_interview_checklist_id, PDO::PARAM_STR);
                $update2->execute();
            }
        }

        // query for data selection - personnel_interview_supervisory_3
        $sql3 = "SELECT * FROM personnel_interview_supervisory_3 WHERE personnel_interview_checklist_id=:personnel_interview_checklist_id AND assessment_id=:assessee_id";
        $query3 = $dbh->prepare($sql3);
        $query3->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
        $query3->bindParam(':personnel_interview_checklist_id', $personnel_interview_checklist_id, PDO::PARAM_STR);
        $query3->execute();
        $results3 = $query3->fetchAll(PDO::FETCH_OBJ);

        if ($query3->rowCount() > 0) {
            foreach ($results3 as $result3) {
                //query for updation - personnel_interview_supervisory_3
                $con3 = "UPDATE personnel_interview_supervisory_3 SET status=:supervisory_3, remarks=:remarks WHERE personnel_interview_checklist_id=:personnel_interview_checklist_id AND assessment_id=:assessee_id";
                $update3 = $dbh->prepare($con3);
                $update3->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                $update3->bindParam(':supervisory_3', $supervisory_3, PDO::PARAM_STR);
                $update3->bindParam(':remarks', $remarks, PDO::PARAM_STR);
                $update3->bindParam(':personnel_interview_checklist_id', $personnel_interview_checklist_id, PDO::PARAM_STR);
                $update3->execute();
            }
        }

        if ($update1 || $update2 || $update3) {
            $infos['personnel-interview-supervisory-update-success'] = "Updated successfully";
        } else {
            $errors['personnel-interview-supervisory-update-fail'] = "Something went wrong";
        }
    }
}

//if user click save-personnel-interview-worker-1 button in assessment personnel interview page
if (isset($_POST['save-personnel-interview-worker-1'])) {
    //getting the post value
    $assessee_id = $_POST['assessee_id'];
    $worker_1_c_score = $_POST['worker_1_c_score'];
    $worker_1_nc_score = $_POST['worker_1_nc_score'];
    $worker_1_na_score = $_POST['worker_1_na_score'];

    // query for data selection - personnel_interview_subscore
    $sql = "SELECT * FROM personnel_interview_subscore WHERE assessment_id=:assessee_id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);

    if ($query->rowCount() > 0) {
        foreach ($results as $result) {
            //query for updation
            $con = "UPDATE personnel_interview_subscore SET worker_1_c_score=:worker_1_c_score, worker_1_nc_score=:worker_1_nc_score, worker_1_na_score=:worker_1_na_score WHERE assessment_id=:assessee_id";
            $update = $dbh->prepare($con);
            $update->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
            $update->bindParam(':worker_1_c_score', $worker_1_c_score, PDO::PARAM_STR);
            $update->bindParam(':worker_1_nc_score', $worker_1_nc_score, PDO::PARAM_STR);
            $update->bindParam(':worker_1_na_score', $worker_1_na_score, PDO::PARAM_STR);
            $update->execute();

            if ($update) {
                //query for data selection
                $conn = "SELECT * FROM assessment WHERE assessee_id=:assessee_id";
                $query1 = $dbh->prepare($conn);
                $query1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                $query1->execute();
                $result1 = $query1->fetchAll(PDO::FETCH_OBJ);

                if ($query1->rowCount() > 0) {
                    foreach ($result1 as $results1) {
                        $conn1 = "UPDATE assessment SET total_percentage=((('$result->managerial_c_score'+'$result->supervisory_c_score'+:worker_1_c_score+'$result->worker_2_c_score'+'$result->worker_3_c_score'+'$result->worker_4_c_score'+'$result->worker_5_c_score'+'$result->worker_6_c_score'+'$result->worker_7_c_score'+'$result->worker_8_c_score'+'$result->worker_9_c_score') / (186 - ('$result->managerial_na_score'+'$result->supervisory_na_score'+:worker_1_na_score+'$result->worker_2_na_score'+'$result->worker_3_na_score'+'$result->worker_4_na_score'+'$result->worker_5_na_score'+'$result->worker_6_na_score'+'$result->worker_7_na_score'+'$result->worker_8_na_score'+'$result->worker_9_na_score')) * 20)+'$results1->document_check_percentage'+'$results1->workplace_inspection_percentage'), 
                        personnel_interview_percentage=(('$result->managerial_c_score'+'$result->supervisory_c_score'+:worker_1_c_score+'$result->worker_2_c_score'+'$result->worker_3_c_score'+'$result->worker_4_c_score'+'$result->worker_5_c_score'+'$result->worker_6_c_score'+'$result->worker_7_c_score'+'$result->worker_8_c_score'+'$result->worker_9_c_score') / (186 - ('$result->managerial_na_score'+'$result->supervisory_na_score'+:worker_1_na_score+'$result->worker_2_na_score'+'$result->worker_3_na_score'+'$result->worker_4_na_score'+'$result->worker_5_na_score'+'$result->worker_6_na_score'+'$result->worker_7_na_score'+'$result->worker_8_na_score'+'$result->worker_9_na_score')) * 20) WHERE assessee_id=:assessee_id";
                        $update1 = $dbh->prepare($conn1);
                        $update1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                        $update1->bindParam(':worker_1_c_score', $worker_1_c_score, PDO::PARAM_STR);
                        $update1->bindParam(':worker_1_na_score', $worker_1_na_score, PDO::PARAM_STR);
                        $update1->execute();
                    }
                }
            }
        }
    }

    foreach ($_POST['personnel_interview_checklist_id'] as $personnel_interview_checklist_id) {
        if (isset($_POST['worker1_' . $personnel_interview_checklist_id])) {
            $worker_1 = $_POST['worker1_' . $personnel_interview_checklist_id];
            $worker1 = implode(', ', $worker_1);
        } else {
            $worker1 = '';
        }
        if (isset($_POST['remarks_' . $personnel_interview_checklist_id])) {
            $remarks = $_POST['remarks_' . $personnel_interview_checklist_id];
        } else {
            $remarks = '';
        }

        // query for data selection - personnel_interview_worker_1
        $sql1 = "SELECT * FROM personnel_interview_worker_1 WHERE personnel_interview_checklist_id=:personnel_interview_checklist_id AND assessment_id=:assessee_id";
        $query1 = $dbh->prepare($sql1);
        $query1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
        $query1->bindParam(':personnel_interview_checklist_id', $personnel_interview_checklist_id, PDO::PARAM_STR);
        $query1->execute();
        $results1 = $query1->fetchAll(PDO::FETCH_OBJ);

        if ($query1->rowCount() > 0) {
            foreach ($results1 as $result1) {
                //query for updation - personnel_interview_worker_1
                $con1 = "UPDATE personnel_interview_worker_1 SET status=:worker1, remarks=:remarks WHERE personnel_interview_checklist_id=:personnel_interview_checklist_id AND assessment_id=:assessee_id";
                $update1 = $dbh->prepare($con1);
                $update1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                $update1->bindParam(':worker1', $worker1, PDO::PARAM_STR);
                $update1->bindParam(':remarks', $remarks, PDO::PARAM_STR);
                $update1->bindParam(':personnel_interview_checklist_id', $personnel_interview_checklist_id, PDO::PARAM_STR);
                $update1->execute();

                if ($update1) {
                    $infos['personnel-interview-worker-1-update-success'] = "Updated successfully";
                } else {
                    $errors['personnel-interview-worker-1-update-fail'] = "Something went wrong";
                }
            }
            // header("location: assessment-workplace-inspection.php?assessee_id=" . $assessee_id . "&info=" . $info);
        }
    }
}

//if user click save-personnel-interview-worker-1-from-history button in assessment personnel interview from history page
if (isset($_POST['save-personnel-interview-worker-1-from-history'])) {
    //getting the post value
    $assessee_id = $_POST['assessee_id'];
    $worker_1_c_score = $_POST['worker_1_c_score'];
    $worker_1_nc_score = $_POST['worker_1_nc_score'];
    $worker_1_na_score = $_POST['worker_1_na_score'];

    // query for data selection - personnel_interview_subscore
    $sql = "SELECT * FROM personnel_interview_subscore WHERE assessment_id=:assessee_id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);

    if ($query->rowCount() > 0) {
        foreach ($results as $result) {
            //query for updation
            $con = "UPDATE personnel_interview_subscore SET worker_1_c_score=:worker_1_c_score, worker_1_nc_score=:worker_1_nc_score, worker_1_na_score=:worker_1_na_score WHERE assessment_id=:assessee_id";
            $update = $dbh->prepare($con);
            $update->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
            $update->bindParam(':worker_1_c_score', $worker_1_c_score, PDO::PARAM_STR);
            $update->bindParam(':worker_1_nc_score', $worker_1_nc_score, PDO::PARAM_STR);
            $update->bindParam(':worker_1_na_score', $worker_1_na_score, PDO::PARAM_STR);
            $update->execute();

            if ($update) {
                //query for data selection
                $conn = "SELECT * FROM assessment WHERE assessee_id=:assessee_id";
                $query1 = $dbh->prepare($conn);
                $query1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                $query1->execute();
                $result1 = $query1->fetchAll(PDO::FETCH_OBJ);

                if ($query1->rowCount() > 0) {
                    foreach ($result1 as $results1) {
                        $conn1 = "UPDATE assessment SET total_percentage=((('$result->managerial_c_score'+'$result->supervisory_c_score'+:worker_1_c_score+'$result->worker_2_c_score'+'$result->worker_3_c_score'+'$result->worker_4_c_score'+'$result->worker_5_c_score'+'$result->worker_6_c_score'+'$result->worker_7_c_score'+'$result->worker_8_c_score'+'$result->worker_9_c_score') / (186 - ('$result->managerial_na_score'+'$result->supervisory_na_score'+:worker_1_na_score+'$result->worker_2_na_score'+'$result->worker_3_na_score'+'$result->worker_4_na_score'+'$result->worker_5_na_score'+'$result->worker_6_na_score'+'$result->worker_7_na_score'+'$result->worker_8_na_score'+'$result->worker_9_na_score')) * 20)+'$results1->document_check_percentage'+'$results1->workplace_inspection_percentage'), 
                        personnel_interview_percentage=(('$result->managerial_c_score'+'$result->supervisory_c_score'+:worker_1_c_score+'$result->worker_2_c_score'+'$result->worker_3_c_score'+'$result->worker_4_c_score'+'$result->worker_5_c_score'+'$result->worker_6_c_score'+'$result->worker_7_c_score'+'$result->worker_8_c_score'+'$result->worker_9_c_score') / (186 - ('$result->managerial_na_score'+'$result->supervisory_na_score'+:worker_1_na_score+'$result->worker_2_na_score'+'$result->worker_3_na_score'+'$result->worker_4_na_score'+'$result->worker_5_na_score'+'$result->worker_6_na_score'+'$result->worker_7_na_score'+'$result->worker_8_na_score'+'$result->worker_9_na_score')) * 20) WHERE assessee_id=:assessee_id";
                        $update1 = $dbh->prepare($conn1);
                        $update1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                        $update1->bindParam(':worker_1_c_score', $worker_1_c_score, PDO::PARAM_STR);
                        $update1->bindParam(':worker_1_na_score', $worker_1_na_score, PDO::PARAM_STR);
                        $update1->execute();
                    }
                }
            }
        }
    }

    foreach ($_POST['personnel_interview_checklist_id'] as $personnel_interview_checklist_id) {
        if (isset($_POST['worker1_' . $personnel_interview_checklist_id])) {
            $worker_1 = $_POST['worker1_' . $personnel_interview_checklist_id];
            $worker1 = implode(', ', $worker_1);
        } else {
            $worker1 = '';
        }
        if (isset($_POST['remarks_' . $personnel_interview_checklist_id])) {
            $remarks = $_POST['remarks_' . $personnel_interview_checklist_id];
        } else {
            $remarks = '';
        }

        // query for data selection - personnel_interview_worker_1
        $sql1 = "SELECT * FROM personnel_interview_worker_1 WHERE personnel_interview_checklist_id=:personnel_interview_checklist_id AND assessment_id=:assessee_id";
        $query1 = $dbh->prepare($sql1);
        $query1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
        $query1->bindParam(':personnel_interview_checklist_id', $personnel_interview_checklist_id, PDO::PARAM_STR);
        $query1->execute();
        $results1 = $query1->fetchAll(PDO::FETCH_OBJ);

        if ($query1->rowCount() > 0) {
            foreach ($results1 as $result1) {
                //query for updation - personnel_interview_worker_1
                $con1 = "UPDATE personnel_interview_worker_1 SET status=:worker1, remarks=:remarks WHERE personnel_interview_checklist_id=:personnel_interview_checklist_id AND assessment_id=:assessee_id";
                $update1 = $dbh->prepare($con1);
                $update1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                $update1->bindParam(':worker1', $worker1, PDO::PARAM_STR);
                $update1->bindParam(':remarks', $remarks, PDO::PARAM_STR);
                $update1->bindParam(':personnel_interview_checklist_id', $personnel_interview_checklist_id, PDO::PARAM_STR);
                $update1->execute();

                if ($update1) {
                    $infos['personnel-interview-worker-1-update-success'] = "Updated successfully";
                } else {
                    $errors['personnel-interview-worker-1-update-fail'] = "Something went wrong";
                }
            }
            // header("location: assessment-workplace-inspection.php?assessee_id=" . $assessee_id . "&info=" . $info);
        }
    }
}

//if user click save-personnel-interview-worker-2 button in assessment personnel interview page
if (isset($_POST['save-personnel-interview-worker-2'])) {
    //getting the post value
    $assessee_id = $_POST['assessee_id'];
    $worker_2_c_score = $_POST['worker_2_c_score'];
    $worker_2_nc_score = $_POST['worker_2_nc_score'];
    $worker_2_na_score = $_POST['worker_2_na_score'];

    // query for data selection - personnel_interview_subscore
    $sql = "SELECT * FROM personnel_interview_subscore WHERE assessment_id=:assessee_id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);

    if ($query->rowCount() > 0) {
        foreach ($results as $result) {
            //query for updation
            $con = "UPDATE personnel_interview_subscore SET worker_2_c_score=:worker_2_c_score, worker_2_nc_score=:worker_2_nc_score, worker_2_na_score=:worker_2_na_score WHERE assessment_id=:assessee_id";
            $update = $dbh->prepare($con);
            $update->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
            $update->bindParam(':worker_2_c_score', $worker_2_c_score, PDO::PARAM_STR);
            $update->bindParam(':worker_2_nc_score', $worker_2_nc_score, PDO::PARAM_STR);
            $update->bindParam(':worker_2_na_score', $worker_2_na_score, PDO::PARAM_STR);
            $update->execute();

            if ($update) {
                //query for data selection
                $conn = "SELECT * FROM assessment WHERE assessee_id=:assessee_id";
                $query1 = $dbh->prepare($conn);
                $query1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                $query1->execute();
                $result1 = $query1->fetchAll(PDO::FETCH_OBJ);

                if ($query1->rowCount() > 0) {
                    foreach ($result1 as $results1) {
                        $conn1 = "UPDATE assessment SET total_percentage=((('$result->managerial_c_score'+'$result->supervisory_c_score'+'$result->worker_1_c_score'+:worker_2_c_score+'$result->worker_3_c_score'+'$result->worker_4_c_score'+'$result->worker_5_c_score'+'$result->worker_6_c_score'+'$result->worker_7_c_score'+'$result->worker_8_c_score'+'$result->worker_9_c_score') / (186 - ('$result->managerial_na_score'+'$result->supervisory_na_score'+'$result->worker_1_na_score'+:worker_2_na_score+'$result->worker_3_na_score'+'$result->worker_4_na_score'+'$result->worker_5_na_score'+'$result->worker_6_na_score'+'$result->worker_7_na_score'+'$result->worker_8_na_score'+'$result->worker_9_na_score')) * 20)+'$results1->document_check_percentage'+'$results1->workplace_inspection_percentage'), 
                        personnel_interview_percentage=(('$result->managerial_c_score'+'$result->supervisory_c_score'+'$result->worker_1_c_score'+:worker_2_c_score+'$result->worker_3_c_score'+'$result->worker_4_c_score'+'$result->worker_5_c_score'+'$result->worker_6_c_score'+'$result->worker_7_c_score'+'$result->worker_8_c_score'+'$result->worker_9_c_score') / (186 - ('$result->managerial_na_score'+'$result->supervisory_na_score'+'$result->worker_1_na_score'+:worker_2_na_score+'$result->worker_3_na_score'+'$result->worker_4_na_score'+'$result->worker_5_na_score'+'$result->worker_6_na_score'+'$result->worker_7_na_score'+'$result->worker_8_na_score'+'$result->worker_9_na_score')) * 20) WHERE assessee_id=:assessee_id";
                        $update1 = $dbh->prepare($conn1);
                        $update1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                        $update1->bindParam(':worker_2_c_score', $worker_2_c_score, PDO::PARAM_STR);
                        $update1->bindParam(':worker_2_na_score', $worker_2_na_score, PDO::PARAM_STR);
                        $update1->execute();
                    }
                }
            }
        }
    }

    foreach ($_POST['personnel_interview_checklist_id'] as $personnel_interview_checklist_id) {
        if (isset($_POST['worker2_' . $personnel_interview_checklist_id])) {
            $worker_2 = $_POST['worker2_' . $personnel_interview_checklist_id];
            $worker2 = implode(', ', $worker_2);
        } else {
            $worker2 = '';
        }
        if (isset($_POST['remarks_' . $personnel_interview_checklist_id])) {
            $remarks = $_POST['remarks_' . $personnel_interview_checklist_id];
        } else {
            $remarks = '';
        }

        // query for data selection - personnel_interview_worker_2
        $sql1 = "SELECT * FROM personnel_interview_worker_2 WHERE personnel_interview_checklist_id=:personnel_interview_checklist_id AND assessment_id=:assessee_id";
        $query1 = $dbh->prepare($sql1);
        $query1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
        $query1->bindParam(':personnel_interview_checklist_id', $personnel_interview_checklist_id, PDO::PARAM_STR);
        $query1->execute();
        $results1 = $query1->fetchAll(PDO::FETCH_OBJ);

        if ($query1->rowCount() > 0) {
            foreach ($results1 as $result1) {
                //query for updation - personnel_interview_worker_2
                $con1 = "UPDATE personnel_interview_worker_2 SET status=:worker2, remarks=:remarks WHERE personnel_interview_checklist_id=:personnel_interview_checklist_id AND assessment_id=:assessee_id";
                $update1 = $dbh->prepare($con1);
                $update1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                $update1->bindParam(':worker2', $worker2, PDO::PARAM_STR);
                $update1->bindParam(':remarks', $remarks, PDO::PARAM_STR);
                $update1->bindParam(':personnel_interview_checklist_id', $personnel_interview_checklist_id, PDO::PARAM_STR);
                $update1->execute();

                if ($update1) {
                    $infos['personnel-interview-worker-2-update-success'] = "Updated successfully";
                } else {
                    $errors['personnel-interview-worker-2-update-fail'] = "Something went wrong";
                }
            }
            // header("location: assessment-workplace-inspection.php?assessee_id=" . $assessee_id . "&info=" . $info);
        }
    }
}

//if user click save-personnel-interview-worker-2-from-history button in assessment personnel interview from history page
if (isset($_POST['save-personnel-interview-worker-2-from-history'])) {
    //getting the post value
    $assessee_id = $_POST['assessee_id'];
    $worker_2_c_score = $_POST['worker_2_c_score'];
    $worker_2_nc_score = $_POST['worker_2_nc_score'];
    $worker_2_na_score = $_POST['worker_2_na_score'];

    // query for data selection - personnel_interview_subscore
    $sql = "SELECT * FROM personnel_interview_subscore WHERE assessment_id=:assessee_id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);

    if ($query->rowCount() > 0) {
        foreach ($results as $result) {
            //query for updation
            $con = "UPDATE personnel_interview_subscore SET worker_2_c_score=:worker_2_c_score, worker_2_nc_score=:worker_2_nc_score, worker_2_na_score=:worker_2_na_score WHERE assessment_id=:assessee_id";
            $update = $dbh->prepare($con);
            $update->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
            $update->bindParam(':worker_2_c_score', $worker_2_c_score, PDO::PARAM_STR);
            $update->bindParam(':worker_2_nc_score', $worker_2_nc_score, PDO::PARAM_STR);
            $update->bindParam(':worker_2_na_score', $worker_2_na_score, PDO::PARAM_STR);
            $update->execute();

            if ($update) {
                //query for data selection
                $conn = "SELECT * FROM assessment WHERE assessee_id=:assessee_id";
                $query1 = $dbh->prepare($conn);
                $query1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                $query1->execute();
                $result1 = $query1->fetchAll(PDO::FETCH_OBJ);

                if ($query1->rowCount() > 0) {
                    foreach ($result1 as $results1) {
                        $conn1 = "UPDATE assessment SET total_percentage=((('$result->managerial_c_score'+'$result->supervisory_c_score'+'$result->worker_1_c_score'+:worker_2_c_score+'$result->worker_3_c_score'+'$result->worker_4_c_score'+'$result->worker_5_c_score'+'$result->worker_6_c_score'+'$result->worker_7_c_score'+'$result->worker_8_c_score'+'$result->worker_9_c_score') / (186 - ('$result->managerial_na_score'+'$result->supervisory_na_score'+'$result->worker_1_na_score'+:worker_2_na_score+'$result->worker_3_na_score'+'$result->worker_4_na_score'+'$result->worker_5_na_score'+'$result->worker_6_na_score'+'$result->worker_7_na_score'+'$result->worker_8_na_score'+'$result->worker_9_na_score')) * 20)+'$results1->document_check_percentage'+'$results1->workplace_inspection_percentage'), 
                        personnel_interview_percentage=(('$result->managerial_c_score'+'$result->supervisory_c_score'+'$result->worker_1_c_score'+:worker_2_c_score+'$result->worker_3_c_score'+'$result->worker_4_c_score'+'$result->worker_5_c_score'+'$result->worker_6_c_score'+'$result->worker_7_c_score'+'$result->worker_8_c_score'+'$result->worker_9_c_score') / (186 - ('$result->managerial_na_score'+'$result->supervisory_na_score'+'$result->worker_1_na_score'+:worker_2_na_score+'$result->worker_3_na_score'+'$result->worker_4_na_score'+'$result->worker_5_na_score'+'$result->worker_6_na_score'+'$result->worker_7_na_score'+'$result->worker_8_na_score'+'$result->worker_9_na_score')) * 20) WHERE assessee_id=:assessee_id";
                        $update1 = $dbh->prepare($conn1);
                        $update1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                        $update1->bindParam(':worker_2_c_score', $worker_2_c_score, PDO::PARAM_STR);
                        $update1->bindParam(':worker_2_na_score', $worker_2_na_score, PDO::PARAM_STR);
                        $update1->execute();
                    }
                }
            }
        }
    }

    foreach ($_POST['personnel_interview_checklist_id'] as $personnel_interview_checklist_id) {
        if (isset($_POST['worker2_' . $personnel_interview_checklist_id])) {
            $worker_2 = $_POST['worker2_' . $personnel_interview_checklist_id];
            $worker2 = implode(', ', $worker_2);
        } else {
            $worker2 = '';
        }
        if (isset($_POST['remarks_' . $personnel_interview_checklist_id])) {
            $remarks = $_POST['remarks_' . $personnel_interview_checklist_id];
        } else {
            $remarks = '';
        }

        // query for data selection - personnel_interview_worker_2
        $sql1 = "SELECT * FROM personnel_interview_worker_2 WHERE personnel_interview_checklist_id=:personnel_interview_checklist_id AND assessment_id=:assessee_id";
        $query1 = $dbh->prepare($sql1);
        $query1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
        $query1->bindParam(':personnel_interview_checklist_id', $personnel_interview_checklist_id, PDO::PARAM_STR);
        $query1->execute();
        $results1 = $query1->fetchAll(PDO::FETCH_OBJ);

        if ($query1->rowCount() > 0) {
            foreach ($results1 as $result1) {
                //query for updation - personnel_interview_worker_2
                $con1 = "UPDATE personnel_interview_worker_2 SET status=:worker2, remarks=:remarks WHERE personnel_interview_checklist_id=:personnel_interview_checklist_id AND assessment_id=:assessee_id";
                $update1 = $dbh->prepare($con1);
                $update1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                $update1->bindParam(':worker2', $worker2, PDO::PARAM_STR);
                $update1->bindParam(':remarks', $remarks, PDO::PARAM_STR);
                $update1->bindParam(':personnel_interview_checklist_id', $personnel_interview_checklist_id, PDO::PARAM_STR);
                $update1->execute();

                if ($update1) {
                    $infos['personnel-interview-worker-2-update-success'] = "Updated successfully";
                } else {
                    $errors['personnel-interview-worker-2-update-fail'] = "Something went wrong";
                }
            }
            // header("location: assessment-workplace-inspection.php?assessee_id=" . $assessee_id . "&info=" . $info);
        }
    }
}

//if user click save-personnel-interview-worker-3 button in assessment personnel interview page
if (isset($_POST['save-personnel-interview-worker-3'])) {
    //getting the post value
    $assessee_id = $_POST['assessee_id'];
    $worker_3_c_score = $_POST['worker_3_c_score'];
    $worker_3_nc_score = $_POST['worker_3_nc_score'];
    $worker_3_na_score = $_POST['worker_3_na_score'];

    // query for data selection - personnel_interview_subscore
    $sql = "SELECT * FROM personnel_interview_subscore WHERE assessment_id=:assessee_id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);

    if ($query->rowCount() > 0) {
        foreach ($results as $result) {
            //query for updation
            $con = "UPDATE personnel_interview_subscore SET worker_3_c_score=:worker_3_c_score, worker_3_nc_score=:worker_3_nc_score, worker_3_na_score=:worker_3_na_score WHERE assessment_id=:assessee_id";
            $update = $dbh->prepare($con);
            $update->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
            $update->bindParam(':worker_3_c_score', $worker_3_c_score, PDO::PARAM_STR);
            $update->bindParam(':worker_3_nc_score', $worker_3_nc_score, PDO::PARAM_STR);
            $update->bindParam(':worker_3_na_score', $worker_3_na_score, PDO::PARAM_STR);
            $update->execute();

            if ($update) {
                //query for data selection
                $conn = "SELECT * FROM assessment WHERE assessee_id=:assessee_id";
                $query1 = $dbh->prepare($conn);
                $query1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                $query1->execute();
                $result1 = $query1->fetchAll(PDO::FETCH_OBJ);

                if ($query1->rowCount() > 0) {
                    foreach ($result1 as $results1) {
                        $conn1 = "UPDATE assessment SET total_percentage=((('$result->managerial_c_score'+'$result->supervisory_c_score'+'$result->worker_1_c_score'+'$result->worker_2_c_score'+:worker_3_c_score+'$result->worker_4_c_score'+'$result->worker_5_c_score'+'$result->worker_6_c_score'+'$result->worker_7_c_score'+'$result->worker_8_c_score'+'$result->worker_9_c_score') / (186 - ('$result->managerial_na_score'+'$result->supervisory_na_score'+'$result->worker_1_na_score'+'$result->worker_2_na_score'+:worker_3_na_score+'$result->worker_4_na_score'+'$result->worker_5_na_score'+'$result->worker_6_na_score'+'$result->worker_7_na_score'+'$result->worker_8_na_score'+'$result->worker_9_na_score')) * 20)+'$results1->document_check_percentage'+'$results1->workplace_inspection_percentage'), 
                        personnel_interview_percentage=(('$result->managerial_c_score'+'$result->supervisory_c_score'+'$result->worker_1_c_score'+'$result->worker_2_c_score'+:worker_3_c_score+'$result->worker_4_c_score'+'$result->worker_5_c_score'+'$result->worker_6_c_score'+'$result->worker_7_c_score'+'$result->worker_8_c_score'+'$result->worker_9_c_score') / (186 - ('$result->managerial_na_score'+'$result->supervisory_na_score'+'$result->worker_1_na_score'+'$result->worker_2_na_score'+:worker_3_na_score+'$result->worker_4_na_score'+'$result->worker_5_na_score'+'$result->worker_6_na_score'+'$result->worker_7_na_score'+'$result->worker_8_na_score'+'$result->worker_9_na_score')) * 20) WHERE assessee_id=:assessee_id";
                        $update1 = $dbh->prepare($conn1);
                        $update1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                        $update1->bindParam(':worker_3_c_score', $worker_3_c_score, PDO::PARAM_STR);
                        $update1->bindParam(':worker_3_na_score', $worker_3_na_score, PDO::PARAM_STR);
                        $update1->execute();
                    }
                }
            }
        }
    }

    foreach ($_POST['personnel_interview_checklist_id'] as $personnel_interview_checklist_id) {
        if (isset($_POST['worker3_' . $personnel_interview_checklist_id])) {
            $worker_3 = $_POST['worker3_' . $personnel_interview_checklist_id];
            $worker3 = implode(', ', $worker_3);
        } else {
            $worker3 = '';
        }
        if (isset($_POST['remarks_' . $personnel_interview_checklist_id])) {
            $remarks = $_POST['remarks_' . $personnel_interview_checklist_id];
        } else {
            $remarks = '';
        }

        // query for data selection - personnel_interview_worker_3
        $sql1 = "SELECT * FROM personnel_interview_worker_3 WHERE personnel_interview_checklist_id=:personnel_interview_checklist_id AND assessment_id=:assessee_id";
        $query1 = $dbh->prepare($sql1);
        $query1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
        $query1->bindParam(':personnel_interview_checklist_id', $personnel_interview_checklist_id, PDO::PARAM_STR);
        $query1->execute();
        $results1 = $query1->fetchAll(PDO::FETCH_OBJ);

        if ($query1->rowCount() > 0) {
            foreach ($results1 as $result1) {
                //query for updation - personnel_interview_worker_3
                $con1 = "UPDATE personnel_interview_worker_3 SET status=:worker3, remarks=:remarks WHERE personnel_interview_checklist_id=:personnel_interview_checklist_id AND assessment_id=:assessee_id";
                $update1 = $dbh->prepare($con1);
                $update1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                $update1->bindParam(':worker3', $worker3, PDO::PARAM_STR);
                $update1->bindParam(':remarks', $remarks, PDO::PARAM_STR);
                $update1->bindParam(':personnel_interview_checklist_id', $personnel_interview_checklist_id, PDO::PARAM_STR);
                $update1->execute();

                if ($update1) {
                    $infos['personnel-interview-worker-3-update-success'] = "Updated successfully";
                } else {
                    $errors['personnel-interview-worker-3-update-fail'] = "Something went wrong";
                }
            }
            // header("location: assessment-workplace-inspection.php?assessee_id=" . $assessee_id . "&info=" . $info);
        }
    }
}

//if user click save-personnel-interview-worker-3-from-history button in assessment personnel interview from history page
if (isset($_POST['save-personnel-interview-worker-3-from-history'])) {
    //getting the post value
    $assessee_id = $_POST['assessee_id'];
    $worker_3_c_score = $_POST['worker_3_c_score'];
    $worker_3_nc_score = $_POST['worker_3_nc_score'];
    $worker_3_na_score = $_POST['worker_3_na_score'];

    // query for data selection - personnel_interview_subscore
    $sql = "SELECT * FROM personnel_interview_subscore WHERE assessment_id=:assessee_id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);

    if ($query->rowCount() > 0) {
        foreach ($results as $result) {
            //query for updation
            $con = "UPDATE personnel_interview_subscore SET worker_3_c_score=:worker_3_c_score, worker_3_nc_score=:worker_3_nc_score, worker_3_na_score=:worker_3_na_score WHERE assessment_id=:assessee_id";
            $update = $dbh->prepare($con);
            $update->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
            $update->bindParam(':worker_3_c_score', $worker_3_c_score, PDO::PARAM_STR);
            $update->bindParam(':worker_3_nc_score', $worker_3_nc_score, PDO::PARAM_STR);
            $update->bindParam(':worker_3_na_score', $worker_3_na_score, PDO::PARAM_STR);
            $update->execute();

            if ($update) {
                //query for data selection
                $conn = "SELECT * FROM assessment WHERE assessee_id=:assessee_id";
                $query1 = $dbh->prepare($conn);
                $query1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                $query1->execute();
                $result1 = $query1->fetchAll(PDO::FETCH_OBJ);

                if ($query1->rowCount() > 0) {
                    foreach ($result1 as $results1) {
                        $conn1 = "UPDATE assessment SET total_percentage=((('$result->managerial_c_score'+'$result->supervisory_c_score'+'$result->worker_1_c_score'+'$result->worker_2_c_score'+:worker_3_c_score+'$result->worker_4_c_score'+'$result->worker_5_c_score'+'$result->worker_6_c_score'+'$result->worker_7_c_score'+'$result->worker_8_c_score'+'$result->worker_9_c_score') / (186 - ('$result->managerial_na_score'+'$result->supervisory_na_score'+'$result->worker_1_na_score'+'$result->worker_2_na_score'+:worker_3_na_score+'$result->worker_4_na_score'+'$result->worker_5_na_score'+'$result->worker_6_na_score'+'$result->worker_7_na_score'+'$result->worker_8_na_score'+'$result->worker_9_na_score')) * 20)+'$results1->document_check_percentage'+'$results1->workplace_inspection_percentage'), 
                        personnel_interview_percentage=(('$result->managerial_c_score'+'$result->supervisory_c_score'+'$result->worker_1_c_score'+'$result->worker_2_c_score'+:worker_3_c_score+'$result->worker_4_c_score'+'$result->worker_5_c_score'+'$result->worker_6_c_score'+'$result->worker_7_c_score'+'$result->worker_8_c_score'+'$result->worker_9_c_score') / (186 - ('$result->managerial_na_score'+'$result->supervisory_na_score'+'$result->worker_1_na_score'+'$result->worker_2_na_score'+:worker_3_na_score+'$result->worker_4_na_score'+'$result->worker_5_na_score'+'$result->worker_6_na_score'+'$result->worker_7_na_score'+'$result->worker_8_na_score'+'$result->worker_9_na_score')) * 20) WHERE assessee_id=:assessee_id";
                        $update1 = $dbh->prepare($conn1);
                        $update1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                        $update1->bindParam(':worker_3_c_score', $worker_3_c_score, PDO::PARAM_STR);
                        $update1->bindParam(':worker_3_na_score', $worker_3_na_score, PDO::PARAM_STR);
                        $update1->execute();
                    }
                }
            }
        }
    }

    foreach ($_POST['personnel_interview_checklist_id'] as $personnel_interview_checklist_id) {
        if (isset($_POST['worker3_' . $personnel_interview_checklist_id])) {
            $worker_3 = $_POST['worker3_' . $personnel_interview_checklist_id];
            $worker3 = implode(', ', $worker_3);
        } else {
            $worker3 = '';
        }
        if (isset($_POST['remarks_' . $personnel_interview_checklist_id])) {
            $remarks = $_POST['remarks_' . $personnel_interview_checklist_id];
        } else {
            $remarks = '';
        }

        // query for data selection - personnel_interview_worker_3
        $sql1 = "SELECT * FROM personnel_interview_worker_3 WHERE personnel_interview_checklist_id=:personnel_interview_checklist_id AND assessment_id=:assessee_id";
        $query1 = $dbh->prepare($sql1);
        $query1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
        $query1->bindParam(':personnel_interview_checklist_id', $personnel_interview_checklist_id, PDO::PARAM_STR);
        $query1->execute();
        $results1 = $query1->fetchAll(PDO::FETCH_OBJ);

        if ($query1->rowCount() > 0) {
            foreach ($results1 as $result1) {
                //query for updation - personnel_interview_worker_3
                $con1 = "UPDATE personnel_interview_worker_3 SET status=:worker3, remarks=:remarks WHERE personnel_interview_checklist_id=:personnel_interview_checklist_id AND assessment_id=:assessee_id";
                $update1 = $dbh->prepare($con1);
                $update1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                $update1->bindParam(':worker3', $worker3, PDO::PARAM_STR);
                $update1->bindParam(':remarks', $remarks, PDO::PARAM_STR);
                $update1->bindParam(':personnel_interview_checklist_id', $personnel_interview_checklist_id, PDO::PARAM_STR);
                $update1->execute();

                if ($update1) {
                    $infos['personnel-interview-worker-3-update-success'] = "Updated successfully";
                } else {
                    $errors['personnel-interview-worker-3-update-fail'] = "Something went wrong";
                }
            }
            // header("location: assessment-workplace-inspection.php?assessee_id=" . $assessee_id . "&info=" . $info);
        }
    }
}

//if user click save-personnel-interview-worker-4 button in assessment personnel interview page
if (isset($_POST['save-personnel-interview-worker-4'])) {
    //getting the post value
    $assessee_id = $_POST['assessee_id'];
    $worker_4_c_score = $_POST['worker_4_c_score'];
    $worker_4_nc_score = $_POST['worker_4_nc_score'];
    $worker_4_na_score = $_POST['worker_4_na_score'];

    // query for data selection - personnel_interview_subscore
    $sql = "SELECT * FROM personnel_interview_subscore WHERE assessment_id=:assessee_id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);

    if ($query->rowCount() > 0) {
        foreach ($results as $result) {
            //query for updation
            $con = "UPDATE personnel_interview_subscore SET worker_4_c_score=:worker_4_c_score, worker_4_nc_score=:worker_4_nc_score, worker_4_na_score=:worker_4_na_score WHERE assessment_id=:assessee_id";
            $update = $dbh->prepare($con);
            $update->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
            $update->bindParam(':worker_4_c_score', $worker_4_c_score, PDO::PARAM_STR);
            $update->bindParam(':worker_4_nc_score', $worker_4_nc_score, PDO::PARAM_STR);
            $update->bindParam(':worker_4_na_score', $worker_4_na_score, PDO::PARAM_STR);
            $update->execute();

            if ($update) {
                //query for data selection
                $conn = "SELECT * FROM assessment WHERE assessee_id=:assessee_id";
                $query1 = $dbh->prepare($conn);
                $query1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                $query1->execute();
                $result1 = $query1->fetchAll(PDO::FETCH_OBJ);

                if ($query1->rowCount() > 0) {
                    foreach ($result1 as $results1) {
                        $conn1 = "UPDATE assessment SET total_percentage=((('$result->managerial_c_score'+'$result->supervisory_c_score'+'$result->worker_1_c_score'+'$result->worker_2_c_score'+'$result->worker_3_c_score'+:worker_4_c_score+'$result->worker_5_c_score'+'$result->worker_6_c_score'+'$result->worker_7_c_score'+'$result->worker_8_c_score'+'$result->worker_9_c_score') / (186 - ('$result->managerial_na_score'+'$result->supervisory_na_score'+'$result->worker_1_na_score'+'$result->worker_2_na_score'+'$result->worker_3_na_score'+:worker_4_na_score+'$result->worker_5_na_score'+'$result->worker_6_na_score'+'$result->worker_7_na_score'+'$result->worker_8_na_score'+'$result->worker_9_na_score')) * 20)+'$results1->document_check_percentage'+'$results1->workplace_inspection_percentage'), 
                        personnel_interview_percentage=(('$result->managerial_c_score'+'$result->supervisory_c_score'+'$result->worker_1_c_score'+'$result->worker_2_c_score'+'$result->worker_3_c_score'+:worker_4_c_score+'$result->worker_5_c_score'+'$result->worker_6_c_score'+'$result->worker_7_c_score'+'$result->worker_8_c_score'+'$result->worker_9_c_score') / (186 - ('$result->managerial_na_score'+'$result->supervisory_na_score'+'$result->worker_1_na_score'+'$result->worker_2_na_score'+'$result->worker_3_na_score'+:worker_4_na_score+'$result->worker_5_na_score'+'$result->worker_6_na_score'+'$result->worker_7_na_score'+'$result->worker_8_na_score'+'$result->worker_9_na_score')) * 20) WHERE assessee_id=:assessee_id";
                        $update1 = $dbh->prepare($conn1);
                        $update1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                        $update1->bindParam(':worker_4_c_score', $worker_4_c_score, PDO::PARAM_STR);
                        $update1->bindParam(':worker_4_na_score', $worker_4_na_score, PDO::PARAM_STR);
                        $update1->execute();
                    }
                }
            }
        }
    }

    foreach ($_POST['personnel_interview_checklist_id'] as $personnel_interview_checklist_id) {
        if (isset($_POST['worker4_' . $personnel_interview_checklist_id])) {
            $worker_4 = $_POST['worker4_' . $personnel_interview_checklist_id];
            $worker4 = implode(', ', $worker_4);
        } else {
            $worker4 = '';
        }
        if (isset($_POST['remarks_' . $personnel_interview_checklist_id])) {
            $remarks = $_POST['remarks_' . $personnel_interview_checklist_id];
        } else {
            $remarks = '';
        }

        // query for data selection - personnel_interview_worker_4
        $sql1 = "SELECT * FROM personnel_interview_worker_4 WHERE personnel_interview_checklist_id=:personnel_interview_checklist_id AND assessment_id=:assessee_id";
        $query1 = $dbh->prepare($sql1);
        $query1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
        $query1->bindParam(':personnel_interview_checklist_id', $personnel_interview_checklist_id, PDO::PARAM_STR);
        $query1->execute();
        $results1 = $query1->fetchAll(PDO::FETCH_OBJ);

        if ($query1->rowCount() > 0) {
            foreach ($results1 as $result1) {
                //query for updation - personnel_interview_worker_4
                $con1 = "UPDATE personnel_interview_worker_4 SET status=:worker4, remarks=:remarks WHERE personnel_interview_checklist_id=:personnel_interview_checklist_id AND assessment_id=:assessee_id";
                $update1 = $dbh->prepare($con1);
                $update1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                $update1->bindParam(':worker4', $worker4, PDO::PARAM_STR);
                $update1->bindParam(':remarks', $remarks, PDO::PARAM_STR);
                $update1->bindParam(':personnel_interview_checklist_id', $personnel_interview_checklist_id, PDO::PARAM_STR);
                $update1->execute();

                if ($update1) {
                    $infos['personnel-interview-worker-4-update-success'] = "Updated successfully";
                } else {
                    $errors['personnel-interview-worker-4-update-fail'] = "Something went wrong";
                }
            }
            // header("location: assessment-workplace-inspection.php?assessee_id=" . $assessee_id . "&info=" . $info);
        }
    }
}

//if user click save-personnel-interview-worker-4-from-history button in assessment personnel interview from history page
if (isset($_POST['save-personnel-interview-worker-4-from-history'])) {
    //getting the post value
    $assessee_id = $_POST['assessee_id'];
    $worker_4_c_score = $_POST['worker_4_c_score'];
    $worker_4_nc_score = $_POST['worker_4_nc_score'];
    $worker_4_na_score = $_POST['worker_4_na_score'];

    // query for data selection - personnel_interview_subscore
    $sql = "SELECT * FROM personnel_interview_subscore WHERE assessment_id=:assessee_id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);

    if ($query->rowCount() > 0) {
        foreach ($results as $result) {
            //query for updation
            $con = "UPDATE personnel_interview_subscore SET worker_4_c_score=:worker_4_c_score, worker_4_nc_score=:worker_4_nc_score, worker_4_na_score=:worker_4_na_score WHERE assessment_id=:assessee_id";
            $update = $dbh->prepare($con);
            $update->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
            $update->bindParam(':worker_4_c_score', $worker_4_c_score, PDO::PARAM_STR);
            $update->bindParam(':worker_4_nc_score', $worker_4_nc_score, PDO::PARAM_STR);
            $update->bindParam(':worker_4_na_score', $worker_4_na_score, PDO::PARAM_STR);
            $update->execute();

            if ($update) {
                //query for data selection
                $conn = "SELECT * FROM assessment WHERE assessee_id=:assessee_id";
                $query1 = $dbh->prepare($conn);
                $query1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                $query1->execute();
                $result1 = $query1->fetchAll(PDO::FETCH_OBJ);

                if ($query1->rowCount() > 0) {
                    foreach ($result1 as $results1) {
                        $conn1 = "UPDATE assessment SET total_percentage=((('$result->managerial_c_score'+'$result->supervisory_c_score'+'$result->worker_1_c_score'+'$result->worker_2_c_score'+'$result->worker_3_c_score'+:worker_4_c_score+'$result->worker_5_c_score'+'$result->worker_6_c_score'+'$result->worker_7_c_score'+'$result->worker_8_c_score'+'$result->worker_9_c_score') / (186 - ('$result->managerial_na_score'+'$result->supervisory_na_score'+'$result->worker_1_na_score'+'$result->worker_2_na_score'+'$result->worker_3_na_score'+:worker_4_na_score+'$result->worker_5_na_score'+'$result->worker_6_na_score'+'$result->worker_7_na_score'+'$result->worker_8_na_score'+'$result->worker_9_na_score')) * 20)+'$results1->document_check_percentage'+'$results1->workplace_inspection_percentage'), 
                        personnel_interview_percentage=(('$result->managerial_c_score'+'$result->supervisory_c_score'+'$result->worker_1_c_score'+'$result->worker_2_c_score'+'$result->worker_3_c_score'+:worker_4_c_score+'$result->worker_5_c_score'+'$result->worker_6_c_score'+'$result->worker_7_c_score'+'$result->worker_8_c_score'+'$result->worker_9_c_score') / (186 - ('$result->managerial_na_score'+'$result->supervisory_na_score'+'$result->worker_1_na_score'+'$result->worker_2_na_score'+'$result->worker_3_na_score'+:worker_4_na_score+'$result->worker_5_na_score'+'$result->worker_6_na_score'+'$result->worker_7_na_score'+'$result->worker_8_na_score'+'$result->worker_9_na_score')) * 20) WHERE assessee_id=:assessee_id";
                        $update1 = $dbh->prepare($conn1);
                        $update1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                        $update1->bindParam(':worker_4_c_score', $worker_4_c_score, PDO::PARAM_STR);
                        $update1->bindParam(':worker_4_na_score', $worker_4_na_score, PDO::PARAM_STR);
                        $update1->execute();
                    }
                }
            }
        }
    }

    foreach ($_POST['personnel_interview_checklist_id'] as $personnel_interview_checklist_id) {
        if (isset($_POST['worker4_' . $personnel_interview_checklist_id])) {
            $worker_4 = $_POST['worker4_' . $personnel_interview_checklist_id];
            $worker4 = implode(', ', $worker_4);
        } else {
            $worker4 = '';
        }
        if (isset($_POST['remarks_' . $personnel_interview_checklist_id])) {
            $remarks = $_POST['remarks_' . $personnel_interview_checklist_id];
        } else {
            $remarks = '';
        }

        // query for data selection - personnel_interview_worker_4
        $sql1 = "SELECT * FROM personnel_interview_worker_4 WHERE personnel_interview_checklist_id=:personnel_interview_checklist_id AND assessment_id=:assessee_id";
        $query1 = $dbh->prepare($sql1);
        $query1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
        $query1->bindParam(':personnel_interview_checklist_id', $personnel_interview_checklist_id, PDO::PARAM_STR);
        $query1->execute();
        $results1 = $query1->fetchAll(PDO::FETCH_OBJ);

        if ($query1->rowCount() > 0) {
            foreach ($results1 as $result1) {
                //query for updation - personnel_interview_worker_4
                $con1 = "UPDATE personnel_interview_worker_4 SET status=:worker4, remarks=:remarks WHERE personnel_interview_checklist_id=:personnel_interview_checklist_id AND assessment_id=:assessee_id";
                $update1 = $dbh->prepare($con1);
                $update1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                $update1->bindParam(':worker4', $worker4, PDO::PARAM_STR);
                $update1->bindParam(':remarks', $remarks, PDO::PARAM_STR);
                $update1->bindParam(':personnel_interview_checklist_id', $personnel_interview_checklist_id, PDO::PARAM_STR);
                $update1->execute();

                if ($update1) {
                    $infos['personnel-interview-worker-4-update-success'] = "Updated successfully";
                } else {
                    $errors['personnel-interview-worker-4-update-fail'] = "Something went wrong";
                }
            }
            // header("location: assessment-workplace-inspection.php?assessee_id=" . $assessee_id . "&info=" . $info);
        }
    }
}

//if user click save-personnel-interview-worker-5 button in assessment personnel interview page
if (isset($_POST['save-personnel-interview-worker-5'])) {
    //getting the post value
    $assessee_id = $_POST['assessee_id'];
    $worker_5_c_score = $_POST['worker_5_c_score'];
    $worker_5_nc_score = $_POST['worker_5_nc_score'];
    $worker_5_na_score = $_POST['worker_5_na_score'];

    // query for data selection - personnel_interview_subscore
    $sql = "SELECT * FROM personnel_interview_subscore WHERE assessment_id=:assessee_id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);

    if ($query->rowCount() > 0) {
        foreach ($results as $result) {
            //query for updation
            $con = "UPDATE personnel_interview_subscore SET worker_5_c_score=:worker_5_c_score, worker_5_nc_score=:worker_5_nc_score, worker_5_na_score=:worker_5_na_score WHERE assessment_id=:assessee_id";
            $update = $dbh->prepare($con);
            $update->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
            $update->bindParam(':worker_5_c_score', $worker_5_c_score, PDO::PARAM_STR);
            $update->bindParam(':worker_5_nc_score', $worker_5_nc_score, PDO::PARAM_STR);
            $update->bindParam(':worker_5_na_score', $worker_5_na_score, PDO::PARAM_STR);
            $update->execute();

            if ($update) {
                //query for data selection
                $conn = "SELECT * FROM assessment WHERE assessee_id=:assessee_id";
                $query1 = $dbh->prepare($conn);
                $query1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                $query1->execute();
                $result1 = $query1->fetchAll(PDO::FETCH_OBJ);

                if ($query1->rowCount() > 0) {
                    foreach ($result1 as $results1) {
                        $conn1 = "UPDATE assessment SET total_percentage=((('$result->managerial_c_score'+'$result->supervisory_c_score'+'$result->worker_1_c_score'+'$result->worker_2_c_score'+'$result->worker_3_c_score'+'$result->worker_4_c_score'+:worker_5_c_score+'$result->worker_6_c_score'+'$result->worker_7_c_score'+'$result->worker_8_c_score'+'$result->worker_9_c_score') / (186 - ('$result->managerial_na_score'+'$result->supervisory_na_score'+'$result->worker_1_na_score'+'$result->worker_2_na_score'+'$result->worker_3_na_score'+'$result->worker_4_na_score'+:worker_5_na_score+'$result->worker_6_na_score'+'$result->worker_7_na_score'+'$result->worker_8_na_score'+'$result->worker_9_na_score')) * 20)+'$results1->document_check_percentage'+'$results1->workplace_inspection_percentage'), 
                        personnel_interview_percentage=(('$result->managerial_c_score'+'$result->supervisory_c_score'+'$result->worker_1_c_score'+'$result->worker_2_c_score'+'$result->worker_3_c_score'+'$result->worker_4_c_score'+:worker_5_c_score+'$result->worker_6_c_score'+'$result->worker_7_c_score'+'$result->worker_8_c_score'+'$result->worker_9_c_score') / (186 - ('$result->managerial_na_score'+'$result->supervisory_na_score'+'$result->worker_1_na_score'+'$result->worker_2_na_score'+'$result->worker_3_na_score'+'$result->worker_4_na_score'+:worker_5_na_score+'$result->worker_6_na_score'+'$result->worker_7_na_score'+'$result->worker_8_na_score'+'$result->worker_9_na_score')) * 20) WHERE assessee_id=:assessee_id";
                        $update1 = $dbh->prepare($conn1);
                        $update->bindParam(':worker_5_c_score', $worker_5_c_score, PDO::PARAM_STR);
                        $update->bindParam(':worker_5_na_score', $worker_5_na_score, PDO::PARAM_STR);
                        $update1->execute();
                    }
                }
            }
        }
    }

    foreach ($_POST['personnel_interview_checklist_id'] as $personnel_interview_checklist_id) {
        if (isset($_POST['worker5_' . $personnel_interview_checklist_id])) {
            $worker_5 = $_POST['worker5_' . $personnel_interview_checklist_id];
            $worker5 = implode(', ', $worker_5);
        } else {
            $worker5 = '';
        }
        if (isset($_POST['remarks_' . $personnel_interview_checklist_id])) {
            $remarks = $_POST['remarks_' . $personnel_interview_checklist_id];
        } else {
            $remarks = '';
        }

        // query for data selection - personnel_interview_worker_5
        $sql1 = "SELECT * FROM personnel_interview_worker_5 WHERE personnel_interview_checklist_id=:personnel_interview_checklist_id AND assessment_id=:assessee_id";
        $query1 = $dbh->prepare($sql1);
        $query1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
        $query1->bindParam(':personnel_interview_checklist_id', $personnel_interview_checklist_id, PDO::PARAM_STR);
        $query1->execute();
        $results1 = $query1->fetchAll(PDO::FETCH_OBJ);

        if ($query1->rowCount() > 0) {
            foreach ($results1 as $result1) {
                //query for updation - personnel_interview_worker_5
                $con1 = "UPDATE personnel_interview_worker_5 SET status=:worker5, remarks=:remarks WHERE personnel_interview_checklist_id=:personnel_interview_checklist_id AND assessment_id=:assessee_id";
                $update1 = $dbh->prepare($con1);
                $update1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                $update1->bindParam(':worker5', $worker5, PDO::PARAM_STR);
                $update1->bindParam(':remarks', $remarks, PDO::PARAM_STR);
                $update1->bindParam(':personnel_interview_checklist_id', $personnel_interview_checklist_id, PDO::PARAM_STR);
                $update1->execute();

                if ($update1) {
                    $infos['personnel-interview-worker-5-update-success'] = "Updated successfully";
                } else {
                    $errors['personnel-interview-worker-5-update-fail'] = "Something went wrong";
                }
            }
            // header("location: assessment-workplace-inspection.php?assessee_id=" . $assessee_id . "&info=" . $info);
        }
    }
}

//if user click save-personnel-interview-worker-5-from-history button in assessment personnel interview from history page
if (isset($_POST['save-personnel-interview-worker-5-from-history'])) {
    //getting the post value
    $assessee_id = $_POST['assessee_id'];
    $worker_5_c_score = $_POST['worker_5_c_score'];
    $worker_5_nc_score = $_POST['worker_5_nc_score'];
    $worker_5_na_score = $_POST['worker_5_na_score'];

    // query for data selection - personnel_interview_subscore
    $sql = "SELECT * FROM personnel_interview_subscore WHERE assessment_id=:assessee_id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);

    if ($query->rowCount() > 0) {
        foreach ($results as $result) {
            //query for updation
            $con = "UPDATE personnel_interview_subscore SET worker_5_c_score=:worker_5_c_score, worker_5_nc_score=:worker_5_nc_score, worker_5_na_score=:worker_5_na_score WHERE assessment_id=:assessee_id";
            $update = $dbh->prepare($con);
            $update->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
            $update->bindParam(':worker_5_c_score', $worker_5_c_score, PDO::PARAM_STR);
            $update->bindParam(':worker_5_nc_score', $worker_5_nc_score, PDO::PARAM_STR);
            $update->bindParam(':worker_5_na_score', $worker_5_na_score, PDO::PARAM_STR);
            $update->execute();

            if ($update) {
                //query for data selection
                $conn = "SELECT * FROM assessment WHERE assessee_id=:assessee_id";
                $query1 = $dbh->prepare($conn);
                $query1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                $query1->execute();
                $result1 = $query1->fetchAll(PDO::FETCH_OBJ);

                if ($query1->rowCount() > 0) {
                    foreach ($result1 as $results1) {
                        $conn1 = "UPDATE assessment SET total_percentage=((('$result->managerial_c_score'+'$result->supervisory_c_score'+'$result->worker_1_c_score'+'$result->worker_2_c_score'+'$result->worker_3_c_score'+'$result->worker_4_c_score'+:worker_5_c_score+'$result->worker_6_c_score'+'$result->worker_7_c_score'+'$result->worker_8_c_score'+'$result->worker_9_c_score') / (186 - ('$result->managerial_na_score'+'$result->supervisory_na_score'+'$result->worker_1_na_score'+'$result->worker_2_na_score'+'$result->worker_3_na_score'+'$result->worker_4_na_score'+:worker_5_na_score+'$result->worker_6_na_score'+'$result->worker_7_na_score'+'$result->worker_8_na_score'+'$result->worker_9_na_score')) * 20)+'$results1->document_check_percentage'+'$results1->workplace_inspection_percentage'), 
                        personnel_interview_percentage=(('$result->managerial_c_score'+'$result->supervisory_c_score'+'$result->worker_1_c_score'+'$result->worker_2_c_score'+'$result->worker_3_c_score'+'$result->worker_4_c_score'+:worker_5_c_score+'$result->worker_6_c_score'+'$result->worker_7_c_score'+'$result->worker_8_c_score'+'$result->worker_9_c_score') / (186 - ('$result->managerial_na_score'+'$result->supervisory_na_score'+'$result->worker_1_na_score'+'$result->worker_2_na_score'+'$result->worker_3_na_score'+'$result->worker_4_na_score'+:worker_5_na_score+'$result->worker_6_na_score'+'$result->worker_7_na_score'+'$result->worker_8_na_score'+'$result->worker_9_na_score')) * 20) WHERE assessee_id=:assessee_id";
                        $update1 = $dbh->prepare($conn1);
                        $update->bindParam(':worker_5_c_score', $worker_5_c_score, PDO::PARAM_STR);
                        $update->bindParam(':worker_5_na_score', $worker_5_na_score, PDO::PARAM_STR);
                        $update1->execute();
                    }
                }
            }
        }
    }

    foreach ($_POST['personnel_interview_checklist_id'] as $personnel_interview_checklist_id) {
        if (isset($_POST['worker5_' . $personnel_interview_checklist_id])) {
            $worker_5 = $_POST['worker5_' . $personnel_interview_checklist_id];
            $worker5 = implode(', ', $worker_5);
        } else {
            $worker5 = '';
        }
        if (isset($_POST['remarks_' . $personnel_interview_checklist_id])) {
            $remarks = $_POST['remarks_' . $personnel_interview_checklist_id];
        } else {
            $remarks = '';
        }

        // query for data selection - personnel_interview_worker_5
        $sql1 = "SELECT * FROM personnel_interview_worker_5 WHERE personnel_interview_checklist_id=:personnel_interview_checklist_id AND assessment_id=:assessee_id";
        $query1 = $dbh->prepare($sql1);
        $query1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
        $query1->bindParam(':personnel_interview_checklist_id', $personnel_interview_checklist_id, PDO::PARAM_STR);
        $query1->execute();
        $results1 = $query1->fetchAll(PDO::FETCH_OBJ);

        if ($query1->rowCount() > 0) {
            foreach ($results1 as $result1) {
                //query for updation - personnel_interview_worker_5
                $con1 = "UPDATE personnel_interview_worker_5 SET status=:worker5, remarks=:remarks WHERE personnel_interview_checklist_id=:personnel_interview_checklist_id AND assessment_id=:assessee_id";
                $update1 = $dbh->prepare($con1);
                $update1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                $update1->bindParam(':worker5', $worker5, PDO::PARAM_STR);
                $update1->bindParam(':remarks', $remarks, PDO::PARAM_STR);
                $update1->bindParam(':personnel_interview_checklist_id', $personnel_interview_checklist_id, PDO::PARAM_STR);
                $update1->execute();

                if ($update1) {
                    $infos['personnel-interview-worker-5-update-success'] = "Updated successfully";
                } else {
                    $errors['personnel-interview-worker-5-update-fail'] = "Something went wrong";
                }
            }
            // header("location: assessment-workplace-inspection.php?assessee_id=" . $assessee_id . "&info=" . $info);
        }
    }
}

//if user click save-personnel-interview-worker-6 button in assessment personnel interview page
if (isset($_POST['save-personnel-interview-worker-6'])) {
    //getting the post value
    $assessee_id = $_POST['assessee_id'];
    $worker_6_c_score = $_POST['worker_6_c_score'];
    $worker_6_nc_score = $_POST['worker_6_nc_score'];
    $worker_6_na_score = $_POST['worker_6_na_score'];

    // query for data selection - personnel_interview_subscore
    $sql = "SELECT * FROM personnel_interview_subscore WHERE assessment_id=:assessee_id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);

    if ($query->rowCount() > 0) {
        foreach ($results as $result) {
            //query for updation
            $con = "UPDATE personnel_interview_subscore SET worker_6_c_score=:worker_6_c_score, worker_6_nc_score=:worker_6_nc_score, worker_6_na_score=:worker_6_na_score WHERE assessment_id=:assessee_id";
            $update = $dbh->prepare($con);
            $update->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
            $update->bindParam(':worker_6_c_score', $worker_6_c_score, PDO::PARAM_STR);
            $update->bindParam(':worker_6_nc_score', $worker_6_nc_score, PDO::PARAM_STR);
            $update->bindParam(':worker_6_na_score', $worker_6_na_score, PDO::PARAM_STR);
            $update->execute();

            if ($update) {
                //query for data selection
                $conn = "SELECT * FROM assessment WHERE assessee_id=:assessee_id";
                $query1 = $dbh->prepare($conn);
                $query1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                $query1->execute();
                $result1 = $query1->fetchAll(PDO::FETCH_OBJ);

                if ($query1->rowCount() > 0) {
                    foreach ($result1 as $results1) {
                        $conn1 = "UPDATE assessment SET total_percentage=((('$result->managerial_c_score'+'$result->supervisory_c_score'+'$result->worker_1_c_score'+'$result->worker_2_c_score'+'$result->worker_3_c_score'+'$result->worker_4_c_score'+'$result->worker_5_c_score'+:worker_6_c_score+'$result->worker_7_c_score'+'$result->worker_8_c_score'+'$result->worker_9_c_score') / (186 - ('$result->managerial_na_score'+'$result->supervisory_na_score'+'$result->worker_1_na_score'+'$result->worker_2_na_score'+'$result->worker_3_na_score'+'$result->worker_4_na_score'+'$result->worker_5_na_score'+:worker_6_na_score+'$result->worker_7_na_score'+'$result->worker_8_na_score'+'$result->worker_9_na_score')) * 20)+'$results1->document_check_percentage'+'$results1->workplace_inspection_percentage'), 
                        personnel_interview_percentage=(('$result->managerial_c_score'+'$result->supervisory_c_score'+'$result->worker_1_c_score'+'$result->worker_2_c_score'+'$result->worker_3_c_score'+'$result->worker_4_c_score'+'$result->worker_5_c_score'+:worker_6_c_score+'$result->worker_7_c_score'+'$result->worker_8_c_score'+'$result->worker_9_c_score') / (186 - ('$result->managerial_na_score'+'$result->supervisory_na_score'+'$result->worker_1_na_score'+'$result->worker_2_na_score'+'$result->worker_3_na_score'+'$result->worker_4_na_score'+'$result->worker_5_na_score'+:worker_6_na_score+'$result->worker_7_na_score'+'$result->worker_8_na_score'+'$result->worker_9_na_score')) * 20) WHERE assessee_id=:assessee_id";
                        $update1 = $dbh->prepare($conn1);
                        $update1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                        $update1->bindParam(':worker_6_c_score', $worker_6_c_score, PDO::PARAM_STR);
                        $update1->bindParam(':worker_6_na_score', $worker_6_na_score, PDO::PARAM_STR);
                        $update1->execute();
                    }
                }
            }
        }
    }

    foreach ($_POST['personnel_interview_checklist_id'] as $personnel_interview_checklist_id) {
        if (isset($_POST['worker6_' . $personnel_interview_checklist_id])) {
            $worker_6 = $_POST['worker6_' . $personnel_interview_checklist_id];
            $worker6 = implode(', ', $worker_6);
        } else {
            $worker6 = '';
        }
        if (isset($_POST['remarks_' . $personnel_interview_checklist_id])) {
            $remarks = $_POST['remarks_' . $personnel_interview_checklist_id];
        } else {
            $remarks = '';
        }

        // query for data selection - personnel_interview_worker_6
        $sql1 = "SELECT * FROM personnel_interview_worker_6 WHERE personnel_interview_checklist_id=:personnel_interview_checklist_id AND assessment_id=:assessee_id";
        $query1 = $dbh->prepare($sql1);
        $query1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
        $query1->bindParam(':personnel_interview_checklist_id', $personnel_interview_checklist_id, PDO::PARAM_STR);
        $query1->execute();
        $results1 = $query1->fetchAll(PDO::FETCH_OBJ);

        if ($query1->rowCount() > 0) {
            foreach ($results1 as $result1) {
                //query for updation - personnel_interview_worker_6
                $con1 = "UPDATE personnel_interview_worker_6 SET status=:worker6, remarks=:remarks WHERE personnel_interview_checklist_id=:personnel_interview_checklist_id AND assessment_id=:assessee_id";
                $update1 = $dbh->prepare($con1);
                $update1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                $update1->bindParam(':worker6', $worker6, PDO::PARAM_STR);
                $update1->bindParam(':remarks', $remarks, PDO::PARAM_STR);
                $update1->bindParam(':personnel_interview_checklist_id', $personnel_interview_checklist_id, PDO::PARAM_STR);
                $update1->execute();

                if ($update1) {
                    $infos['personnel-interview-worker-6-update-success'] = "Updated successfully";
                } else {
                    $errors['personnel-interview-worker-6-update-fail'] = "Something went wrong";
                }
            }
            // header("location: assessment-workplace-inspection.php?assessee_id=" . $assessee_id . "&info=" . $info);
        }
    }
}

//if user click save-personnel-interview-worker-6-from-history button in assessment personnel interview from history page
if (isset($_POST['save-personnel-interview-worker-6-from-history'])) {
    //getting the post value
    $assessee_id = $_POST['assessee_id'];
    $worker_6_c_score = $_POST['worker_6_c_score'];
    $worker_6_nc_score = $_POST['worker_6_nc_score'];
    $worker_6_na_score = $_POST['worker_6_na_score'];

    // query for data selection - personnel_interview_subscore
    $sql = "SELECT * FROM personnel_interview_subscore WHERE assessment_id=:assessee_id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);

    if ($query->rowCount() > 0) {
        foreach ($results as $result) {
            //query for updation
            $con = "UPDATE personnel_interview_subscore SET worker_6_c_score=:worker_6_c_score, worker_6_nc_score=:worker_6_nc_score, worker_6_na_score=:worker_6_na_score WHERE assessment_id=:assessee_id";
            $update = $dbh->prepare($con);
            $update->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
            $update->bindParam(':worker_6_c_score', $worker_6_c_score, PDO::PARAM_STR);
            $update->bindParam(':worker_6_nc_score', $worker_6_nc_score, PDO::PARAM_STR);
            $update->bindParam(':worker_6_na_score', $worker_6_na_score, PDO::PARAM_STR);
            $update->execute();

            if ($update) {
                //query for data selection
                $conn = "SELECT * FROM assessment WHERE assessee_id=:assessee_id";
                $query1 = $dbh->prepare($conn);
                $query1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                $query1->execute();
                $result1 = $query1->fetchAll(PDO::FETCH_OBJ);

                if ($query1->rowCount() > 0) {
                    foreach ($result1 as $results1) {
                        $conn1 = "UPDATE assessment SET total_percentage=((('$result->managerial_c_score'+'$result->supervisory_c_score'+'$result->worker_1_c_score'+'$result->worker_2_c_score'+'$result->worker_3_c_score'+'$result->worker_4_c_score'+'$result->worker_5_c_score'+:worker_6_c_score+'$result->worker_7_c_score'+'$result->worker_8_c_score'+'$result->worker_9_c_score') / (186 - ('$result->managerial_na_score'+'$result->supervisory_na_score'+'$result->worker_1_na_score'+'$result->worker_2_na_score'+'$result->worker_3_na_score'+'$result->worker_4_na_score'+'$result->worker_5_na_score'+:worker_6_na_score+'$result->worker_7_na_score'+'$result->worker_8_na_score'+'$result->worker_9_na_score')) * 20)+'$results1->document_check_percentage'+'$results1->workplace_inspection_percentage'), 
                        personnel_interview_percentage=(('$result->managerial_c_score'+'$result->supervisory_c_score'+'$result->worker_1_c_score'+'$result->worker_2_c_score'+'$result->worker_3_c_score'+'$result->worker_4_c_score'+'$result->worker_5_c_score'+:worker_6_c_score+'$result->worker_7_c_score'+'$result->worker_8_c_score'+'$result->worker_9_c_score') / (186 - ('$result->managerial_na_score'+'$result->supervisory_na_score'+'$result->worker_1_na_score'+'$result->worker_2_na_score'+'$result->worker_3_na_score'+'$result->worker_4_na_score'+'$result->worker_5_na_score'+:worker_6_na_score+'$result->worker_7_na_score'+'$result->worker_8_na_score'+'$result->worker_9_na_score')) * 20) WHERE assessee_id=:assessee_id";
                        $update1 = $dbh->prepare($conn1);
                        $update1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                        $update1->bindParam(':worker_6_c_score', $worker_6_c_score, PDO::PARAM_STR);
                        $update1->bindParam(':worker_6_na_score', $worker_6_na_score, PDO::PARAM_STR);
                        $update1->execute();
                    }
                }
            }
        }
    }

    foreach ($_POST['personnel_interview_checklist_id'] as $personnel_interview_checklist_id) {
        if (isset($_POST['worker6_' . $personnel_interview_checklist_id])) {
            $worker_6 = $_POST['worker6_' . $personnel_interview_checklist_id];
            $worker6 = implode(', ', $worker_6);
        } else {
            $worker6 = '';
        }
        if (isset($_POST['remarks_' . $personnel_interview_checklist_id])) {
            $remarks = $_POST['remarks_' . $personnel_interview_checklist_id];
        } else {
            $remarks = '';
        }

        // query for data selection - personnel_interview_worker_6
        $sql1 = "SELECT * FROM personnel_interview_worker_6 WHERE personnel_interview_checklist_id=:personnel_interview_checklist_id AND assessment_id=:assessee_id";
        $query1 = $dbh->prepare($sql1);
        $query1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
        $query1->bindParam(':personnel_interview_checklist_id', $personnel_interview_checklist_id, PDO::PARAM_STR);
        $query1->execute();
        $results1 = $query1->fetchAll(PDO::FETCH_OBJ);

        if ($query1->rowCount() > 0) {
            foreach ($results1 as $result1) {
                //query for updation - personnel_interview_worker_6
                $con1 = "UPDATE personnel_interview_worker_6 SET status=:worker6, remarks=:remarks WHERE personnel_interview_checklist_id=:personnel_interview_checklist_id AND assessment_id=:assessee_id";
                $update1 = $dbh->prepare($con1);
                $update1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                $update1->bindParam(':worker6', $worker6, PDO::PARAM_STR);
                $update1->bindParam(':remarks', $remarks, PDO::PARAM_STR);
                $update1->bindParam(':personnel_interview_checklist_id', $personnel_interview_checklist_id, PDO::PARAM_STR);
                $update1->execute();

                if ($update1) {
                    $infos['personnel-interview-worker-6-update-success'] = "Updated successfully";
                } else {
                    $errors['personnel-interview-worker-6-update-fail'] = "Something went wrong";
                }
            }
            // header("location: assessment-workplace-inspection.php?assessee_id=" . $assessee_id . "&info=" . $info);
        }
    }
}

//if user click save-personnel-interview-worker-7 button in assessment personnel interview page
if (isset($_POST['save-personnel-interview-worker-7'])) {
    //getting the post value
    $assessee_id = $_POST['assessee_id'];
    $worker_7_c_score = $_POST['worker_7_c_score'];
    $worker_7_nc_score = $_POST['worker_7_nc_score'];
    $worker_7_na_score = $_POST['worker_7_na_score'];

    // query for data selection - personnel_interview_subscore
    $sql = "SELECT * FROM personnel_interview_subscore WHERE assessment_id=:assessee_id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);

    if ($query->rowCount() > 0) {
        foreach ($results as $result) {
            //query for updation
            $con = "UPDATE personnel_interview_subscore SET worker_7_c_score=:worker_7_c_score, worker_7_nc_score=:worker_7_nc_score, worker_7_na_score=:worker_7_na_score WHERE assessment_id=:assessee_id";
            $update = $dbh->prepare($con);
            $update->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
            $update->bindParam(':worker_7_c_score', $worker_7_c_score, PDO::PARAM_STR);
            $update->bindParam(':worker_7_nc_score', $worker_7_nc_score, PDO::PARAM_STR);
            $update->bindParam(':worker_7_na_score', $worker_7_na_score, PDO::PARAM_STR);
            $update->execute();

            if ($update) {
                //query for data selection
                $conn = "SELECT * FROM assessment WHERE assessee_id=:assessee_id";
                $query1 = $dbh->prepare($conn);
                $query1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                $query1->execute();
                $result1 = $query1->fetchAll(PDO::FETCH_OBJ);

                if ($query1->rowCount() > 0) {
                    foreach ($result1 as $results1) {
                        $conn1 = "UPDATE assessment SET total_percentage=((('$result->managerial_c_score'+'$result->supervisory_c_score'+'$result->worker_1_c_score'+'$result->worker_2_c_score'+'$result->worker_3_c_score'+'$result->worker_4_c_score'+'$result->worker_5_c_score'+'$result->worker_6_c_score'+:worker_7_c_score+'$result->worker_8_c_score'+'$result->worker_9_c_score') / (186 - ('$result->managerial_na_score'+'$result->supervisory_na_score'+'$result->worker_1_na_score'+'$result->worker_2_na_score'+'$result->worker_3_na_score'+'$result->worker_4_na_score'+'$result->worker_5_na_score'+'$result->worker_6_na_score'+:worker_7_na_score+'$result->worker_8_na_score'+'$result->worker_9_na_score')) * 20)+'$results1->document_check_percentage'+'$results1->workplace_inspection_percentage'), 
                        personnel_interview_percentage=(('$result->managerial_c_score'+'$result->supervisory_c_score'+'$result->worker_1_c_score'+'$result->worker_2_c_score'+'$result->worker_3_c_score'+'$result->worker_4_c_score'+'$result->worker_5_c_score'+'$result->worker_6_c_score'+:worker_7_c_score+'$result->worker_8_c_score'+'$result->worker_9_c_score') / (186 - ('$result->managerial_na_score'+'$result->supervisory_na_score'+'$result->worker_1_na_score'+'$result->worker_2_na_score'+'$result->worker_3_na_score'+'$result->worker_4_na_score'+'$result->worker_5_na_score'+'$result->worker_6_na_score'+:worker_7_na_score+'$result->worker_8_na_score'+'$result->worker_9_na_score')) * 20) WHERE assessee_id=:assessee_id";
                        $update1 = $dbh->prepare($conn1);
                        $update1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                        $update1->bindParam(':worker_7_c_score', $worker_7_c_score, PDO::PARAM_STR);
                        $update1->bindParam(':worker_7_na_score', $worker_7_na_score, PDO::PARAM_STR);
                        $update1->execute();
                    }
                }
            }
        }
    }

    foreach ($_POST['personnel_interview_checklist_id'] as $personnel_interview_checklist_id) {
        if (isset($_POST['worker7_' . $personnel_interview_checklist_id])) {
            $worker_7 = $_POST['worker7_' . $personnel_interview_checklist_id];
            $worker7 = implode(', ', $worker_7);
        } else {
            $worker7 = '';
        }
        if (isset($_POST['remarks_' . $personnel_interview_checklist_id])) {
            $remarks = $_POST['remarks_' . $personnel_interview_checklist_id];
        } else {
            $remarks = '';
        }

        // query for data selection - personnel_interview_worker_7
        $sql1 = "SELECT * FROM personnel_interview_worker_7 WHERE personnel_interview_checklist_id=:personnel_interview_checklist_id AND assessment_id=:assessee_id";
        $query1 = $dbh->prepare($sql1);
        $query1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
        $query1->bindParam(':personnel_interview_checklist_id', $personnel_interview_checklist_id, PDO::PARAM_STR);
        $query1->execute();
        $results1 = $query1->fetchAll(PDO::FETCH_OBJ);

        if ($query1->rowCount() > 0) {
            foreach ($results1 as $result1) {
                //query for updation - personnel_interview_worker_6
                $con1 = "UPDATE personnel_interview_worker_7 SET status=:worker7, remarks=:remarks WHERE personnel_interview_checklist_id=:personnel_interview_checklist_id AND assessment_id=:assessee_id";
                $update1 = $dbh->prepare($con1);
                $update1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                $update1->bindParam(':worker7', $worker7, PDO::PARAM_STR);
                $update1->bindParam(':remarks', $remarks, PDO::PARAM_STR);
                $update1->bindParam(':personnel_interview_checklist_id', $personnel_interview_checklist_id, PDO::PARAM_STR);
                $update1->execute();

                if ($update1) {
                    $infos['personnel-interview-worker-7-update-success'] = "Updated successfully";
                } else {
                    $errors['personnel-interview-worker-7-update-fail'] = "Something went wrong";
                }
            }
            // header("location: assessment-workplace-inspection.php?assessee_id=" . $assessee_id . "&info=" . $info);
        }
    }
}

//if user click save-personnel-interview-worker-7-from-history button in assessment personnel interview from history page
if (isset($_POST['save-personnel-interview-worker-7-from-history'])) {
    //getting the post value
    $assessee_id = $_POST['assessee_id'];
    $worker_7_c_score = $_POST['worker_7_c_score'];
    $worker_7_nc_score = $_POST['worker_7_nc_score'];
    $worker_7_na_score = $_POST['worker_7_na_score'];

    // query for data selection - personnel_interview_subscore
    $sql = "SELECT * FROM personnel_interview_subscore WHERE assessment_id=:assessee_id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);

    if ($query->rowCount() > 0) {
        foreach ($results as $result) {
            //query for updation
            $con = "UPDATE personnel_interview_subscore SET worker_7_c_score=:worker_7_c_score, worker_7_nc_score=:worker_7_nc_score, worker_7_na_score=:worker_7_na_score WHERE assessment_id=:assessee_id";
            $update = $dbh->prepare($con);
            $update->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
            $update->bindParam(':worker_7_c_score', $worker_7_c_score, PDO::PARAM_STR);
            $update->bindParam(':worker_7_nc_score', $worker_7_nc_score, PDO::PARAM_STR);
            $update->bindParam(':worker_7_na_score', $worker_7_na_score, PDO::PARAM_STR);
            $update->execute();

            if ($update) {
                //query for data selection
                $conn = "SELECT * FROM assessment WHERE assessee_id=:assessee_id";
                $query1 = $dbh->prepare($conn);
                $query1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                $query1->execute();
                $result1 = $query1->fetchAll(PDO::FETCH_OBJ);

                if ($query1->rowCount() > 0) {
                    foreach ($result1 as $results1) {
                        $conn1 = "UPDATE assessment SET total_percentage=((('$result->managerial_c_score'+'$result->supervisory_c_score'+'$result->worker_1_c_score'+'$result->worker_2_c_score'+'$result->worker_3_c_score'+'$result->worker_4_c_score'+'$result->worker_5_c_score'+'$result->worker_6_c_score'+:worker_7_c_score+'$result->worker_8_c_score'+'$result->worker_9_c_score') / (186 - ('$result->managerial_na_score'+'$result->supervisory_na_score'+'$result->worker_1_na_score'+'$result->worker_2_na_score'+'$result->worker_3_na_score'+'$result->worker_4_na_score'+'$result->worker_5_na_score'+'$result->worker_6_na_score'+:worker_7_na_score+'$result->worker_8_na_score'+'$result->worker_9_na_score')) * 20)+'$results1->document_check_percentage'+'$results1->workplace_inspection_percentage'), 
                        personnel_interview_percentage=(('$result->managerial_c_score'+'$result->supervisory_c_score'+'$result->worker_1_c_score'+'$result->worker_2_c_score'+'$result->worker_3_c_score'+'$result->worker_4_c_score'+'$result->worker_5_c_score'+'$result->worker_6_c_score'+:worker_7_c_score+'$result->worker_8_c_score'+'$result->worker_9_c_score') / (186 - ('$result->managerial_na_score'+'$result->supervisory_na_score'+'$result->worker_1_na_score'+'$result->worker_2_na_score'+'$result->worker_3_na_score'+'$result->worker_4_na_score'+'$result->worker_5_na_score'+'$result->worker_6_na_score'+:worker_7_na_score+'$result->worker_8_na_score'+'$result->worker_9_na_score')) * 20) WHERE assessee_id=:assessee_id";
                        $update1 = $dbh->prepare($conn1);
                        $update1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                        $update1->bindParam(':worker_7_c_score', $worker_7_c_score, PDO::PARAM_STR);
                        $update1->bindParam(':worker_7_na_score', $worker_7_na_score, PDO::PARAM_STR);
                        $update1->execute();
                    }
                }
            }
        }
    }

    foreach ($_POST['personnel_interview_checklist_id'] as $personnel_interview_checklist_id) {
        if (isset($_POST['worker7_' . $personnel_interview_checklist_id])) {
            $worker_7 = $_POST['worker7_' . $personnel_interview_checklist_id];
            $worker7 = implode(', ', $worker_7);
        } else {
            $worker7 = '';
        }
        if (isset($_POST['remarks_' . $personnel_interview_checklist_id])) {
            $remarks = $_POST['remarks_' . $personnel_interview_checklist_id];
        } else {
            $remarks = '';
        }

        // query for data selection - personnel_interview_worker_7
        $sql1 = "SELECT * FROM personnel_interview_worker_7 WHERE personnel_interview_checklist_id=:personnel_interview_checklist_id AND assessment_id=:assessee_id";
        $query1 = $dbh->prepare($sql1);
        $query1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
        $query1->bindParam(':personnel_interview_checklist_id', $personnel_interview_checklist_id, PDO::PARAM_STR);
        $query1->execute();
        $results1 = $query1->fetchAll(PDO::FETCH_OBJ);

        if ($query1->rowCount() > 0) {
            foreach ($results1 as $result1) {
                //query for updation - personnel_interview_worker_6
                $con1 = "UPDATE personnel_interview_worker_7 SET status=:worker7, remarks=:remarks WHERE personnel_interview_checklist_id=:personnel_interview_checklist_id AND assessment_id=:assessee_id";
                $update1 = $dbh->prepare($con1);
                $update1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                $update1->bindParam(':worker7', $worker7, PDO::PARAM_STR);
                $update1->bindParam(':remarks', $remarks, PDO::PARAM_STR);
                $update1->bindParam(':personnel_interview_checklist_id', $personnel_interview_checklist_id, PDO::PARAM_STR);
                $update1->execute();

                if ($update1) {
                    $infos['personnel-interview-worker-7-update-success'] = "Updated successfully";
                } else {
                    $errors['personnel-interview-worker-7-update-fail'] = "Something went wrong";
                }
            }
            // header("location: assessment-workplace-inspection.php?assessee_id=" . $assessee_id . "&info=" . $info);
        }
    }
}

//if user click save-personnel-interview-worker-8 button in assessment personnel interview page
if (isset($_POST['save-personnel-interview-worker-8'])) {
    //getting the post value
    $assessee_id = $_POST['assessee_id'];
    $worker_8_c_score = $_POST['worker_8_c_score'];
    $worker_8_nc_score = $_POST['worker_8_nc_score'];
    $worker_8_na_score = $_POST['worker_8_na_score'];

    // query for data selection - personnel_interview_subscore
    $sql = "SELECT * FROM personnel_interview_subscore WHERE assessment_id=:assessee_id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);

    if ($query->rowCount() > 0) {
        foreach ($results as $result) {
            //query for updation
            $con = "UPDATE personnel_interview_subscore SET worker_8_c_score=:worker_8_c_score, worker_8_nc_score=:worker_8_nc_score,  worker_8_na_score=:worker_8_na_score WHERE assessment_id=:assessee_id";
            $update = $dbh->prepare($con);
            $update->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
            $update->bindParam(':worker_8_c_score', $worker_8_c_score, PDO::PARAM_STR);
            $update->bindParam(':worker_8_nc_score', $worker_8_nc_score, PDO::PARAM_STR);
            $update->bindParam(':worker_8_na_score', $worker_8_na_score, PDO::PARAM_STR);
            $update->execute();

            if ($update) {
                //query for data selection
                $conn = "SELECT * FROM assessment WHERE assessee_id=:assessee_id";
                $query1 = $dbh->prepare($conn);
                $query1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                $query1->execute();
                $result1 = $query1->fetchAll(PDO::FETCH_OBJ);

                if ($query1->rowCount() > 0) {
                    foreach ($result1 as $results1) {
                        $conn1 = "UPDATE assessment SET total_percentage=((('$result->managerial_c_score'+'$result->supervisory_c_score'+'$result->worker_1_c_score'+'$result->worker_2_c_score'+'$result->worker_3_c_score'+'$result->worker_4_c_score'+'$result->worker_5_c_score'+'$result->worker_6_c_score'+'$result->worker_7_c_score'+:worker_8_c_score+'$result->worker_9_c_score') / (186 - ('$result->managerial_na_score'+'$result->supervisory_na_score'+'$result->worker_1_na_score'+'$result->worker_2_na_score'+'$result->worker_3_na_score'+'$result->worker_4_na_score'+'$result->worker_5_na_score'+'$result->worker_6_na_score'+'$result->worker_7_na_score'+:worker_8_na_score+'$result->worker_9_na_score')) * 20)+'$results1->document_check_percentage'+'$results1->workplace_inspection_percentage'), 
                        personnel_interview_percentage=(('$result->managerial_c_score'+'$result->supervisory_c_score'+'$result->worker_1_c_score'+'$result->worker_2_c_score'+'$result->worker_3_c_score'+'$result->worker_4_c_score'+'$result->worker_5_c_score'+'$result->worker_6_c_score'+'$result->worker_7_c_score'+:worker_8_c_score+'$result->worker_9_c_score') / (186 - ('$result->managerial_na_score'+'$result->supervisory_na_score'+'$result->worker_1_na_score'+'$result->worker_2_na_score'+'$result->worker_3_na_score'+'$result->worker_4_na_score'+'$result->worker_5_na_score'+'$result->worker_6_na_score'+'$result->worker_7_na_score'+:worker_8_na_score+'$result->worker_9_na_score')) * 20) WHERE assessee_id=:assessee_id";
                        $update1 = $dbh->prepare($conn1);
                        $update1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                        $update1->bindParam(':worker_8_c_score', $worker_8_c_score, PDO::PARAM_STR);
                        $update1->bindParam(':worker_8_na_score', $worker_8_na_score, PDO::PARAM_STR);
                        $update1->execute();
                    }
                }
            }
        }
    }

    foreach ($_POST['personnel_interview_checklist_id'] as $personnel_interview_checklist_id) {
        if (isset($_POST['worker8_' . $personnel_interview_checklist_id])) {
            $worker_8 = $_POST['worker8_' . $personnel_interview_checklist_id];
            $worker8 = implode(', ', $worker_8);
        } else {
            $worker8 = '';
        }
        if (isset($_POST['remarks_' . $personnel_interview_checklist_id])) {
            $remarks = $_POST['remarks_' . $personnel_interview_checklist_id];
        } else {
            $remarks = '';
        }

        // query for data selection - personnel_interview_worker_8
        $sql1 = "SELECT * FROM personnel_interview_worker_8 WHERE personnel_interview_checklist_id=:personnel_interview_checklist_id AND assessment_id=:assessee_id";
        $query1 = $dbh->prepare($sql1);
        $query1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
        $query1->bindParam(':personnel_interview_checklist_id', $personnel_interview_checklist_id, PDO::PARAM_STR);
        $query1->execute();
        $results1 = $query1->fetchAll(PDO::FETCH_OBJ);

        if ($query1->rowCount() > 0) {
            foreach ($results1 as $result1) {
                //query for updation - personnel_interview_worker_8
                $con1 = "UPDATE personnel_interview_worker_8 SET status=:worker8, remarks=:remarks WHERE personnel_interview_checklist_id=:personnel_interview_checklist_id AND assessment_id=:assessee_id";
                $update1 = $dbh->prepare($con1);
                $update1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                $update1->bindParam(':worker8', $worker8, PDO::PARAM_STR);
                $update1->bindParam(':remarks', $remarks, PDO::PARAM_STR);
                $update1->bindParam(':personnel_interview_checklist_id', $personnel_interview_checklist_id, PDO::PARAM_STR);
                $update1->execute();

                if ($update1) {
                    $infos['personnel-interview-worker-8-update-success'] = "Updated successfully";
                } else {
                    $errors['personnel-interview-worker-8-update-fail'] = "Something went wrong";
                }
            }
            // header("location: assessment-workplace-inspection.php?assessee_id=" . $assessee_id . "&info=" . $info);
        }
    }
}

//if user click save-personnel-interview-worker-8-from-history button in assessment personnel interview from history page
if (isset($_POST['save-personnel-interview-worker-8-from-history'])) {
    //getting the post value
    $assessee_id = $_POST['assessee_id'];
    $worker_8_c_score = $_POST['worker_8_c_score'];
    $worker_8_nc_score = $_POST['worker_8_nc_score'];
    $worker_8_na_score = $_POST['worker_8_na_score'];

    // query for data selection - personnel_interview_subscore
    $sql = "SELECT * FROM personnel_interview_subscore WHERE assessment_id=:assessee_id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);

    if ($query->rowCount() > 0) {
        foreach ($results as $result) {
            //query for updation
            $con = "UPDATE personnel_interview_subscore SET worker_8_c_score=:worker_8_c_score, worker_8_nc_score=:worker_8_nc_score, worker_8_na_score=:worker_8_na_score WHERE assessment_id=:assessee_id";
            $update = $dbh->prepare($con);
            $update->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
            $update->bindParam(':worker_8_c_score', $worker_8_c_score, PDO::PARAM_STR);
            $update->bindParam(':worker_8_nc_score', $worker_8_nc_score, PDO::PARAM_STR);
            $update->bindParam(':worker_8_na_score', $worker_8_na_score, PDO::PARAM_STR);
            $update->execute();

            if ($update) {
                //query for data selection
                $conn = "SELECT * FROM assessment WHERE assessee_id=:assessee_id";
                $query1 = $dbh->prepare($conn);
                $query1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                $query1->execute();
                $result1 = $query1->fetchAll(PDO::FETCH_OBJ);

                if ($query1->rowCount() > 0) {
                    foreach ($result1 as $results1) {
                        $conn1 = "UPDATE assessment SET total_percentage=((('$result->managerial_c_score'+'$result->supervisory_c_score'+'$result->worker_1_c_score'+'$result->worker_2_c_score'+'$result->worker_3_c_score'+'$result->worker_4_c_score'+'$result->worker_5_c_score'+'$result->worker_6_c_score'+'$result->worker_7_c_score'+:worker_8_c_score+'$result->worker_9_c_score') / (186 - ('$result->managerial_na_score'+'$result->supervisory_na_score'+'$result->worker_1_na_score'+'$result->worker_2_na_score'+'$result->worker_3_na_score'+'$result->worker_4_na_score'+'$result->worker_5_na_score'+'$result->worker_6_na_score'+'$result->worker_7_na_score'+:worker_8_na_score+'$result->worker_9_na_score')) * 20)+'$results1->document_check_percentage'+'$results1->workplace_inspection_percentage'), 
                        personnel_interview_percentage=(('$result->managerial_c_score'+'$result->supervisory_c_score'+'$result->worker_1_c_score'+'$result->worker_2_c_score'+'$result->worker_3_c_score'+'$result->worker_4_c_score'+'$result->worker_5_c_score'+'$result->worker_6_c_score'+'$result->worker_7_c_score'+:worker_8_c_score+'$result->worker_9_c_score') / (186 - ('$result->managerial_na_score'+'$result->supervisory_na_score'+'$result->worker_1_na_score'+'$result->worker_2_na_score'+'$result->worker_3_na_score'+'$result->worker_4_na_score'+'$result->worker_5_na_score'+'$result->worker_6_na_score'+'$result->worker_7_na_score'+:worker_8_na_score+'$result->worker_9_na_score')) * 20) WHERE assessee_id=:assessee_id";
                        $update1 = $dbh->prepare($conn1);
                        $update1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                        $update1->bindParam(':worker_8_c_score', $worker_8_c_score, PDO::PARAM_STR);
                        $update1->bindParam(':worker_8_na_score', $worker_8_na_score, PDO::PARAM_STR);
                        $update1->execute();
                    }
                }
            }
        }
    }

    foreach ($_POST['personnel_interview_checklist_id'] as $personnel_interview_checklist_id) {
        if (isset($_POST['worker8_' . $personnel_interview_checklist_id])) {
            $worker_8 = $_POST['worker8_' . $personnel_interview_checklist_id];
            $worker8 = implode(', ', $worker_8);
        } else {
            $worker8 = '';
        }
        if (isset($_POST['remarks_' . $personnel_interview_checklist_id])) {
            $remarks = $_POST['remarks_' . $personnel_interview_checklist_id];
        } else {
            $remarks = '';
        }

        // query for data selection - personnel_interview_worker_8
        $sql1 = "SELECT * FROM personnel_interview_worker_8 WHERE personnel_interview_checklist_id=:personnel_interview_checklist_id AND assessment_id=:assessee_id";
        $query1 = $dbh->prepare($sql1);
        $query1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
        $query1->bindParam(':personnel_interview_checklist_id', $personnel_interview_checklist_id, PDO::PARAM_STR);
        $query1->execute();
        $results1 = $query1->fetchAll(PDO::FETCH_OBJ);

        if ($query1->rowCount() > 0) {
            foreach ($results1 as $result1) {
                //query for updation - personnel_interview_worker_8
                $con1 = "UPDATE personnel_interview_worker_8 SET status=:worker8, remarks=:remarks WHERE personnel_interview_checklist_id=:personnel_interview_checklist_id AND assessment_id=:assessee_id";
                $update1 = $dbh->prepare($con1);
                $update1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                $update1->bindParam(':worker8', $worker8, PDO::PARAM_STR);
                $update1->bindParam(':remarks', $remarks, PDO::PARAM_STR);
                $update1->bindParam(':personnel_interview_checklist_id', $personnel_interview_checklist_id, PDO::PARAM_STR);
                $update1->execute();

                if ($update1) {
                    $infos['personnel-interview-worker-8-update-success'] = "Updated successfully";
                } else {
                    $errors['personnel-interview-worker-8-update-fail'] = "Something went wrong";
                }
            }
            // header("location: assessment-workplace-inspection.php?assessee_id=" . $assessee_id . "&info=" . $info);
        }
    }
}

//if user click save-personnel-interview-worker-9 button in assessment personnel interview page
if (isset($_POST['save-personnel-interview-worker-9'])) {
    //getting the post value
    $assessee_id = $_POST['assessee_id'];
    $worker_9_c_score = $_POST['worker_9_c_score'];
    $worker_9_nc_score = $_POST['worker_9_nc_score'];
    $worker_9_na_score = $_POST['worker_9_na_score'];

    // query for data selection - personnel_interview_subscore
    $sql = "SELECT * FROM personnel_interview_subscore WHERE assessment_id=:assessee_id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);

    if ($query->rowCount() > 0) {
        foreach ($results as $result) {
            //query for updation
            $con = "UPDATE personnel_interview_subscore SET worker_9_c_score=:worker_9_c_score, worker_9_nc_score=:worker_9_nc_score, worker_9_na_score=:worker_9_na_score WHERE assessment_id=:assessee_id";
            $update = $dbh->prepare($con);
            $update->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
            $update->bindParam(':worker_9_c_score', $worker_9_c_score, PDO::PARAM_STR);
            $update->bindParam(':worker_9_nc_score', $worker_9_nc_score, PDO::PARAM_STR);
            $update->bindParam(':worker_9_na_score', $worker_9_na_score, PDO::PARAM_STR);
            $update->execute();

            if ($update) {
                //query for data selection
                $conn = "SELECT * FROM assessment WHERE assessee_id=:assessee_id";
                $query1 = $dbh->prepare($conn);
                $query1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                $query1->execute();
                $result1 = $query1->fetchAll(PDO::FETCH_OBJ);

                if ($query1->rowCount() > 0) {
                    foreach ($result1 as $results1) {
                        $conn1 = "UPDATE assessment SET total_percentage=((('$result->managerial_c_score'+'$result->supervisory_c_score'+'$result->worker_1_c_score'+'$result->worker_2_c_score'+'$result->worker_3_c_score'+'$result->worker_4_c_score'+'$result->worker_5_c_score'+'$result->worker_6_c_score'+'$result->worker_7_c_score'+'$result->worker_8_c_score'+:worker_9_c_score) / (186 - ('$result->managerial_na_score'+'$result->supervisory_na_score'+'$result->worker_1_na_score'+'$result->worker_2_na_score'+'$result->worker_3_na_score'+'$result->worker_4_na_score'+'$result->worker_5_na_score'+'$result->worker_6_na_score'+'$result->worker_7_na_score'+'$result->worker_8_na_score'+:worker_9_na_score)) * 20)+'$results1->document_check_percentage'+'$results1->workplace_inspection_percentage'), 
                        personnel_interview_percentage=(('$result->managerial_c_score'+'$result->supervisory_c_score'+'$result->worker_1_c_score'+'$result->worker_2_c_score'+'$result->worker_3_c_score'+'$result->worker_4_c_score'+'$result->worker_5_c_score'+'$result->worker_6_c_score'+'$result->worker_7_c_score'+'$result->worker_8_c_score'+:worker_9_c_score) / (186 - ('$result->managerial_na_score'+'$result->supervisory_na_score'+'$result->worker_1_na_score'+'$result->worker_2_na_score'+'$result->worker_3_na_score'+'$result->worker_4_na_score'+'$result->worker_5_na_score'+'$result->worker_6_na_score'+'$result->worker_7_na_score'+'$result->worker_8_na_score'+:worker_9_na_score)) * 20) WHERE assessee_id=:assessee_id";
                        $update1 = $dbh->prepare($conn1);
                        $update1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                        $update1->bindParam(':worker_9_c_score', $worker_9_c_score, PDO::PARAM_STR);
                        $update1->bindParam(':worker_9_na_score', $worker_9_na_score, PDO::PARAM_STR);
                        $update1->execute();
                    }
                }
            }
        }
    }

    foreach ($_POST['personnel_interview_checklist_id'] as $personnel_interview_checklist_id) {
        if (isset($_POST['worker9_' . $personnel_interview_checklist_id])) {
            $worker_9 = $_POST['worker9_' . $personnel_interview_checklist_id];
            $worker9 = implode(', ', $worker_9);
        } else {
            $worker9 = '';
        }
        if (isset($_POST['remarks_' . $personnel_interview_checklist_id])) {
            $remarks = $_POST['remarks_' . $personnel_interview_checklist_id];
        } else {
            $remarks = '';
        }

        // query for data selection - personnel_interview_worker_9
        $sql1 = "SELECT * FROM personnel_interview_worker_9 WHERE personnel_interview_checklist_id=:personnel_interview_checklist_id AND assessment_id=:assessee_id";
        $query1 = $dbh->prepare($sql1);
        $query1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
        $query1->bindParam(':personnel_interview_checklist_id', $personnel_interview_checklist_id, PDO::PARAM_STR);
        $query1->execute();
        $results1 = $query1->fetchAll(PDO::FETCH_OBJ);

        if ($query1->rowCount() > 0) {
            foreach ($results1 as $result1) {
                //query for updation - personnel_interview_worker_9
                $con1 = "UPDATE personnel_interview_worker_9 SET status=:worker9, remarks=:remarks WHERE personnel_interview_checklist_id=:personnel_interview_checklist_id AND assessment_id=:assessee_id";
                $update1 = $dbh->prepare($con1);
                $update1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                $update1->bindParam(':worker9', $worker9, PDO::PARAM_STR);
                $update1->bindParam(':remarks', $remarks, PDO::PARAM_STR);
                $update1->bindParam(':personnel_interview_checklist_id', $personnel_interview_checklist_id, PDO::PARAM_STR);
                $update1->execute();

                if ($update1) {
                    $infos['personnel-interview-worker-9-update-success'] = "Updated successfully";
                } else {
                    $errors['personnel-interview-worker-9-update-fail'] = "Something went wrong";
                }
            }
            // header("location: assessment-workplace-inspection.php?assessee_id=" . $assessee_id . "&info=" . $info);
        }
    }
}

//if user click save-personnel-interview-worker-9-from-history button in assessment personnel interview from history page
if (isset($_POST['save-personnel-interview-worker-9-from-history'])) {
    //getting the post value
    $assessee_id = $_POST['assessee_id'];
    $worker_9_c_score = $_POST['worker_9_c_score'];
    $worker_9_nc_score = $_POST['worker_9_nc_score'];
    $worker_9_na_score = $_POST['worker_9_na_score'];

    // query for data selection - personnel_interview_subscore
    $sql = "SELECT * FROM personnel_interview_subscore WHERE assessment_id=:assessee_id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);

    if ($query->rowCount() > 0) {
        foreach ($results as $result) {
            //query for updation
            $con = "UPDATE personnel_interview_subscore SET worker_9_c_score=:worker_9_c_score, worker_9_nc_score=:worker_9_nc_score, worker_9_na_score=:worker_9_na_score WHERE assessment_id=:assessee_id";
            $update = $dbh->prepare($con);
            $update->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
            $update->bindParam(':worker_9_c_score', $worker_9_c_score, PDO::PARAM_STR);
            $update->bindParam(':worker_9_nc_score', $worker_9_nc_score, PDO::PARAM_STR);
            $update->bindParam(':worker_9_na_score', $worker_9_na_score, PDO::PARAM_STR);
            $update->execute();

            if ($update) {
                //query for data selection
                $conn = "SELECT * FROM assessment WHERE assessee_id=:assessee_id";
                $query1 = $dbh->prepare($conn);
                $query1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                $query1->execute();
                $result1 = $query1->fetchAll(PDO::FETCH_OBJ);

                if ($query1->rowCount() > 0) {
                    $conn1 = "UPDATE assessment SET personnel_interview_percentage=(('$result->managerial_c_score'+'$result->supervisory_c_score'+'$result->worker_1_c_score'+'$result->worker_2_c_score'+'$result->worker_3_c_score'+'$result->worker_4_c_score'+'$result->worker_5_c_score'+'$result->worker_6_c_score'+'$result->worker_7_c_score'+'$result->worker_8_c_score'+:worker_9_c_score) / (186 - ('$result->managerial_na_score'+'$result->supervisory_na_score'+'$result->worker_1_na_score'+'$result->worker_2_na_score'+'$result->worker_3_na_score'+'$result->worker_4_na_score'+'$result->worker_5_na_score'+'$result->worker_6_na_score'+'$result->worker_7_na_score'+'$result->worker_8_na_score'+:worker_9_na_score)) * 20) WHERE assessee_id=:assessee_id";
                    $update1 = $dbh->prepare($conn1);
                    $update1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                    $update1->bindParam(':worker_9_c_score', $worker_9_c_score, PDO::PARAM_STR);
                    $update1->bindParam(':worker_9_na_score', $worker_9_na_score, PDO::PARAM_STR);
                    $update1->execute();
                }
            }
        }
    }

    foreach ($_POST['personnel_interview_checklist_id'] as $personnel_interview_checklist_id) {
        if (isset($_POST['worker9_' . $personnel_interview_checklist_id])) {
            $worker_9 = $_POST['worker9_' . $personnel_interview_checklist_id];
            $worker9 = implode(', ', $worker_9);
        } else {
            $worker9 = '';
        }
        if (isset($_POST['remarks_' . $personnel_interview_checklist_id])) {
            $remarks = $_POST['remarks_' . $personnel_interview_checklist_id];
        } else {
            $remarks = '';
        }

        // query for data selection - personnel_interview_worker_9
        $sql1 = "SELECT * FROM personnel_interview_worker_9 WHERE personnel_interview_checklist_id=:personnel_interview_checklist_id AND assessment_id=:assessee_id";
        $query1 = $dbh->prepare($sql1);
        $query1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
        $query1->bindParam(':personnel_interview_checklist_id', $personnel_interview_checklist_id, PDO::PARAM_STR);
        $query1->execute();
        $results1 = $query1->fetchAll(PDO::FETCH_OBJ);

        if ($query1->rowCount() > 0) {
            foreach ($results1 as $result1) {
                //query for updation - personnel_interview_worker_9
                $con1 = "UPDATE personnel_interview_worker_9 SET status=:worker9, remarks=:remarks WHERE personnel_interview_checklist_id=:personnel_interview_checklist_id AND assessment_id=:assessee_id";
                $update1 = $dbh->prepare($con1);
                $update1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                $update1->bindParam(':worker9', $worker9, PDO::PARAM_STR);
                $update1->bindParam(':remarks', $remarks, PDO::PARAM_STR);
                $update1->bindParam(':personnel_interview_checklist_id', $personnel_interview_checklist_id, PDO::PARAM_STR);
                $update1->execute();

                if ($update1) {
                    $infos['personnel-interview-worker-9-update-success'] = "Updated successfully";
                } else {
                    $errors['personnel-interview-worker-9-update-fail'] = "Something went wrong";
                }
            }
            // header("location: assessment-workplace-inspection.php?assessee_id=" . $assessee_id . "&info=" . $info);
        }
    }
}

if (isset($_POST['edit'])) {
    $assessee_id = $_POST['assessee_id'];
    header('location: edit-assessment-from-history.php?assessee_id=' . $assessee_id);
}

if (isset($_POST['print'])) {
    $assessee_id = $_POST['assessee_id'];
    header('location: print.php?assessee_id=' . $assessee_id);
}

// if (isset($_GET['delete_id'])) {
if (isset($_POST['delete'])) {
    // $assessee_id = intval($_GET['delete_id']);
    $assessee_id = $_POST['assessee_id'];

    $sql = "DELETE FROM assessment WHERE assessee_id=:assessee_id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
    $query->execute();

    if ($query) {
        $con = "DELETE FROM document_check_subscore WHERE assessment_id=:assessee_id;
                DELETE FROM workplace_inspection_subscore WHERE assessment_id=:assessee_id;
                DELETE FROM personnel_interview_subscore WHERE assessment_id=:assessee_id;";
        $delete_subscore = $dbh->prepare($con);
        $delete_subscore->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
        $delete_subscore->execute();

        if ($delete_subscore && deleteDocumentCheck($assessee_id) && deleteWorkplaceInspection($assessee_id) && deletePersonnelInterviewManagerialSupervisory($assessee_id) && deletePersonnelInterviewWorker($assessee_id)) {
            $infos['delete-assessment-success'] = 'Deleted successfully';
        } else {
            $errors['delete-assessment-fail'] = 'Something went wrong';
        }
    }
}

function deleteDocumentCheck($assessee_id)
{
    if (!isset($_SESSION)) {
        session_start();
    }
    include 'includes/config.php';

    $assessment_id = $assessee_id;

    //query for data selection - document check
    $sql = "DELETE FROM document_check_assessment WHERE assessment_id=:assessment_id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':assessment_id', $assessment_id, PDO::PARAM_STR);
    $query->execute();

    if ($query) {
        return true;
    } else {
        return false;
    }
}

function deleteWorkplaceInspection($assessee_id)
{
    if (!isset($_SESSION)) {
        session_start();
    }
    include 'includes/config.php';

    $assessment_id = $assessee_id;

    //query for data selection - document check
    $sql = "DELETE FROM workplace_inspection_assessment WHERE assessment_id=:assessment_id;
            DELETE FROM workplace_inspection_high_risk_1 WHERE assessment_id=:assessment_id;
            DELETE FROM workplace_inspection_high_risk_2 WHERE assessment_id=:assessment_id;
            DELETE FROM workplace_inspection_high_risk_3 WHERE assessment_id=:assessment_id;";
    $query = $dbh->prepare($sql);
    $query->bindParam(':assessment_id', $assessment_id, PDO::PARAM_STR);
    $query->execute();

    if ($query) {
        return true;
    } else {
        return false;
    }
}

function deletePersonnelInterviewManagerialSupervisory($assessee_id)
{
    if (!isset($_SESSION)) {
        session_start();
    }
    include 'includes/config.php';

    $assessment_id = $assessee_id;

    //query for data selection - document check
    $sql = "DELETE FROM personnel_interview_managerial WHERE assessment_id=:assessment_id;
            DELETE FROM personnel_interview_supervisory_1 WHERE assessment_id=:assessment_id;
            DELETE FROM personnel_interview_supervisory_2 WHERE assessment_id=:assessment_id;
            DELETE FROM personnel_interview_supervisory_3 WHERE assessment_id=:assessment_id;";
    $query = $dbh->prepare($sql);
    $query->bindParam(':assessment_id', $assessment_id, PDO::PARAM_STR);
    $query->execute();

    if ($query) {
        return true;
    } else {
        return false;
    }
}

function deletePersonnelInterviewWorker($assessee_id)
{
    if (!isset($_SESSION)) {
        session_start();
    }
    include 'includes/config.php';

    $assessment_id = $assessee_id;

    //query for data selection - document check
    $sql = "DELETE FROM personnel_interview_worker_1 WHERE assessment_id=:assessment_id;
            DELETE FROM personnel_interview_worker_2 WHERE assessment_id=:assessment_id;
            DELETE FROM personnel_interview_worker_3 WHERE assessment_id=:assessment_id;
            DELETE FROM personnel_interview_worker_4 WHERE assessment_id=:assessment_id;
            DELETE FROM personnel_interview_worker_5 WHERE assessment_id=:assessment_id;
            DELETE FROM personnel_interview_worker_6 WHERE assessment_id=:assessment_id;
            DELETE FROM personnel_interview_worker_7 WHERE assessment_id=:assessment_id;
            DELETE FROM personnel_interview_worker_8 WHERE assessment_id=:assessment_id;
            DELETE FROM personnel_interview_worker_9 WHERE assessment_id=:assessment_id;";
    $query = $dbh->prepare($sql);
    $query->bindParam(':assessment_id', $assessment_id, PDO::PARAM_STR);
    $query->execute();

    if ($query) {
        return true;
    } else {
        return false;
    }
}
