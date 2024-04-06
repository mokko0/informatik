<?php
session_start();
if(!isset($_SESSION['email']) || !isset($_SESSION['password']) || !isset($_SESSION['id_utente']) || !isset($_SESSION['tipo_utente']) || $_SESSION['tipo_utente'] != "cliente") {
    header("Location: ../logindenied.php");
}

$id = $_SESSION['id_utente'];
?>


<!DOCTYPE html>
<html>
<head>

    <title>Area Cliente</title>
    
    <link rel="stylesheet" href="../css/style.css" type="text/css">
    <style>
    /* CSS aggiuntivo per sovrascrivere lo stile esistente */
    body {
        background-color: black; /* Cambia il colore di sfondo */
        margin-top: 80px; /* Aumenta ulteriormente lo spazio tra la navbar e il body */
    }

    .container {
        margin: 20px auto;
        width: 80%; /* Ridimensiona il contenitore */
        background-color: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .button-container {
        margin-bottom: 20px;
    }

    /* Modifica lo stile del pulsante di logout */
    input[type="button"][name="logout"],
    input[type="button"][name="login"],
    input[type="button"][name="bookings"],
    input[type="button"][name="loans"] {
        background: #333; /* Cambia il colore di sfondo */
        color: #fff; /* Cambia il colore del testo */
        padding: 10px 20px; /* Modifica il padding */
        border-radius: 5px; /* Aggiunge i bordi arrotondati */
        border: none; /* Rimuove il bordo */
        cursor: pointer;
        transition: background-color 0.3s;
        margin-left: 10px; /* Aggiunge un margine sinistro per separare i pulsanti */
    }

    input[type="button"][name="logout"]:hover,
    input[type="button"][name="login"]:hover,
    input[type="button"][name="bookings"]:hover,
    input[type="button"][name="loans"]:hover {
        background: #555; /* Cambia il colore di sfondo al passaggio del mouse */
    }

    .navbar {
        overflow: hidden;
        background-color: #333;
        position: fixed;
        top: 0;
        width: 100%;
        z-index: 1000; /* Assicura che la navbar sia sopra a tutti gli altri elementi */
        display: flex; /* Utilizza Flexbox per allineare i pulsanti */
        justify-content: flex-end; /* Allinea i pulsanti verso destra */
        padding: 10px; /* Aggiunge spazio interno */
    }

    .navbar input[type="button"] {
        margin-left: 10px; /* Aggiunge spazio tra i pulsanti */
    }

    .navbar input[type="button"]:first-child {
        margin-left: 0; /* Rimuove il margine sinistro dal primo pulsante */
    }

    .navbar input[type="button"]:last-child {
        margin-right: 10px; /* Aggiunge margine destro all'ultimo pulsante */
    }

    .navbar a {
        color: #f2f2f2;
        text-align: center;
        padding: 14px 20px;
        text-decoration: none;
        transition: background-color 0.3s;
    }

    .navbar a:hover {
        background-color: #ddd;
        color: black;
    }

    .navbar a.active {
        background-color: #04AA6D;
        color: white;
    }
</style>
</head>
<body>

<div class="navbar">
    <div>
        <!-- Aggiungi qui i pulsanti se necessario -->
    </div>
    <div class="top-right-container">
        <input type="button" name="logout" value="Logout" onclick="location.href='../logout.php'">
        <input type="button" name="login" value="Home" onclick="location.href='visualizzaCliente.php'">
        <input type="button" name="bookings" value="Prenotazioni" onclick="window.location.href='visualizzaPrenotazioni.php?id=<?php echo $id?>'">
    </div>
</div>

<div style="margin-top:80px;"> <!-- Aumenta ulteriormente lo spazio tra la navbar e il body -->
    <div class="button-container">
        <!-- Rimuovi i bottoni duplicati da qui -->
    </div>
    <div class="container">
        <div class="table-wrapper">
            <table class="fl-table">
                <thead>
                <tr>
                    <th>Numero di inventario</th>
                    <th>Tipologia</th>
                    <th>Categoria</th>
                    <th>Articolo</th>
                    <th>Stato</th>
                    <th>Centro</th>
                    <th>Città</th>
                    <th>Indirizzo</th>
                    <th>Prenotalo</th>
                </tr>
                </thead>
                <tbody>
                <?php

                    if(isset($_GET['idCentro']))
                    {
                        $idCentro = $_GET['idCentro'];
                    }

                    if(isset($_GET['idCategoria']))
                    {
                        $idCategoria = $_GET['idCategoria'];
                    }

                    if(isset($idCentro))
                    {
                        $selectSql = "SELECT id_articolo, numero_inventario, tipologia, categoria, articolo, stato, nome, citta, indirizzo
                                FROM articoli a JOIN centri c 
                                ON a.fk_id_centro = c.id_centro
                                JOIN categorie cat 
                                ON a.fk_id_categoria = cat.id_categoria
                                WHERE fk_id_centro = $idCentro
                                ORDER BY tipologia ASC";
                    }
                    else if(isset($idCategoria))
                    {
                        $selectSql = "SELECT id_articolo, numero_inventario, tipologia, categoria, articolo, stato, nome, citta, indirizzo
                                    FROM articoli a JOIN centri c 
                                    ON a.fk_id_centro = c.id_centro
                                    JOIN categorie cat 
                                    ON a.fk_id_categoria = cat.id_categoria
                                    WHERE fk_id_categoria = $idCategoria";
                    }
                    else
                    {
                        $selectSql = "SELECT id_articolo, numero_inventario, tipologia, categoria, articolo, stato, nome, citta, indirizzo
                                    FROM articoli a JOIN centri c 
                                    ON a.fk_id_centro = c.id_centro
                                    JOIN categorie cat 
                                    ON a.fk_id_categoria = cat.id_categoria
                                    ORDER BY tipologia";
                    }

                    require_once '../db.php';
                    $result = $conn->query($selectSql);
                    while($row = $result->fetch_array(MYSQLI_ASSOC))
                    {
                        $n_inventario = $row['numero_inventario'];
                        $tipologia = $row['tipologia'];
                        $categoria = $row['categoria'];
                        $articolo = $row['articolo'];
                        $stato = $row['stato'];
                        $nome = $row['nome'];
                        $citta = $row['citta'];
                        $indirizzo = $row['indirizzo'];
                        $isAvailable = ($stato == "disponibile") ? 1 : 0;

                        echo
                            '<tr>
                                <td class="colonnaNumero">'.$n_inventario.'</td>
                                <td class="colonnaTipo">'.$tipologia.'</td>
                                <td class="colonnaCategoria">'.$categoria.'</td>
                                <td class="colonnaArticolo">'.$articolo.'</td>
                                <td class="colonnaStato">'.$stato.'</td>
                                <td class="colonnaNome">'.$nome.'</td>
                                <td class="colonnaCitta">'.$citta.'</td>
                                <td class="colonnaIndirizzo">'.$indirizzo.'</td>
                                <td class="colonnaTasti">
                                    <button class="btn" type="button" onclick="location.href=\'prenota.php?id='.$row["id_articolo"].'\'" '. ($isAvailable == 1 ? "" : "disabled") . '>Prenota</button>
                                </td>
                            </tr>';
                    }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
<?php $conn->close();?>
</html>