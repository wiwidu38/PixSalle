<?php
declare(strict_types=1);

namespace Salle\PixSalle\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Salle\PixSalle\Repository\UserRepository;
use Salle\PixSalle\Service\ValidatorService;
use Salle\PixSalle\Model\User;
use Slim\Views\Twig;
use Slim\Routing\RouteContext;

class UserController
{
    private Twig $twig;
    private UserRepository $userRepository;
    private ValidatorService $validator;

    public function __construct(Twig $twig, UserRepository $userRepository){
        $this->twig = $twig;
        $this->userRepository = $userRepository;
        $this->validator = new ValidatorService();
    }

    public function showWalletPage(Request $request, Response $response): Response {
      $user = null;
      if(array_key_exists('user_id',$_SESSION)){
        $user = $this->userRepository->getUserById(intval($_SESSION['user_id']));
        return $this->twig->render($response, 'wallet.twig',
          [
            'user' => $user
          ]);
      }else{
        return $this->twig->render($response, 'sign-in.twig',
          [
            'message' => 'You have to be login to access to this page'
          ]);
      }

    }

    public function addMoneyWallet(Request $request, Response $response): Response {
      $user = null;
      if(array_key_exists('user_id',$_SESSION)){
        $user = $this->userRepository->getUserById(intval($_SESSION['user_id']));
        $data = $request->getParsedBody();
        $routeParser = RouteContext::fromRequest($request)->getRouteParser();

        $errors = [];

        $errors['username'] = $this->validator->validateUsername($data['username']);
        $errors['phone'] = $this->validator->validatePhone($data['phone']);

        if ($errors['phone'] == '') {
            unset($errors['phone']);
        }

        if ($errors['username'] == '') {
            unset($errors['username']);
        }

        if (count($errors) == 0) {
            $this->userRepository->updateProfile(intval($_SESSION['user_id']), $data['username'], $data['phone'], '');
            $user = $this->userRepository->getUserById(intval($_SESSION['user_id']));
            return $this->twig->render(
                $response,
                'profile.twig',
                [
                    'info' => 'Informations was updated successfully',
                    'user' => $user
                ]
            );
        }
        return $this->twig->render(
            $response,
            'profile.twig',
            [
                'formErrors' => $errors,
                'user' => $user
            ]
        );
      }else{
        return $this->twig->render($response, 'sign-in.twig',
          [
            'message' => 'You have to be login to access to this page'
          ]);
      }
    }
}
