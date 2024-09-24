<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elimina Progetto</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <?php
    $connessione = new mysqli();
    try {
        $connessione->connect("localhost", "root", "", "Azienda");
    } catch (Exception $e) {
        echo "Errore";
    }

    $idProgetto = $_GET["progetto"];
    if (isset($idProgetto) && $idProgetto != null && is_numeric($idProgetto)) {
        $stmt = $connessione->prepare("SELECT ID, Nome, Descrizione FROM Progetto WHERE ID = ?");
        $stmt->bind_param("i", $idProgetto);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            $stmt->bind_result($idProgetto_2, $nomeProgetto, $descrizioneProgetto);
            $stmt->fetch();
        }
    } else {
        header("Location: progetti.php");
    }
    ?>

    <div id="modal" class="modal-container">
        <div class="modal-content">
            <h2>Conferma di cancellazione</h2>
            <p class="confirmation-message">
                <b>Nome Progetto</b>: <?php echo $nomeProgetto; ?>
                <br>
                <b>Descrizione</b>: <?php echo $descrizioneProgetto; ?>
                <?php echo $idProgetto_2; ?>
            </p>

            <form class="button-container" method="POST">
                <button type="submit" id="cancelBtn" name="cancellaOperazione" class="button cancel-button">
                    Ritorna Indietro
                </button>
                <button type="submit" id="deleteBtn" name="eliminaProgetto" class="button delete-button">
                    Elimina Progetto
                </button>
            </form>
        </div>
    </div>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["cancellaOperazione"])) {
            header("Location: progetti.php");
        } else if (isset($_POST["eliminaProgetto"])) {
            $queryEliminazioneProgetto = "DELETE FROM Progetto WHERE ID = '$idProgetto_2'";
            $risultato = $connessione->query($queryEliminazioneProgetto);
            if ($risultato === TRUE) {
                header("Location: progetti.php");
            } else {
                echo "<p style='text-align: center; color: red; font-weight: bold; margin-top: 1rem'>Errore durante l'eliminazione ... Riprovare</p>";
            }
        } else {
            echo "ERRORE";
        }
    }
    $connessione->close();
    ?>
</body>

</html>