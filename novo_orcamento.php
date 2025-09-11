<?php
session_start();

// Limpa os dados do orçamento
unset($_SESSION['produtos']);
unset($_SESSION['cliente']);

// Redireciona para a página inicial do orçamento
header('Location: index.php');
exit;
