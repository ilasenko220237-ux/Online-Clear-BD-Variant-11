<?php
require_once __DIR__ . '/../src/ServiceRepository.php';
require_once __DIR__ . '/../src/Validator.php';

class ServiceController extends BaseController {
    private $repo;

    public function __construct($pdo) {
        parent::__construct($pdo);
        $this->repo = new ServiceRepository($pdo);
    }

    public function index() {
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        $services = $this->repo->findAll($search);
        $this->render('service/list', [
            'services' => $services,
            'search' => $search
        ]);
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Validator::checkCsrfToken($_POST['csrf_token'] ?? '')) {
                Validator::setFlash('error', 'Ошибка безопасности (CSRF)');
                $this->redirect('index.php', ['entity' => 'service', 'action' => 'create']);
            }

            $errors = Validator::validateService($_POST);
            if (!empty($errors)) {
                $this->render('service/create', ['errors' => $errors, 'old' => $_POST]);
                return;
            }

            try {
                $this->repo->create($_POST);
                Validator::setFlash('success', 'Услуга добавлена');
                $this->redirect('index.php', ['entity' => 'service']);
            } catch (Exception $e) {
                Validator::setFlash('error', $e->getMessage());
                $this->render('service/create', ['old' => $_POST]);
            }
        } else {
            $this->render('service/create', ['csrf' => Validator::generateCsrfToken()]);
        }
    }

    public function edit($id) {
        $service = $this->repo->findById($id);
        if (!$service) {
            Validator::setFlash('error', 'Услуга не найдена');
            $this->redirect('index.php', ['entity' => 'service']);
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Validator::checkCsrfToken($_POST['csrf_token'] ?? '')) {
                Validator::setFlash('error', 'Ошибка безопасности (CSRF)');
                $this->redirect('index.php', ['entity' => 'service', 'action' => 'edit', 'id' => $id]);
            }

            $errors = Validator::validateService($_POST);
            if (!empty($errors)) {
                $this->render('service/edit', ['errors' => $errors, 'old' => $_POST, 'service' => $service]);
                return;
            }

            try {
                $this->repo->update($id, $_POST);
                Validator::setFlash('success', 'Данные обновлены');
                $this->redirect('index.php', ['entity' => 'service']);
            } catch (Exception $e) {
                Validator::setFlash('error', $e->getMessage());
                $this->render('service/edit', ['old' => $_POST, 'service' => $service]);
            }
        } else {
            $this->render('service/edit', [
                'service' => $service,
                'csrf' => Validator::generateCsrfToken()
            ]);
        }
    }

    public function delete($id) {
        $service = $this->repo->findById($id);
        if (!$service) {
            Validator::setFlash('error', 'Услуга не найдена');
            $this->redirect('index.php', ['entity' => 'service']);
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Validator::checkCsrfToken($_POST['csrf_token'] ?? '')) {
                Validator::setFlash('error', 'Ошибка безопасности');
                $this->redirect('index.php', ['entity' => 'service']);
            }

            if ($this->repo->hasRelatedOrders($id)) {
                Validator::setFlash('error', 'Нельзя удалить: с услугой связаны заказы');
                $this->redirect('index.php', ['entity' => 'service']);
            }

            $this->repo->delete($id);
            Validator::setFlash('success', 'Услуга удалена');
            $this->redirect('index.php', ['entity' => 'service']);
        } else {
            $this->render('service/delete', [
                'service' => $service,
                'csrf' => Validator::generateCsrfToken()
            ]);
        }
    }

    public function view($id) {
        $service = $this->repo->findById($id);
        if (!$service) {
            Validator::setFlash('error', 'Услуга не найдена');
            $this->redirect('index.php', ['entity' => 'service']);
        }
        $this->render('service/view', ['service' => $service]);
    }
}