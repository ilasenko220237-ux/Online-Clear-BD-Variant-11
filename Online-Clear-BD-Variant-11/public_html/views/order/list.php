<?php /** @var array $orders */ /** @var array $filters */ ?>
<h2>📦 Заказы</h2>
<a href="?entity=order&action=create" class="btn">+ Создать</a>

<form method="get" style="background: #f8f9fa; padding: 10px; border-radius: 4px; margin: 10px 0;">
    <input type="hidden" name="entity" value="order">
    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
        <div><label>С:</label><input type="date" name="date_from" value="<?php echo htmlspecialchars($filters['date_from'] ?? ''); ?>"></div>
        <div><label>По:</label><input type="date" name="date_to" value="<?php echo htmlspecialchars($filters['date_to'] ?? ''); ?>"></div>
        <div><label>Статус:</label>
            <select name="status_id">
                <option value="">Все</option>
                <option value="1" <?php echo ($filters['status_id'] ?? '') == '1' ? 'selected' : ''; ?>>Принято</option>
                <option value="2" <?php echo ($filters['status_id'] ?? '') == '2' ? 'selected' : ''; ?>>В чистке</option>
                <option value="3" <?php echo ($filters['status_id'] ?? '') == '3' ? 'selected' : ''; ?>>Готово</option>
                <option value="4" <?php echo ($filters['status_id'] ?? '') == '4' ? 'selected' : ''; ?>>Выдано</option>
            </select>
        </div>
        <div><label>Клиент:</label><input type="text" name="client_search" placeholder="ФИО..." value="<?php echo htmlspecialchars($filters['client_search'] ?? ''); ?>"></div>
        <div style="align-self: flex-end;"><button type="submit">Фильтр</button> <a href="?entity=order">Сбросить</a></div>
    </div>
</form>

<table>
    <tr><th>ID</th><th>Клиент</th><th>Услуга</th><th>Приём</th><th>Выдача</th><th>Статус</th><th>Действия</th></tr>
    <?php if (!empty($orders)): foreach ($orders as $o): ?>
    <?php
        $bg_color = '#e2e3e5';
        if ($o['status_id'] == 1) $bg_color = '#fff3cd';
        elseif ($o['status_id'] == 2) $bg_color = '#cce5ff';
        elseif ($o['status_id'] == 3) $bg_color = '#d4edda';
        elseif ($o['status_id'] == 4) $bg_color = '#e2e3e5';
        
        $next_status_name = '';
        if ($o['status_id'] == 1) $next_status_name = 'В чистку';
        elseif ($o['status_id'] == 2) $next_status_name = 'Готово';
        elseif ($o['status_id'] == 3) $next_status_name = 'Выдать';
    ?>
    <tr>
        <td><?php echo $o['order_id']; ?></td>
        <td><?php echo htmlspecialchars($o['last_name'].' '.$o['first_name']); ?></td>
        <td><?php echo htmlspecialchars($o['service_name']); ?></td>
        <td><?php echo htmlspecialchars($o['acceptance_datetime']); ?></td>
        <td><?php echo htmlspecialchars($o['planned_delivery_datetime']); ?></td>
        <td><span style="background: <?php echo $bg_color; ?>; padding: 4px 8px; border-radius: 4px;"><?php echo htmlspecialchars($o['status_name']); ?></span></td>
        <td>
            <!-- Кнопка перехода на следующий этап -->
            <?php if ($o['status_id'] < 4): ?>
            <form method="post" action="index.php" style="display:inline;">
                <input type="hidden" name="entity" value="order">
                <input type="hidden" name="action" value="change_status">
                <input type="hidden" name="order_id" value="<?php echo $o['order_id']; ?>">
                <input type="hidden" name="status_id" value="<?php echo $o['status_id'] + 1; ?>">
                <button type="submit" onclick="return confirm('Перевести в статус «<?php echo $next_status_name; ?>»?')" 
                        style="background:#28a745;color:#fff;border:none;padding:5px 10px;border-radius:4px;cursor:pointer;font-size:11px;">
                    <?php echo $next_status_name; ?>
                </button>
            </form>
            <?php endif; ?>

            <!-- ✅ Кнопка удаления (только для статуса 4 "Выдано") -->
            <?php if ($o['status_id'] == 4): ?>
            <form method="post" action="index.php" style="display:inline;" onsubmit="return confirm('Удалить выполненный заказ №<?php echo $o['order_id']; ?>? Данные будут утеряны.');">
                <input type="hidden" name="entity" value="order">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" value="<?php echo $o['order_id']; ?>">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
                <button type="submit" style="background:#dc3545;color:#fff;border:none;padding:5px 10px;border-radius:4px;cursor:pointer;font-size:11px;margin-left:5px;">🗑️ Удалить</button>
            </form>
            <?php endif; ?>

            <a href="?entity=order&action=view&id=<?php echo $o['order_id']; ?>" style="margin-left:5px; text-decoration:none;">👁</a>
        </td>
    </tr>
    <?php endforeach; else: ?>
    <tr><td colspan="7" style="text-align:center;">Нет заказов</td></tr>
    <?php endif; ?>
</table>