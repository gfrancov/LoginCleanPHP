<?php

session_start();
if( !$_SESSION['usuari'] ) {

    header('Location: index.php');
    exit();

}

include 'database.php';

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

        // Selecció de totes les partides
        $partidesSQL = "SELECT * FROM partides"; 
        $resultPartides = mysqli_query($connexio, $partidesSQL);

        // Declarar array de classificacio
        $classificacio = array();

        // Recorro totes les partides realitzades
        foreach ($resultPartides as $partida) {
            
            if($partida['Punts1'] > $partida['Punts2']) {
                // Si l'equip 1 guanya
                $guanyador = $partida['IDParella1'];
                $perdedor = $partida['IDParella2'];

            } else if( $partida['Punts1'] < $partida['Punts2'] ) {
                // Si l'equip 2 guanya
                $guanyador = $partida['IDParella2'];
                $perdedor = $partida['IDParella1'];
            }

            // Parella 1 (es declara si encara no existeix)
            if( !isset($classificacio[ $partida['IDParella1'] ])) {

                $classificacio[ $partida['IDParella1'] ] = array(
                    "id" => $partida['IDParella1'],
                    "punts" => 0,
                    "guanyats" => 0,
                    "perduts" => 0,
                    "punts" => 0
                );

            }

            // Parella 2 (es declara si encara no existeix)
            if( !isset($classificacio[ $partida['IDParella2'] ])) {

                $classificacio[ $partida['IDParella2'] ] = array(
                    "id" => $partida['IDParella2'],
                    "punts" => 0,
                    "guanyats" => 0,
                    "perduts" => 0,
                    "punts" => 0
                );

            }

            // Si el guanyador és l'equip 1 Parella 1 suma guanyats i Parella 2 suma perduts
            if( $guanyador == $partida['IDParella1'] ) {
                $classificacio[ $partida['IDParella1'] ]['guanyats'] += 1;
                $classificacio[ $partida['IDParella2'] ]['perduts'] += 1;
            } else if ($guanyador == $partida['IDParella2']) {
                // Si el guanyador és l'equip 2 Parella 1 suma perduts i Parella 2 suma guanyats
                $classificacio[ $partida['IDParella2'] ]['guanyats'] += 1;
                $classificacio[ $partida['IDParella1'] ]['perduts'] += 1;
            }

            // Assignem els punts d'aquesta partida
            $classificacio[ $partida['IDParella1'] ]['punts'] += $partida['Punts1'];
            $classificacio[ $partida['IDParella2'] ]['punts'] += $partida['Punts2'];

        }

        // Ficar la classificacio a la base de dades
        foreach ($classificacio as $fila) {
            $classificacioSQL = "UPDATE parelles SET PG = {$fila['guanyats']}, PP = {$fila['perduts']}, DP = {$fila['punts']} WHERE IDParella = {$fila['id']}";
            $resultClassificacio = mysqli_query($connexio, $classificacioSQL);
        }

    ?>



    <?php

    // Query a la base de dades, amb l'ordre per partits guanyats
    $segonaParellesSQL = "SELECT * FROM parelles ORDER BY PG DESC";
    $resultSegonaParelles = mysqli_query($connexio, $segonaParellesSQL);

    ?>

    <table border="1">
        <tr>
            <th>IDParella</th>
            <th>Nom parella</th>
            <th>PG<br>Partides Guanyades</th>
            <th>PP<br>Partides Perdudes</th>
            <th>DP<br>Difèrencia Punts</th>
        </tr>
        <?php

            // Recorre la query i printar-la en una taula
            foreach ($resultSegonaParelles as $segonaParella) {
                echo "<tr>";
                echo "
                <td>" . $segonaParella['IDParella'] . "</td>
                <td>" . $segonaParella['Descripcio'] . "</td>
                <td>" . $segonaParella['PG'] . "</td>
                <td>" . $segonaParella['PP'] . "</td>
                <td>" . $segonaParella['DP'] . "</td>
                ";
                echo "</tr>";
            }

        ?>
    </table>

    <p><a href="llistat.php">Tornar al llistat de partides</a></p>
    <p><a href="logout.php">Sortir</a></p>

</body>
</html>