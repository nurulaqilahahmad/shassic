<?php
session_start();
error_reporting(0);
include('includes/config.php');
require_once "controller.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SHASSIC | Dashboard</title>

    <link rel="icon" type="image/x-icon" href="img/favicon.png">

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Poppins:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/landing.css">

</head>

<body id="page-top">

    <?php if ($_SESSION['login']) { ?>

        <div class="landing-container">
            <?php
            $email = $_SESSION['login'];
            $sql = "SELECT * from user where email=:email";
            $query = $dbh->prepare($sql);
            $query->bindParam(':email', $email, PDO::PARAM_STR);
            $query->execute();
            $results = $query->fetchAll(PDO::FETCH_OBJ);
            $cnt = 1;
            if ($query->rowCount() > 0) {
                foreach ($results as $result) {
            ?>
                    <div class="landing-navbar">
                        <img src="img/landing/logo.png" class="landing-logo">
                        <nav class="landing-nav">
                            <ul class="landing-ul" id="menuList">
                                <li class="landing-li"><a href="about.php" class="landing-a">ABOUT</a></li>
                            </ul>
                        </nav>
                        <img src="img/landing/menu.png" class="menu-icon" onclick="togglemenu()">
                    </div>


                    <!-- Page Wrapper -->
                    <div id="wrapper">

                        <!-- Sidebar -->
                        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

                            <!-- Sidebar - Brand -->
                            <!-- <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html"> -->
                            <!-- <div class="sidebar-brand-icon rotate-n-15">
                            <i class="fas fa-laugh-wink"></i>
                        </div> -->
                            <!-- <div class="sidebar-brand-text mx-3"><?php echo htmlentities($_SESSION['username']); ?></div> -->
                            <!-- <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo htmlentities($_SESSION['username']); ?></span>
                            </a> -->

                            <li class="nav-item">
                                <a class="nav-link disabled" style="text-align: center;">
                                    <img class="img-profile rounded-circle" src="img/undraw_profile.svg" href="user-profile.php">
                                </a>

                            </li>

                            <li class="nav-item">
                                <a class="nav-link disabled font-weight-bold text-center" href="index.html">
                                    <span><?php echo htmlentities($result->fullname); ?></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link disabled font-weight-bold text-center" href="index.html">
                                    <span>@<?php echo htmlentities($result->username); ?></span>
                                </a>
                            </li>

                            <!-- Divider -->
                            <hr class="sidebar-divider">

                            <!-- Heading -->
                            <div class="sidebar-heading">
                                Tasks
                            </div>

                            <li class="nav-item">
                                <a class="nav-link font-weight-bold" href="#">

                                    <span>Edit Profile</span>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link font-weight-bold" href="history.php">
                                    <span>History</span>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link font-weight-bold" href="#" style="color:red;" data-toggle="modal" data-target="#logoutModal">
                                    <span>Logout</span>
                                </a>
                            </li>

                            <!-- Nav Item - Dashboard -->
                            <!-- <li class="nav-item active">
                                <a class="nav-link" href="index.html" style="text-align: center;"> -->
                            <!-- <i class="fas fa-fw fa-tachometer-alt"></i> -->
                            <!-- <img class="img-profile rounded-circle" src="img/undraw_profile.svg"><br>
                                    <span><?php echo htmlentities($result->fullname); ?></span>
                                </a>
                            </li> -->

                            <!-- Divider -->
                            <!-- <hr class="sidebar-divider"> -->



                        </ul>
                        <!-- End of Sidebar -->

                        <!-- Content Wrapper -->
                        <div id="content-wrapper" class="d-flex flex-column">

                            <!-- Main Content -->
                            <div id="content justify-content-center " style="margin-top: auto; margin-bottom: auto;">

                                <!-- Begin Page Content -->
                                <div class="container-fluid">

                                    <!-- Page Heading -->
                                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                                        <h1 class="h1 mb-0 text-white font-weight-bold">SHASSIC</h1>
                                    </div>

                                    <!-- Content Row -->
                                    <div class="row">

                                        <h5 class="h5 mb-5 text-justify text-white font-weight-bold">an independent method to assess and evaluate the safety and health performance of a contractor in construction works / projects</h5>

                                    </div>

                                    <!-- <div class="row">

                                    <button type="submit" class="mt-5 d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm font-weight-bold" name="add" style="margin-top: 10rem;">Add New Assessment</button>

                                    </div> -->

                                    <div class="row text-center">
                                        <a class="mt-5 d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm font-weight-bold" href="assessment.php" style="margin-top: 10rem;">Add New Assessment</a>
                                    </div>
                                </div>

                            </div>
                            <!-- End of Main Content -->



                        </div>
                        <!-- End of Content Wrapper -->

                    </div>
                    <!-- End of Page Wrapper -->

                    <!-- Footer -->
                    <footer class="small">
                        <div class="container my-auto justify-content-center">
                            <div class="copyright text-center my-auto">
                                <span>Copyright &copy; SHASSIC 2022</span>
                            </div>
                        </div>
                    </footer>
                    <!-- End of Footer -->

                    <!-- Scroll to Top Button-->
                    <a class="scroll-to-top rounded" href="#page-top">
                        <!-- <i class="fas fa-angle-up"></i> -->
                        &uarr;
                    </a>

                    <!-- Logout Modal-->
                    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title font-weight-bold" id="exampleModalLabel">Ready to Leave?</h5>
                                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                                <div class="modal-body font-weight-bold">Select "Logout" below if you are ready to end your current session.</div>
                                <div class="modal-footer">
                                    <button class="btn btn-secondary font-weight-bold" type="button" data-dismiss="modal">Cancel</button>
                                    <a class="btn btn-primary font-weight-bold" href="logout.php">Logout</a>
                                </div>
                            </div>
                        </div>
                    </div>
            <?php }
            } ?>
        </div>
        <!-- Bootstrap core JavaScript-->
        <script src="vendor/jquery/jquery.min.js"></script>
        <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

        <!-- Core plugin JavaScript-->
        <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

        <!-- Custom scripts for all pages-->
        <script src="js/sb-admin-2.min.js"></script>

        <!-- Page level plugins -->
        <script src="vendor/chart.js/Chart.min.js"></script>

        <!-- Page level custom scripts -->
        <script src="js/demo/chart-area-demo.js"></script>
        <script src="js/demo/chart-pie-demo.js"></script>

    <?php } ?>
</body>

</html>