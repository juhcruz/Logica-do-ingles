<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$modulo_id = isset($_GET['modulo_id']) ? (int)$_GET['modulo_id'] : 0;
$sucesso = '';

if (isset($_GET['apagar_palavra'])) {
    $palavra_id_apagar = (int)$_GET['apagar_palavra'];
    try {
        $delStmt = $pdo->prepare("DELETE p FROM palavras p INNER JOIN modulos m ON p.modulo_id = m.id WHERE p.id = ? AND m.user_id = ?");
        $delStmt->execute([$palavra_id_apagar, $user_id]);
        $sucesso = "Palavra apagada com sucesso!";
    } catch (PDOException $e) {
        // Ignorar erro silencioso ou registar se necessário
    }
}

// Buscar palavras do módulo selecionado (ou de todos os módulos do utilizador)
if ($modulo_id > 0) {
    $stmt = $pdo->prepare("SELECT p.*, m.nome as modulo_nome FROM palavras p INNER JOIN modulos m ON p.modulo_id = m.id WHERE p.modulo_id = ? AND m.user_id = ?");
    $stmt->execute([$modulo_id, $user_id]);
} else {
    $stmt = $pdo->prepare("SELECT p.*, m.nome as modulo_nome FROM palavras p INNER JOIN modulos m ON p.modulo_id = m.id WHERE m.user_id = ?");
    $stmt->execute([$user_id]);
}

$palavras = $stmt->fetchAll(PDO::FETCH_ASSOC);

include __DIR__ . '/includes/header.php';
?>

<main class="container-principal secao-pagina">

    <div class="topo-navegacao"">
        <h2>Estudo Ativo</h2>
        <a href="painel.php" class="btn-voltar">← Voltar ao Painel</a>
    </div>

    <?php if (!empty($sucesso)): ?>
        <div class="alerta-sucesso"><?php echo htmlspecialchars($sucesso); ?></div>
    <?php endif; ?>

    <?php if (count($palavras) > 0): ?>
        <div class="estudo-palco">
            <div id="flashcard-3d" class="flashcard-container">
                <!-- FRENTE: INGLÊS -->
                <div class="flashcard-face flashcard-frente">
                    <span id="contador-cartao" class="flashcard-contador"></span>
                    <span id="card-modulo" class="flashcard-modulo"></span>
                    <div id="card-palavra" class="flashcard-palavra"></div>
                </div>

                <!-- VERSO: TRADUÇÃO PORTUGUÊS -->
                <div id="card-resposta" class="flashcard-face flashcard-verso">
                    <span class="flashcard-etiqueta-lado">Tradução</span>
                    <div id="card-traducao" class="flashcard-traducao"></div>
                    <p id="card-frase" class="flashcard-exemplo"></p>

                    <div style="margin-top: 18px;">
                        <a id="btn-excluir" href="#" class="btn-acao apagar">Excluir esta Palavra 🗑️</a>
                    </div>
                </div>
            </div>

            <!-- Botões de Controlo -->
            <div class="flashcard-acoes">
                <button type="button" id="btn-revelar" class="btn-flashcard btn-flashcard-primario">Mostrar Resposta</button>
                <button type="button" id="btn-memorizei" class="btn-flashcard" style="background-color: #10b981; color: #ffffff; display: none;">Memorizei ✓</button>
                <button type="button" id="btn-rever" class="btn-flashcard" style="background-color: #f59e0b; color: #ffffff; display: none;">Rever Depois</button>
                <button type="button" id="btn-proximo" class="btn-flashcard btn-flashcard-secundario">Próximo ➔</button>
            </div>

            <p id="pontuacao-estudo" style="text-align: center; font-weight: 700; color: var(--texto-principal); margin-top: 16px;"></p>
        </div>
    <?php else: ?>
        <div class="cartao-simples" style="text-align: center; padding: 36px;">
            <p>Ainda não existem palavras registadas para estudar neste módulo.</p>
            <a href="painel.php" class="btn-azul btn-azul-inline">Voltar ao Painel</a>
        </div>
    <?php endif; ?>
</main>

<script>
    const baralho = <?php echo json_encode($palavras, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?: '[]'; ?>;
    let indiceAtual = 0;
    let memorizadas = new Set();

    const elCard3D = document.getElementById('flashcard-3d');
    const elContador = document.getElementById('contador-cartao');
    const elPontuacao = document.getElementById('pontuacao-estudo');
    const elModulo = document.getElementById('card-modulo');
    const elPalavra = document.getElementById('card-palavra');
    const elTraducao = document.getElementById('card-traducao');
    const elFrase = document.getElementById('card-frase');

    const btnRevelar = document.getElementById('btn-revelar');
    const btnMemorizei = document.getElementById('btn-memorizei');
    const btnRever = document.getElementById('btn-rever');
    const btnProximo = document.getElementById('btn-proximo');
    const btnExcluir = document.getElementById('btn-excluir');

    function resetarParaFrente() {
        elCard3D.classList.remove('virado');
        btnRevelar.style.display = 'inline-flex';
        btnMemorizei.style.display = 'none';
        btnRever.style.display = 'none';
    }

    function renderizarCartao() {
        if (!baralho || baralho.length === 0) return;
        const item = baralho[indiceAtual];
        elContador.textContent = `Cartão ${indiceAtual + 1} de ${baralho.length}`;
        elModulo.textContent = `Módulo: ${item.modulo_nome}`;
        elPalavra.textContent = item.palavra_en;
        elTraducao.textContent = item.traducao_pt;
        elFrase.textContent = item.frase_exemplo ? `"${item.frase_exemplo}"` : '';

        const currentModuleParam = <?php echo $modulo_id; ?> > 0 ? `&modulo_id=<?php echo $modulo_id; ?>` : '';
        btnExcluir.href = `estudo_ativo.php?apagar_palavra=${item.id}${currentModuleParam}`;
        btnExcluir.onclick = function() {
            return confirm(`Tem a certeza que deseja eliminar a palavra "${item.palavra_en}"?`);
        };

        resetarParaFrente();
    }

    btnRevelar.addEventListener('click', function() {
        elCard3D.classList.add('virado');
        btnRevelar.style.display = 'none';
        btnMemorizei.style.display = 'inline-flex';
        btnRever.style.display = 'inline-flex';
    });

    btnMemorizei.addEventListener('click', function() {
        memorizadas.add(baralho[indiceAtual].id);
        elPontuacao.textContent = `Memorizadas nesta sessão: ${memorizadas.size}`;
        avancarCartao();
    });

    btnRever.addEventListener('click', function() {
        baralho.push(baralho[indiceAtual]);
        avancarCartao();
    });

    function avancarCartao() {
        if (indiceAtual < baralho.length - 1) {
            indiceAtual++;
            renderizarCartao();
        } else {
            alert(`Sessão concluída! Memorizaste ${memorizadas.size} palavra(s).`);
            indiceAtual = 0;
            renderizarCartao();
        }
    }

    btnProximo.addEventListener('click', avancarCartao);

    if (baralho.length > 0) {
        renderizarCartao();
    }
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>