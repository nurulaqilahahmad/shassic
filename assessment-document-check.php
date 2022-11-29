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

    <title>SHASSIC | Document Check</title>

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
                                                                <!-- Page Heading -->
                                                                <div class="text-center">
                                                                    <div class="card-header py-3">
                                                                        <div class="text-center" style="display:flex; width:auto; justify-content: start;">
                                                                            <a class="font-weight-bold" href="assessment-component.php?assessee_id=<?php echo htmlentities($result->assessee_id); ?>">
                                                                                &larr; Back</a>
                                                                        </div>
                                                                        <h1 class="h3 mb-4 text-gray-800 font-weight-bold">Document Check</h1>
                                                                    </div>
                                                                </div>

                                                                <div class="card-body">
                                                                    <form class="" action="" method="POST">
                                                                        <div class="table-responsive">
                                                                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th>Item</th>
                                                                                        <th>Checklist</th>
                                                                                        <th>C</th>
                                                                                        <th>NC</th>
                                                                                        <th>NA</th>
                                                                                        <th>Remarks</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tfoot>
                                                                                    <tr>
                                                                                        <th colspan="2">TOTAL SCORE</th>
                                                                                        <th id="selectedC">0</th>
                                                                                        <th id="selectedNC">0</th>
                                                                                        <th id="selectedNA">0</th>
                                                                                        <th id="selectedTotal"></th>
                                                                                        <!-- <input type="number" class="form-control form-control-user font-weight-bold" 
                                                                                        name="document_check_percentage" id="selected" value hidden="7"> -->
                                                                                    </tr>
                                                                                </tfoot>
                                                                                <tbody>
                                                                                    <?php
                                                                                    $sql = "SELECT * from document_check_section";
                                                                                    $query = $dbh->prepare($sql);
                                                                                    $query->execute();
                                                                                    $sections = $query->fetchAll(PDO::FETCH_OBJ);
                                                                                    $cnt = 1;
                                                                                    if ($query->rowCount() > 0) {
                                                                                        foreach ($sections as $section) {
                                                                                    ?>
                                                                                            <tr>
                                                                                                <th><?php echo htmlentities($section->item_no) ?></th>
                                                                                                <th colspan="5" class="text-left"><?php echo htmlentities($section->item_name) ?></th>
                                                                                            </tr>
                                                                                            <?php
                                                                                            $sql = "SELECT * from document_check_checklist where item_id='$section->id'";
                                                                                            $query = $dbh->prepare($sql);
                                                                                            $query->execute();
                                                                                            $checklists = $query->fetchAll(PDO::FETCH_OBJ);
                                                                                            $cnt = 1;
                                                                                            if ($query->rowCount() > 0) {
                                                                                                foreach ($checklists as $checklist) {
                                                                                            ?>
                                                                                                    <tr>
                                                                                                        <td><?php echo htmlentities($cnt++) ?></td>
                                                                                                        <td class="text-left"><?php echo htmlentities($checklist->checklist) ?></td>
                                                                                                        <td><input type="checkbox" class="checkbox1" onclick="countSelected()"></td>
                                                                                                        <td><input type="checkbox" class="checkbox2" onclick="countSelected()"></td>
                                                                                                        <td><input type="checkbox" class="checkbox3" onclick="countSelected()"></td>
                                                                                                        <td></td>
                                                                                                    </tr>
                                                                                            <?php }
                                                                                            } ?>
                                                                                    <?php }
                                                                                    } ?>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>

                                                                        <!-- <form class="" action="" method="POST"> -->
                                                                        <div class="form-group" id="row">
                                                                            <div class="col-sm-4 mb-3 mb-sm-0"></div>
                                                                            <div class="col-sm-4 mb-3 mb-sm-0">
                                                                                <div class="form-group">
                                                                                    <input type="hidden" class="form-control form-control-user font-weight-bold" name="assessee_id" id="assessee_id" value="<?php echo htmlentities($result->assessee_id); ?>">
                                                                                    <input type="hidden" class="form-control form-control-user font-weight-bold" name="document_check_percentage" id="document_check_percentage" onchange="countSelected()">
                                                                                </div>
                                                                                <button type="submit" class="btn btn-primary btn-user btn-block font-weight-bold" name="save-document-check">Save</button>
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

    <script>
        function countSelected() {
            var checkboxes = document.querySelectorAll('.checkbox1');
            var checkboxes2 = document.querySelectorAll('.checkbox2');
            var checkboxes3 = document.querySelectorAll('.checkbox3');

            var totalScore = 0;
            var countC = 0;
            var countNC = 0;
            var countNA = 0;
            var documentCheck = 0;

            checkboxes.forEach(item => {
                if (item.checked == true) {
                    countC++;
                }
            })

            checkboxes2.forEach(item => {
                if (item.checked == true) {
                    countNC++;
                }
            })

            checkboxes3.forEach(item => {
                if (item.checked == true) {
                    countNA++;
                }
            })

            document.getElementById('selectedC').innerHTML = countC;
            document.getElementById('selectedNC').innerHTML = countNC;
            document.getElementById('selectedNA').innerHTML = countNA;

            totalScore = countC + countNC + countNA;
            documentCheck = (countC / (57 - countNA) * 20);
            let d = documentCheck.toFixed(2);

            document.getElementById('selectedTotal').innerHTML = totalScore;
            document.getElementById('document_check_percentage').value = d;

        }
    </script>

</body>

</html>