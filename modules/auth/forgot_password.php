<?php
// password reset via security question verification
$pageTitle = 'Forgot Password';
require_once __DIR__ . '/../../includes/bootstrap.php';
if (isLoggedIn()) redirect('/index.php');

$db = Database::getInstance();
$step = $_SESSION['recovery_step'] ?? 'email';
$errors = [];

if ($step === 'email' && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['find_email'])) {
    if (!verifyCSRF()) $errors[] = 'Invalid token.';
    else {
        $email = trim($_POST['email'] ?? '');
        if (!validateEmail($email)) $errors[] = 'Invalid email.';
        else {
            $s = $db->prepare("SELECT id,security_question,role FROM users WHERE email=:e AND is_active=1");
            $s->execute([':e'=>$email]);
            $user = $s->fetch();
            if (!$user) $errors[] = 'No account found.';
            else {
                $_SESSION['recovery_step'] = 'verify';
                $_SESSION['recovery_user'] = $user;
                $_SESSION['recovery_email'] = $email;
                $step = 'verify';
            }
        }
    }
}

if ($step === 'verify' && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['verify_answer'])) {
    if (!verifyCSRF()) $errors[] = 'Invalid token.';
    else {
        $user = $_SESSION['recovery_user'] ?? null;
        $answer = trim($_POST['security_answer'] ?? '');
        if (!$user) { $errors[] = 'Session expired.'; $step = 'email'; }
        else {
            $s = $db->prepare("SELECT security_answer_hash FROM users WHERE id=:id");
            $s->execute([':id'=>$user['id']]);
            $row = $s->fetch();
            if ($row && password_verify(strtolower($answer), $row['security_answer_hash'])) {
                $_SESSION['recovery_step'] = 'reset';
                $step = 'reset';
            } else {
                $errors[] = 'Incorrect answer.';
            }
        }
    }
}

if ($step === 'reset' && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset_password'])) {
    if (!verifyCSRF()) $errors[] = 'Invalid token.';
    else {
        $user = $_SESSION['recovery_user'] ?? null;
        $newPw = $_POST['new_password'] ?? '';
        $confirmPw = $_POST['confirm_password'] ?? '';
        if (!$user) { $errors[] = 'Session expired.'; }
        elseif (strlen($newPw) < 6) $errors[] = 'Min 6 characters.';
        elseif ($newPw !== $confirmPw) $errors[] = 'Passwords do not match.';
        else {
            if ($user['role'] === 'admin') {
                $secKey = trim($_POST['security_key'] ?? '');
                if ($secKey !== ADMIN_SECURITY_KEY) { $errors[] = 'Invalid admin security key.'; }
                else { $hash = password_hash($secKey.':'.$newPw, PASSWORD_BCRYPT, ['cost'=>12]); }
            } else {
                $hash = password_hash($newPw, PASSWORD_BCRYPT, ['cost'=>12]);
            }
            if (empty($errors)) {
                $db->prepare("UPDATE users SET password_hash=:p WHERE id=:id")->execute([':p'=>$hash,':id'=>$user['id']]);
                logAction('password_reset', "User #{$user['id']}");
                unset($_SESSION['recovery_step'],$_SESSION['recovery_user'],$_SESSION['recovery_email']);
                setFlash('success', 'Password reset! Please login.');
                redirect('/modules/auth/login.php');
            }
        }
    }
}

require_once APP_ROOT . '/includes/header.php';
?>

<div class="auth-container">
    <div class="card auth-card shadow">
        <div class="card-header"><h4 class="mb-0"><i class="fas fa-key me-2"></i>Password Recovery</h4></div>
        <div class="card-body p-4">
            <?php if ($errors): ?>
            <div class="alert alert-danger"><ul class="mb-0"><?php foreach($errors as $e):?><li><?=e($e)?></li><?php endforeach;?></ul></div>
            <?php endif; ?>

            <div class="d-flex justify-content-center mb-4">
                <span class="badge <?=$step==='email'?'bg-danger':'bg-secondary'?> me-1 px-3 py-2">1. Email</span>
                <span class="badge <?=$step==='verify'?'bg-danger':'bg-secondary'?> me-1 px-3 py-2">2. Verify</span>
                <span class="badge <?=$step==='reset'?'bg-danger':'bg-secondary'?> px-3 py-2">3. Reset</span>
            </div>

            <?php if ($step === 'email'): ?>
            <form method="POST">
                <?= csrfField() ?>
                <div class="mb-3">
                    <label class="form-label">Your registered email</label>
                    <input type="email" class="form-control" name="email" required autofocus>
                </div>
                <button type="submit" name="find_email" class="btn btn-blood w-100 py-2">Find Account</button>
            </form>

            <?php elseif ($step === 'verify'): ?>
            <?php $ru = $_SESSION['recovery_user'] ?? []; ?>
            <form method="POST">
                <?= csrfField() ?>
                <div class="mb-3">
                    <label class="form-label fw-bold"><?= e($ru['security_question'] ?? '') ?></label>
                    <input type="text" class="form-control" name="security_answer" required autofocus>
                </div>
                <button type="submit" name="verify_answer" class="btn btn-blood w-100 py-2">Verify</button>
            </form>

            <?php elseif ($step === 'reset'): ?>
            <?php $ru = $_SESSION['recovery_user'] ?? []; ?>
            <form method="POST">
                <?= csrfField() ?>
                <?php if (($ru['role']??'') === 'admin'): ?>
                <div class="mb-3">
                    <label class="form-label fw-bold">Admin Security Key</label>
                    <input type="password" class="form-control" name="security_key" required>
                </div>
                <?php endif; ?>
                <div class="mb-3"><label class="form-label">New Password</label><input type="password" class="form-control" name="new_password" required minlength="6"></div>
                <div class="mb-3"><label class="form-label">Confirm Password</label><input type="password" class="form-control" name="confirm_password" required></div>
                <button type="submit" name="reset_password" class="btn btn-blood w-100 py-2">Reset Password</button>
            </form>
            <?php endif; ?>

            <div class="text-center mt-3">
                <a href="login.php" class="auth-link"><i class="fas fa-arrow-left me-1"></i>Back to Login</a>
                <?php if ($step !== 'email'): ?> <span class="text-muted mx-1">·</span> <a href="forgot_password.php?reset=1" class="auth-link">Start Over</a><?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php
if (isset($_GET['reset'])) { unset($_SESSION['recovery_step'],$_SESSION['recovery_user'],$_SESSION['recovery_email']); redirect('/modules/auth/forgot_password.php'); }
require_once APP_ROOT . '/includes/footer.php';
?>
