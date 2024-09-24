<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dipendenti Azienda - Admin</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <?php
    session_start();

    $connessione = new mysqli();
    try {
        $connessione->connect("localhost", "root", "", "Azienda");
    } catch (Exception $e) {
        echo "Errore";
    }

    include ("./navbar.php");

    if (!isset($_SESSION["amministratore"])) {
        echo "<div style='text-align: center; margin-top: 5rem;'>";
        echo "<h1 style='font-size: 4rem;'>Benvenuto</h1>";
        echo "<p>Sembra che non sia registrato/loggato, passa da <a href='./login.html'>qui</a>";
        echo "</div>";
    } else {

        echo "<div style='text-align: center; margin-top: 5rem;'>";
        echo "<h1 style='font-size: 4rem;'>Dipendenti</h1>";
        echo "Qui sono riportati tutti i dipendenti dell'azienda a cui è possibile assegnare un progetto:";
        echo "</div>";


        $query = "SELECT Dipendente.* FROM Dipendente";
        $risultato = $connessione->query($query);

        if ($risultato->num_rows > 0) {
            while ($record = $risultato->fetch_assoc()) {
                $codDipendente = $record["CodD"];
                $nomeDipendente = $record["Nome"];
                $cognomeDipendente = $record["Cognome"];
                $numTelDipendente = $record["NumeroTelefono"];
                $emailDipendente = $record["Email"];
                $skillDipendente = $record["Skills"];

                echo "<div class='dipendente-container'>";
                echo "<div class='dipendente-info'>";
                echo "<b>Dipendente</b>: $nomeDipendente $cognomeDipendente [$codDipendente] <br>";
                echo "<b>Skill</b>:  $skillDipendente <br>";

                $queryProgetti = "SELECT DISTINCT Progetto.Nome AS ProgettoNome, Progetto.Amministratore AS ProgettoAmministratore FROM Progetto, Assegnazione, Dipendente WHERE Progetto.ID = Assegnazione.Progetto AND Dipendente.CodD = Assegnazione.Dipendente AND Dipendente.CodD = '$codDipendente'";
                echo "<b id='progetto-assegnato-title'>Progetto Assegnato</b>: ";
                $risultatoProgettiDipendenti = $connessione->query($queryProgetti);
                if ($risultatoProgettiDipendenti->num_rows > 0) {
                    while ($recordProgetti = $risultatoProgettiDipendenti->fetch_assoc()) {
                        $nomeProgetto = $recordProgetti["ProgettoNome"];
                        $amministratoreProgetto = $recordProgetti["ProgettoAmministratore"];
                        
                        $queryAmministratoreProgetto = "SELECT Cognome, Nome FROM Amministratore WHERE CF = '$amministratoreProgetto'";
                        $risultatoAmministratoreProgetto = $connessione->query($queryAmministratoreProgetto);
                        
                        if ($risultatoAmministratoreProgetto->num_rows == 1) {
                            $record = $risultatoAmministratoreProgetto->fetch_assoc();
                            $nomeAmministratore = $record["Nome"];
                            $cognomeAmministratore = $record["Cognome"];

                            echo "<ul class='elenco-progetti'>";
                            echo "<li>$nomeProgetto [ $cognomeAmministratore $nomeAmministratore ] </li>";
                            echo "</ul>";
                        }
                    }
                } else {
                    echo "Nessuno";
                }


                echo "<br> <br> <b>Contatti</b> <br>";
                echo "<b>Numero di telefono</b>: $numTelDipendente <br>";
                echo "<b>Email</b>: $emailDipendente <br>";
                echo "</div>";
                echo "<div class='dipendente-progetti'>";
                echo "<a href='assegna_progetto.php?dipendente=$codDipendente'><button class='btn assegna-progetto'>Assegna progetto</button></a>";
                echo "</div>";
                echo "</div>";
            }
        }
    }

    $connessione->close();
    ?>
</body>

</html>