<?php /** @var array $errors, $old, string $csrf */ ?>
<h2> Добавить услугу</h2>
<?php if (!empty($errors)): ?>
    <div class="flash flash-error">
        <ul><?php foreach($errors as $e) echo "<li>".htmlspecialchars($e)."</li>"; ?></ul>
    </div>
<?php endif; ?>

<form method="post">
    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf); ?>">
    <label>Название: <input type="text" name="service_name" value="<?php echo isset($old['service_name']) ? htmlspecialchars($old['service_name']) : ''; ?>" required></label>
    <label>Цена: <input type="number" step="0.01" name="price" value="<?php echo isset($old['price']) ? htmlspecialchars($old['price']) : ''; ?>" required></label>
    <label>Тип изделия: <input type="text" name="type_id" placeholder="ID типа" value="<?php echo isset($old['type_id']) ? htmlspecialchars($old['type_id']) : ''; ?>"></label>
    <label>Метод: <input type="text" name="method_id" placeholder="ID метода" value="<?php echo isset($old['method_id']) ? htmlspecialchars($old['method_id']) : ''; ?>"></label>
    <br>
    <button type="submit">Сохранить</button>
    <a href="?entity=service" style="margin-left: 10px;">Отмена</a>
</form>