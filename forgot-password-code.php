<?php require_once "controller.php"; ?>
<?php
$email = $_SESSION['email'];
if ($email == false) {
    header('location: login.php');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SHASSIC | Forgot Password</title>

    <link rel="icon" type="image/x-icon" href="img/favicon.png">

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Poppins:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/landing.css">

</head>

<body class="bg-gradient-primary">

    <div class="landing-container">


        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-7 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row justify-content-center">
                            <div class="col-lg-12">
                                <div class="p-5">
                                    <form class="user" method="post">
                                        <div class="text-center">
                                            <h1 class="h4 text-gray-900 mb-4 font-weight-bold">Code Verification</h1>
                                            <?php
                                            if ($_SESSION['info'] != "") {
                                            ?>
                                                <div class="col-lg-12 mb-4">
                                                    <div class="card bg-success text-white shadow">
                                                        <div class="card-body text-center font-weight-bold" style="margin: 10px">
                                                            <?php echo $_SESSION['info']; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php
                                            }
                                            ?>
                                            <?php
                                            if (count($errors) > 0) {
                                            ?>
                                                <div class="col-lg-12 mb-4">
                                                    <div class="card bg-danger text-white shadow">
                                                        <div class="card-body text-center font-weight-bold" style="margin: 10px;">
                                                            <?php foreach ($errors as $error) {
                                                                echo $error;
                                                            } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php
                                            }
                                            ?>
                                        </div>
                                        <div class="form-group mb-4">
                                            <input type="number" class="form-control form-control-user font-weight-bold" id="password_code" name="password_code" aria-describedby="emailHelp" placeholder="Code" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-user btn-block font-weight-bold" name="check-pwcode">Submit</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

</body>

</html>