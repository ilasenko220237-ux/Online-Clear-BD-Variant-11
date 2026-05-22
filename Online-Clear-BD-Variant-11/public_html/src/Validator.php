<?php
class Validator {
    public static function generateCsrfToken() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public static function checkCsrfToken($token) {
        if (session_status() === PHP_SESSION_NONE) session_start();
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }

    public static function setFlash($type, $message) {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $_SESSION['flash'] = ['type' => $type, 'message' => $message];
    }

    public static function getFlash() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);
        return $flash;
    }

    public static function validateClient($data) {
        $errors = [];
        if (empty(trim($data['last_name'] ?? ''))) $errors['last_name'] = 'Фамилия обязательна';
        if (empty(trim($data['first_name'] ?? ''))) $errors['first_name'] = 'Имя обязательно';
        if (!preg_match('/^\+?7?\d{10,11}$/', preg_replace('/\D/', '', $data['phone'] ?? ''))) {
            $errors['phone'] = 'Неверный формат телефона';
        }
        if (!filter_var($data['email'] ?? '', FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Некорректный email';
        return $errors;
    }

    public static function validateService($data) {
        $errors = [];
        if (empty(trim($data['service_name'] ?? ''))) $errors['service_name'] = 'Название услуги обязательно';
        if (!isset($data['price']) || $data['price'] <= 0) $errors['price'] = 'Цена должна быть больше 0';
        if (empty($data['type_id'])) $errors['type_id'] = 'Выберите тип изделия';
        if (empty($data['method_id'])) $errors['method_id'] = 'Выберите способ чистки';
        return $errors;
    }

    public static function validateOrder($data) {
        $errors = [];
        if (empty($data['client_id'])) $errors['client_id'] = 'Выберите клиента';
        if (empty($data['service_id'])) $errors['service_id'] = 'Выберите услугу';
        if (empty($data['acceptance_datetime'])) $errors['acceptance_datetime'] = 'Укажите время приёма';
        if (empty($data['planned_delivery_datetime'])) $errors['planned_delivery_datetime'] = 'Укажите время выдачи';
        if (!empty($data['acceptance_datetime']) && !empty($data['planned_delivery_datetime'])) {
            if ($data['planned_delivery_datetime'] < $data['acceptance_datetime']) {
                $errors['planned_delivery_datetime'] = 'Время выдачи не может быть раньше времени приёма';
            }
        }
        return $errors;
    }
}