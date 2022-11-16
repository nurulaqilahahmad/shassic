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

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('tr').each(function() {
                var totmarks = 0;
                $(this).find('#selectedC').each(function() {
                    var marks = $(this).text();
                    if (marks.length !== 0) {
                        totmarks += parseInt(marks);
                    }
                });
                console.log(totmarks);
            })
        });
    </script>

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

                <!-- Content Wrapper -->
                <div id="content-wrapper" class="d-flex flex-column">

                    <!-- Main Content -->
                    <div id="content">

                        <!-- Begin Page Content -->
                        <div class="container-fluid">

                            <!-- Document Check Data Table -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <div class="text-center" style="display:flex; width:auto; justify-content: start;">
                                        <a class="font-weight-bold" href="index.php">
                                            &larr; Back</a>
                                    </div>
                                    <h6 class="h3 mb-4 text-gray-800 font-weight-bold">Document Check</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
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
                                            <tfoot>
                                                <tr>
                                                    <th colspan="2">TOTAL SCORE</th>
                                                    <th id="selectedC">0</th>
                                                    <th id="selectedNC">0</th>
                                                    <th id="selectedNA">0</th>
                                                    <!-- <th id="selectedTotal">0</th> -->
                                                    <th id="selectedtotal" style="display:none">H</th>
                                                </tr>
                                            </tfoot>
                                            <tbody>
                                                <!-- <?php
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
                                                                    <td><input type="checkbox" class="checkbox1"></td>
                                                                    <td><input type="checkbox" class="checkbox2"></td>
                                                                    <td><input type="checkbox" class="checkbox3"></td>
                                                                    <td></td>
                                                                </tr>
                                                        <?php }
                                                                } ?>
                                                <?php }
                                                        } ?> -->

                                                <tr>
                                                    <td>1</td>
                                                    <td>Has the SHC conducted a review to ensure
                                                        suitability of Project OSH Policy Statement?</td>
                                                    <td><input type="checkbox" class="checkbox1" onclick="checkboxesTotal()"></td>
                                                    <td><input type="checkbox" class="checkbox2"></td>
                                                    <td><input type="checkbox" class="checkbox3"></td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td>2</td>
                                                    <td>Whether Project OSH Policy Statement was written
                                                        in Bahasa Malaysia?</td>
                                                    <td><input type="checkbox" class="checkbox1" onclick="checkboxesTotal()"></td>
                                                    <td><input type="checkbox" class="checkbox2"></td>
                                                    <td><input type="checkbox" class="checkbox3"></td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td>3</td>
                                                    <td>Whether Project OSH Policy Statement was written
                                                        in English?</td>
                                                    <td><input type="checkbox" class="checkbox1" onclick="checkboxesTotal()"></td>
                                                    <td><input type="checkbox" class="checkbox2"></td>
                                                    <td><input type="checkbox" class="checkbox3"></td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td>4</td>
                                                    <td>Whether Project OSH Policy is signed by the top
                                                        management?</td>
                                                    <td><input type="checkbox" class="checkbox1" onclick="checkboxesTotal()"></td>
                                                    <td><input type="checkbox" class="checkbox2"></td>
                                                    <td><input type="checkbox" class="checkbox3"></td>
                                                    <td></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>

                        </div>
                        <!-- /.container-fluid -->

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
        var checkboxes1 = document.querySelectorAll('.checkbox1');
        var checkboxes2 = document.querySelectorAll('.checkbox2');
        var checkboxes3 = document.querySelectorAll('.checkbox3');
        var countC = 0;
        var countNC = 0;
        var countNA = 0;
        var total = 0;

        //FOR INDIVIDUALS CHECKBOX  C COUNT
        for (var i = 0; i < checkboxes1.length; i++) {
            checkboxes1[i].addEventListener('click', function() {
                // make sure if checkbox is checked or not
                if (this.checked == true) {
                    countC++;
                } else {
                    countC--;
                }
                document.getElementById('selectedC').innerHTML = countC;
            });
        }

        //FOR INDIVIDUALS CHECKBOX  NC COUNT
        for (var i = 0; i < checkboxes2.length; i++) {
            checkboxes2[i].addEventListener('click', function() {
                // make sure if checkbox is checked or not
                if (this.checked == true) {
                    countNC++;
                } else {
                    countNC--;
                }
                document.getElementById('selectedNC').innerHTML = countNC;
            })
        }

        //FOR INDIVIDUALS CHECKBOX  NA COUNT
        for (var i = 0; i < checkboxes3.length; i++) {
            checkboxes3[i].addEventListener('click', function() {
                // make sure if checkbox is checked or not
                if (this.checked == true) {
                    countNA++;
                } else {
                    countNA--;
                }
                document.getElementById('selectedNA').innerHTML = countNA;
            })

        }

        function checkboxesTotal() {
            var checkBox = document.getElementsByClassName("checkbox1");
            var text = document.getElementById("selectedTotal");
            if(this.checked == true){
                // total++;
            }else {
                text.style.display = "none";
                // total--;
            }
            //  document.getElementById('selectedTotal').innerHTML = total;
        }
    </script>

</body>

</html>