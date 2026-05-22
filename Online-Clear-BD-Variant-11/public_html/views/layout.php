<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Химчистка онлайн</title>
    <style>
        body { font-family: sans-serif; margin: 20px; background: #f4f6f8; }
        nav { margin-bottom: 20px; background: #2c3e50; padding: 10px; border-radius: 4px; }
        nav a { color: #fff; margin-right: 15px; text-decoration: none; font-weight: bold; }
        nav a:hover { text-decoration: underline; }
        .container { background: #fff; padding: 20px; border-radius: 5px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #ecf0f1; }
        .btn { display: inline-block; padding: 8px 12px; background: #3498db; color: #fff; text-decoration: none; border-radius: 4px; margin-bottom: 10px; }
        .flash { padding: 10px; margin: 10px 0; border-radius: 4px; border: 1px solid transparent; }
        .flash-success { background: #d4edda; color: #155724; border-color: #c3e6cb; }
        .flash-error { background: #f8d7da; color: #721c24; border-color: #f5c6cb; }
        form { margin: 15px 0; }
        label { display: block; margin: 5px 0 2px; font-weight: bold; }
        input, select, textarea { width: 100%; max-width: 400px; padding: 8px; box-sizing: border-box; }
    </style>
</head>
<body>
    <nav>
        <a href="?entity=client">Клиенты</a>
        <a href="?entity=service">Услуги</a>
        <a href="?entity=order">Заказы</a>
    </nav>
    <div class="container">
        <?php if (!empty($_SESSION['flash'])): ?>
            <div class="flash flash-<?php echo htmlspecialchars($_SESSION['flash']['type']); ?>">
                <?php echo htmlspecialchars($_SESSION['flash']['message']); ?>
            </div>
            <?php unset($_SESSION['flash']); ?>
        <?php endif; ?>
        <?php if (isset($__content_file) && file_exists($__content_file)): ?>
            <?php include $__content_file; ?>
        <?php endif; ?>
    </div>
</body>
</html>