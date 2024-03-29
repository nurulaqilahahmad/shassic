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

    <title>SHASSIC | Edit Assessment</title>

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
                <a href="landing.php" class="d-flex landing-a">
                    <h1 style="font-size: 24px; font-weight: 700; color: #fff; margin-top: 3rem; margin-bottom: 3rem">SHASSIC<span style="color: #558381;">.</span></h1>
                </a>
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
                                    <div class="card shadow mb-4">
                                        <div class="card-header py-3">
                                            <div class="text-center" id="row">
                                                <div class="col-sm-6" style="display:flex; width:auto; justify-content: start;">
                                                    <a class="font-weight-bold" href="landing.php">
                                                        < Back </a>
                                                </div>
                                                <div class="col-sm-6" style="display:flex; width:auto; justify-content: end;">
                                                    <a class="font-weight-bold" href="assessment-component.php?assessee_id=<?php echo htmlentities($result->assessee_id); ?>">
                                                        Next ></a>
                                                </div>
                                            </div>
                                            <!-- Page Heading -->
                                            <h1 class="h3 mb-4 text-gray-800 font-weight-bold">Edit Assessment</h1>
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
                                            } ?>
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
                                            } ?>


                                            <form class="user" method="POST" enctype="multipart/form-data">
                                                <div class="form-group">
                                                    <input type="hidden" class="form-control form-control-user font-weight-bold" name="assessor_id" id="assessor_id" value="<?php echo htmlentities($result->assessor_id); ?>">
                                                </div>
                                                <div class="form-group">
                                                    <input type="hidden" class="form-control form-control-user font-weight-bold" name="assessor_name" id="assessor_name" value="<?php echo htmlentities($result->assessor_name); ?>">
                                                </div>
                                                <div class="form-group">
                                                    <input type="hidden" class="form-control form-control-user font-weight-bold" name="assessee_id" id="assessee_id" value="<?php echo htmlentities($result->assessee_id); ?>">
                                                </div>
                                                <div class="form-group">
                                                    <img class="img-profile" src="img/project-image/<?php echo htmlentities($result->project_picture); ?>" height="20%" width="20%">
                                                </div>
                                                <div class="form-group">
                                                    <input type="text" class="form-control form-control-user font-weight-bold" name="assessee_name" id="assessee_name" placeholder="Assessee Name" required value="<?php echo htmlentities($result->assessee_name); ?>">
                                                </div>
                                                <div class="form-group" id="row">
                                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                                        <input type="text" class="form-control form-control-user font-weight-bold" name="project_name" id="project_name" placeholder="Project Name" required value="<?php echo htmlentities($result->project_name); ?>">
                                                    </div>
                                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                                        <!-- <input type="text" onfocus="(this.type='date')" onchange="(this.type='date')" class="form-control form-control-user font-weight-bold" name="project_date" id="project_date" required placeholder="Project Date" date_format='dd/mm/yyyy' value="<?php echo date_format(new DateTime($result->project_date), 'd/m/Y'); ?>"> -->
                                                        <input type="date" class="form-control form-control-user font-weight-bold" name="project_date" id="project_date" required placeholder="Project Date" date_format='dd/mm/yyyy' value="<?php echo htmlentities($result->project_date); ?>">
                                                    </div>
                                                </div>
                                                <div class="form-group" id="row">
                                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                                        <input type="text" class="form-control form-control-user font-weight-bold" name="project_location" id="project_location" placeholder="Project Location" required value="<?php echo htmlentities($result->project_location); ?>">
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="file" class="form-control form-control-user font-weight-bold" name="project_picture" id="project_picture" placeholder="Project Picture" accept="image/*" />
                                                        <input type="text" hidden class="form-control form-control-user font-weight-bold" name="old_project_picture" id="old_project_picture" value="<?php echo htmlentities($result->project_picture); ?>" />
                                                    </div>
                                                </div>
                                                <div class="form-group" id="row">
                                                    <div class="col-sm-4 mb-3 mb-sm-0"></div>
                                                    <div class="col-sm-4 mb-3 mb-sm-0">
                                                        <button type="submit" class="btn btn-primary btn-user btn-block font-weight-bold" name="update">Update</button>
                                                    </div>
                                                    <div class="col-sm-4 mb-3 mb-sm-0"></div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                </div>
                                <!-- container-fluid -->

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
                    <span>Copyright &copy; SHASSIC 2023</span>
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