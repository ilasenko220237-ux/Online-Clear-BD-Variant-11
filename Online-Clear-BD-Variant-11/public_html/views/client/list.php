<?php /** @var array $clients */ /** @var string $search */ ?>
<h2>👥 Клиенты</h2>
<a href="?entity=client&action=create" class="btn">+ Добавить</a>

<form method="get" style="margin: 10px 0;">
    <input type="hidden" name="entity" value="client">
    <input type="text" name="search" placeholder="Поиск по ФИО или телефону..." value="<?php echo htmlspecialchars($search ?? ''); ?>">
    <button type="submit">Найти</button>
    <a href="?entity=client" style="margin-left: 10px; color: #3498db;">Сбросить</a>
</form>

<table>
    <tr><th>ID</th><th>ФИО</th><th>Телефон</th><th>Email</th><th>Действия</th></tr>
    <?php if (!empty($clients)): foreach ($clients as $c): ?>
    <tr>
        <td><?php echo $c['client_id']; ?></td>
        <td><?php echo htmlspecialchars($c['last_name'].' '.$c['first_name'].' '.($c['patronymic']??'')); ?></td>
        <td><?php echo htmlspecialchars($c['phone']); ?></td>
        <td><?php echo htmlspecialchars($c['email']); ?></td>
        <td>
            <a href="?entity=client&action=view&id=<?php echo $c['client_id']; ?>">👁</a>
            <a href="?entity=client&action=edit&id=<?php echo $c['client_id']; ?>">✏️</a>
            <a href="?entity=client&action=delete&id=<?php echo $c['client_id']; ?>" onclick="return confirm('Удалить клиента?')">🗑️</a>
        </td>
    </tr>
    <?php endforeach; else: ?>
    <tr><td colspan="5" style="text-align:center;">Нет данных</td></tr>
    <?php endif; ?>
</table>