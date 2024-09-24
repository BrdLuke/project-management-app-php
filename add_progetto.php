<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aggiungi Progetto</title>
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
        <h1>Progetto</h1>
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

            <button>Aggiungi progetto</button>
            <p style="font-style: italic;"><a href="./index.php">Ritorna alla home</a></p>
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
                $stmt = $connessione->prepare("INSERT INTO Progetto(Nome, Descrizione, Amministratore, Cliente, Stato) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("sssss", $nomeProgetto, $descrizioneProgetto, $cfAmministratore, $clienteProgetto, $statoprogetto);

                $risultato = $stmt->execute();

                if($risultato === TRUE) {
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
    $connessione->close();
    ?>

</body>

</html>