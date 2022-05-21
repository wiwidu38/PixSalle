<?php
declare(strict_types=1);

namespace Salle\PixSalle\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Salle\PixSalle\Repository\UserRepository;
use Salle\PixSalle\Model\User;
use Slim\Views\Twig;
use Slim\Routing\RouteContext;

class HomeController
{
    private Twig $twig;
    private UserRepository $userRepository;

    public function __construct(Twig $twig, UserRepository $userRepository){
        $this->twig = $twig;
        $this->userRepository = $userRepository;
    }

    public function showHomePage(Request $request, Response $response): Response {
      $user = null;
      if(array_key_exists('user_id',$_SESSION))
        $user = $this->userRepository->getUserById(intval($_SESSION['user_id']));
        return $this->twig->render($response, 'home.twig',
      [
        'user' => $user
      ]);
    }
}
