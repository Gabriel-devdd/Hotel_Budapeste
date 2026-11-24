<?php
session_start();

if (isset($_SESSION['usuario_id'])) {
    header("Location: reserva.php");
    exit();
}

$erro = $_SESSION['erro_login'] ?? '';
unset($_SESSION['erro_login']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | O Grande Hotel Budapeste</title>
    <link rel="stylesheet" href="./CSS/style.css">
</head>
<body>
    <header>
        <div class="logo">
            <span>∞</span>
            <h1>O GRANDE HOTEL<br>BUDAPESTE</h1>
        </div>
        <nav>
            <ul>
                <li><a href="index.html">Início</a></li>
                <li><a href="paradoxo.html">Paradoxo de Hilbert</a></li>
                <li><a href="fotos.html">Fotos & Suítes</a></li>
                <li><a href="historias.html">Histórias</a></li>
                <li><a href="concierge.html">Concierge</a></li>
                <li><a href="login.php" class="btn-topo">FAÇA LOGIN</a></li>
            </ul>
        </nav>
    </header>

    <section>
        <span class="subtitulo-dourado">Acesso do Hóspede</span>
        <h2>Entrar para Ver e Fazer Reservas</h2>

        <div class="painel-duplo" style="max-width: 550px; margin: 0 auto;">
            <div class="box-login">
                <h3>Autenticação</h3>
                <p style="font-size: 13px; color: #EAD8DA; margin-bottom: 15px;">
                    Reveja suas reservas e lembre-se que elas sempre estarão disponíveis.
                </p>

                <?php if ($erro !== ''): ?>
                    <p class="erro"><?php echo htmlspecialchars($erro); ?></p>
                <?php endif; ?>

                <form action="autenticacao.php" method="POST">
                    <label for="email">E-mail</label>
                    <input type="email" name="email" id="email" placeholder="Seu e-mail..." required>

                    <label for="senha">Senha</label>
                    <input type="password" name="senha" id="senha" placeholder="Sua senha..." required>

                    <button type="submit" class="btn-dourado">ENTRAR</button>
                </form>

                <p style="margin-top: 20px; font-size: 13px;">
                    Ainda não tem conta? <a href="cadastro.php" style="color: #E2C078;">Cadastre-se aqui</a>
                </p>
            </div>
        </div>
    </section>

    <footer>
        <p><strong>∞ GRAND BUDAPEST</strong></p>
        <p>© 2026 O Grande Hotel Budapeste. Todos os direitos infinitos reservados.</p>
        <p>PRAÇA ZUBROWKA, N° ∞</p>
    </footer>
</body>
</html>