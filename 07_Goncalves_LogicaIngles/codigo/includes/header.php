<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$paginaAtual = basename($_SERVER['PHP_SELF'] ?? '');

$utilizadorLogado = !empty($_SESSION['utilizador_id'])
    || !empty($_SESSION['usuario_id'])
    || !empty($_SESSION['id_utilizador'])
    || !empty($_SESSION['id_usuario'])
    || !empty($_SESSION['user_id'])
    || !empty($_SESSION['utilizador'])
    || !empty($_SESSION['usuario'])
    || !empty($_SESSION['email']);

$tituloPagina = $tituloPagina ?? 'Lógica do Inglês';
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($tituloPagina, ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="icon" type="image/svg+xml" href="assets/img/logo.svg">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <header class="topo-navegacao">
        <a href="index.php" class="marca-app" aria-label="Lógica do Inglês - Início">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="logo-app" role="img" aria-label="Logótipo Lógica do Inglês">
                <defs>
                    <linearGradient id="bg-gradiente-header" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" stop-color="#38bdf8" />
                        <stop offset="45%" stop-color="#3b5bfd" />
                        <stop offset="100%" stop-color="#9333ea" />
                    </linearGradient>
                    <linearGradient id="brilho-topo-header" x1="0%" y1="0%" x2="0%" y2="100%">
                        <stop offset="0%" stop-color="#ffffff" stop-opacity="0.16" />
                        <stop offset="100%" stop-color="#ffffff" stop-opacity="0" />
                    </linearGradient>
                    <filter id="sombra-limpa-header" x="-15%" y="-15%" width="130%" height="135%">
                        <feDropShadow dx="0" dy="8" stdDeviation="10" flood-color="#1e1b4b" flood-opacity="0.18" />
                    </filter>
                </defs>
                <rect x="24" y="24" width="464" height="464" rx="112" ry="112" fill="url(#bg-gradiente-header)" />
                <rect x="24" y="24" width="464" height="464" rx="112" ry="112" fill="url(#brilho-topo-header)" />
                <g filter="url(#sombra-limpa-header)">
                    <path d="M 184 214 L 184 294 A 72 72 0 0 0 328 294 L 328 214"
                          fill="none"
                          stroke="#ffffff"
                          stroke-width="44"
                          stroke-linecap="round"
                          stroke-linejoin="round" />
                    <circle cx="184" cy="142" r="24" fill="#ffffff" />
                    <circle cx="328" cy="142" r="24" fill="#f59e0b" />
                </g>
            </svg>
            <span class="titulo-app">Lógica do Inglês</span>
        </a>

        <nav class="topo-nav" aria-label="Navegação principal">
            <?php if ($utilizadorLogado): ?>
                <a href="painel.php" class="btn-voltar<?= $paginaAtual === 'painel.php' ? ' ativo-nav' : '' ?>">Painel</a>
                <a href="estudo_ativo.php" class="btn-voltar<?= $paginaAtual === 'estudo_ativo.php' ? ' ativo-nav' : '' ?>">Estudo Ativo</a>
                <a href="palacio_memoria.php" class="btn-voltar<?= $paginaAtual === 'palacio_memoria.php' ? ' ativo-nav' : '' ?>">Palácio da Memória</a>
                <a href="logout.php" class="btn-voltar">Sair</a>
            <?php else: ?>
                <a href="login.php" class="btn-voltar<?= $paginaAtual === 'login.php' ? ' ativo-nav' : '' ?>">Entrar</a>
                <a href="registo.php" class="btn-voltar<?= $paginaAtual === 'registo.php' ? ' ativo-nav' : '' ?>">Criar Conta</a>
            <?php endif; ?>
        </nav>
    </header>