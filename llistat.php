<?php

session_start();
if( !$_SESSION['usuari'] ) {

    header('Location: index.php');
    exit();

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

    <p><a href="logout.php">Tancar sessió</a></p>

</body>
</html>