<?php
/*
 * in diesem file wird die navigation kreiert
 * das menü ist userabhängig, nur ein admin sieht alles
 *
 * es ist voll responsible, das heisst, bei kleinen bildschrimen wird das menü in einem burger-button
 * zusammengefasst
 */

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
