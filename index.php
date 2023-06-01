<?php
session_start();

if($_POST) {

    include 'database.php';

    if($connexio) {

        $loginSQL = "SELECT * FROM usuaris WHERE usuari = '{$_POST['usuari']}' AND contrasenya = '{$_POST['contrasenya']}'"; 
        $result = mysqli_query($connexio, $loginSQL);

        $tempUsuari = mysqli_fetch_row($result);

        if($tempUsuari) {
            
            $_SESSION['usuari'] = [
                'usuari' => $tempUsuari[0],
                'nom' => $tempUsuari[2],
                'administrador' => $tempUsuari[3],
                'sexe' => $tempUsuari[4],
            ];

            header('Location: llistat.php');

        } else {

            echo "<p style='color: red;'>L'usuari no existeix!</p>";

        }
        
    } else {
        mysqli_connect_error();
        echo "<p style='color: red;'>Error amb la connexió amb la base de dades!</p>";
    }

} else if( isset($_SESSION['usuari'])) {

    echo "<p style='color: green'>" . $_SESSION['usuari']['nom'] . " s'ha recordat la teva sessió, redirigint...</p>";
    ?> 
    <meta http-equiv="refresh" content="2; url=llistat.php" />
    <?php
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enric</title>
</head>
<body>
    <h1>Validació d'usuaris!</h1>
    <form method="POST" action="#">

        <table>
            <tr>
                <td>Usuari:</td>
                <td>
                    <input type="text" id="usuari" name="usuari" required/>
                </td>
            </tr>
            <tr>
                <td>Contrasenya</td>
                <td>
                    <input type="password" id="contrasenya" name="contrasenya" required>
                </td>
            </tr>
            <tr>
                <td>
                    <input type="submit" value="Iniciar sessió">
                </td>
                <td>
                    <input type="reset" value="Netejar">
                </td>
            </tr>
        </table>

    </form>

</body>
</html>