<?
use Slim\Routing\RouteCollectorProxy;

$app->group('/api', function (RouteCollectorProxy $app) {
    $app->post('/issue-priority-automation', InstanceController::class.":cloneInstance")->setName('cloneInstance');
});