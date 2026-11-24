<?php
// codigo/painel.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/db.php';

// Requisito: Verificação de sessão no topo de cada página restrita
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$erro = '';
$sucesso = '';

// Requisito: DELETE com WHERE e prepared statement
if (isset($_GET['apagar'])) {
    $modulo_id = (int) $_GET['apagar'];

    try {
        $stmt = $pdo->prepare("DELETE FROM modulos WHERE id = ? AND user_id = ?");
        $stmt->execute([$modulo_id, $user_id]);
        $sucesso = "Módulo apagado com sucesso!";
    } catch (PDOException $e) {
        $erro = "Erro ao apagar o módulo.";
    }
}

// Requisito: Pesquisa ou filtro na listagem (Funcionalidade 4 do Dossier)
$pesquisa = isset($_GET['pesquisa']) ? trim($_GET['pesquisa']) : '';

if (!empty($pesquisa)) {
    $stmt = $pdo->prepare("SELECT * FROM modulos WHERE user_id = ? AND nome LIKE ? ORDER BY id DESC");
    $stmt->execute([$user_id, "%$pesquisa%"]);
} else {
    // Requisito: SELECT
    $stmt = $pdo->prepare("SELECT * FROM modulos WHERE user_id = ? ORDER BY id DESC");
    $stmt->execute([$user_id]);
}
$modulos = $stmt->fetchAll(PDO::FETCH_ASSOC);

include __DIR__ . '/includes/header.php';
?>

<main class="container-principal secao-pagina">
    <div class="painel-boas-vindas">
        <div>
            <p>Bem-vindo, <strong><?php echo htmlspecialchars($_SESSION['user_email']); ?></strong></p>
        </div>
    </div>

    <?php if (!empty($erro)): ?>
        <div class="alerta-erro"><?php echo htmlspecialchars($erro); ?></div>
    <?php endif; ?>
    <?php if (!empty($sucesso)): ?>
        <div class="alerta-sucesso"><?php echo htmlspecialchars($sucesso); ?></div>
    <?php endif; ?>

    <div class="cartao-simples acoes-painel">
        <div class="acoes-esquerda">
            <a href="criar_palavra.php" class="btn-azul btn-azul-inline">+ Adicionar Novo Módulo</a>
        </div>

        <!-- Formulário de Pesquisa / Filtro (Funcionalidade 4) -->
        <form action="painel.php" method="GET" class="form-pesquisa">
            <input type="text" name="pesquisa" placeholder="Pesquisar módulo..." value="<?php echo htmlspecialchars($pesquisa); ?>">
            <button type="submit" class="btn-voltar">Filtrar</button>
        </form>
    </div>

    <h3 class="secao-titulo">Os Meus Módulos de Estudo</h3>

    <?php if (count($modulos) > 0): ?>
        <div class="tabela-wrapper">
            <table class="tabela-dados">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome do Módulo</th>
                        <th>Descrição</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($modulos as $m): ?>
                    <tr>
                        <td><?php echo (int) $m['id']; ?></td>
                        <td><strong><?php echo htmlspecialchars($m['nome']); ?></strong></td>
                        <td><?php echo htmlspecialchars($m['descricao']); ?></td>
                        <td class="celula-acoes">
                            <a href="estudo_ativo.php?modulo_id=<?php echo (int) $m['id']; ?>" class="btn-acao ver">Ver</a>
                            <a href="editar_modulo.php?id=<?php echo (int) $m['id']; ?>" class="btn-acao editar">Editar</a>
                            <a href="painel.php?apagar=<?php echo (int) $m['id']; ?>" class="btn-acao apagar" onclick="return confirm('Tem a certeza que deseja apagar este módulo e todas as suas palavras?');">Excluir</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="cartao-simples">
            <p>Ainda não tem módulos criados. Clique em <strong>+ Adicionar Novo Módulo</strong> para começar.</p>
        </div>
    <?php endif; ?>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>