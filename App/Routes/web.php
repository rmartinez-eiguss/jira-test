<?
use Slim\Routing\RouteCollectorProxy;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->group('/[{path:.*}]', function (RouteCollectorProxy $app) {
    $app->get('', App\Controllers\JiraIssuesPriorityController::class);
});