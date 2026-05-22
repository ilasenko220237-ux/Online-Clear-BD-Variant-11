<?php
require_once __DIR__ . '/../src/ClientRepository.php';
require_once __DIR__ . '/../src/Validator.php';

class ClientController extends BaseController {
    private $repo;

    public function __construct($pdo) {
        parent::__construct($pdo);
        $this->repo = new ClientRepository($pdo);
    }

    public function index() {
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        $clients = $this->repo->findAll($search);
        $this->render('client/list', [
            'clients' => $clients,
            'search' => $search
        ]);
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Validator::checkCsrfToken($_POST['csrf_token'] ?? '')) {
                Validator::setFlash('error', 'Ошибка безопасности (CSRF)');
                $this->redirect('index.php', ['entity' => 'client', 'action' => 'create']);
            }

            $errors = Validator::validateClient($_POST);
            if (!empty($errors)) {
                $this->render('client/create', ['errors' => $errors, 'old' => $_POST]);
                return;
            }

            try {
                $this->repo->create($_POST);
                Validator::setFlash('success', 'Клиент добавлен');
                $this->redirect('index.php', ['entity' => 'client']);
            } catch (Exception $e) {
                Validator::setFlash('error', $e->getMessage());
                $this->render('client/create', ['old' => $_POST]);
            }
        } else {
            $this->render('client/create', ['csrf' => Validator::generateCsrfToken()]);
        }
    }

    public function edit($id) {
        $client = $this->repo->findById($id);
        if (!$client) {
            Validator::setFlash('error', 'Клиент не найден');
            $this->redirect('index.php', ['entity' => 'client']);
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Validator::checkCsrfToken($_POST['csrf_token'] ?? '')) {
                Validator::setFlash('error', 'Ошибка безопасности (CSRF)');
                $this->redirect('index.php', ['entity' => 'client', 'action' => 'edit', 'id' => $id]);
            }

            $errors = Validator::validateClient($_POST);
            if (!empty($errors)) {
                $this->render('client/edit', ['errors' => $errors, 'old' => $_POST, 'client' => $client]);
                return;
            }

            try {
                $this->repo->update($id, $_POST);
                Validator::setFlash('success', 'Данные обновлены');
                $this->redirect('index.php', ['entity' => 'client']);
            } catch (Exception $e) {
                Validator::setFlash('error', $e->getMessage());
                $this->render('client/edit', ['old' => $_POST, 'client' => $client]);
            }
        } else {
            $this->render('client/edit', [
                'client' => $client,
                'csrf' => Validator::generateCsrfToken()
            ]);
        }
    }

    public function delete($id) {
        $client = $this->repo->findById($id);
        if (!$client) {
            Validator::setFlash('error', 'Клиент не найден');
            $this->redirect('index.php', ['entity' => 'client']);
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Validator::checkCsrfToken($_POST['csrf_token'] ?? '')) {
                Validator::setFlash('error', 'Ошибка безопасности');
                $this->redirect('index.php', ['entity' => 'client']);
            }

            if ($this->repo->hasRelatedOrders($id)) {
                Validator::setFlash('error', 'Нельзя удалить: у клиента есть заказы');
                $this->redirect('index.php', ['entity' => 'client']);
            }

            $this->repo->delete($id);
            Validator::setFlash('success', 'Клиент удалён');
            $this->redirect('index.php', ['entity' => 'client']);
        } else {
            $this->render('client/delete', [
                'client' => $client,
                'csrf' => Validator::generateCsrfToken()
            ]);
        }
    }

    public function view($id) {
        $client = $this->repo->findById($id);
        if (!$client) {
            Validator::setFlash('error', 'Клиент не найден');
            $this->redirect('index.php', ['entity' => 'client']);
        }
        $this->render('client/view', ['client' => $client]);
    }
}