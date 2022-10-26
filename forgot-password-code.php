<?php require_once "controller.php"; ?>
<?php 
$email = $_SESSION['email'];
if($email == false){
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

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Poppins:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/main.css">

</head>

<body class="bg-gradient-primary">

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-password-image"></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-2">Code Verification</h1>
                                        <br>
                                        <!-- <?php
                                        if (isset($_SESSION['info'])) {
                                        ?>
                                            <div class="alert alert-success text-center" style="padding: 0.4rem 0.4rem">
                                                <?php echo $_SESSION['info']; ?>
                                            </div>
                                        <?php
                                        }
                                        ?>
                                        <?php
                                        if (count($error) > 0) {
                                        ?>
                                            <div class="alert alert-danger text-center">
                                                <?php
                                                foreach ($error as $showerror) {
                                                    echo $showerror;
                                                }
                                                ?>
                                            </div>
                                        <?php
                                        }
                                        ?> -->
                                    </div>
                                    <form class="user" method="post">
                                        <div class="form-group">
                                            <input type="number" class="form-control form-control-user" id="password_code" name="password_code" aria-describedby="emailHelp" placeholder="Code" required>
                                        </div>
                                        <br>
                                        <button type="submit" class="btn btn-primary btn-user btn-block" name="check-pwcode">Submit</button>
                                        <!-- <a href="login.html" class="btn btn-primary btn-user btn-block">
                                            Reset Password
                                        </a> -->
                                    </form>
                                    <!-- <hr>
                                    <div class="text-center">
                                        <a class="small" href="register.php">Create an Account!</a>
                                    </div>
                                    <div class="text-center">
                                        <a class="small" href="login.php">Already have an account? Login!</a>
                                    </div> -->
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