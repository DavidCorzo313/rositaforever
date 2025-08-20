<?php
// cierra la sesion si detecta parametro logut o sino lo digie al login
session_start();
if (isset($_GET['logout'])) { //Verifica si en la URL se pasó un parámetro logout 
    session_destroy(); 
    header('Location: views/usuarios/login.php');
    exit;
}
// if ($_SESSION[''])
header('Location: views/usuarios/login.php');