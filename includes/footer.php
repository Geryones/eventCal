
<footer class="navbar-inverse navbar navbar-fixed-bottom">
    <div class="container-fluid ">
        <div class="row">
            <div class="col-sm-4">
                <h6>Navigation</h6>
                <ul class="list-unstyled">
                    <li>
                        <a href="index.php">Home</a>
                    </li>
                    <li>
                        <a href="archiv.php">Archiv</a>
                    </li>
                    <li>
                        <a href="contact.php">Contact</a>
                    </li>
                </ul>
            </div>
            <?php
            if ($user->isLoggedIn()) {
            ?>
            <div class="col-sm-4">
                <h6>Admin</h6>
                <ul class="list-unstyled">

                        <li>
                            <a href="admin.php">Uebersicht</a>
                        </li>
                        <li>
                            <a href="event.php">Create Event</a>
                        </li>
                        <li>
                            <a href="pricegroup.php">Manage PriceGroups</a>
                        </li>
                        <li>
                            <a href="genre.php">Manage Genres</a>
                        </li>
                        <li>
                            <a href="register.php">Create New Admin</a>
                        </li>
                    <?php
                    }
                    ?>
                </ul>
            </div>
            <?php
            if ($user->isLoggedIn()) {
            ?>
            <div class="col-sm-4">
                <h6>Logout</h6>
                <ul class="list-unstyled">

                    <li>
                        <a href="logout.php">Logout</a>
                    </li>
                    <?php
                    }
                    ?>
                </ul>
        </div>

    </div>

</footer>