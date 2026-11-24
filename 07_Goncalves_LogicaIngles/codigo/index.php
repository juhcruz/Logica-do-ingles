<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/db.php';

$erro = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'registar') {
    $email = trim($_POST['email']);
    $pass = $_POST['password'];

    if (!empty($email) && !empty($pass)) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $passwordHash = password_hash($pass, PASSWORD_DEFAULT);

            try {
                // Requisito: Consultas com prepare() e execute() (contra SQL Injection)
                $stmt = $pdo->prepare("INSERT INTO utilizadores (email, password) VALUES (?, ?)");
                $stmt->execute([$email, $passwordHash]);
                $sucesso = "Registo efetuado com sucesso! Pode fazer login.";
            } catch (PDOException $e) {
                // Requisito: Restrição UNIQUE na base de dados (email duplicado)
                if ($e->getCode() == 23000) {
                    $erro = "Este email já se encontra registado.";
                } else {
                    $erro = "Erro ao registar: " . $e->getMessage();
                }
            }
        } else {
            $erro = "Por favor, introduza um email válido.";
        }
    } else {
        $erro = "Preencha todos os campos para o registo.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'login') {
    $email = trim($_POST['email']);
    $pass = $_POST['password'];

    if (!empty($email) && !empty($pass)) {
        $stmt = $pdo->prepare("SELECT * FROM utilizadores WHERE email = ?");
        $stmt->execute([$email]);
        $utilizador = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($utilizador && password_verify($pass, $utilizador['password'])) {
            $_SESSION['user_id'] = $utilizador['id'];
            $_SESSION['user_email'] = $utilizador['email'];

            // Requisito: header('Location: ...'); exit; depois de cada POST
            header('Location: painel.php');
            exit;
        } else {
            $erro = "Credenciais inválidas. Verifique o email e a password.";
        }
    } else {
        $erro = "Preencha todos os campos para iniciar sessão.";
    }
}

include __DIR__ . '/includes/header.php';
?>

<main class="container-principal secao-pagina">
    <?php if (!empty($erro)): ?>
        <div class="alerta-erro"><?php echo htmlspecialchars($erro); ?></div>
    <?php endif; ?>
    <?php if (!empty($sucesso)): ?>
        <div class="alerta-sucesso"><?php echo htmlspecialchars($sucesso); ?></div>
    <?php endif; ?>

    <div class="auth-grelha">
        <div class="cartao-simples">
            <h2 class="secao-titulo">Iniciar Sessão</h2>
            <form action="index.php" method="POST" id="formLogin">
                <input type="hidden" name="acao" value="login">

                <div class="campo-form">
                    <label for="login_email" class="rotulo-campo">Email</label>
                    <input type="email" id="login_email" name="email" required>
                </div>

                <div class="campo-form">
                    <label for="login_password" class="rotulo-campo">Password</label>
                    <input type="password" id="login_password" name="password" required>
                </div>

                <button type="submit" class="btn-azul">Entrar</button>
            </form>
        </div>

        <div class="cartao-simples">
            <h2 class="secao-titulo">Criar Conta</h2>
            <form action="index.php" method="POST" id="formRegisto">
                <input type="hidden" name="acao" value="registar">

                <div class="campo-form">
                    <label for="reg_email" class="rotulo-campo">Email</label>
                    <input type="email" id="reg_email" name="email" required>
                </div>

                <div class="campo-form">
                    <label for="reg_password" class="rotulo-campo">Password</label>
                    <input type="password" id="reg_password" name="password" required>
                </div>

                <label for="termos" class="checkbox-termos">
                    <input type="checkbox" id="termos" name="termos" required>
                    <span>Aceito a política de privacidade e termos de uso.</span>
                </label>

                <button type="submit" class="btn-azul">Registar</button>
            </form>
        </div>
    </div>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>