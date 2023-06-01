<?php

session_start();
if( !$_SESSION['usuari'] ) {

    header('Location: index.php');
    exit();

}

include 'database.php';

if($_POST) {

    // Inicialitzar string
    $llistatEsborrar = "(";

    // Agafo els IDs d'esborrar, i els fico a l'string inicialitzat anteriorment
    foreach ($_POST['esborrar'] as $idEsborrar) {

        $llistatEsborrar .= $idEsborrar . ","; // L'afegeixo amb una coma al final

    }

    // Trec la coma del últim valor amb rtrim
    $llistatEsborrar = rtrim($llistatEsborrar, ",");

    // Tanco amb el parèntesi
    $llistatEsborrar .= ")";

    $esborrarSQL = "DELETE FROM partides WHERE IDPartida in {$llistatEsborrar}";
    // Exemple de com hauria de ser:
    // "DELETE FROM partides WHERE IDPartida in (1,4,7,12)"
    $resultEsborrar = mysqli_query($connexio, $esborrarSQL);

}

?>

<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enric</title>
</head>
<body>
    
    <?php

        // Missatge de benvinguda
        $missatge = "";

        if($_SESSION['usuari']['sexe'] == 'H') {

            if($_SESSION['usuari']['administrador'] == "S") {
                $missatge .= "Benvingut administrador ";
            } else {
                $missatge .= "Benvingut usuari ";
            }

        } else {

            if($_SESSION['usuari']['administrador'] == "S") {
                $missatge .= "Benvinguda administradora ";
            } else {
                $missatge .= "Benvinguda usuària ";
            }
        }

        $missatge .= $_SESSION['usuari']['nom'];

    ?>

    <p><?php echo $missatge ?></p>

    <!-- Contingut -->

    <?php

    // Creació de la taula
    $partidesSQL = "SELECT * FROM partides"; 
    $resultPartides = mysqli_query($connexio, $partidesSQL);

    $parellesSQL = "SELECT IDParella, Descripcio FROM parelles";
    $resultParelles = mysqli_query($connexio, $parellesSQL);
    
    // Creació d'una array amb els noms de les parelles
    // Queden d'aquesta manera:
    // $parelles = 
    // [1] = Intocables
    // [2] = Papafritas
    foreach ($resultParelles as $fila) {
        $parelles[ $fila['IDParella'] ] = $fila['Descripcio'];
    }

?>

<form action="#" method="POST">

    <table border="1">
        <tr>
            <?php if($_SESSION['usuari']['administrador'] == "S") echo "<th>Esborrar</th>" ?>
            <th>IDPartida</th>
            <th>IDRonda</th>
            <th>IDParella 1</th>
            <th>Nom parella 1</th>
            <th>IDParella 2</th>
            <th>Nom parella 2</th>
            <th>Punts parella 1</th>
            <th>Punts parella 2</th>
        </tr>
        <?php

        foreach ($resultPartides as $partida) {
            
            echo "<tr>";
            
            if($_SESSION['usuari']['administrador'] == "S") {
                echo "<td><input type='checkbox' name='esborrar[]' id='esborrar' value='{$partida['IDPartida']}'/></td>";
            };

            echo"
                <td>{$partida['IDPartida']}</td>
                <td>{$partida['IDRonda']}</td>
                <td>{$partida['IDParella1']}</td>
                <td>{$parelles[ $partida['IDParella1'] ]}</td>
                <td>{$partida['IDParella2']}</td>
                <td>{$parelles[ $partida['IDParella2'] ]}</td>
                <td>{$partida['Punts1']}</td>
                <td>{$partida['Punts2']}</td>
            ";

            echo "</tr>";

        }

        ?>
    </table>

    <?php
        if($_SESSION['usuari']['administrador'] == "S") {
            echo "<p><input type='submit' value='Eliminar partides'></p>";
        }
        ?>
    

</form>

<?php

    if($_SESSION['usuari']['administrador'] == "S") {
        echo "<p><a href='partida.php'>Afegir nova partida</a></p>";
    }
    ?>
    <p><a href="resum.php">Veure classificació</a></p>
    <p><a href="logout.php">Sortir</a></p>

</body>
</html>