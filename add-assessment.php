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

    <title>SHASSIC | Add New Assessment</title>

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

                                    <!-- Outer Row -->
                                    <div class="row justify-content-center">
                                        <div class="col-xl-12 col-lg-12 col-md-9">
                                            <div class="card o-hidden border-0 shadow-lg my-5">
                                                <div class="p-0" id="card-body">
                                                    <div id="row">
                                                        <div class="col-lg-12">
                                                            <div class="p-5">
                                                                <div class="text-center">
                                                                    <div class="text-center" style="display:flex; width:auto; justify-content: start;">
                                                                        <a class="font-weight-bold" href="index.php">
                                                                            < Back</a>
                                                                    </div>

                                                                    <!-- Page Heading -->
                                                                    <h1 class="h3 mb-4 text-gray-800 font-weight-bold">Add New Assessment</h1>
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

                                                                <form class="user" method="POST" enctype="multipart/form-data">
                                                                    <div class="form-group">
                                                                        <input type="hidden" class="form-control form-control-user font-weight-bold" name="assessor_id" id="assessor_id" value="<?php echo htmlentities($result->id); ?>">
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <input type="hidden" class="form-control form-control-user font-weight-bold" name="assessor_name" id="assessor_name" value="<?php echo htmlentities($result->fullname); ?>">
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <input type="text" class="form-control form-control-user font-weight-bold" name="assessee_name" id="assessee_name" placeholder="Assessee Name" required>
                                                                    </div>
                                                                    <div class="form-group" id="row">
                                                                        <div class="col-sm-6 mb-3 mb-sm-0">
                                                                            <input type="text" class="form-control form-control-user font-weight-bold" name="project_name" id="project_name" placeholder="Project Name" required>
                                                                        </div>
                                                                        <div class="col-sm-6 mb-3 mb-sm-0">
                                                                            <input type="text" onfocus="(this.type='date')" onchange="(this.type='date')" class="form-control form-control-user font-weight-bold" name="project_date" id="project_date" required placeholder="Project Date" date_format='dd/mm/yyyy'>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group" id="row">
                                                                        <div class="col-sm-6 mb-3 mb-sm-0">
                                                                            <input type="text" class="form-control form-control-user font-weight-bold" name="project_location" id="project_location" placeholder="Project Location" required>
                                                                        </div>
                                                                        <div class="col-sm-6">
                                                                            <input placeholder="Project Image" type="text" onfocus="(this.type='file')" class="form-control form-control-user font-weight-bold" name="project_picture" id="project_picture" accept="image/*" onchange="document.getElementById('project_picture').src = window.URL.createObjectURL(this.files[0])" required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group" id="row">
                                                                        <div class="col-sm-4 mb-3 mb-sm-0"></div>
                                                                        <div class="col-sm-4 mb-3 mb-sm-0">
                                                                            <button type="submit" class="btn btn-primary btn-user btn-block font-weight-bold" name="add">Add</button>
                                                                        </div>
                                                                        <div class="col-sm-4 mb-3 mb-sm-0"></div>
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