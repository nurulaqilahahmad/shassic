<?php require_once "controller.php"; ?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SHASSIC | Register</title>

    <link rel="icon" type="image/x-icon" href="img/favicon.png">

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Poppins:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/landing.css">

    <!-- <style type="text/css" media="print">
        @media print{
            .noprint, .noprint *{
                display: none;
            }
        }
    </style> -->

</head>

<!-- <body class="bg-gradient-primary"> -->

<body>

    <!-- <div class="container"> -->
    <div class="landing-container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <!-- <div class="container"> -->
            <div class="col-xl-7 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="p-0" id="card-body">
                        <!-- Nested Row within Card Body -->
                        <div id="row">
                            <div class="col-lg-12">
                                <div class="p-5">
                                    <div class="text-center">
                                        <a href="landing.php"><img src="img/shassic-logo.jpg" width="200px""></a>
                                        <br>
                                        <br>
                                    </div>
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4 font-weight-bold">Create an Account!</h1>
                                        <?php
                                        if ($_SESSION['info'] != "") {
                                        ?>
                                            <div class="col-lg-12 mb-4">
                                                <div class="card bg-success text-white shadow">
                                                    <div class="card-body text-center font-weight-bold" style="margin: 10px">
                                                        <?php echo $_SESSION['info']; ?><a class="small font-weight-bold" href="login.php">Login Now</a>
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
                                    <form class="user" method="POST">
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user font-weight-bold" name="fullname" id="fullname" placeholder="Full Name" required>
                                        </div>
                                        <div class="form-group" id="row">
                                            <div class="col-sm-6 mb-3 mb-sm-0">
                                                <input type="text" class="form-control form-control-user font-weight-bold" name="username" id="username" placeholder="Username" required>
                                            </div>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control form-control-user font-weight-bold" name="code" id="code" placeholder="Code" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <input type="email" class="form-control form-control-user font-weight-bold" name="email" id="email" placeholder="Email Address" required>
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user font-weight-bold" name="password" id="password" placeholder="Password" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-user btn-block font-weight-bold" name="register">Register Account</button>
                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small font-weight-bold" href="login.php">Already have an account? Login!</a>
                                    </div>
                                    <!-- <div class="text-center">
                                        <button type="submit" class="btn btn-primary btn-user btn-block noprint" onclick="window.print()" style="font-weight: bold;">Print</button>
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

    <!-- Animated Web Design -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.3/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.3/ScrollTrigger.min.js"></script>
    <script>
        let text = document.getElementById('text');
        let cartoon = document.getElementById('cartoon');
        let button = document.getElementById('button');

        window.addEventListener('scroll', function() {
            let value = window.scrollY;
            text.style.marginTop = value * 1.5 + 'px';
            button.style.marginTop = value * 1.5 + 'px';
        })
    </script>

</body>

</html>