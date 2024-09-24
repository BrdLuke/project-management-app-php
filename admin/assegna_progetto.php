<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assegnazione progetto - Admin</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <?php
    session_start();
    include ("./navbar.php");

    if (!isset($_SESSION["amministratore"])) {
        echo "<div style='text-align: center; margin-top: 5rem;'>";
        echo "<h1 style='font-size: 4rem;'>Benvenuto</h1>";
        echo "<p>Sembra che non sia registrato/loggato, passa da <a href='./login.html'>qui</a>";
        echo "</div>";
    }

    $connessione = new mysqli("localhost", "root", "", "Azienda");
    if ($connessione->connect_error) {
        die("Errore di connessione: " . $connessione->connect_error);
    }
    ?>

    <div class="container">
        <form method="POST">
            <?php
            $cfAmministratore = $_SESSION["amministratore"]["cf"];

            $codDipendente = $_GET["dipendente"];
            if (!isset($codDipendente) || $codDipendente == null) {
                header("Location: dipendenti.php");
                exit();
            } else {
                $stmt = $connessione->prepare("SELECT CodD, Nome, Cognome, Email FROM Dipendente WHERE CodD = ?");
                $stmt->bind_param("s", $codDipendente);
                $stmt->execute();
                $stmt->store_result();

                if ($stmt->num_rows == 1) {
                    $stmt->bind_result($codDipendente_2, $nomeDipendente, $cognomeDipendente, $emailDipendente);
                    $stmt->fetch();
                    ?>

                    <div class='input'> <b>Dipendente:</b> <input value='<?php echo $codDipendente_2; ?>' readonly> </div>
                    <div class='input'> <b>Nome:</b> <input value='<?php echo $nomeDipendente; ?>' readonly> </div>
                    <div class='input'> <b>Cognome:</b> <input value='<?php echo $cognomeDipendente; ?>' readonly> </div>
                    <div class='input'> <b>Email:</b> <input value='<?php echo $emailDipendente; ?>' readonly> </div>

                    <div class="input">
                        <p><b>Progetto</b>: </p>
                        <select name="id_progetto">
                            <?php
                            $queryProgetti = "SELECT Progetto.ID AS ProgettoID, Progetto.Nome AS ProgettoNome FROM Progetto WHERE Progetto.Amministratore = '$cfAmministratore'";
                            $risultatoProgetti = $connessione->query($queryProgetti);

                            if ($risultatoProgetti->num_rows > 0) {
                                while ($recordProgetti = $risultatoProgetti->fetch_assoc()) {
                                    $idProgetto = $recordProgetti["ProgettoID"];
                                    $nomeProgetto = $recordProgetti["ProgettoNome"];
                                    echo "<option value='$idProgetto'>$nomeProgetto</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <div class="input">
                        <p><b>Assegna Tasks</b>: </p>
                        <select name="tasks[]" id="tasks" multiple>
                            <?php
                            $queryTasks = "SELECT DISTINCT ID, Nome, Descrizione FROM Tasks";
                            $risultato = $connessione->query($queryTasks);

                            if ($risultato->num_rows > 0) {
                                while ($record = $risultato->fetch_assoc()) {
                                    $idTask = $record["ID"];
                                    $nomeTask = $record["Nome"];
                                    $descrizioneTask = $record["Descrizione"];
                                    echo "<option value='$idTask'>$nomeTask -- $descrizioneTask</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <button type="submit">Assegna progetto</button>
                    <p style="font-style: italic;"><a href="./index.php">Ritorna alla home</a></p>

                    <?php
                } else {
                    header("Location: progetti.php");
                    exit();
                }
            }
            ?>
        </form>
    </div>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $idProgetto = $_POST["id_progetto"];
        $tasks = $_POST['tasks'];

        if (!empty($tasks) && !empty($idProgetto)) {
            try {
                $stmt = $connessione->prepare("INSERT INTO Assegnazione(Dipendente, Progetto) VALUES (?, ?)");
                $stmt->bind_param("si", $codDipendente_2, $idProgetto);
                $risultato = $stmt->execute();

                if ($risultato === TRUE) {
                    $assegnazioneID = $connessione->insert_id;

                    foreach ($tasks as $task_id) {
                        $stmt2 = $connessione->prepare("INSERT INTO Assegnazione_Tasks(Assegnazione, Task) VALUES (?, ?)");
                        $stmt2->bind_param('ii', $assegnazioneID, $task_id);

                        if ($stmt2->execute() !== TRUE) {
                            echo "<p style='text-align: center; color: red; font-weight: bold; margin-top: 1rem'> Errore durante l'assegnazione del task $task_id </p> <br>";
                        }
                    }
                    header("Location: dipendenti.php");
                } else {
                    echo "Errore durante l'assegnazione del progetto.";
                }
            } catch (Exception $e) {
                echo "<p style='text-align: center; color: red; font-weight: bold; margin-top: 1rem'>Errore durante l'invio ... Riprovare" . $e->getMessage();
                echo "</p>";
            }
        } else {
            echo "<p style='background-color: white; width: 20%; margin-right: auto; margin-left: auto; text-align: center; color: red; font-weight: bold; margin-top: -10rem'>Seleziona almeno un task e specifica un progetto valido.</p>";
        }
    }
    $connessione->close();
    ?>
</body>

</html>