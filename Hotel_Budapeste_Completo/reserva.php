<?php
session_start();
require_once 'conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$usuarioId = $_SESSION['usuario_id'];
$usuarioNome = $_SESSION['usuario_nome'] ?? 'Hóspede';
$mensagem = "";
$tipoMensagem = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idHotel = filter_input(INPUT_POST, 'id_hotel', FILTER_VALIDATE_INT);
    $idQuarto = filter_input(INPUT_POST, 'id_quarto', FILTER_VALIDATE_INT);
    $dataEntrada = $_POST['data_entrada'] ?? '';
    $dataSaida = $_POST['data_saida'] ?? '';

    if (!$idHotel || !$idQuarto || $dataEntrada === '' || $dataSaida === '') {
        $mensagem = "Preencha todos os campos da reserva.";
        $tipoMensagem = "erro";
    } elseif ($dataSaida <= $dataEntrada) {
        $mensagem = "A data de saída deve ser posterior à data de entrada.";
        $tipoMensagem = "erro";
    } else {
        $stmtVerifica = $pdo->prepare("SELECT id_quarto FROM quartos WHERE id_quarto = :id_quarto AND id_hotel = :id_hotel LIMIT 1");
        $stmtVerifica->execute([
            ':id_quarto' => $idQuarto,
            ':id_hotel' => $idHotel,
        ]);

        if (!$stmtVerifica->fetch(PDO::FETCH_ASSOC)) {
            $mensagem = "O quarto selecionado não pertence à unidade de hotel escolhida.";
            $tipoMensagem = "erro";
        } else {
            $sql = "INSERT INTO reserva (id_usuario, id_hotel, id_quarto, data_entrada, data_saida)
                    VALUES (:id_usuario, :id_hotel, :id_quarto, :data_entrada, :data_saida)";

            try {
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':id_usuario' => $usuarioId,
                    ':id_hotel' => $idHotel,
                    ':id_quarto' => $idQuarto,
                    ':data_entrada' => $dataEntrada,
                    ':data_saida' => $dataSaida,
                ]);

                $mensagem = "Reserva realizada com sucesso!";
                $tipoMensagem = "sucesso";
            } catch (PDOException $e) {
                $mensagem = "Erro ao salvar a reserva no banco de dados.";
                $tipoMensagem = "erro";
            }
        }
    }
}

$hoteis = $pdo->query("SELECT id_hotel, nome FROM hotel_unidade ORDER BY id_hotel")->fetchAll(PDO::FETCH_ASSOC);

$quartos = $pdo->query("
    SELECT q.id_quarto, q.nome_quarto, q.preco_diaria, q.id_hotel, h.nome AS nome_hotel
    FROM quartos q
    INNER JOIN hotel_unidade h ON q.id_hotel = h.id_hotel
    ORDER BY h.id_hotel, q.id_quarto
")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nova Reserva | O Grande Hotel Budapeste</title>
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
                <li><a href="fotos.html">Fotos & Suítes</a></li>
                <li><a href="reserva.php">Fazer Reserva</a></li>
                <li><a href="consulta.php">Minhas Reservas</a></li>
                <li><a href="logout.php" class="btn-topo">SAIR</a></li>
            </ul>
        </nav>
    </header>

    <section>
        <span class="subtitulo-dourado">Área do Hóspede</span>
        <h2>Bem-vindo, <?php echo htmlspecialchars($usuarioNome); ?>!</h2>
        <p class="descricao">Selecione a unidade, a suíte e as datas da sua estadia.</p>

        <div class="modulo-alocacao" style="max-width: 650px; margin: 0 auto; text-align: left;">
            <h3>Nova Reserva</h3>

            <?php if ($mensagem !== ''): ?>
                <p class="<?php echo htmlspecialchars($tipoMensagem); ?>">
                    <?php echo htmlspecialchars($mensagem); ?>
                </p>
            <?php endif; ?>

            <form action="reserva.php" method="POST">
                <label for="id_hotel">Hotel / Unidade</label>
                <select name="id_hotel" id="id_hotel" required>
                    <option value="">Selecione...</option>
                    <?php foreach ($hoteis as $hotel): ?>
                        <option value="<?php echo htmlspecialchars($hotel['id_hotel']); ?>">
                            <?php echo htmlspecialchars($hotel['nome']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="id_quarto">Quarto</label>
                <select name="id_quarto" id="id_quarto" required>
                    <option value="">Selecione...</option>
                    <?php foreach ($quartos as $quarto): ?>
                        <option value="<?php echo htmlspecialchars($quarto['id_quarto']); ?>">
                            <?php echo htmlspecialchars($quarto['nome_hotel'] . ' — ' . $quarto['nome_quarto'] . ' (R$ ' . number_format($quarto['preco_diaria'], 2, ',', '.') . '/diária)'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="data_entrada">Data de Entrada</label>
                <input type="date" name="data_entrada" id="data_entrada" required>

                <label for="data_saida">Data de Saída</label>
                <input type="date" name="data_saida" id="data_saida" required>

                <button type="submit" class="btn-dourado">CONFIRMAR RESERVA</button>
            </form>
        </div>
    </section>

    <footer>
        <p><strong>∞ GRAND BUDAPEST</strong></p>
        <p>© 2026 O Grande Hotel Budapeste. Todos os direitos infinitos reservados.</p>
        <p>PRAÇA ZUBROWKA, N° ∞</p>
    </footer>
</body>
</html>