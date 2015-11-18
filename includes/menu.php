<?php

$user = new User();
?>

<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <button class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse" type="button">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>

        <a class="navbar-brand" href="index.php"><img src="includes/media/pics/logo.png" alt="logo" width="125"
                                                      height="56"/></a>

        <div class="collapse navbar-collapse navbar-responsive-collapse">
            <ul class="nav navbar-nav navbar-right">
                <li><a href="index.php">Home</a></li>
                <li><a href="archiv.php">Archiv</a></li>
                <li><a href="contact.php">Contact us</a></li>
                <?php
                if ($user->isLoggedIn()) {
                    ?>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">AdminBereich <b
                                class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="event.php">Create Event</a></li>
                            <li><a href="genre.php">Manage Genre</a></li>
                            <li><a href="pricegroup.php">Manage Pricegroup</a></li>
                        </ul>
                    </li>

                <?php
                }
                ?>
                <li><a href="test.php">Testing Page</a></li>
                <?php
                if ($user->isLoggedIn()) {
                    echo '<li><a href="logout.php"> Logout</a></li>';
                } else {
                    echo '<li><a href="login.php"> Login</a></li>';
                }
                ?>

            </ul>
        </div>
    </div>
</div>
