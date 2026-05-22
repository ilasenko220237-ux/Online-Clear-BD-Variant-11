<?php /** @var array $errors, $old, string $csrf */ ?>
<h2>➕ Добавить клиента</h2>
<?php if (!empty($errors)): ?>
    <div class="flash flash-error">
        <ul><?php foreach($errors as $e) echo "<li>".htmlspecialchars($e)."</li>"; ?></ul>
    </div>
<?php endif; ?>

<form method="post">
    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf); ?>">
    <label>Фамилия: <input type="text" name="last_name" value="<?php echo isset($old['last_name']) ? htmlspecialchars($old['last_name']) : ''; ?>" required></label>
    <label>Имя: <input type="text" name="first_name" value="<?php echo isset($old['first_name']) ? htmlspecialchars($old['first_name']) : ''; ?>" required></label>
    <label>Отчество: <input type="text" name="patronymic" value="<?php echo isset($old['patronymic']) ? htmlspecialchars($old['patronymic']) : ''; ?>"></label>
    <label>Телефон: <input type="tel" name="phone" value="<?php echo isset($old['phone']) ? htmlspecialchars($old['phone']) : ''; ?>" required></label>
    <label>Email: <input type="email" name="email" value="<?php echo isset($old['email']) ? htmlspecialchars($old['email']) : ''; ?>" required></label>
    <br>
    <button type="submit">Сохранить</button>
    <a href="?entity=client" style="margin-left: 10px;">Отмена</a>
</form>