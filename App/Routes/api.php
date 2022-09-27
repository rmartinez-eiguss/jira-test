<?
use Slim\Routing\RouteCollectorProxy;

$app->group('/api', function (RouteCollectorProxy $app) {
    $app->post('/issue-set-priority-automation', App\Controllers\JiraIssuesPriorityController::class);
});