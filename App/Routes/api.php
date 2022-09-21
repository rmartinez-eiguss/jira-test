<?
use Slim\Routing\RouteCollectorProxy;

$app->group('/api', function (RouteCollectorProxy $app) {
    $app->post('/issue-priority-automation', JiraIssuesPriorityAutomationController::class);
});