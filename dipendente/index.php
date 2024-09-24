<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HomePage - Dipendente</title>
    <link rel="stylesheet" href="styles.css">
    <?php
    $connessione = new mysqli();
    try {
        $connessione->connect("localhost", "root", "", "Azienda");
    } catch (Exception $e) {
        echo "Errore";
    }
    ?>
</head>

<body>

    <?php
    include_once ("./navbar.php");
    ?>

    <div class="container-progetti">
        <?php
        if (isset($_SESSION["dipendente"])) {
            echo "<h1>Benvenuto/a, " . $_SESSION["dipendente"]["nome"] . "</h1>";
            echo "<p>Progetti commissionati: </p>";
            $codDipendenteCorrente = $_SESSION["dipendente"]["cf"];

            $query = "SELECT DISTINCT Progetto.Nome AS Nome, Progetto.Descrizione AS Descrizione, Progetto.Stato AS Stato, Progetto.Amministratore AS Amministratore, Progetto.ID AS ID  FROM Progetto, Dipendente, Assegnazione WHERE Progetto.ID = Assegnazione.Progetto AND Dipendente.CodD = Assegnazione.Dipendente AND Dipendente.CodD = '$codDipendenteCorrente'";

            $risultato = $connessione->query($query);

            if ($risultato->num_rows > 0) {
                while ($record = $risultato->fetch_assoc()) {
                    $idProgetto = $record["ID"];
                    $nomeProgetto = $record["Nome"];
                    $descrizioneProgetto = $record["Descrizione"];
                    $amministratoreProgetto = $record["Amministratore"];
                    $statoProgetto = $record["Stato"];

                    echo "<section class='progetti'>";
                    echo "<div class='progetto-wrapper'>";
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

                    $queryAmministratori = "SELECT DISTINCT Nome, Cognome FROM Amministratore WHERE CF = '$amministratoreProgetto'";
                    $risultatoAmministratori = $connessione->query($queryAmministratori);

                    if ($risultatoAmministratori->num_rows > 0) {
                        while ($record = $risultatoAmministratori->fetch_assoc()) {
                            $nomeAmministratore = $record["Nome"];
                            $cognomeAmministratore = $record["Cognome"];
                        }
                    }

                    echo "<b>Amministratore del progetto</b>: $cognomeAmministratore $nomeAmministratore<br>";
                    echo "<b>Descrizione del progetto</b>: $descrizioneProgetto <br>";

                    echo "<b>Tasks assegnate</b>:";
                    $queryTasks = " SELECT Assegnazione_Tasks.Completato AS TaskCompletata, Tasks.Nome AS NomeTask
                                    FROM Progetto, Dipendente, Assegnazione, Assegnazione_Tasks, Tasks 
                                    WHERE Progetto.ID = Assegnazione.Progetto 
                                    AND Dipendente.CodD = Assegnazione.Dipendente 
                                    AND Assegnazione.ID = Assegnazione_Tasks.Assegnazione 
                                    AND Tasks.ID = Assegnazione_Tasks.Task 
                                    AND Progetto.ID = '$idProgetto'
                                    AND Dipendente.CodD = '$codDipendenteCorrente'";
                    $risultatoTasks = $connessione->query($queryTasks);

                    if ($risultatoTasks->num_rows > 0) {
                        while ($recordTask = $risultatoTasks->fetch_assoc()) {
                            $task = $recordTask["NomeTask"];
                            $taskCompletata = $recordTask["TaskCompletata"];
                            echo "<ul style='text-align: left; margin-left: 2rem;'>";
                            if($taskCompletata == 0) {
                                $taskStato = "Non completata";
                            } else {
                                $taskStato = "Completata";
                            }
                            echo "<li>$task [$taskStato]</li>";
                            echo "</ul>";
                        }
                    }
                    echo "</p>";

                    echo "<form method='POST'>";
                    echo "<input id='id_progetto' name='idProgetto' value='$idProgetto' hidden></input>";
                    echo "<button id='modifica_dati' name='modifica_dati'>Modifica dati</button>";
                    echo "</form>";
                    
                    echo "</div>";
                    echo "</div>";
                    echo "</section>";
                }
            }
        } else {
            echo "<h1>Benvenuto/a</h1>";
            echo "<p>Sembra che non sia registrato/loggato, passa da <a href='./login.html'>qui</a>";
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST["modifica_dati"])) {
                header("Location: edit_progetto.php?progetto=$idProgetto");
            }
        }
        ?>
    </div>


    <?php
    $connessione->close();
    ?>
</body>

</html>