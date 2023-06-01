<?php

session_start();

session_unset();
session_destroy();

echo "<p style='color: red;'>Tancant sessió...</p>";
?>

<meta http-equiv="refresh" content="0; url=index.php" />
