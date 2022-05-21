<?php
declare(strict_types=1);

namespace Salle\PixSalle\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Salle\PixSalle\Service\ValidatorService;
use Salle\PixSalle\Repository\UserRepository;
use Salle\PixSalle\Model\User;
use Slim\Views\Twig;
use Slim\Routing\RouteContext;

class HomeController
{
    private Twig $twig;

    public function __construct(Twig $twig){
        $this->twig = $twig;
    }

    public function showHomePage(Request $request, Response $response): Response {
        return $this->twig->render($response, 'home.twig');
    }
}
