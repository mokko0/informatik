<?php
    session_start();
    if(!isset($_SESSION['email']) || !isset($_SESSION['password']) || !isset($_SESSION['id_utente']) || !isset($_SESSION['tipo_utente']) || $_SESSION['tipo_utente'] != "cliente") {
        header("Location: ../logindenied.php");
    }

    require_once "../db.php";

    $idUtente = $_SESSION['id_utente'];
    $idArticolo = $_GET['id'];
    $checkSql = "SELECT stato FROM articoli WHERE id_articolo = $idArticolo";
    $checkResult = $conn->query($checkSql);
    $checkRow = $checkResult->fetch_assoc();
    if($checkRow['stato'] != "disponibile") {
        header("Location: ../articoloNonDisponibile.php");
    }
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seleziona una data</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        body {
            background: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
        }
        .btn-back {
            margin-bottom: 20px;
        }
        .date-selection {
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .date-selection h1 {
            margin-bottom: 20px;
        }
        .date-selection form .form-group {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <input type="button" class="btn btn-secondary btn-back" value="Indietro" onclick="location.href='cliente.php'">
        <div class="date-selection">
            <h1>Seleziona la data</h1>
            <form method="POST" action="salvaPrenotazione.php">
                <div class="form-group">
                    <input type="date" class="form-control" name="selected_date">
                </div>
                <input type="hidden" name="id_articolo" <?php echo "value=" . $idArticolo ?>>
                <input type="submit" class="btn btn-primary" value="Invia">
            </form>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>