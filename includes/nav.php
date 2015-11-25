<?php

$user = new User();
?>

<div class="navbar navbar-fixed-top navbar-inverse">
    <button class="navbar-toggle" data-target=".navbar-responsive-collapse" data-toggle="collapse" type="button">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
    </button>
    <div class="container">
        <a class="navbar-brand" href="index.php"><img src="includes/media/pics/logo.png" alt="Logo" width="50" height="22"></a>

        <div class="navbar-collapse collapse navbar-responsive-collapse">
            <ul class="nav navbar-nav">
                <li>
                    <a href="index.php">Home</a>
                </li>
                <li>
                    <a href="archiv.php">Archiv</a>
                </li>
                <li>
                    <a href="Contact.php">Contact</a>
                </li>
                <?php
                if ($user->isLoggedIn()) {
                   ?>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Admin Bereich <strong class="caret"></strong> </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="admin.php">Uebersicht</a>
                            </li>
                            <li>
                                <a href="register.php">Create New Admin</a>
                            </li>
                            <li>
                                <a href="test.php">Test-Page</a>
                            </li>
                            <li class="divider"></li>
                            <li class="dropdown-header">Manage Events</li>
                            <li>
                                <a href="event.php">Create Event</a>
                            </li>
                            <li class="divider"></li>
                            <li class="dropdown-header">Manage Genres</li>
                            <li>
                                <a href="genre.php">Create Genres</a>
                            </li>
                            <li>
                                <a href="deleteGenre.php">Delete Genre</a>
                            </li>
                            <li class="divider"></li>
                            <li class="dropdown-header">Manage PriceGroups</li>
                            <li>
                                <a href="pricegroup.php">Create Group</a>
                            </li>
                            <li>
                                <a href="deletePricegroup.php">Delete Group</a>
                            </li>

                        </ul><!--end of dropdown content-->
                    </li><!--end of dropdown-->
                    <?php
                  echo '<li><a href="logout.php"> Logout</a></li>';
                } else {
                    echo '<li><a href="login.php"> Login</a></li>';
                }
                ?>
            </ul>
        </div><!--end of nav Collapse-->

    </div><!--end container inner nav-->

</div><!--navBar fertig-->-->
<hr>
<?php

/*


<div class="navbar navbar-fixed-top navbar-inverse">
    <div class="container">

        <!-- .btn-navbar is used as the toggle for collapsed navbar content -->
        <button class="navbar-toggle" data-target=".navbar-responsive-collapse" data-toggle="collapse" type="button">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>

        <a class="navbar-brand" href="index.php"><img src="includes/media/pics/logo.png" width="50" height="22" alt="Your Logo"></a>

        <div class="nav-collapse collapse navbar-responsive-collapse">
            <ul class="nav navbar-nav">

                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Services <strong class="caret"></strong></a>

                    <ul class="dropdown-menu">
                        <li>
                            <a href="#">Web Design</a>
                        </li>

                        <li>
                            <a href="#">Web Development</a>
                        </li>

                        <li>
                            <a href="#">SEO</a>
                        </li>

                        <li class="divider"></li>

                        <li class="dropdown-header">More Services</li>

                        <li>
                            <a href="#">Content Creation</a>
                        </li>

                        <li>
                            <a href="#">Social Media Marketing</a>
                        </li>
                    </ul><!-- end dropdown-menu -->
               </li>
            </ul>

            <form class="navbar-form pull-left">
                <input type="text" class="form-control" placeholder="Search this site..." id="searchInput">
                <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search"></span></button>
            </form><!-- end navbar-form -->

            <ul class="nav navbar-nav pull-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-user"></span> My Account <strong class="caret"></strong></a>

                    <ul class="dropdown-menu">
                        <li>
                            <a href="#"><span class="glyphicon glyphicon-wrench"></span> Settings</a>
                        </li>

                        <li>
                            <a href="#"><span class="glyphicon glyphicon-refresh"></span> Update Profile</a>
                        </li>

                        <li>
                            <a href="#"><span class="glyphicon glyphicon-briefcase"></span> Billing</a>
                        </li>

                        <li class="divider"></li>

                        <li>
                            <a href="#"><span class="glyphicon glyphicon-off"></span> Sign out</a>
                        </li>
                    </ul>
                </li>
            </ul><!-- end nav pull-right -->
     </div><!-- end nav-collapse -->

     </div><!-- end container -->
    </div><!-- end navbar-->*/