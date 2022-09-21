<?
namespace App\Exception;

use Slim\Psr7\Request;
use Slim\Psr7\Response;

class Handler
{
    public function __invoke(
        Request $request,
        \Throwable $e,
        bool $displayErrorDetails,
        bool $logErrors,
        bool $logErrorDetails,
        ?LoggerInterface $logger = null
    )
    {
        $response = new Response();        
        $response->getBody()->write(
            json_encode([
                "error" => $e->getMessage(),
                "errorTrace" => explode("\n",$e->__toString()),
            ])
        );
        $code = $e->getCode() ?: 500;

        return $response->withStatus($code)->withHeader('Content-Type', 'application/json');
    }
}