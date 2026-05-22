<?php /** @var array $errors, $old, $clients, $services, string $csrf */ ?>
<h2>➕ Новый заказ</h2>
<?php if (!empty($errors)): ?>
    <div class="flash flash-error">
        <ul><?php foreach($errors as $e) echo "<li>".htmlspecialchars($e)."</li>"; ?></ul>
    </div>
<?php endif; ?>

<form method="post">
    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf); ?>">

    <label>Клиент:
        <select name="client_id" required>
            <option value="">-- Выберите --</option>
            <?php foreach($clients as $c): ?>
            <option value="<?php echo $c['client_id']; ?>" <?php echo isset($old['client_id']) && $old['client_id'] == $c['client_id'] ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($c['last_name'].' '.$c['first_name']); ?>
            </option>
            <?php endforeach; ?>
        </select>
    </label>

    <label>Услуга:
        <select name="service_id" required>
            <option value="">-- Выберите --</option>
            <?php foreach($services as $s): ?>
            <option value="<?php echo $s['service_id']; ?>" <?php echo isset($old['service_id']) && $old['service_id'] == $s['service_id'] ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($s['service_name'].' ('.$s['price'].'₽)'); ?>
            </option>
            <?php endforeach; ?>
        </select>
    </label>

    <label>Время приёма: <input type="datetime-local" name="acceptance_datetime" value="<?php echo isset($old['acceptance_datetime']) ? htmlspecialchars($old['acceptance_datetime']) : ''; ?>" required></label>
    <label>Время выдачи: <input type="datetime-local" name="planned_delivery_datetime" value="<?php echo isset($old['planned_delivery_datetime']) ? htmlspecialchars($old['planned_delivery_datetime']) : ''; ?>" required></label>
    <label>Описание вещи: <textarea name="item_description"><?php echo isset($old['item_description']) ? htmlspecialchars($old['item_description']) : ''; ?></textarea></label>

    <br>
    <button type="submit">Создать заказ</button>
    <a href="?entity=order" style="margin-left: 10px;">Отмена</a>
</form>