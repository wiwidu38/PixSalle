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

        $errors['amount'] = $this->validator->validateAmount(floatval($data['amount']));

        if ($errors['amount'] == '') {
            unset($errors['amount']);
        }

        if (count($errors) == 0) {
            $this->userRepository->addAmount(intval($_SESSION['user_id']),$data['amount']);
            $user = $this->userRepository->getUserById(intval($_SESSION['user_id']));
            return $this->twig->render(
                $response,
                'wallet.twig',
                [
                    'info' => 'Amount is successfully added to your wallet',
                    'user' => $user
                ]
            );
        }
        return $this->twig->render(
            $response,
            'wallet.twig',
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

    public function membershipPage(Request $request, Response $response): Response {
      $user = null;
      if(array_key_exists('user_id',$_SESSION)){
        $user = $this->userRepository->getUserById(intval($_SESSION['user_id']));
        return $this->twig->render($response, 'membership.twig',
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

    public function changePlan(Request $request, Response $response): Response {
      $user = null;
      if(array_key_exists('user_id',$_SESSION)){
        $user = $this->userRepository->getUserById(intval($_SESSION['user_id']));
        $data = $request->getParsedBody();
        $routeParser = RouteContext::fromRequest($request)->getRouteParser();
        $newPlan = '';
        if(isset($data['cool']))
          $newPlan = 'cool';
        else $newPlan = 'active';
        $this->userRepository->changePlan(intval($_SESSION['user_id']),$newPlan);
        $user = $this->userRepository->getUserById(intval($_SESSION['user_id']));
        return $this->twig->render(
            $response,
            'membership.twig',
            [
                'info' => 'Your plan has been updated',
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
