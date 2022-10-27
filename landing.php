<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title >SHASSIC | Main</title>
    
    <link rel="icon" type="image/x-icon" href="img/favicon.png">
    <link rel="stylesheet" type="text/css" href="css/landing.css">
    
</head>

<body>
    <div class="container">
        <div class="navbar">
            <img src="img/landing/logo.png" class="logo">
            <nav>
                <ul id="menuList">
                    <li><a href="login.php">LOGIN</a></li>
                    <li><a href="register.php">REGISTER</a></li>
                    <li><a href="">ABOUT</a></li>
                </ul>
            </nav>
            <img src="img/landing/menu.png" class="menu-icon" onclick="togglemenu()">
        </div>

        <div class="row">
            <div class="col-1">
                <h2 data-text="SHASSIC">SHASSIC</h2>
                <!-- <p>Safety and Health Assessment <br> System in Construction</p> -->
                <button type="button">EXPLORE<img src="img/landing/arrow.png"></button>
            </div>

            <div class="col-2">
                <img src="img/landing/engineer.png" class="controller">
                <div class="color-box"></div>
            </div>
        </div>
    </div>

    <script>
        var menuList = document.getElementById("menuList");
        menuList.style.maxHeight = "0px";

        function togglemenu() {
            if (menuList.style.maxHeight == "0px") {
                menuList.style.maxHeight = "130px";
            } else {
                menuList.style.maxHeight = "0px";
            }
        }
    </script>
</body>

</html>