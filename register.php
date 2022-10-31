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

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Poppins:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/main.css">

</head>

<!-- <body class="bg-gradient-primary"> -->
<body>

    <!-- <div class="container"> -->
    <div class="landing-container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

    <!-- <div class="container"> -->
    <section class="sec">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="p-0" id="card-body">
                        <!-- Nested Row within Card Body -->
                        <div id="row">
                            <div class="col-lg-12">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4" style="font-weight: bold;">Create an Account!</h1>
                                    </div>
                                    <form class="user" method="POST">
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user" name="fullname" id="fullname" placeholder="Full Name" required style="font-weight: bold;">
                                        </div>
                                        <div class="form-group" id="row">
                                            <div class="col-sm-6 mb-3 mb-sm-0">
                                                <input type="text" class="form-control form-control-user" name="username" id="username" placeholder="Username" required style="font-weight: bold;">
                                            </div>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control form-control-user" name="code" id="code" placeholder="Code" required style="font-weight: bold;">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <input type="email" class="form-control form-control-user" name="email" id="email" placeholder="Email Address" required style="font-weight: bold;">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user" name="password" id="password" placeholder="Password" required style="font-weight: bold;">
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-user btn-block" name="register" style="font-weight: bold;">Register Account</button>
                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="login.php" style="font-weight: bold;">Already have an account? Login!</a>
                                    </div>
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

        window.addEventListener('scroll', function(){
            let value = window.scrollY;
            text.style.marginTop = value * 1.5 + 'px';
            button.style.marginTop = value * 1.5 + 'px';
        })
    </script>

</body>

</html>