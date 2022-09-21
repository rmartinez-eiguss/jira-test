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
        $issueId = $this->getParam($request, "issueId");

        if(empty($issueId)){
            throw new \Exception("Invalid issue id");
        }

        $priority = $this->jiraIssueService->getIssuePriority($issueId);
        $this->jiraIssueService->setIssuePriority($issueId, $priority);

        return $this->jsonResponse(
            $response,
            ['issue' => [
                'id' => $issueId,
                'priority' => $priority,
            ]]
        );
    }
}