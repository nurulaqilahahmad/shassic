<?php
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

    <title>SHASSIC | Edit Password</title>

    <link rel="icon" type="image/x-icon" href="img/favicon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

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
            <div class="landing-navbar">
                <img src="img/landing/logo.png" class="landing-logo">
                <img src="img/landing/menu.png" class="menu-icon" onclick="togglemenu()">
            </div>

            <!-- Page Wrapper -->
            <div id="wrapper">
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

                        <!-- Content Wrapper -->
                        <div id="content-wrapper" class="d-flex flex-column">

                            <!-- Main Content -->
                            <div id="content">

                                <!-- Begin Page Content -->
                                <div class="container-fluid">

                                    <div class="card shadow mb-4">

                                        <!-- Title and back button -->
                                        <div class="card-header py-3">
                                            <div class="text-center" id="row">
                                                <div class="col-sm-6" style="display:flex; width:auto; justify-content: start;">
                                                    <a class="font-weight-bold" href="edit-profile.php">
                                                        < Back</a>
                                                </div>
                                            </div>
                                            <!-- Page Heading -->
                                            <h1 class="h3 mb-4 text-gray-800 font-weight-bold">Edit Password</h1>
                                        </div>

                                        <div class="card-body">
                                            <?php
                                            if (count($infos) > 0) {
                                            ?>
                                                <div class="col-lg-12 mb-4">
                                                    <div class="card bg-success text-white shadow">
                                                        <div class="card-body text-center" style="font-weight: bold;">
                                                            <?php foreach ($infos as $info) {
                                                                echo $info;
                                                            } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php
                                            }
                                            ?>
                                            <!-- Display error if any -->
                                            <?php
                                            if (count($errors) > 0) {
                                            ?>
                                                <div class="col-lg-12 mb-4">
                                                    <div class="card bg-danger text-white shadow">
                                                        <div class="card-body text-center" style="font-weight: bold;">
                                                            <?php foreach ($errors as $error) {
                                                                echo $error;
                                                            } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php
                                            }
                                            ?>

                                            <form class="user" method="POST">
                                                <?php if (isset($_GET['success'])) { ?>
                                                    <p class="success"><?php echo $_GET['success']; ?></p>
                                                <?php } ?>
                                                <?php if (isset($_GET['wrong'])) { ?>
                                                    <p class="wrong"><?php echo $_GET['wrong']; ?></p>
                                                <?php } ?>

                                                <div class="form-group">
                                                    <input type="hidden" class="form-control form-control-user font-weight-bold" name="id" id="id" value="<?php echo htmlentities($result->id); ?>">
                                                </div>

                                                <div class="form-group">
                                                    <input type="hidden" class="form-control form-control-user font-weight-bold" name="email" id="email" value="<?php echo htmlentities($result->email); ?>">
                                                </div>

                                                <div class="form-group" id="row">
                                                    <div class="col-sm-4 mb-3 mb-sm-0"></div>
                                                    <div class="col-sm-4 mb-3 mb-sm-0">
                                                        <label><b>Old Password</b></label>
                                                        <input type="password" class="form-control form-control-user font-weight-bold" name="op" id="op" placeholder="Old Password">
                                                    </div>
                                                    <div class="col-sm-4 mb-3 mb-sm-0"></div>
                                                </div>

                                                <div class="form-group" id="row">
                                                    <div class="col-sm-4 mb-3 mb-sm-0"></div>
                                                    <div class="col-sm-4 mb-3 mb-sm-0">
                                                        <label><b>New Password</b></label>
                                                        <input type="password" class="form-control form-control-user font-weight-bold" name="np" id="np" placeholder="New Password">
                                                    </div>
                                                    <div class="col-sm-4 mb-3 mb-sm-0"></div>
                                                </div>

                                                <div class="form-group" id="row">
                                                    <div class="col-sm-4 mb-3 mb-sm-0"></div>
                                                    <div class="col-sm-4 mb-3 mb-sm-0">
                                                        <label><b>Confirm New Password</b></label>
                                                        <input type="password" class="form-control form-control-user font-weight-bold" name="c_np" id="c_np" placeholder="Confirm New Password">
                                                    </div>
                                                    <div class="col-sm-4 mb-3 mb-sm-0"></div>
                                                </div>

                                                <div class="form-group" id="row">
                                                    <div class="col-sm-4 mb-3 mb-sm-0"></div>
                                                    <div class="col-sm-4 mb-3 mb-sm-0">
                                                        <button type="submit" class="btn btn-primary btn-user btn-block font-weight-bold" name="edit_password">Edit</button>
                                                    </div>
                                                    <div class="col-sm-4 mb-3 mb-sm-0"></div>
                                                </div>
                                            </form>

                                        </div>

                                    </div>
                                    <!-- /.container-fluid -->

                                </div>
                                <!-- End of Page Content -->

                            </div>
                            <!-- End of Main Content -->

                        </div>
                        <!-- End of Content Wrapper -->
                <?php }
                } ?>
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

            <!-- Bootstrap core JavaScript-->
            <script src="vendor/jquery/jquery.min.js"></script>
            <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

            <!-- Core plugin JavaScript-->
            <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

            <!-- Custom scripts for all pages-->
            <script src="js/sb-admin-2.min.js"></script>
        </div>
    <?php } else {
        header("location: login.php");
    } ?>
</body>

</html>