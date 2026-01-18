<?php
require __DIR__ . '/../vendor/autoload.php';

use Jenssegers\Blade\Blade;

$views = __DIR__ . '/../app/views';
$cache = __DIR__ . '/../cache/views';

$blade = new Blade($views, $cache);

require_once __DIR__ . '/../app/controllers/StudentController.php';

$controller = new StudentController($blade);

$page = $_GET['page'] ?? 'index';

if ($page === 'create') {
    $controller->create();
} elseif ($page === 'store') {
    $controller->store();
} elseif ($page === 'edit') {
    $controller->edit($_GET['id']);
} elseif ($page === 'update') {
    $controller->update($_GET['id']);
} elseif ($page === 'delete') {
    $controller->delete($_GET['id']);
} else {
    $controller->index();
}
?>