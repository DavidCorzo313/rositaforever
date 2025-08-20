<?php
session_start();

// Incluye la conexión a la base de datos (aunque no se use aquí directamente)
require_once __DIR__ . '/config/database.php';

// Limpia las variables de sesión
session_unset();

// Destruye la sesión completamente
session_destroy();

// Redirige al formulario de inicio de sesión
header("Location: /rositaforever/views/usuarios/login.php");
exit();
