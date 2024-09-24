<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HomePage - Admin</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <?php
    session_start();

    include ("./navbar.php");

    // Controllo per verificare il contenuto della sessione
    // echo '<pre>';
    // print_r($_SESSION);
    // echo '</pre>';
    
    $connessione = new mysqli();
    try {
        $connessione->connect("localhost", "root", "", "Azienda");
    } catch (Exception $e) {
        echo "Errore";
    }

    if (isset($_SESSION["amministratore"])) {
        $amministratore = $_SESSION["amministratore"]["nome"];
        echo "<div style='text-align: center; margin-top: 5rem;'>";
        echo "<h1 style='font-size: 4rem;'>Benvenuto $amministratore</h1>";
        echo "Qui sono riportati tutti i progetti di cui sei responsabile:";
        echo "</div>";

        $cfAmministratore = $_SESSION["amministratore"]["cf"];
        $query = "SELECT Progetto.ID AS IDProgetto, Progetto.Nome AS NomeProgetto, Progetto.Descrizione AS DescrizioneProgetto, Progetto.Stato AS StatoProgetto, Cliente.Nome AS NomeCliente, Cliente.Cognome AS CognomeCliente, Cliente.Email AS EmailCliente FROM Progetto, Amministratore, Cliente WHERE Amministratore.CF = Progetto.Amministratore AND Cliente.Email = Progetto.Cliente AND Progetto.Amministratore = '$cfAmministratore'";

        $risultato = $connessione->query($query);
        if ($risultato->num_rows > 0) {
            while ($record = $risultato->fetch_assoc()) {
                $idProgetto = $record["IDProgetto"];
                $nomeProgetto = $record["NomeProgetto"];
                $descrizioneProgetto = $record["DescrizioneProgetto"];
                $statoProgetto = $record["StatoProgetto"];
                $nomeCliente = $record["NomeCliente"];
                $congomeCliente = $record["CognomeCliente"];
                $emailCliente = $record["EmailCliente"];

                echo "<section class='progetti'>";
                echo "<div class='progetto-body'>";
                echo "<h2>$nomeProgetto</h2>";
                echo "<p class='info-progetto'>";
                if ($statoProgetto == "Avviato") {
                    echo "<b>Stato progetto</b>: <span class='box blue'></span></span> $statoProgetto<br>";
                } else if ($statoProgetto == "In corso") {
                    echo "<b>Stato progetto</b>: <span class='box orange'></span></span> $statoProgetto<br>";
                } else if ($statoProgetto == "Completato") {
                    echo "<b>Stato progetto</b>: <span class='box green'></span></span> $statoProgetto<br>";
                }
                echo "<b>Descrizione del progetto</b>: $descrizioneProgetto <br>";
                echo "<b>Cliente</b>: <a id='cliente' href='./info_cliente.php?email=$emailCliente'>$congomeCliente $nomeCliente [ $emailCliente ]</a> <br>";

                echo "<b>Dipendenti assegnati</b>:";
                $queryDipendenti = "SELECT Dipendente.Nome AS DipendenteNome, Dipendente.Cognome AS DipendenteCognome, Assegnazione.DataOraAssegnazione AS DataOraAssegnazione FROM Dipendente, Progetto, Assegnazione WHERE Dipendente.CodD = Assegnazione.Dipendente AND Progetto.ID = Assegnazione.Progetto AND Progetto.ID = '$idProgetto'";
                $risultatoDipendenti = $connessione->query($queryDipendenti);

                if ($risultatoDipendenti->num_rows > 0) {
                    while ($record = $risultatoDipendenti->fetch_assoc()) {
                        $nomeDipendente = $record["DipendenteNome"];
                        $cognomeDipendente = $record["DipendenteCognome"];
                        $dataAssegnazione = $record["DataOraAssegnazione"];

                        echo "<ul style='text-align: left; padding-left: 2rem;'>";
                        echo "<li>$cognomeDipendente $nomeDipendente ( Assegnazione: $dataAssegnazione )</li>";
                        echo "</ul>";
                    }
                } else {
                    echo "<span> Nessun dipendente assegnato a questo progetto</span>";
                }

                echo "</p>";
                echo "</div>";
                echo "</div>";
                echo "</section>";
            }
        } else {
            echo "<p style='text-align:center;'><b>Nessun progetto</b></p>";
        }

    } else {
        echo "<div style='text-align: center; margin-top: 5rem;'>";
        echo "<h1 style='font-size: 4rem;'>Benvenuto</h1>";
        echo "<p>Sembra che non sia registrato/loggato, passa da <a href='./login.html'>qui</a>";
        echo "</div>";
    }
    $connessione->close();
    ?>
</body>

</html>