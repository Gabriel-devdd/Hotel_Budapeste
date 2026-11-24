<?php
session_start();
require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if ($email === '' || $senha === '') {
        $_SESSION['erro_login'] = "Preencha e-mail e senha.";
        header("Location: login.php");
        exit();
    }

    $stmt = $pdo->prepare("SELECT id_usuario, nome, senha FROM usuario WHERE email = :email LIMIT 1");
    $stmt->execute([':email' => $email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && $senha === $usuario['senha']) {
        session_regenerate_id(true);
        $_SESSION['usuario_id'] = $usuario['id_usuario'];
        $_SESSION['usuario_nome'] = $usuario['nome'];

        header("Location: reserva.php");
        exit();
    }

    $_SESSION['erro_login'] = "E-mail ou senha incorretos.";
    header("Location: login.php");
    exit();
}

header("Location: login.php");
exit();