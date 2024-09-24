<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assegnazione tasks - Admin</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <?php
    session_start();

    $connessione = new mysqli("localhost", "root", "", "Azienda");
    if ($connessione->connect_error) {
        die("Errore di connessione: " . $connessione->connect_error);
    }

    include ("./navbar.php");

    if (!isset($_SESSION["amministratore"])) {
        echo "<div style='text-align: center; margin-top: 5rem;'>";
        echo "<h1 style='font-size: 4rem;'>Benvenuto</h1>";
        echo "<p>Sembra che non sia registrato/loggato, passa da <a href='./login.html'>qui</a>";
        echo "</div>";
        exit();
    }

    $idProgetto = $_GET["progetto"];
    if (isset($idProgetto) && $idProgetto != null && is_numeric($idProgetto)) {
        echo "<h1 style='font-size: 4rem; margin-top: 5rem; text-align:center;'>Visualizza tasks assegnate</h1>";

        // Fetch delle informazioni del progetto
        $stmt = $connessione->prepare("SELECT Nome, Descrizione FROM Progetto WHERE ID = ?");
        $stmt->bind_param("i", $idProgetto);
        $stmt->execute();
        $stmt->bind_result($nomeProgetto, $descrizioneProgetto);
        $stmt->fetch();
        $stmt->close();

        echo "<div class='visualizza-tasks-container'>";
        echo "<h3>Progetto</h3>";
        echo "<p><b>Nome</b>: $nomeProgetto</p>";
        echo "<p><b>Descrizione</b>: $descrizioneProgetto</p>";
        echo "<br>";
        echo "<h3>Tasks assegnate:</h3>";

        // Fetch delle task assegnate
        $stmt = $connessione->prepare("SELECT Dipendente.CodD, Dipendente.Cognome, Dipendente.Nome, Assegnazione_Tasks.DataOraAssegnazione, Assegnazione_Tasks.Completato, Tasks.Nome 
                                       FROM Dipendente
                                       JOIN Assegnazione ON Dipendente.CodD = Assegnazione.Dipendente 
                                       JOIN Assegnazione_Tasks ON Assegnazione.ID = Assegnazione_Tasks.Assegnazione 
                                       JOIN Tasks ON Tasks.ID = Assegnazione_Tasks.Task 
                                       WHERE Assegnazione.Progetto = ?");
        $stmt->bind_param("i", $idProgetto);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($codD, $cognomeDipendente, $nomeDipendente, $dataOraAssegnazioneTask, $statoTask, $taskNome);

            // Raggruppare i tasks per dipendente (array multidimensionale)
            $tasksPerDipendente = array();

            // Utilizza un ciclo while per iterare sui risultati
            while ($stmt->fetch()) {
                $tasksPerDipendente[$codD]['nomeDipendente'] = $cognomeDipendente . " " . $nomeDipendente;
                $tasksPerDipendente[$codD]['tasks'][] = array(
                    'taskNome' => $taskNome,
                    'dataOraAssegnazione' => $dataOraAssegnazioneTask,
                    'statoTask' => $statoTask
                );
            }

            // Output dei tasks per ogni dipendente
            foreach ($tasksPerDipendente as $codD => $infoDipendente) {
                echo "<h4>Dipendente: " . $infoDipendente['nomeDipendente'] . "</h4>";
                echo "<ul class='elenco-tasks'>";
                foreach ($infoDipendente['tasks'] as $taskInfo) {
                    echo "<li>" . $taskInfo['taskNome'] . " - Assegnata il: " . $taskInfo['dataOraAssegnazione'];
                    echo "<br>";

                    // Stato task
                    if ($taskInfo['statoTask'] == 0) {
                        echo "Non completata";
                    } else {
                        echo "Completata";
                    }
                    echo "</li>";
                    echo "<br>";
                }
                echo "</ul> <br>";
            }

        } else {
            echo "<p style='text-align: center;'>Nessuna task assegnata.</p>";
        }

        $stmt->close();
        echo "</div>";
    } else {
        header("Location: progetti.php");
        exit();
    }

    $connessione->close();
    ?>
    <br>
    <br>
    <p style="font-style: italic; text-align:center; margin-bottom: 5rem;"><a href="./progetti.php">Ritorna ai progetti</a></p>
</body>

</html>