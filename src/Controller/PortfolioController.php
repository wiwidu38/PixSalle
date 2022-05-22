<?php
declare(strict_types=1);

namespace Salle\PixSalle\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Salle\PixSalle\Repository\UserRepository;
use Salle\PixSalle\Repository\PhotoRepository;
use Salle\PixSalle\Service\ValidatorService;
use Salle\PixSalle\Model\User;
use Slim\Views\Twig;
use Slim\Routing\RouteContext;

class PortfolioController
{
    private Twig $twig;
    private UserRepository $userRepository;
    private PhotoRepository $photoRepository;
    private ValidatorService $validator;

    public function __construct(Twig $twig, UserRepository $userRepository, PhotoRepository $photoRepository){
        $this->twig = $twig;
        $this->userRepository = $userRepository;
        $this->photoRepository = $photoRepository;
        $this->validator = new ValidatorService();
    }

    public function explorePage(Request $request, Response $response): Response {
      if(array_key_exists('user_id',$_SESSION)){
        $user = $this->userRepository->getUserById(intval($_SESSION['user_id']));
        $arrayPhotos = $this->photoRepository->getPhotos(10,0);
        return $this->twig->render($response, 'explore.twig',
          [
            'user' => $user,
            'arrayPhotos' => $arrayPhotos
          ]);
      }else{
        return $this->twig->render($response, 'explore.twig',
          [
            'message' => 'You have to be login to access to this page'
          ]);
      }

    }

    public function portfolioPage(Request $request, Response $response): Response {
      if(array_key_exists('user_id',$_SESSION)){
        $user = $this->userRepository->getUserById(intval($_SESSION['user_id']));
        if(strcmp($user->portfolio,'') == 0){
          return $this->twig->render($response, 'newPortfolio.twig',
            [
              'user' => $user
            ]);
        }else{
          $arrayAlbum = $this->photoRepository->getAlbumsByUser(intval($_SESSION['user_id']));
          $user = $this->userRepository->getUserById(intval($_SESSION['user_id']));
          // $idAlbum = $this->databaseConnection->lastInsertId();
          // $ch = curl_init();
          // $data = array('symbology' => 'QRCode','code' => 'localhost:8030/portfolio/album/'.$idAlbum);
          // curl_setopt($ch, CURLOPT_HTTPHEADER, array(
          //                 'Accept: image/png',
          //                 'Content-Type: application/json'
          //             ));
          // curl_setopt($ch, CURLOPT_POST, 1);
          // curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
          // curl_setopt($ch, CURLOPT_URL, 'http://localhost:8020/BarcodeGenerator');
          // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          // $img = curl_exec($ch);
          // curl_close($ch);
          // file_put_contents('/barcodes/'.$idAlbum.'.png', $img);
          return $this->twig->render($response, 'displayPortfolio.twig',
            [
              'user' => $user,
              'arrayAlbum' => $arrayAlbum
            ]);
        }

      }else{
        return $this->twig->render($response, 'sign-in.twig',
          [
            'message' => 'You have to be login to access to this page'
          ]);
      }
    }

    public function newPortfolio(Request $request, Response $response): Response {
      if(array_key_exists('user_id',$_SESSION)){
        $data = $request->getParsedBody();
        $this->userRepository->addPortfolio(intval($_SESSION['user_id']),$data['name']);
        $user = $this->userRepository->getUserById(intval($_SESSION['user_id']));
          return $this->twig->render($response, 'displayPortfolio.twig',
            [
              'user' => $user,
              'message' => 'You have succcessfully created your porfolio now you can add album'
            ]);
      }else{
        return $this->twig->render($response, 'sign-in.twig',
          [
            'message' => 'You have to be login to access to this page'
          ]);
      }
    }

    public function newAlbumPage(Request $request, Response $response): Response {
      if(array_key_exists('user_id',$_SESSION)){
        $user = $this->userRepository->getUserById(intval($_SESSION['user_id']));
        return $this->twig->render($response, 'newAlbum.twig',
          [
            'user' => $user
          ]);
      }else{
        return $this->twig->render($response, 'explore.twig',
          [
            'message' => 'You have to be login to access to this page'
          ]);
      }
    }

    public function addAlbum(Request $request, Response $response): Response {
      if(array_key_exists('user_id',$_SESSION)){
        $data = $request->getParsedBody();
        $this->photoRepository->addAlbum($data['name'],intval($_SESSION['user_id']));
        $user = $this->userRepository->getUserById(intval($_SESSION['user_id']));
          return $this->twig->render($response, 'displayPortfolio.twig',
            [
              'user' => $user,
              'info' => 'You new album was successfully created'
            ]);
      }else{
        return $this->twig->render($response, 'sign-in.twig',
          [
            'message' => 'You have to be login to access to this page'
          ]);
      }
    }

    public function showAlbumPage(Request $request, Response $response, $args): Response {
      if(array_key_exists('user_id',$_SESSION)){
        $user = $this->userRepository->getUserById(intval($_SESSION['user_id']));
        $photosAlbum = $this->photoRepository->getPhotosByAlbum(intval($args['id']),10,0);
            return $this->twig->render($response, 'displayAlbum.twig',
              [
                'user' => $user,
                'photosAlbum' => $photosAlbum
              ]);
      }else{
          return $this->twig->render($response, 'sign-in.twig',
            [
              'message' => 'You have to be login to access to this page'
            ]);
        }
      }

      public function deleteAlbum(Request $request, Response $response, $args): Response {
        if(array_key_exists('user_id',$_SESSION)){
          $this->photoRepository->deleteAlbum(intval($args['id']));
          return $response->withHeader('Location', '/portfolio')->withStatus(302);
        }else{
            return $this->twig->render($response, 'sign-in.twig',
              [
                'message' => 'You have to be login to access to this page'
              ]);
          }
        }
}
