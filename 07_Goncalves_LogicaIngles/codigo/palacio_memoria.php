<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>A Lógica do Inglês - Palácio da Memória</title>
    <link rel="icon" type="image/svg+xml" href="assets/img/logo.svg">
    <style>
        :root {
            --azul-projeto: #3b5bfd;
            --azul-hover: #2945e8;
            --roxo-destaque: #9333ea;
            --magenta-destaque: #c026d3;
            --gradiente-primario: linear-gradient(135deg, #2563eb 0%, #6d28d9 55%, #c026d3 100%);
            --gradiente-suave: linear-gradient(135deg, rgba(56, 189, 248, 0.14) 0%, rgba(168, 85, 247, 0.14) 100%);
            --cor-telha: #c25929;
            --fundo-telhado: #fffaf7;
            --fundo-pagina: #f5f7ff;
            --fundo-cartao: #ffffff;
            --texto-principal: #1e293b;
            --texto-secundario: #64748b;
            --borda-suave: #e2e8f0;
            --borda-gradiente: #c4b5fd;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }

        body {
            background:
                radial-gradient(circle at top left, rgba(56, 189, 248, 0.14), transparent 45%),
                radial-gradient(circle at bottom right, rgba(192, 38, 211, 0.12), transparent 45%),
                var(--fundo-pagina);
            color: var(--texto-principal);
            padding: 24px 15px;
            line-height: 1.45;
            min-height: 100vh;
        }

        /* Cabeçalho com título à esquerda e navegação à direita */
        header.topo-navegacao {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            max-width: 960px;
            margin: 0 auto 25px auto;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--borda-suave);
        }

        .marca-app {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            flex-shrink: 0;
        }

        .marca-app:hover {
            text-decoration: none;
        }

        .logo-app {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: block;
            flex-shrink: 0;
        }

        .titulo-app {
            text-align: left;
            text-decoration: none;
            background: var(--gradiente-primario);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            font-size: 1.7rem;
            font-weight: 700;
            letter-spacing: -0.02em;
            margin: 0;
            white-space: nowrap;
        }

        .topo-nav {
            display: flex;
            gap: 8px;
            align-items: center;
            flex-wrap: nowrap;
        }

        .btn-voltar {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background-color: #f8fafc;
            color: var(--texto-principal);
            text-decoration: none;
            padding: 8px 14px;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 500;
            border: 1px solid var(--borda-suave);
            cursor: pointer;
            transition: background-color 0.2s, border-color 0.2s, color 0.2s;
            white-space: nowrap;
        }

        .btn-voltar:hover {
            background: var(--gradiente-suave);
            border-color: var(--borda-gradiente);
            color: var(--roxo-destaque);
        }

        /* Contentor Principal */
        .container-principal {
            width: 100%;
            max-width: 960px;
            margin: 0 auto;
            background: var(--fundo-cartao);
            border: 1px solid var(--borda-suave);
            border-radius: 16px;
            padding: 32px;
            box-shadow: 0 10px 30px rgba(79, 70, 229, 0.06);
        }

        /* Estrutura da Casa (Palácio da Memória) */
        .palacio-casa {
            max-width: 820px;
            margin: 0 auto 28px auto;
        }

        /* Telhado Minimalista com SVG para borda nítida */
        .telhado-wrapper {
            position: relative;
            width: 100%;
            padding: 58px 20px 26px 20px;
            margin-bottom: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .telhado-svg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
            pointer-events: none;
        }

        .telhado-conteudo {
            position: relative;
            z-index: 2;
            text-align: center;
            max-width: 420px;
            width: 100%;
        }

        .badge-numero {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            font-size: 0.8rem;
            font-weight: 700;
            margin-right: 6px;
        }

        .badge-telhado {
            background-color: #ffedd5;
            color: var(--cor-telha);
        }

        .telhado-titulo {
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--texto-principal);
            margin-bottom: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .telhado-subtitulo {
            font-size: 0.85rem;
            color: var(--texto-secundario);
            margin-bottom: 14px;
        }

        /* Corpo da Casa - Grelha 2x2 */
        .corpo-casa {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            max-width: 740px;
            margin: 0 auto;
        }

        .quarto-card {
            background: var(--fundo-cartao);
            border: 1px solid var(--borda-suave);
            border-radius: 12px;
            padding: 22px;
            box-shadow: 0 4px 14px rgba(109, 40, 217, 0.04);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: transform 0.2s, box-shadow 0.2s, border-color 0.2s;
        }

        .quarto-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(109, 40, 217, 0.09);
            border-color: var(--borda-gradiente);
        }

        /* Bordas superiores coloridas para distinção espacial */
        .borda-tempo { border-top: 4px solid #f59e0b; }
        .borda-ligacoes { border-top: 4px solid #10b981; }
        .borda-verbos { border-top: 4px solid #3b82f6; }
        .borda-estruturas { border-top: 4px solid #9333ea; }

        .quarto-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .quarto-titulo {
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--texto-principal);
            display: flex;
            align-items: center;
        }

        .quarto-local {
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--texto-principal);
            margin-bottom: 4px;
        }

        .quarto-desc {
            font-size: 0.85rem;
            color: var(--texto-secundario);
            margin-bottom: 18px;
        }

        /* Botões com Efeito Gradiente */
        .btn-azul {
            display: block;
            width: 100%;
            background: var(--gradiente-primario);
            background-size: 160% 160%;
            background-position: 0% 50%;
            color: #ffffff;
            text-align: center;
            text-decoration: none;
            padding: 10px 16px;
            border-radius: 8px;
            font-size: 0.92rem;
            font-weight: 600;
            transition: background-position 0.35s ease, transform 0.15s ease, box-shadow 0.2s ease;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(109, 40, 217, 0.2);
        }

        .btn-azul:hover {
            background-position: 100% 50%;
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(147, 51, 234, 0.28);
        }

        .btn-telhado {
            max-width: 240px;
            margin: 0 auto;
            padding: 9px 16px;
        }

        /* Barra Inferior de Progresso e Desafio */
        .rodape-desafio {
            background: var(--fundo-cartao);
            border: 1px solid var(--borda-suave);
            border-radius: 12px;
            padding: 18px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            max-width: 740px;
            margin: 0 auto;
            box-shadow: 0 4px 14px rgba(109, 40, 217, 0.04);
        }

        .info-bloco {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .info-bloco h4 {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--texto-principal);
            margin-bottom: 2px;
        }

        .info-bloco p {
            font-size: 0.85rem;
            color: var(--texto-secundario);
            margin-bottom: 0;
        }

        .btn-estudo {
            width: auto;
            padding: 10px 24px;
        }

        /* Responsividade */
        @media (max-width: 820px) {
            header.topo-navegacao {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }

            .topo-nav {
                flex-wrap: wrap;
            }
        }

        @media (max-width: 680px) {
            .container-principal {
                padding: 20px 16px;
            }
            .corpo-casa {
                grid-template-columns: 1fr;
            }
            .telhado-wrapper {
                padding: 25px 20px;
                border: 2px solid var(--cor-telha);
                border-radius: 12px;
                background-color: var(--fundo-telhado);
            }
            .telhado-svg {
                display: none;
            }
        }
    </style>
</head>
<body>

<header class="topo-navegacao">
    <a href="index.php" class="marca-app" aria-label="Lógica do Inglês - Início">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="logo-app" role="img" aria-label="Logótipo Lógica do Inglês">
            <defs>
                <linearGradient id="bg-gradiente-palacio" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" stop-color="#38bdf8" />
                    <stop offset="45%" stop-color="#3b5bfd" />
                    <stop offset="100%" stop-color="#9333ea" />
                </linearGradient>
                <linearGradient id="brilho-topo-palacio" x1="0%" y1="0%" x2="0%" y2="100%">
                    <stop offset="0%" stop-color="#ffffff" stop-opacity="0.16" />
                    <stop offset="100%" stop-color="#ffffff" stop-opacity="0" />
                </linearGradient>
                <filter id="sombra-limpa-palacio" x="-15%" y="-15%" width="130%" height="135%">
                    <feDropShadow dx="0" dy="8" stdDeviation="10" flood-color="#1e1b4b" flood-opacity="0.18" />
                </filter>
            </defs>
            <rect x="24" y="24" width="464" height="464" rx="112" ry="112" fill="url(#bg-gradiente-palacio)" />
            <rect x="24" y="24" width="464" height="464" rx="112" ry="112" fill="url(#brilho-topo-palacio)" />
            <g filter="url(#sombra-limpa-palacio)">
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
        <a href="painel.php" class="btn-voltar">← Voltar ao Painel</a>
    </nav>
</header>

<div class="container-principal palacio-casa">
    <!-- Estrutura Visual da Casa -->
    <div class="palacio-casa">

        <!-- Telhado Minimalista: Modificadores -->
        <div class="telhado-wrapper">
            <svg class="telhado-svg" viewBox="0 0 800 185" preserveAspectRatio="none">
                <polygon points="400,6 792,179 8,179"
                         fill="#fffaf7"
                         stroke="#c25929"
                         stroke-width="2.5"
                         stroke-linejoin="round" />
            </svg>
            <div class="telhado-conteudo">
                <div class="telhado-titulo">
                    <span class="badge-numero badge-telhado">5</span> Modificadores <span>🔍</span>
                </div>
                <p class="telhado-subtitulo">Modificação e Grau</p>
                <a href="modulo.php?id=5" class="btn-azul btn-telhado">Explorar Divisão</a>
            </div>
        </div>

        <div class="corpo-casa">

            <div class="quarto-card borda-tempo">
                <div>
                    <div class="quarto-header">
                        <span class="quarto-titulo">
                            <span class="badge-numero" style="background:#fef3c7; color:#b45309;">2</span> Tempo
                        </span>
                        <span>⏰</span>
                    </div>
                    <div class="quarto-local">Sala do Tempo</div>
                    <p class="quarto-desc">Tempo e Aspeto</p>
                </div>
                <a href="modulo.php?id=2" class="btn-azul">Explorar Quarto</a>
            </div>

            <!-- Andar Superior Direito: Ligações -->
            <div class="quarto-card borda-ligacoes">
                <div>
                    <div class="quarto-header">
                        <span class="quarto-titulo">
                            <span class="badge-numero" style="background:#d1fae5; color:#047857;">4</span> Ligações
                        </span>
                        <span>🔗</span>
                    </div>
                    <div class="quarto-local">Corredores</div>
                    <p class="quarto-desc">Orações Complexas e Conjunções</p>
                </div>
                <a href="modulo.php?id=4" class="btn-azul">Explorar Quarto</a>
            </div>

            <!-- Andar Inferior Esquerdo: Verbos -->
            <div class="quarto-card borda-verbos">
                <div>
                    <div class="quarto-header">
                        <span class="quarto-titulo">
                            <span class="badge-numero" style="background:#dbeafe; color:#1d4ed8;">1</span> Verbos
                        </span>
                        <span>🚪</span>
                    </div>
                    <div class="quarto-local">Quarto dos Verbos</div>
                    <p class="quarto-desc">Padrões Verbais e Valência</p>
                </div>
                <a href="estudo_ativo.php?id=1" class="btn-azul">Entrar no Palácio</a>
            </div>

            <!-- Andar Inferior Direito: Estruturas -->
            <div class="quarto-card borda-estruturas">
                <div>
                    <div class="quarto-header">
                        <span class="quarto-titulo">
                            <span class="badge-numero" style="background:#ede9fe; color:#6d28d9;">3</span> Estruturas
                        </span>
                        <span>🏛️</span>
                    </div>
                    <div class="quarto-local">Biblioteca / Hall</div>
                    <p class="quarto-desc">Sintaxe</p>
                </div>
                <a href="modulo.php?id=3" class="btn-azul">Explorar Cozinha</a>
            </div>

        </div>
    </div>

    <!-- Rodapé de Progresso e Desafio -->
    <div class="rodape-desafio">
        <div class="info-bloco">
            <span style="font-size: 1.5rem;">📊</span>
            <div>
                <h4>Análise</h4>
                <p>0 / 5 divisões exploradas</p>
            </div>
        </div>

        <div class="info-bloco">
            <span style="font-size: 1.5rem;">🏆</span>
            <div>
                <h4>Desafio de Memória</h4>
                <p>Recorda os cinco pilares da gramática inglesa!</p>
            </div>
        </div>

        <a href="desafio.php" class="btn-azul btn-estudo">Iniciar Estudo</a>
    </div>
</div>

</body>
</html>