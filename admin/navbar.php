<nav>
    <div class="logo">
        GPM
    </div>
    <div class="nav-elements">
        <ul>
            <li><a href="./index.php">Home</a></li>
            <li><a href="./progetti.php">Progetti</a></li>
            <li><a href="./dipendenti.php">Dipendenti</a></li>
            <?php
            if (isset($_SESSION["amministratore"])) {
                echo "<li><a href='./logout.php' id='logout'>Logout</a></li>";
            } else {
                echo "<li><a href='./registrazione.html' id='logout'>Registrati</a></li>";
            }
            ?>
        </ul>
    </div>
</nav>