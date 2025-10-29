<?php
if (!function_exists('protect')) {
    function protect() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }   
        if (!isset($_SESSION['nome_usuario']) || !isset($_SESSION['id_usuario'])) {
            header("Location: ../public/login.php");
            exit();
        }
    }
}
?>
