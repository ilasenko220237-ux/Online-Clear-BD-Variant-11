<?php /** @var array $order */ ?>
<h2>📄 Заказ #<?php echo $order['order_id']; ?></h2>
<table>
    <tr><th>Клиент</th><td><?php echo htmlspecialchars($order['last_name'].' '.$order['first_name']); ?></td></tr>
    <tr><th>Услуга</th><td><?php echo htmlspecialchars($order['service_name']); ?></td></tr>
    <tr><th>Приём</th><td><?php echo htmlspecialchars($order['acceptance_datetime']); ?></td></tr>
    <tr><th>Выдача</th><td><?php echo htmlspecialchars($order['planned_delivery_datetime']); ?></td></tr>
    <tr><th>Статус</th><td><?php echo htmlspecialchars($order['status_name']); ?></td></tr>
    <tr><th>Описание</th><td><?php echo htmlspecialchars(isset($order['item_description']) ? $order['item_description'] : '-'); ?></td></tr>
</table>
<br><a href="?entity=order">← Назад</a>