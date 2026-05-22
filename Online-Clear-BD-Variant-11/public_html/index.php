<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/src/Database.php';
$pdo = Database::getConnection();

// Читаем из POST или GET
$entity = isset($_POST['entity']) ? $_POST['entity'] : (isset($_GET['entity']) ? $_GET['entity'] : 'client');
$action = isset($_POST['action']) ? $_POST['action'] : (isset($_GET['action']) ? $_GET['action'] : 'index');
$id     = isset($_GET['id']) ? $_GET['id'] : (isset($_POST['id']) ? $_POST['id'] : null);

$controllers = [
    'client'   => 'ClientController',
    'service'  => 'ServiceController',
    'order'    => 'OrderController'
];

if (!isset($controllers[$entity])) {
    die("Сущность '$entity' не найдена.");
}

require_once __DIR__ . '/controllers/BaseController.php';
require_once __DIR__ . '/controllers/' . $controllers[$entity] . '.php';

$ctrlClass = $controllers[$entity];
$controller = new $ctrlClass($pdo);

switch ($action) {
    case 'index':
    case 'list':
        $controller->index(); break;
    case 'create':
        $controller->create(); break;
    case 'edit':
        $controller->edit($id); break;
    case 'delete':
        $controller->delete($id); break;
    case 'view':
        $controller->view($id); break;
    case 'change_status':
        $controller->changeStatus(); break;
    default:
        $controller->index();
}