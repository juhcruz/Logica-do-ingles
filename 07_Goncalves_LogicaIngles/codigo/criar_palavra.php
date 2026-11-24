<?php
// codigo/criar_palavra.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$erro = '';

// Buscar módulos do utilizador para o select
$stmt_mod = $pdo->prepare("SELECT * FROM modulos WHERE user_id = ?");
$stmt_mod->execute([$user_id]);
$lista_modulos = $stmt_mod->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome_modulo = trim($_POST['nome_modulo'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $palavra_en = trim($_POST['palavra_en'] ?? '');
    $traducao_pt = trim($_POST['traducao_pt'] ?? '');
    $frase_exemplo = trim($_POST['frase_exemplo'] ?? '');

    if (!empty($nome_modulo) && !empty($palavra_en) && !empty($traducao_pt) && !empty($frase_exemplo)) {
        try {
            // Iniciar a transação PDO com segurança
            $pdo->beginTransaction();

            // Verificar se o módulo já existe (usando a coluna 'nome' da base de dados)
            $stmt_chk = $pdo->prepare("SELECT id FROM modulos WHERE user_id = ? AND nome = ?");
            $stmt_chk->execute([$user_id, $nome_modulo]);
            $mod = $stmt_chk->fetch(PDO::FETCH_ASSOC);

            if ($mod) {
                $modulo_id = $mod['id'];
            } else {
                $stmt_ins_mod = $pdo->prepare("INSERT INTO modulos (user_id, nome, descricao) VALUES (?, ?, ?)");
                $stmt_ins_mod->execute([$user_id, $nome_modulo, $descricao]);
                $modulo_id = $pdo->lastInsertId();
            }

            // Inserir a palavra associada ao ID do módulo
            $stmt_palavra = $pdo->prepare("INSERT INTO palavras (modulo_id, palavra_en, traducao_pt, frase_exemplo) VALUES (?, ?, ?, ?)");
            $stmt_palavra->execute([$modulo_id, $palavra_en, $traducao_pt, $frase_exemplo]);

            $pdo->commit();

            header('Location: painel.php');
            exit;

        } catch (Exception $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            $erro = "Erro ao guardar dados: " . $e->getMessage();
        }
    } else {
        $erro = "Por favor, preencha todos os campos obrigatórios.";
    }
}

include __DIR__ . '/includes/header.php';
?>

<main class="container-principal secao-pagina">
    <div class="painel-boas-vindas">
        <div>
            <h2>Criar Módulo e Adicionar Vocabulário</h2>
            <p>Cria um novo módulo ou escolhe um existente e regista uma palavra com contexto.</p>
        </div>
        <a href="painel.php" class="btn-voltar">← Voltar ao Painel</a>
    </div>

    <?php if (!empty($erro)): ?>
        <div class="alerta-erro"><?php echo htmlspecialchars($erro); ?></div>
    <?php endif; ?>

    <form action="criar_palavra.php" method="POST" id="formCriarPalavra" class="cartao-simples">
        <div class="campo-form">
            <h3 class="secao-titulo">Informação do Módulo</h3>

            <?php if (!empty($lista_modulos)): ?>
                <div class="campo-form">
                    <label for="modulo_existente" class="rotulo-campo">Selecionar Módulo Existente (Opcional):</label>
                    <select id="modulo_existente" name="modulo_existente" onchange="if(this.value) document.getElementById('nome_modulo').value = this.value;">
                        <option value="">-- Ou escreve um novo módulo abaixo --</option>
                        <?php foreach ($lista_modulos as $mod): ?>
                            <?php
                                $nomeModVal = $mod['nome'] ?? ($mod['nome_modulo'] ?? '');
                            ?>
                            <option value="<?php echo htmlspecialchars($nomeModVal); ?>">
                                <?php echo htmlspecialchars($nomeModVal); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php endif; ?>

            <div class="form-dupla-coluna">
                <div class="campo-form">
                    <label for="nome_modulo" class="rotulo-campo">Nome do Módulo / Categoria:</label>
                    <input type="text" id="nome_modulo" name="nome_modulo" required placeholder="Ex: Tecnologia IT">
                </div>

                <div class="campo-form">
                    <label for="descricao" class="rotulo-campo">Descrição (Opcional):</label>
                    <input type="text" id="descricao" name="descricao" placeholder="Ex: Vocabulário técnico de redes">
                </div>
            </div>
        </div>

        <div class="campo-form">
            <h3 class="secao-titulo">Detalhes da Palavra</h3>

            <div class="form-dupla-coluna">
                <div class="campo-form">
                    <label for="palavra_en" class="rotulo-campo">Palavra em Inglês:</label>
                    <input type="text" id="palavra_en" name="palavra_en" required placeholder="Ex: Resilient">
                </div>

                <div class="campo-form">
                    <label for="traducao_pt" class="rotulo-campo">Tradução em Português:</label>
                    <input type="text" id="traducao_pt" name="traducao_pt" required placeholder="Ex: Resiliente">
                </div>
            </div>

            <div class="campo-form">
                <label for="frase_exemplo" class="rotulo-campo">Frase de Exemplo (Contexto):</label>
                <textarea id="frase_exemplo" name="frase_exemplo" required rows="3" placeholder="Ex: Our system is robust and resilient."></textarea>
            </div>
        </div>

        <div class="acoes-formulario">
            <a href="painel.php" class="btn-voltar">Cancelar</a>
            <button type="submit" class="btn-azul btn-azul-inline">Salvar Vocabulário</button>
        </div>
    </form>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>