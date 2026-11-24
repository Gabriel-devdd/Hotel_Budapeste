<?php
session_start();
require_once 'conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$usuarioId = $_SESSION['usuario_id'];
$usuarioNome = $_SESSION['usuario_nome'] ?? 'Hóspede';

$sql = "SELECT
            r.id_reserva,
            h.nome AS nome_hotel,
            q.nome_quarto,
            q.preco_diaria,
            r.data_entrada,
            r.data_saida
        FROM reserva r
        INNER JOIN hotel_unidade h ON r.id_hotel = h.id_hotel
        INNER JOIN quartos q ON r.id_quarto = q.id_quarto
        WHERE r.id_usuario = :id_usuario
        ORDER BY r.id_reserva DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute([':id_usuario' => $usuarioId]);
$reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minhas Reservas | O Grande Hotel Budapeste</title>
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
        <span class="subtitulo-dourado">Acomodações Reservadas</span>
        <h2>Reservas de <?php echo htmlspecialchars($usuarioNome); ?></h2>
        <p class="descricao">Consulte suas estadias confirmadas no Grande Hotel Budapeste.</p>

        <?php if (count($reservas) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Hotel / Unidade</th>
                        <th>Quarto</th>
                        <th>Diária</th>
                        <th>Entrada</th>
                        <th>Saída</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reservas as $reserva): ?>
                        <tr>
                            <td>#<?php echo htmlspecialchars($reserva['id_reserva']); ?></td>
                            <td><?php echo htmlspecialchars($reserva['nome_hotel']); ?></td>
                            <td><?php echo htmlspecialchars($reserva['nome_quarto']); ?></td>
                            <td>R$ <?php echo htmlspecialchars(number_format($reserva['preco_diaria'], 2, ',', '.')); ?></td>
                            <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($reserva['data_entrada']))); ?></td>
                            <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($reserva['data_saida']))); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="box-consulta">
                <p>Você ainda não possui reservas cadastradas.</p>
                <a href="reserva.php" class="btn-dourado">FAZER PRIMEIRA RESERVA</a>
            </div>
        <?php endif; ?>
    </section>

    <footer>
        <p><strong>∞ GRAND BUDAPEST</strong></p>
        <p>© 2026 O Grande Hotel Budapeste. Todos os direitos infinitos reservados.</p>
        <p>PRAÇA ZUBROWKA, N° ∞</p>
    </footer>
</body>
</html>