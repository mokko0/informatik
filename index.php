<!DOCTYPE html>
<html>
<head>
    <title>Login area riservata</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        body {
            background: #f4f4f4;
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
        }
        .login-box {
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .login-box h2 {
            margin-bottom: 20px;
            text-align: center;
        }
        .login-box .form-group {
            margin-bottom: 20px;
        }
        .login-box .form-group label {
            margin-left: 5px;
        }
        .btn-register {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<?php
// Verifica se il form è stato inviato
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //Recupera i dati dal form
    $email = $_POST["email"];
    $pass = $_POST["pass"];

    //Connessione al database
    require_once "db.php";

    if(filter_var($email, FILTER_VALIDATE_EMAIL))
    {
        $passmd5 = md5($pass);
        //Prepara e esegui la query per l'inserimento dei dati nella tabella
        $sql = "SELECT * FROM utenti WHERE email='$email' and password='$passmd5'";

        $result = $conn->query($sql);
        if($result->num_rows>0)
        {
            session_start();
            $_SESSION['email'] = $email;
            $_SESSION['password'] = $passmd5;
            while($row = $result->fetch_assoc())
            {
                $_SESSION['id_utente'] = $row["id_utente"];
                $_SESSION['tipo_utente'] = $row["tipo_utente"];
                switch($row["tipo_utente"])
                {
                    case "cliente":
                        header("Location: ./cliente/visualizzaCliente.php");
                        break;
                    case "operatore":
                    case "admin":
                        header("Location: backend/sceltaAdmin.php");
                        break;
                }
            }
        }
        else {
            echo '<script>alert("Identificazione non riuscita: nome utente o password errati")</script>';
        }
    }
    else{
        echo '<script>alert("Dati inseriti non validi")</script>';
    }
}
?>
<div class="container">
    <input type="button" class="btn btn-secondary btn-register" value="Registrati" onclick="location.href='registrazione_cliente.php'">
    <div class="login-box">
        <h2>Inserisci le tue credenziali:</h2>
        <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
            <div class="form-group">
                <input type="email" class="form-control" name="email" required>
                <label>Email</label>
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="pass" required>
                <label>Password</label>
            </div>
            <input class="btn btn-primary" type="submit" value="Accedi">
        </form>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>