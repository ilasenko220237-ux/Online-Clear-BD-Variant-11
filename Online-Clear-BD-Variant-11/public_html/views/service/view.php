<?php /** @var array $service */ ?>
<h2>🧼 Услуга</h2>
<table>
    <tr><th>Название</th><td><?php echo htmlspecialchars($service['service_name']); ?></td></tr>
    <tr><th>Цена</th><td><?php echo htmlspecialchars($service['price']); ?> ₽</td></tr>
    <tr><th>Тип</th><td><?php echo htmlspecialchars(isset($service['type_name']) ? $service['type_name'] : '-'); ?></td></tr>
    <tr><th>Метод</th><td><?php echo htmlspecialchars(isset($service['method_name']) ? $service['method_name'] : '-'); ?></td></tr>
</table>
<br><a href="?entity=service">← Назад</a>