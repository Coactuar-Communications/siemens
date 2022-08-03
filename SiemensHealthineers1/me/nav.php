<nav class="navbar navbar-expand-md navbar-dark bg-dark">
    <a class="navbar-brand" href="#"><?= $admin_title ?></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarText">
        <ul class="navbar-nav me-auto">

            <li class="nav-item">
                <a class="nav-link" href="users.php">Registered Users</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="auditoriums.php">Auditoriums</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="sessions.php">Webcast Sessions</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="questions.php">Questions</a>
            </li>
            <!-- <li class="nav-item">
                <a class="nav-link" href="polls.php">Polls</a>
            </li> -->

        </ul>
        <ul class="navbar-nav ms-auto">
            <li class="nav-item active">
                <a class="nav-link" href="#">Super Admin</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="logout.php">Logout</a>
            </li>
        </ul>
    </div>
</nav>