<?php
namespace App\Services;

use JiraRestApi\Issue\IssueService;
use JiraRestApi\Issue\IssueField;

class JiraIssueService
{
    protected $jiraIssuesService;

    protected $issuesFields = [
        'summary',
        'issuetype',
        'priority',
        'Story Points',
    ];
    protected $storyPointsFieldId = "customfield_10117";
    protected $productDirectionAlignmentFieldId = "customfield_11409";
    protected $productDirectionAlignmentsScore = [
        'Totally aligned' => 5,
        'Quite aligned' => 4,
        'Partially aligned' => 3,
        'Not aligned' => 2,
        'Against the direction' => 1,
    ];
    protected $severityFieldId = "customfield_11103";
    protected $severitiesScore = [
        "Critical" => 100,
        "Major" => 75,
        "Moderate" => 50,
        "Minor" => 25,
        "Cosmetic" => 1,
    ];
    protected $priorityScoreFieldId = "customfield_11408";

    public function __construct(IssueService $jiraIssuesService)
    {
        $this->jiraIssuesService = $jiraIssuesService;
    }

    public function getIssuePriority($issueId)
    {
        $issue = $this->getIssue($issueId);
        $issueType = !empty($issue->fields->issuetype->name) ? $issue->fields->issuetype->name : 'None' ;
        switch ($issueType) {
            case 'None':
                return 0;
                break;
            case 'Bug':
                return $this->getBugPriority($issue);
                break;
            default:
                return $this->getGeneralPriority($issue);
                break;
        }
    }

    public function getIssue($issueId)
    {
        try {
            $queryParam = [
                'fields' => array_merge(
                    $this->issuesFields,
                    [
                        $this->severityFieldId,
                        $this->storyPointsFieldId,
                        $this->productDirectionAlignmentFieldId,
                    ],
                )
            ];
echo json_encode($this->jiraIssuesService->get($issueId, $queryParam));exit();
            return $this->jiraIssuesService->get($issueId, $queryParam);
        } catch (\Exception $e) {
            print('Error Occured! ');
        }
    }

    public function setIssuePriority($issueId, $priority)
    {
        $issueField = new IssueField(true);
        $issueField->addCustomField($this->priorityScoreFieldId, $priority);
        $editParams = [
            'notifyUsers' => false,
        ];
        $ret = $this->jiraIssuesService->update($issueId, $issueField, $editParams);
    }


    /**
     * Score of a general issue:
     * Value / Effort * (5 + ProductDirectionAlignment)
     * 
     * @param object $issue
     * @return integer
     */
    protected function getGeneralPriority($issue)
    {
        return ($this->getCustomerScore($issue) / $this->getEffortScore($issue)) * (5 + $this->getProductAlignmentScore($issue));
    }

    /**
     * Score of a bug:
     * (Severity * 0.8 + Value * 0.2) / Effort * 10
     * 
     * @param object $issue
     * @return integer
     */
    protected function getBugPriority($issue)
    {
        return (($this->getSeverityScore($issue) * 0.8 + $this->getCustomerScore($issue) * 0.2) / $this->getEffortScore($issue)) * 10;
    }

    /**
     * On a scale of 1 through 5, we are going to evaluate alignment with product vision.
     * 
     * @param object $issue
     * @return integer
     */
    protected function getProductAlignmentScore($issue)
    {
        $productDirectionAlignment = !empty($issue->fields->{$this->productDirectionAlignmentFieldId}->value) ?
            $issue->fields->{$this->productDirectionAlignmentFieldId}->value
            : 'none';
        
        return !empty($this->productDirectionAlignmentsScore[$productDirectionAlignment]) ?
            $this->productDirectionAlignmentsScore[$productDirectionAlignment]
            : 0;
    }

    /**
     * Value will be calculated on a scale between 1 to 100, 
     * in which the aggregation of ACV for the list of affected customers / reporter customers will be used for placement
     * 
     * @param object $issue
     * @return integer
     */
    protected function getCustomerScore($issue)
    {
        // TODO call grandpa

        return 50;
    }

    /**
     * Effort score is calculated using the SP, if no story points sets, this value will be 100
     * 
     * @param object $issue
     * @return integer
     */
    protected function getEffortScore($issue)
    {
        return !empty($issue->fields->{$this->storyPointsFieldId}) ?
        $issue->fields->{$this->storyPointsFieldId}
        : 100;
    }

    /**
     * Severity will be a value between 1 and 100
     * Each Severity has an score defined in $this->severitiesScore
     * 
     * @param object $issue
     * @return integer
     */
    protected function getSeverityScore($issue)
    {
        $severityName = !empty($issue->fields->{$this->severityFieldId}->value) ?
            $issue->fields->{$this->severityFieldId}->value
            : 'none';
        
        return !empty($this->severitiesScore[$severityName]) ?
            $this->severitiesScore[$severityName]
            : 1;
    }
}