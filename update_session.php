<?php
session_start();

// Perbarui waktu aktivitas terakhir
$_SESSION['last_activity'] = time();
?>
