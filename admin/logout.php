<?php
require_once __DIR__ . '/../includes/functions.php';
session_destroy();
session_start();
$_SESSION['cart'] = [];
redirect(BASE_URL . '/public/index.php');
