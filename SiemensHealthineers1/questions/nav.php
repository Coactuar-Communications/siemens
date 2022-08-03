<nav class="navbar navbar-expand-md navbar-dark bg-dark">
    <a class="navbar-brand" href="./"><?= $admin_title ?></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarText">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="questions.php?sess=<?= $sess_id ?>">Questions</a>
            </li>

        </ul>
    </div>
</nav>