<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?= esc($appName ?? 'Sistem Monitoring Walet') ?></title>
    <link rel="stylesheet" href="/assets/css/vendor/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/vendor/all.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        body {
            background: linear-gradient(135deg, #1a2942 0%, #2c3e50 50%, #1e6091 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: rgba(255,255,255,0.98);
            border-radius: 16px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.3);
            max-width: 420px;
            width: 100%;
            padding: 40px;
        }
        .login-logo {
            text-align: center;
            margin-bottom: 30px;
        }
        .login-logo i {
            font-size: 56px;
            color: #1e6091;
        }
        .login-logo h2 {
            color: #1a2942;
            font-weight: 700;
            margin-top: 10px;
            font-size: 22px;
        }
        .login-logo small {
            color: #6c757d;
        }
        .form-control {
            border-radius: 8px;
            padding: 12px 15px;
            border: 2px solid #e9ecef;
            transition: all 0.2s;
        }
        .form-control:focus {
            border-color: #1e6091;
            box-shadow: 0 0 0 0.2rem rgba(30, 96, 145, 0.15);
        }
        .btn-login {
            background: linear-gradient(135deg, #1e6091, #2980b9);
            color: white;
            font-weight: 600;
            padding: 12px;
            border-radius: 8px;
            border: none;
            transition: transform 0.1s;
        }
        .btn-login:hover {
            transform: translateY(-1px);
            color: white;
        }
        .alert {
            border-radius: 8px;
            font-size: 14px;
        }
        .default-creds {
            background: #f8f9fa;
            border: 1px dashed #dee2e6;
            border-radius: 8px;
            padding: 12px;
            font-size: 12px;
            color: #6c757d;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-logo">
            <i class="fas fa-feather-alt"></i>
            <h2>Sistem Monitoring Walet</h2>
            <small>Smart Bird's Nest Production Monitoring</small>
        </div>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger" role="alert">
                <i class="fas fa-exclamation-circle"></i> <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success" role="alert">
                <i class="fas fa-check-circle"></i> <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('warning')): ?>
            <div class="alert alert-warning" role="alert">
                <i class="fas fa-exclamation-triangle"></i> <?= session()->getFlashdata('warning') ?>
            </div>
        <?php endif; ?>

        <form method="post" action="/login/auth">
            <?= csrf_field() ?>
            <div class="form-group">
                <label><i class="fas fa-user"></i> Username</label>
                <input type="text" name="username" class="form-control" value="<?= old('username') ?>" required autofocus>
            </div>
            <div class="form-group">
                <label><i class="fas fa-lock"></i> Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-login btn-block">
                <i class="fas fa-sign-in-alt"></i> Login
            </button>
        </form>

        <div class="default-creds">
            <strong><i class="fas fa-info-circle"></i> Akun Default:</strong><br>
            Admin: <code>admin / admin123</code><br>
            Petugas: <code>petugas / petugas123</code><br>
            Owner: <code>owner / owner123</code><br>
            <small class="text-danger"><i class="fas fa-exclamation-triangle"></i> Wajib ganti password setelah login pertama.</small>
        </div>
    </div>
</body>
</html>
