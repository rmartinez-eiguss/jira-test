<?
use Slim\Routing\RouteCollectorProxy;

$app->group('/api', function (RouteCollectorProxy $app) {
    $app->get('/issue-priority-automation', JiraIssuesPriorityAutomationController::class);
    $app->group('/[{path:.*}]', function (RouteCollectorProxy $app) {
        $app->get('', JiraIssuesPriorityAutomationController::class);
    });
});