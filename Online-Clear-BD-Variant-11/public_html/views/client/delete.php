<?php /** @var array $client, string $csrf */ ?>
<h2>🗑️ Удаление клиента</h2>
<div class="flash flash-error">Вы уверены, что хотите удалить <?php echo htmlspecialchars($client['last_name'].' '.$client['first_name']); ?>?</div>
<form method="post">
    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf); ?>">
    <button type="submit" style="background:#dc3545;">Да, удалить</button>
    <a href="?entity=client" style="margin-left: 10px;">Отмена</a>
</form>