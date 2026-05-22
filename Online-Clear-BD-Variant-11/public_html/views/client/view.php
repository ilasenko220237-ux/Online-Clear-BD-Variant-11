<?php /** @var array $client */ ?>
<h2>👤 Карточка клиента</h2>
<table>
    <tr><th>ФИО</th><td><?php echo htmlspecialchars($client['last_name'].' '.$client['first_name'].' '.(isset($client['patronymic']) ? $client['patronymic'] : '')); ?></td></tr>
    <tr><th>Телефон</th><td><?php echo htmlspecialchars($client['phone']); ?></td></tr>
    <tr><th>Email</th><td><?php echo htmlspecialchars($client['email']); ?></td></tr>
</table>
<br><a href="?entity=client">← Назад</a>