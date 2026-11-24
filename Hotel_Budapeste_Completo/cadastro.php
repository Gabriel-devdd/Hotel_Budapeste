<?php
require_once 'conexao.php';

$mensagem = "";
$tipoMensagem = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $nome_usuario = trim($_POST['nome_usuario'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $telefone = trim($_POST['telefone'] ?? '');

    if ($nome !== '' && $nome_usuario !== '' && $email !== '' && $senha !== '' && $telefone !== '') {
        $sql = "INSERT INTO usuario (nome, nome_usuario, email, senha, telefone)
                VALUES (:nome, :nome_usuario, :email, :senha, :telefone)";

        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':nome' => $nome,
                ':nome_usuario' => $nome_usuario,
                ':email' => $email,
                ':senha' => $senha,
                ':telefone' => $telefone,
            ]);

            $mensagem = "Cadastro realizado com sucesso! Faça login para continuar.";
            $tipoMensagem = "sucesso";
        } catch (PDOException $e) {
            $mensagem = "Não foi possível concluir o cadastro.";
            $tipoMensagem = "erro";
        }
    } else {
        $mensagem = "Preencha todos os campos obrigatórios!";
        $tipoMensagem = "erro";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro | O Grande Hotel Budapeste</title>
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
        <span class="subtitulo-dourado">Novo Hóspede</span>
        <h2>Criar Conta no Hotel Budapeste</h2>

        <div class="painel-duplo" style="max-width: 550px; margin: 0 auto;">
            <div class="box-login">
                <?php if ($mensagem !== ''): ?>
                    <p class="<?php echo $tipoMensagem; ?>"><?php echo htmlspecialchars($mensagem); ?></p>
                <?php endif; ?>

                <form action="cadastro.php" method="POST">
                    <label for="nome">Nome Completo</label>
                    <input type="text" name="nome" id="nome" placeholder="Seu nome..." required>

                    <label for="nome_usuario">Nome de Usuário</label>
                    <input type="text" name="nome_usuario" id="nome_usuario" placeholder="Seu usuário..." required>

                    <label for="email">E-mail</label>
                    <input type="email" name="email" id="email" placeholder="Seu e-mail..." required>

                    <label for="senha">Senha</label>
                    <input type="password" name="senha" id="senha" placeholder="Sua senha..." required>

                    <label for="telefone">Telefone</label>
                    <input type="tel" name="telefone" id="telefone" placeholder="(xx) xxxxx-xxxx" required>

                    <button type="submit" class="btn-dourado">CADASTRAR</button>
                </form>

                <p style="margin-top: 20px; font-size: 13px;">
                    Já possui conta? <a href="login.php" style="color: #E2C078;">Faça login</a>
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