<?php
namespace App\Controllers;

use Slim\Psr7\Request;
use Slim\Psr7\Response;

class BaseController 
{
    const RESPONSE_CONTENT_TYPE = 'application/json; charset=utf-8';
    
    /**
     * Generate a JSON response
     *
     * @param Response $response
     * @param array    $body
     * @param int      $status
     *
     * @return Message|Response
     */
    protected function JsonResponse(Response $response, array $body = [], int $status = 200) 
    {
        if ($body) {
            $response->getBody()->write(
                json_encode($body)
            );
        }

        return $response->withStatus($status)->withHeader('Content-Type', static::RESPONSE_CONTENT_TYPE);
    }
    
    protected function getParam($request, $param , $default=null)
    {
        $params = $this->getParams($request);

        return isset($params[$param]) ? $params[$param] : $default;
    }

    protected function getParams($request)
    {
        $postParams = json_decode($request->getBody()->__toString(), true);
        $getParams = $request->getQueryParams();
        $params = array_merge(
            !empty($postParams) ? $postParams :[] ,
            !empty($getParams) ? $getParams :[] 
        );
        return $params;
    }
}