<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifica Progetto</title>
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
        } else {
            header("Location: progetti.php");
        }
        ?>
        <br>
        <form method="POST">
            <div class="input">
                <p>Nome: </p>
                <input type="text" name="nome" id="nome" maxlength="50">
            </div>

            <div class="input">
                <p>Descrizione: </p>
                <input type="text" name="descrizione_progetto" id="descrizione_progetto" maxlength="255">
            </div>

            <div class="input">
                <p>Scegli l'amministratore delegato: </p>
                <select name="amministratore" id="amministratore">
                    <?php
                    $queryAmministratori = "SELECT DISTINCT CF, Nome, Cognome FROM Amministratore";
                    $risultato = $connessione->query($queryAmministratori);

                    if ($risultato->num_rows > 0) {
                        while ($record = $risultato->fetch_assoc()) {
                            $cfAmministratore = $record["CF"];
                            $nomeAmministratore = $record["Nome"];
                            $cognomeAmministratore = $record["Cognome"];
                            echo "<option name='amministratore' value='$cfAmministratore' id='opzione'>$nomeAmministratore $cognomeAmministratore</option>";
                        }
                    }
                    ?>
                </select>
            </div>

            <button>Modifica dati progetto</button>
            <p style="font-style: italic;"><a href="./progetti.php">Ritorna ai progetti</a></p>
        </form>
    </div>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nomeProgetto = $_POST["nome"];
        $descrizioneProgetto = $_POST["descrizione_progetto"];
        $cfAmministratore = $_POST["amministratore"];
        if (isset($_SESSION["cliente"])) {
            $clienteProgetto = $_SESSION["cliente"]["email"];
        }
        $statoprogetto = "Avviato";

        if (strlen($nomeProgetto) == 0 && strlen($descrizioneProgetto) == 0) {
            echo "<p style='text-align: center; color: red; font-weight: bold; margin-top: 1rem'>Inserisci tutti i dati richiesti</p>";
            die();
        } else {
            try {
                $stmt = $connessione->prepare("UPDATE Progetto SET Nome = ?, Descrizione = ?, Amministratore = ?, Stato = ? WHERE Cliente = ? AND ID = ?");
                $stmt->bind_param("sssssi", $nomeProgetto, $descrizioneProgetto, $cfAmministratore, $statoprogetto, $clienteProgetto, $idProgetto);

                $risultato = $stmt->execute();

                if ($risultato === TRUE) {
                    header("Location: index.php");
                } else {
                    header("Location: error.php");
                }
                $connessione->close();
            } catch (Exception $e) {
                echo "<p style='text-align: center; color: red; font-weight: bold; margin-top: 1rem'>Errore durante l'invio ... Riprovare";
                echo "</p>";
            }
        }
    }
    ?>

</body>

</html>