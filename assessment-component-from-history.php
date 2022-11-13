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

    <title>SHASSIC | Assessment</title>

    <link rel="icon" type="image/x-icon" href="img/favicon.png">
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> -->

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
                <!-- <nav class="landing-nav">
                    <ul class="landing-ul" id="menuList">
                        <li class="landing-li"><a href="about.php" class="landing-a">ABOUT</a></li>
                    </ul>
                </nav> -->
                <img src="img/landing/menu.png" class="menu-icon" onclick="togglemenu()">
            </div>

            <!-- Page Wrapper -->
            <div id="wrapper">

                <?php
                $email = $_SESSION['login'];
                $assessee_id = $_GET['assessee_id'];
                $sql = "SELECT * from assessment where assessee_id=:assessee_id";
                $query = $dbh->prepare($sql);
                $query->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
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

                                    <!-- Outer Row -->
                                    <div class="row justify-content-center">


                                        <div class="col-xl-12 col-lg-12 col-md-9">
                                            <div class="card o-hidden border-0 shadow-lg my-5">
                                                <div class="p-0" id="card-body">
                                                    <div id="row">
                                                        <div class="col-lg-12">
                                                            <div class="p-5">
                                                                <div class="text-center">
                                                                    <div class="text-center" id="row">
                                                                        <div class="col-sm-6" style="display:flex; width:auto; justify-content: start;">
                                                                            <a class="font-weight-bold" href="edit-assessment-from-history.php?assessee_id=<?php echo htmlentities($result->assessee_id); ?>">
                                                                                &larr; Back</a>
                                                                        </div>
                                                                        <div class="col-sm-6" style="display:flex; width:auto; justify-content: end;">
                                                                            <a class="font-weight-bold" href="#">
                                                                                Next &rarr;</a>
                                                                        </div>
                                                                    </div>

                                                                    <!-- Page Heading -->
                                                                    <h1 class="h3 mb-4 text-gray-800 font-weight-bold"><?php echo htmlentities($result->assessee_name); ?> - <?php echo htmlentities($result->project_name); ?></h1>
                                                                    <?php
                                                                    if ($_SESSION['info'] != "") {
                                                                    ?>
                                                                        <div class="col-lg-12 mb-4">
                                                                            <div class="card bg-success text-white shadow">
                                                                                <div class="card-body text-center font-weight-bold">
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
                                                                </div>

                                                                <form class="user" method="POST">
                                                                    <div class="form-group" id="row">
                                                                        <div class="col-sm-4 mb-3 mb-sm-0">
                                                                            <a href="assessment-document-check.php">
                                                                                <div class="card mb-4">
                                                                                    <div class="card-body card-hover py-3">
                                                                                        <h6 class="m-0 font-weight-bold">Document Check</h6>
                                                                                    </div>
                                                                                </div>
                                                                            </a>
                                                                        </div>
                                                                        <div class="col-sm-4 mb-3 mb-sm-0">
                                                                            <a href="assessment-workplace-inspection.php">
                                                                                <div class="card mb-4">
                                                                                    <div class="card-body card-hover py-3">
                                                                                        <h6 class="m-0 font-weight-bold">Workplace Inspection</h6>
                                                                                    </div>
                                                                                </div>
                                                                            </a>
                                                                        </div>
                                                                        <div class="col-sm-4 mb-3 mb-sm-0">
                                                                            <a href="assessment-personnel-interview.php">
                                                                                <div class="card mb-4">
                                                                                    <div class="card-body card-hover py-3">
                                                                                        <h6 class="m-0 font-weight-bold">Personnel Interview</h6>
                                                                                    </div>
                                                                                </div>
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>


                                    </div>

                                </div>
                                <!-- /.container-fluid -->

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