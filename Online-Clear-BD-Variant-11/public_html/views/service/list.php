<?php /** @var array $services */ /** @var string $search */ ?>
<h2>🧼 Услуги</h2>
<a href="?entity=service&action=create" class="btn">+ Добавить</a>

<form method="get" style="margin: 10px 0;">
    <input type="hidden" name="entity" value="service">
    <input type="text" name="search" placeholder="Поиск по названию..." value="<?php echo htmlspecialchars($search ?? ''); ?>">
    <button type="submit">Найти</button>
    <a href="?entity=service" style="margin-left: 10px; color: #3498db;">Сбросить</a>
</form>

<table>
    <tr><th>ID</th><th>Название</th><th>Тип</th><th>Метод</th><th>Цена</th><th>Действия</th></tr>
    <?php if (!empty($services)): foreach ($services as $s): ?>
    <tr>
        <td><?php echo $s['service_id']; ?></td>
        <td><?php echo htmlspecialchars($s['service_name']); ?></td>
        <td><?php echo htmlspecialchars($s['type_name'] ?? '-'); ?></td>
        <td><?php echo htmlspecialchars($s['method_name'] ?? '-'); ?></td>
        <td><?php echo htmlspecialchars($s['price']); ?> ₽</td>
        <td>
            <a href="?entity=service&action=view&id=<?php echo $s['service_id']; ?>">👁</a>
            <a href="?entity=service&action=edit&id=<?php echo $s['service_id']; ?>">✏️</a>
            <a href="?entity=service&action=delete&id=<?php echo $s['service_id']; ?>" onclick="return confirm('Удалить услугу?')">🗑️</a>
        </td>
    </tr>
    <?php endforeach; else: ?>
    <tr><td colspan="6" style="text-align:center;">Нет данных</td></tr>
    <?php endif; ?>
</table>