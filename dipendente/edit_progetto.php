<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifica Stato Progetto - Admin</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>

    <?php
    include_once ("./navbar.php");

    $connessione = new mysqli();
    try {
        $connessione->connect("localhost", "root", "", "Azienda");
    } catch (Exception $e) {
        echo "Errore";
    }
    ?>

    <div class="container">
        <h1>Modifica Progetto</h1>
        <?php
        $idProgetto = $_GET["progetto"];
        if (isset($idProgetto) && $idProgetto != null && is_numeric($idProgetto)) {
            echo "Inserisci i dati per aggiornare il progetto";

            $queryProgetto = "SELECT DISTINCT Nome, Descrizione, Stato FROM Progetto WHERE ID = '$idProgetto'";
            $risultatoProgetto = $connessione->query($queryProgetto);

            if ($risultatoProgetto->num_rows == 1) {
                while ($recordProgetto = $risultatoProgetto->fetch_assoc()) {
                    $nomeProgetto = $recordProgetto["Nome"];
                    $descrizioneProgetto = $recordProgetto["Descrizione"];
                    $statoProgetto = $recordProgetto["Stato"];
                }
            } else {
                header("Location: index.php");
                exit();
            }

        } else {
            header("Location: index.php");
            exit();
        }
        ?>
        <br>
        <form method="POST">
            <div class="input">
                <p>Nome Progetto: </p>
                <?php
                echo "<input type='text' name='nome_progetto' id='nome' value='$nomeProgetto' readonly>";
                ?>
            </div>

            <div class="input">
                <p>Descrizione: </p>
                <?php
                echo "<input type='text' name='descrizione_progetto' id='descrizione_progetto' value='" . $descrizioneProgetto . "' readonly>";
                ?>
            </div>

            <div class="input">
                <p>Task: </p>
                <select name="task" id="task">
                    <?php
                    $queryTask = "SELECT Tasks.Nome AS TaskNome, Tasks.ID 
                        FROM Tasks 
                        JOIN Assegnazione_Tasks ON Tasks.ID = Assegnazione_Tasks.Task 
                        JOIN Assegnazione ON Assegnazione.ID = Assegnazione_Tasks.Assegnazione 
                        WHERE Assegnazione.Progetto = '$idProgetto'";
                    $risultato = $connessione->query($queryTask);

                    if ($risultato->num_rows > 0) {
                        while ($record = $risultato->fetch_assoc()) {
                            $taskNome = $record["TaskNome"];
                            $taskID = $record["ID"];
                            echo "<option value='$taskID'>$taskNome</option>";
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="input">
                <p>Stato task: </p>
                <select name="task_stato" id="task_stato">
                    <option value="0" id="opzione">Non completata</option>
                    <option value="1" id="opzione">Completata</option>
                </select>
            </div>

            <button>Salva</button>
            <p style="font-style: italic;"><a href="./index.php">Ritorna ai progetti</a></p>
        </form>
    </div>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $taskID = $_POST["task"];
        $taskStato = (int) $_POST["task_stato"];

        try {
            $stmt2 = $connessione->prepare("UPDATE Assegnazione_Tasks SET Completato = ? WHERE Task = ?");
            $stmt2->bind_param("ii", $taskStato, $taskID);

            $risultato2 = $stmt2->execute();
            if ($risultato2 === TRUE) {
                header("Location: index.php");
                die();
            } else {
                header("Location: error.php");
            }

            $connessione->close();
        } catch (Exception $e) {
            echo "<p style='text-align: center; color: red; font-weight: bold; margin-top: 1rem'>Errore durante l'invio ... Riprovare</p>";
        }
    }
    ?>

</body>

</html>