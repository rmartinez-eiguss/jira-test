<?php
namespace App\Controllers;

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use App\Controllers\BaseController;
use App\Services\JiraIssueService;

class JiraIssuesPriorityAutomationController extends BaseController
{
    protected $jiraIssueService;

    public function __construct(JiraIssueService $jiraIssueService)
    {
        $this->jiraIssueService = $jiraIssueService;
    }

    public function __invoke(Request $request, Response $response)
    {
        echo json_encode($this->getParams($request)); exit();
    }
}