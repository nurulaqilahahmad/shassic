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
            $con = "INSERT INTO workplace_inspection_subscore(assessment_id) VALUES(:assessee_id);";
            $insert_subscore = $dbh->prepare($con);
            $insert_subscore->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
            $insert_subscore->execute();

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

            $infos['add-new-assessment-success'] = "You have successfully added a new assessment";
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
    $workplace_inspection_checklist_id = $_POST['workplace_inspection_checklist_id'];
    // $checklist_count = count($workplace_inspection_checklist_id);
    // echo "<script>console.log($checklist_count);</script>";
    $high_risk_score = $_POST['high_risk_score'];

    if (isset($_POST['highrisk1'])) {
        // $highrisk1 = $_POST['highrisk1'];
        $highrisk1 = implode(', ', $_POST['highrisk1']);
        // echo "<script>console.log($highrisk1);</script>";
    }

    // for ($loop = 0; $loop < $checklist_count; $loop++) {
    //     if (isset($_POST['highrisk1'])) {
    //         // $highrisk1 = implode(', ', $_POST['highrisk1[' . $workplace_inspection_checklist_id[$loop] . ']']);
    //         $highrisk1 = implode(', ', $_POST['highrisk1']);
    //     }
    // }

    // $highrisk1 = implode(', ', $_POST['highrisk1']);
    // $highrisk1count = count($_POST['highrisk1']);
    // echo "<script>console.log($highrisk1count);</script>";
    // $high_risk_1 = array($highrisk1);

    // if (isset($_POST['highrisk2'])) {
    //     $highrisk2 = implode(', ', $_POST['highrisk2']);
    //     $high_risk_2 = array($highrisk2);
    // }
    // if (isset($_POST['highrisk3'])) {
    //     $highrisk3 = implode(', ', $_POST['highrisk3']);
    //     $high_risk_3 = array($highrisk3);
    // }

    // query for data selection - workplace_inspection_subscore
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
        // $infos['high-risk-update-success'] = "Updated successfully";
        // header("location: assessment-workplace-inspection.php?assessee_id=" . $assessee_id . "&info=" . $info);
    }

    // query for data selection - workplace_inspection_high_risk_1
    $sql1 = "SELECT * FROM workplace_inspection_high_risk_1 WHERE assessment_id=:assessee_id AND workplace_inspection_checklist_id=:workplace_inspection_checklist_id";
    $query1 = $dbh->prepare($sql1);
    $query1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
    $query1->bindParam(':workplace_inspection_checklist_id', $workplace_inspection_checklist_id, PDO::PARAM_STR);
    $query1->execute();
    $results1 = $query1->fetchAll(PDO::FETCH_OBJ);

    if ($query1->rowCount() > 0) {
        foreach ($results1 as $result1) {
                //query for updation - workplace_inspection_high_risk_1
                $con1 = "UPDATE workplace_inspection_high_risk_1 SET status=:highrisk1 WHERE assessment_id=:assessee_id AND workplace_inspection_checklist_id=:workplace_inspection_checklist_id";
                // $con1 = "UPDATE workplace_inspection_high_risk_1 SET status=? WHERE assessment_id=:assessee_id AND workplace_inspection_checklist_id=?";
                $update1 = $dbh->prepare($con1);
                $update1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                $update1->bindParam(':highrisk1', $highrisk1, PDO::PARAM_STR);
                $update1->bindParam(':workplace_inspection_checklist_id', $workplace_inspection_checklist_id, PDO::PARAM_STR);
                $update1->execute();
                // $update1->execute([$high_risk_1, $workplace_inspection_checklist_id[$loop]]);
        }
        $infos['high-risk-update-success'] = "Updated successfully";
        // header("location: assessment-workplace-inspection.php?assessee_id=" . $assessee_id . "&info=" . $info);
    }

    // // query for data selection - workplace_inspection_high_risk_1
    // $sql1 = "SELECT * FROM workplace_inspection_high_risk_1 WHERE assessment_id=:assessee_id";
    // $query1 = $dbh->prepare($sql1);
    // $query1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
    // $query1->execute();
    // $results1 = $query1->fetchAll(PDO::FETCH_OBJ);

    // if ($query1->rowCount() > 0) {
    //     foreach ($results1 as $result1) {
    //         for ($loop = 0; $loop < $checklist_count; $loop++) {
    //             //query for updation - workplace_inspection_high_risk_1
    //             $workplace_inspection_checklist_id_count = $workplace_inspection_checklist_id[$loop];
    //             $con1 = "UPDATE workplace_inspection_high_risk_1 SET status=:highrisk1 WHERE assessment_id=:assessee_id AND workplace_inspection_checklist_id=:workplace_inspection_checklist_id_count";
    //             // $con1 = "UPDATE workplace_inspection_high_risk_1 SET status=? WHERE assessment_id=:assessee_id AND workplace_inspection_checklist_id=?";
    //             $update1 = $dbh->prepare($con1);
    //             $update1->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
    //             $update1->bindParam(':highrisk1', $highrisk1, PDO::PARAM_STR);
    //             $update1->bindParam(':workplace_inspection_checklist_id_count', $workplace_inspection_checklist_id_count, PDO::PARAM_STR);
    //             $update1->execute();
    //             // $update1->execute([$high_risk_1, $workplace_inspection_checklist_id[$loop]]);
    //         }
    //     }
    //     $infos['high-risk-update-success'] = "Updated successfully";
    //     // header("location: assessment-workplace-inspection.php?assessee_id=" . $assessee_id . "&info=" . $info);
    // }

    // query for data selection - workplace_inspection_high_risk_2
    // $sql2 = "SELECT * FROM workplace_inspection_high_risk_2 WHERE assessment_id=:assessee_id";
    // $query2 = $dbh->prepare($sql2);
    // $query2->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
    // $query2->execute();
    // $results2 = $query2->fetchAll(PDO::FETCH_OBJ);

    // if ($query2->rowCount() > 0) {
    //     foreach ($results2 as $result2) {
    //         //query for updation - workplace_inspection_high_risk_2
    //         $workplace_inspection_checklist_id = $_POST['workplace_inspection_checklist_id'];
    //         $con2 = "UPDATE workplace_inspection_high_risk_2 SET status=:high_risk_2 WHERE assessment_id=:assessee_id AND workplace_inspection_checklist_id=:workplace_inspection_checklist_id";
    //         $update2 = $dbh->prepare($con2);
    //         $update2->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
    //         $update2->bindParam(':high_risk_2', $high_risk_2, PDO::PARAM_STR);
    //         $update2->bindParam(':workplace_inspection_checklist_id', $workplace_inspection_checklist_id, PDO::PARAM_STR);
    //         $update2->execute();
    //     }
    //     // $infos['high-risk-update-success'] = "Updated successfully";
    //     // header("location: assessment-workplace-inspection.php?assessee_id=" . $assessee_id . "&info=" . $info);
    // }

    // // query for data selection - workplace_inspection_high_risk_3
    // $sql3 = "SELECT * FROM workplace_inspection_high_risk_3 WHERE assessment_id=:assessee_id";
    // $query3 = $dbh->prepare($sql3);
    // $query3->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
    // $query3->execute();
    // $results3 = $query3->fetchAll(PDO::FETCH_OBJ);

    // if ($query3->rowCount() > 0) {
    //     foreach ($results3 as $result3) {
    //         //query for updation - workplace_inspection_high_risk_2
    //         $workplace_inspection_checklist_id = $_POST['workplace_inspection_checklist_id'];
    //         $con3 = "UPDATE workplace_inspection_high_risk_3 SET status=:high_risk_3 WHERE assessment_id=:assessee_id AND workplace_inspection_checklist_id=:workplace_inspection_checklist_id";
    //         $update3 = $dbh->prepare($con3);
    //         $update3->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
    //         $update3->bindParam(':high_risk_3', $high_risk_3, PDO::PARAM_STR);
    //         $update3->bindParam(':workplace_inspection_checklist_id', $workplace_inspection_checklist_id, PDO::PARAM_STR);
    //         $update3->execute();
    //     }
    // $infos['high-risk-update-success'] = "Updated successfully";
    // header("location: assessment-workplace-inspection.php?assessee_id=" . $assessee_id . "&info=" . $info);
    // }
}

//the workplace inspection total score is changed every time the sub score is updated
