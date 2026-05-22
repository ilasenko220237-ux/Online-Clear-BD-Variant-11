<?php
require_once __DIR__ . '/../src/OrderRepository.php';
require_once __DIR__ . '/../src/Validator.php';

class OrderController extends BaseController {
    private $repo;

    public function __construct($pdo) {
        parent::__construct($pdo);
        $this->repo = new OrderRepository($pdo);
    }

    public function index() {
        $filters = [
            'date_from' => isset($_GET['date_from']) ? $_GET['date_from'] : '',
            'date_to' => isset($_GET['date_to']) ? $_GET['date_to'] : '',
            'status_id' => isset($_GET['status_id']) ? $_GET['status_id'] : '',
            'client_search' => isset($_GET['client_search']) ? $_GET['client_search'] : ''
        ];

        $orders = $this->repo->findAllWithFilters($filters);
        $this->render('order/list', [
            'orders' => $orders,
            'filters' => $filters
        ]);
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Validator::checkCsrfToken(isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '')) {
                Validator::setFlash('error', 'Ошибка безопасности (CSRF)');
                $this->redirect('index.php', ['entity' => 'order', 'action' => 'create']);
            }

            $errors = Validator::validateOrder($_POST);
            if (!empty($errors)) {
                $this->render('order/create', ['errors' => $errors, 'old' => $_POST]);
                return;
            }

            try {
                $this->repo->createOrder($_POST);
                Validator::setFlash('success', 'Заказ оформлен');
                $this->redirect('index.php', ['entity' => 'order']);
            } catch (Exception $e) {
                Validator::setFlash('error', $e->getMessage());
                $this->render('order/create', ['old' => $_POST]);
            }
        } else {
            require_once __DIR__ . '/../src/ClientRepository.php';
            require_once __DIR__ . '/../src/ServiceRepository.php';
            
            $clients = (new ClientRepository($this->pdo))->findAll();
            $services = (new ServiceRepository($this->pdo))->findAll();

            $this->render('order/create', [
                'clients' => $clients,
                'services' => $services,
                'csrf' => Validator::generateCsrfToken()
            ]);
        }
    }

    public function view($id) {
        $all = $this->repo->findAllWithFilters([]);
        $order = null;
        foreach ($all as $o) {
            if ($o['order_id'] == $id) {
                $order = $o;
                break;
            }
        }
        
        if (!$order) {
            Validator::setFlash('error', 'Заказ не найден');
            $this->redirect('index.php', ['entity' => 'order']);
        }
        
        $this->render('order/view', ['order' => $order]);
    }

    public function changeStatus() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?entity=order');
            exit;
        }

        $id = isset($_POST['order_id']) ? (int)$_POST['order_id'] : 0;
        $status = isset($_POST['status_id']) ? (int)$_POST['status_id'] : 0;

        if ($id > 0 && $status > 0) {
            try {
                $this->repo->changeStatus($id, $status);
                Validator::setFlash('success', 'Статус изменён');
            } catch (Exception $e) {
                Validator::setFlash('error', $e->getMessage());
            }
        }

        header('Location: index.php?entity=order');
        exit;
    }

    // ✅ Новый метод удаления
    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Validator::checkCsrfToken(isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '')) {
                Validator::setFlash('error', 'Ошибка безопасности');
            } else {
                $this->repo->delete($id);
                Validator::setFlash('success', 'Заказ удалён');
            }
        }
        header('Location: index.php?entity=order');
        exit;
    }
}