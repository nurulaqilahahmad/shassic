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

    <title>SHASSIC | Workplace Inspection</title>

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
                                                                <div class="text-center">
                                                                    <div class="text-center" style="display:flex; width:auto; justify-content: start;">
                                                                        <a class="font-weight-bold" href="assessment-component-from-history.php?assessee_id=<?php echo htmlentities($result->assessee_id); ?>">
                                                                            < Back</a>
                                                                    </div>

                                                                    <!-- Page Heading -->
                                                                    <h1 class="h3 mb-4 text-gray-800 font-weight-bold">Workplace Inspection</h1>
                                                                    <?php
                                                                    if (count($infos) > 0) {
                                                                    ?>
                                                                        <div class="col-lg-12 mb-4">
                                                                            <div class="card bg-success text-white shadow">
                                                                                <div class="card-body text-center font-weight-bold">
                                                                                    <?php foreach ($infos as $info) {
                                                                                        echo $info;
                                                                                    } ?>
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
                                                                    <div class="tab form-group">
                                                                        <button class="tablinks font-weight-bold" style="width: 49%;" onclick="openSection(event, 'general')">General</button>
                                                                        <button class="tablinks font-weight-bold" style="width: 49%;" onclick="openSection(event, 'construction-work')">Construction Work</button>
                                                                    </div>
                                                                </div>
                                                                <!-- End of text-center -->

                                                                <div id="general" class="tabcontent">
                                                                    <div class="table-responsive">
                                                                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                                                            <form class="user" method="POST" id="workplace-inspection-general">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th>ITEM</th>
                                                                                        <th>CHECKLIST</th>
                                                                                        <th>C</th>
                                                                                        <th>NC</th>
                                                                                        <th>NA</th>
                                                                                        <th>REMARKS</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    <?php
                                                                                    $sql = "SELECT * from workplace_inspection_section where id between 1 and 3";
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
                                                                                            $sql = "SELECT * from workplace_inspection_checklist where item_id='$section->id'";
                                                                                            $query = $dbh->prepare($sql);
                                                                                            $query->execute();
                                                                                            $checklists = $query->fetchAll(PDO::FETCH_OBJ);
                                                                                            $cnt = 1;
                                                                                            if ($query->rowCount() > 0) {
                                                                                                foreach ($checklists as $checklist) {
                                                                                                    $checklist_id = $checklist->id;
                                                                                            ?>
                                                                                                    <tr>
                                                                                                        <input type="hidden" class="form-control form-control-user font-weight-bold" name="assessee_id" id="assessee_id" value="<?= $result->assessee_id ?>">
                                                                                                        <input type="hidden" class="form-control form-control-user font-weight-bold" name="workplace_inspection_checklist_id[]" id="workplace_inspection_checklist_id[]" value="<?= $checklist_id ?>">
                                                                                                        <td><?= $cnt++ ?></td>
                                                                                                        <td class="text-left"><?php echo htmlentities($checklist->checklist) ?></td>
                                                                                                        <?php
                                                                                                        $sql = "SELECT * FROM workplace_inspection_assessment WHERE assessment_id='$result->assessee_id' AND workplace_inspection_checklist_id='$checklist_id'";
                                                                                                        $query = $dbh->prepare($sql);
                                                                                                        $query->execute();
                                                                                                        $workinsps1 = $query->fetchAll(PDO::FETCH_OBJ);
                                                                                                        if ($query->rowCount() > 0) {
                                                                                                            foreach ($workinsps1 as $workinsp1) {
                                                                                                        ?>
                                                                                                                <td><input type="checkbox" class="checkbox1" onchange="countSelected()" name="workinsp_<?= $checklist_id ?>[]" value="C" <?php if (in_array("C", explode(", ", $workinsp1->status))) echo 'checked = "checked"'; ?>></td>
                                                                                                                <td><input type="checkbox" class="checkbox2" onchange="countSelected()" name="workinsp_<?= $checklist_id ?>[]" value="NC" <?php if (in_array("NC", explode(", ", $workinsp1->status))) echo 'checked = "checked"'; ?>></td>
                                                                                                                <td><input type="checkbox" class="checkbox3" onchange="countSelected()" name="workinsp_<?= $checklist_id ?>[]" value="NA" <?php if (in_array("NA", explode(", ", $workinsp1->status))) echo 'checked = "checked"'; ?>></td>
                                                                                                                <td><textarea form="workplace-inspection-general" rows="2" cols="20" id="remarks" name="remarks_<?= $checklist_id ?>"><?= $workinsp1->remarks ?></textarea></td>
                                                                                                    </tr>
                                                                                            <?php }
                                                                                                        } ?>
                                                                                    <?php }
                                                                                            } ?>
                                                                            <?php }
                                                                                    } ?>
                                                                                </tbody>
                                                                                <tfoot>
                                                                                    <tr>
                                                                                        <th colspan="2">SUB SCORE</th>
                                                                                        <th id="selectedC">
                                                                                            <script>
                                                                                                document.getElementById('selectedC').innerHTML = document.querySelectorAll('input[class="checkbox1"]:checked').length
                                                                                            </script>
                                                                                        </th>
                                                                                        <th id="selectedNC">
                                                                                            <script>
                                                                                                document.getElementById('selectedNC').innerHTML = document.querySelectorAll('input[class="checkbox2"]:checked').length
                                                                                            </script>
                                                                                        </th>
                                                                                        <th id="selectedNA">
                                                                                            <script>
                                                                                                document.getElementById('selectedNA').innerHTML = document.querySelectorAll('input[class="checkbox3"]:checked').length
                                                                                            </script>
                                                                                        </th>
                                                                                        <th id="selectedTotal"></th>
                                                                                    </tr>
                                                                                </tfoot>
                                                                        </table>
                                                                    </div>
                                                                    <!-- End of Table Responsive -->
                                                                    <div class="form-group" id="row">
                                                                        <div class="col-sm-4 mb-3 mb-sm-0">
                                                                            <input type="hidden" class="form-control form-control-user font-weight-bold" name="general_c_score" id="general_c_score" onchange="countSelected()">
                                                                            <input type="hidden" class="form-control form-control-user font-weight-bold" name="general_na_score" id="general_na_score" onchange="countSelected()">
                                                                        </div>
                                                                        <div class="col-sm-4 mb-3 mb-sm-0">
                                                                            <button type="submit" class="btn btn-primary btn-user btn-block font-weight-bold" name="save-workplace-inspection-general">Save</button>
                                                                        </div>
                                                                    </div>
                                                                    </form>
                                                                </div>
                                                                <!-- End of tab-content -->

                                                                <div id="construction-work" class="tabcontent">
                                                                    <div class="table-responsive">
                                                                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                                                            <form class="user" method="POST" id="workplace-inspection-high-risk">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th rowspan="2" class="align-middle">ITEM</th>
                                                                                        <th rowspan="2" class="align-middle">CHECKLIST</th>
                                                                                        <th colspan="3">HIGH RISK 1</th>
                                                                                        <th colspan="3">HIGH RISK 2</th>
                                                                                        <th colspan="3">HIGH RISK 3</th>
                                                                                        <th rowspan="2" class="align-middle">REMARKS</th>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <th>C</th>
                                                                                        <th>NC</th>
                                                                                        <th>NA</th>
                                                                                        <th>C</th>
                                                                                        <th>NC</th>
                                                                                        <th>NA</th>
                                                                                        <th>C</th>
                                                                                        <th>NC</th>
                                                                                        <th>NA</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    <?php
                                                                                    $sql = "SELECT * from workplace_inspection_section where id between 4 and 7";
                                                                                    $query = $dbh->prepare($sql);
                                                                                    $query->execute();
                                                                                    $sections = $query->fetchAll(PDO::FETCH_OBJ);
                                                                                    $cnt = 1;
                                                                                    if ($query->rowCount() > 0) {
                                                                                        foreach ($sections as $section) {
                                                                                    ?>

                                                                                            <tr>
                                                                                                <th><?php echo htmlentities($section->item_no) ?></th>
                                                                                                <th colspan="11" class="text-left"><?php echo htmlentities($section->item_name) ?></th>
                                                                                            </tr>
                                                                                            <?php
                                                                                            $sql = "SELECT * from workplace_inspection_checklist where item_id='$section->id'";
                                                                                            $query = $dbh->prepare($sql);
                                                                                            $query->execute();
                                                                                            $checklists = $query->fetchAll(PDO::FETCH_OBJ);
                                                                                            $cnt = 1;
                                                                                            if ($query->rowCount() > 0) {
                                                                                                foreach ($checklists as $checklist) {
                                                                                                    $checklist_id = $checklist->id;
                                                                                            ?>
                                                                                                    <tr>
                                                                                                        <input type="hidden" class="form-control form-control-user font-weight-bold" name="assessee_id" id="assessee_id" value="<?= $result->assessee_id ?>">
                                                                                                        <input type="hidden" class="form-control form-control-user font-weight-bold" name="workplace_inspection_checklist_id[]" id="workplace_inspection_checklist_id[]" value="<?= $checklist_id ?>">
                                                                                                        <td><?= $cnt++ ?></td>
                                                                                                        <td class="text-left"><?php echo htmlentities($checklist->checklist) ?></td>
                                                                                                        <?php
                                                                                                        $sql = "SELECT * FROM workplace_inspection_high_risk_1 WHERE assessment_id='$result->assessee_id' AND workplace_inspection_checklist_id='$checklist_id'";
                                                                                                        $query = $dbh->prepare($sql);
                                                                                                        $query->execute();
                                                                                                        $highrisks1 = $query->fetchAll(PDO::FETCH_OBJ);
                                                                                                        if ($query->rowCount() > 0) {
                                                                                                            foreach ($highrisks1 as $highrisk1) {
                                                                                                        ?>
                                                                                                                <td><input type="checkbox" class="checkbox4" id="C" name="highrisk1_<?= $checklist_id ?>[]" onchange="countSelected()" value="C" <?php if (in_array("C", explode(", ", $highrisk1->status))) echo 'checked = "checked"'; ?>></td>
                                                                                                                <td><input type="checkbox" class="checkbox5" id="NC" name="highrisk1_<?= $checklist_id ?>[]" onchange="countSelected()" value="NC" <?php if (in_array("NC", explode(", ", $highrisk1->status))) echo 'checked = "checked"'; ?>></td>
                                                                                                                <td><input type="checkbox" class="checkbox6" id="NA" name="highrisk1_<?= $checklist_id ?>[]" onchange="countSelected()" value="NA" <?php if (in_array("NA", explode(", ", $highrisk1->status))) echo 'checked = "checked"'; ?>></td>
                                                                                                        <?php }
                                                                                                        } ?>
                                                                                                        <?php
                                                                                                        $sql = "SELECT * FROM workplace_inspection_high_risk_2 WHERE assessment_id='$result->assessee_id' AND workplace_inspection_checklist_id='$checklist_id'";
                                                                                                        $query = $dbh->prepare($sql);
                                                                                                        $query->execute();
                                                                                                        $highrisks2 = $query->fetchAll(PDO::FETCH_OBJ);
                                                                                                        if ($query->rowCount() > 0) {
                                                                                                            foreach ($highrisks2 as $highrisk2) {
                                                                                                        ?>
                                                                                                                <td><input type="checkbox" class="checkbox7" name="highrisk2_<?= $checklist_id ?>[]" onchange="countSelected()" value="C" <?php if (in_array("C", explode(", ", $highrisk2->status))) echo 'checked = "checked"'; ?>></td>
                                                                                                                <td><input type="checkbox" class="checkbox8" name="highrisk2_<?= $checklist_id ?>[]" onchange="countSelected()" value="NC" <?php if (in_array("NC", explode(", ", $highrisk2->status))) echo 'checked = "checked"'; ?>></td>
                                                                                                                <td><input type="checkbox" class="checkbox9" name="highrisk2_<?= $checklist_id ?>[]" onchange="countSelected()" value="NA" <?php if (in_array("NA", explode(", ", $highrisk2->status))) echo 'checked = "checked"'; ?>></td>
                                                                                                        <?php }
                                                                                                        } ?>
                                                                                                        <?php
                                                                                                        $sql = "SELECT * FROM workplace_inspection_high_risk_3 WHERE assessment_id='$result->assessee_id' AND workplace_inspection_checklist_id='$checklist_id'";
                                                                                                        $query = $dbh->prepare($sql);
                                                                                                        $query->execute();
                                                                                                        $highrisks3 = $query->fetchAll(PDO::FETCH_OBJ);
                                                                                                        if ($query->rowCount() > 0) {
                                                                                                            foreach ($highrisks3 as $highrisk3) {
                                                                                                        ?>
                                                                                                                <td><input type="checkbox" class="checkbox10" name="highrisk3_<?= $checklist_id ?>[]" onchange="countSelected()" value="C" <?php if (in_array("C", explode(", ", $highrisk3->status))) echo 'checked = "checked"'; ?>></td>
                                                                                                                <td><input type="checkbox" class="checkbox11" name="highrisk3_<?= $checklist_id ?>[]" onchange="countSelected()" value="NC" <?php if (in_array("NC", explode(", ", $highrisk3->status))) echo 'checked = "checked"'; ?>></td>
                                                                                                                <td><input type="checkbox" class="checkbox12" name="highrisk3_<?= $checklist_id ?>[]" onchange="countSelected()" value="NA" <?php if (in_array("NA", explode(", ", $highrisk3->status))) echo 'checked = "checked"'; ?>></td>
                                                                                                                <td><textarea form="workplace-inspection-high-risk" rows="2" cols="20" id="remarks" name="remarks_<?= $checklist_id ?>"><?= $highrisk3->remarks ?></textarea></td>
                                                                                                        <?php }
                                                                                                        } ?>
                                                                                                    </tr>
                                                                                            <?php }
                                                                                            } ?>
                                                                                    <?php }
                                                                                    } ?>
                                                                                </tbody>
                                                                                <tfoot>
                                                                                    <tr>
                                                                                        <th colspan="2" class="text-right">SUB SCORE</th>
                                                                                        <th id="selectedC2">
                                                                                            <script>
                                                                                                document.getElementById('selectedC2').innerHTML = document.querySelectorAll('input[class="checkbox4"]:checked').length
                                                                                            </script>
                                                                                        </th>
                                                                                        <th id="selectedNC2">
                                                                                            <script>
                                                                                                document.getElementById('selectedNC2').innerHTML = document.querySelectorAll('input[class="checkbox5"]:checked').length
                                                                                            </script>
                                                                                        </th>
                                                                                        <th id="selectedNA2">
                                                                                            <script>
                                                                                                document.getElementById('selectedNA2').innerHTML = document.querySelectorAll('input[class="checkbox6"]:checked').length
                                                                                            </script>
                                                                                        </th>
                                                                                        <th id="selectedC3">
                                                                                            <script>
                                                                                                document.getElementById('selectedC3').innerHTML = document.querySelectorAll('input[class="checkbox7"]:checked').length
                                                                                            </script>
                                                                                        </th>
                                                                                        <th id="selectedNC3">
                                                                                            <script>
                                                                                                document.getElementById('selectedNC3').innerHTML = document.querySelectorAll('input[class="checkbox8"]:checked').length
                                                                                            </script>
                                                                                        </th>
                                                                                        <th id="selectedNA3">
                                                                                            <script>
                                                                                                document.getElementById('selectedNA3').innerHTML = document.querySelectorAll('input[class="checkbox9"]:checked').length
                                                                                            </script>
                                                                                        </th>
                                                                                        <th id="selectedC4">
                                                                                            <script>
                                                                                                document.getElementById('selectedC4').innerHTML = document.querySelectorAll('input[class="checkbox10"]:checked').length
                                                                                            </script>
                                                                                        </th>
                                                                                        <th id="selectedNC4">
                                                                                            <script>
                                                                                                document.getElementById('selectedNC4').innerHTML = document.querySelectorAll('input[class="checkbox11"]:checked').length
                                                                                            </script>
                                                                                        </th>
                                                                                        <th id="selectedNA4">
                                                                                            <script>
                                                                                                document.getElementById('selectedNA4').innerHTML = document.querySelectorAll('input[class="checkbox12"]:checked').length
                                                                                            </script>
                                                                                        </th>
                                                                                        <th id="selectedTotal2"></th>
                                                                                    </tr>
                                                                                </tfoot>
                                                                        </table>
                                                                    </div>
                                                                    <!-- End of table-responsive -->
                                                                    <div class="form-group" id="row">
                                                                        <div class="col-sm-4 mb-3 mb-sm-0">
                                                                            <input type="hidden" class="form-control form-control-user font-weight-bold" name="high_risk_c_score" id="high_risk_c_score" onchange="countSelected()">
                                                                            <input type="hidden" class="form-control form-control-user font-weight-bold" name="high_risk_na_score" id="high_risk_na_score" onchange="countSelected()">
                                                                        </div>
                                                                        <div class="col-sm-4 mb-3 mb-sm-0">
                                                                            <button type="submit" class="btn btn-primary btn-user btn-block font-weight-bold" name="save-workplace-inspection-high-risk">Save</button>
                                                                        </div>
                                                                    </div>
                                                                    </form>

                                                                </div>
                                                                <!-- End of tab-content -->
                                                            </div>
                                                            <!-- End of p-5 -->
                                                        </div>
                                                        <!-- End of col-lg-12 -->
                                                    </div>
                                                    <!-- End of row -->
                                                </div>
                                                <!-- End of card-body -->
                                            </div>
                                            <!-- End of card -->
                                        </div>
                                        <!-- End of col-xl-12 -->
                                    </div>
                                    <!-- End of Outer Row -->
                                </div>
                                <!-- End of Page Content -->
                            </div>
                            <!-- End of Main Content -->
                        </div>
                        <!-- End of Content Wrapper -->


                <?php }
                }  ?>
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
        <!-- End of Landing Container -->

        <script>
            function openSection(evt, sectionName) {
                var i, tabcontent, tablinks;
                tabcontent = document.getElementsByClassName("tabcontent");
                for (i = 0; i < tabcontent.length; i++) {
                    tabcontent[i].style.display = "none";
                }
                tablinks = document.getElementsByClassName("tablinks");
                for (i = 0; i < tablinks.length; i++) {
                    tablinks[i].className = tablinks[i].className.replace(" active", "");
                }
                document.getElementById(sectionName).style.display = "block";
                evt.currentTarget.className += " active";
            }

            function countSelected() {
                var checkboxes = document.querySelectorAll('.checkbox1');
                var checkboxes2 = document.querySelectorAll('.checkbox2');
                var checkboxes3 = document.querySelectorAll('.checkbox3');

                var checkboxes4 = document.querySelectorAll('.checkbox4');
                var checkboxes5 = document.querySelectorAll('.checkbox5');
                var checkboxes6 = document.querySelectorAll('.checkbox6');

                var checkboxes7 = document.querySelectorAll('.checkbox7');
                var checkboxes8 = document.querySelectorAll('.checkbox8');
                var checkboxes9 = document.querySelectorAll('.checkbox9');

                var checkboxes10 = document.querySelectorAll('.checkbox10');
                var checkboxes11 = document.querySelectorAll('.checkbox11');
                var checkboxes12 = document.querySelectorAll('.checkbox12');

                var totalScore = 0;
                var countC = 0;
                var countNC = 0;
                var countNA = 0;

                var totalScore2 = 0;
                var countC2 = 0;
                var countNC2 = 0;
                var countNA2 = 0;

                var countC3 = 0;
                var countNC3 = 0;
                var countNA3 = 0;

                var countC4 = 0;
                var countNC4 = 0;
                var countNA4 = 0;

                // Count the checked checkboxes
                checkboxes.forEach(item => {
                    if (item.checked == true) {
                        countC++;
                    }
                });

                checkboxes2.forEach(item => {
                    if (item.checked == true) {
                        countNC++;
                    }
                });

                checkboxes3.forEach(item => {
                    if (item.checked == true) {
                        countNA++;
                    }
                });

                checkboxes4.forEach(item => {
                    if (item.checked == true) {
                        countC2++;
                    }
                });

                checkboxes5.forEach(item => {
                    if (item.checked == true) {
                        countNC2++;
                    }
                });

                checkboxes6.forEach(item => {
                    if (item.checked == true) {
                        countNA2++;
                    }
                });

                checkboxes7.forEach(item => {
                    if (item.checked == true) {
                        countC3++;
                    }
                });

                checkboxes8.forEach(item => {
                    if (item.checked == true) {
                        countNC3++;
                    }
                });

                checkboxes9.forEach(item => {
                    if (item.checked == true) {
                        countNA3++;
                    }
                })

                checkboxes10.forEach(item => {
                    if (item.checked == true) {
                        countC4++;
                    }
                });

                checkboxes11.forEach(item => {
                    if (item.checked == true) {
                        countNC4++;
                    }
                });

                checkboxes12.forEach(item => {
                    if (item.checked == true) {
                        countNA4++;
                    }
                });

                // Display the count of checked checkboxes
                document.getElementById('selectedC').innerHTML = countC;
                document.getElementById('selectedNC').innerHTML = countNC;
                document.getElementById('selectedNA').innerHTML = countNA;

                document.getElementById('selectedC2').innerHTML = countC2;
                document.getElementById('selectedNC2').innerHTML = countNC2;
                document.getElementById('selectedNA2').innerHTML = countNA2;

                document.getElementById('selectedC3').innerHTML = countC3;
                document.getElementById('selectedNC3').innerHTML = countNC3;
                document.getElementById('selectedNA3').innerHTML = countNA3;

                document.getElementById('selectedC4').innerHTML = countC4;
                document.getElementById('selectedNC4').innerHTML = countNC4;
                document.getElementById('selectedNA4').innerHTML = countNA4;

                totalScore = countC + countNC + countNA;
                // document.getElementById('selectedTotal').innerHTML = totalScore;
                // document.getElementById('general_score').value = totalScore;
                // workInsp_general = (countC / (9 - countNA));
                // let g = workInsp_general.toFixed(2);
                document.getElementById('general_c_score').value = countC;
                document.getElementById('general_na_score').value = countNA;

                totalScore2 = countC2 + countNC2 + countNA2 + countC3 + countNC3 + countNA3 + countC4 + countNC4 + countNA4;
                // document.getElementById('selectedTotal2').innerHTML = totalScore2;
                // highrisk1 = (countC2 / (21 - countNA2));
                // highrisk2 = (countC3 / (21 - countNA3));
                // highrisk3 = (countC4 / (21 - countNA4));
                // workInsp_highrisk = highrisk1 + highrisk2 + highrisk3;
                document.getElementById('high_risk_c_score').value = countC2 + countC3 + countC4;
                document.getElementById('high_risk_na_score').value = countNA2 + countNA3 + countNA4;
            }

            var generalCScore = document.getElementById('general_c_score');
            var generalNAScore = document.getElementById('general_na_score');
            var general_c = document.querySelectorAll('input[class="checkbox1"]:checked').length;
            var general_na = document.querySelectorAll('input[class="checkbox3"]:checked').length;
            generalCScore.setAttribute('value', general_c);
            generalNAScore.setAttribute('value', general_na);

            var highRiskCScore = document.getElementById('high_risk_c_score');
            var highRiskNAScore = document.getElementById('high_risk_na_score');
            var highrisk_c = document.querySelectorAll('input[class="checkbox4"]:checked').length + document.querySelectorAll('input[class="checkbox7"]:checked').length + document.querySelectorAll('input[class="checkbox10"]:checked').length;
            var highrisk_na = document.querySelectorAll('input[class="checkbox6"]:checked').length + document.querySelectorAll('input[class="checkbox9"]:checked').length + document.querySelectorAll('input[class="checkbox12"]:checked').length;
            highRiskCScore.setAttribute('value', highrisk_c);
            highRiskNAScore.setAttribute('value', highrisk_na);
        </script>

    <?php } else {
        header("location: login.php");
    } ?>

</body>

</html>