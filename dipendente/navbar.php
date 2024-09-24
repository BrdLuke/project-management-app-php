<nav>
    <div class="logo">
        GPM
    </div>
    <div class="nav-elements">
        <ul>
            <li><a href="./index.php">Home</a></li>
            <?php
            session_start();
            if (isset($_SESSION["dipendente"])) {
                echo "<li><a href='./logout.php' id='logout'>Logout</a></li>";
            } else {
                echo "<li><a href='./registrazione.html' id='logout'>Registrati</a></li>";
            }
            ?>
        </ul>
    </div>
</nav>