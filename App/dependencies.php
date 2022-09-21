<?php
use JiraRestApi\Configuration\ArrayConfiguration;
use JiraRestApi\Issue\IssueService;

// use App\Classes\SessionManager;

$container->set(IssueService::class, function() {
    return new IssueService(new ArrayConfiguration(
        [
            'jiraHost' => getenv('JIRA_HOST'),
            // 'useTokenBasedAuth' => true,
            // 'personalAccessToken' => getenv('JIRA_PERSONAL_ACCESS_TOKEN')
            'jiraUser' => getenv('JIRA_USER'),
            'jiraPassword' => getenv('JIRA_PASS'),
        ]
    ));
});