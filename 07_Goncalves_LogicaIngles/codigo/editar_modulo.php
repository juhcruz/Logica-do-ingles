<?php
// codigo/editar_modulo.php
require_once 'db.php';
include 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$erro = '';
$sucesso = '';

// Buscar dados atuais do módulo
$stmt = $pdo->prepare("SELECT * FROM modulos WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $user_id]);
$modulo = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$modulo) {
    header('Location: painel.php');
    exit;
}

// Processar formulário de atualização
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $descricao = trim($_POST['descricao']);

    if (!empty($nome)) {
        try {
            $update = $pdo->prepare("UPDATE modulos SET nome = ?, descricao = ? WHERE id = ? AND user_id = ?");
            $update->execute([$nome, $descricao, $id, $user_id]);
            $sucesso = "Módulo atualizado com sucesso!";
            // Atualizar dados na vista local
            $modulo['nome'] = $nome;
            $modulo['descricao'] = $descricao;
        } catch (PDOException $e) {
            $erro = "Erro ao atualizar o módulo.";
        }
    } else {
        $erro = "O nome do módulo é obrigatório.";
    }
}
?>

<div class="painel-wrapper">
    <div class="painel-header">
        <h2>Editar Módulo de Estudo</h2>
        <a href="painel.php" class="btn-voltar">← Voltar ao Painel</a>
    </div>

    <?php if (!empty($erro)): ?>
        <div class="alert erro"><?php echo htmlspecialchars($erro); ?></div>
    <?php endif; ?>
    <?php if (!empty($sucesso)): ?>
        <div class="alert sucesso"><?php echo htmlspecialchars($sucesso); ?></div>
    <?php endif; ?>

    <form action="editar_modulo.php?id=<?php echo $id; ?>" method="POST">
        <label for="nome">Nome do Módulo:</label>
        <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($modulo['nome']); ?>" required>

        <label for="descricao">Descrição:</label>
        <textarea id="descricao" name="descricao" rows="4"><?php echo htmlspecialchars($modulo['descricao']); ?></textarea>

        <button type="submit" class="btn" style="margin-top: 15px;">Guardar Alterações</button>
    </form>
</div>

<?php include 'includes/footer.php'; ?>