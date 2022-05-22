<?php

declare(strict_types=1);

use Salle\PixSalle\Controller\API\BlogAPIController;
use Salle\PixSalle\Controller\HomeController;
use Salle\PixSalle\Controller\UserController;
use Salle\PixSalle\Controller\ProfileController;
use Salle\PixSalle\Controller\PortfolioController;
use Salle\PixSalle\Controller\SignUpController;
use Salle\PixSalle\Controller\UserSessionController;
use Slim\App;

function addRoutes(App $app): void
{
    $app->get('/', HomeController::class . ':showHomePage')->setName('home');
    $app->get('/sign-in', UserSessionController::class . ':showSignInForm')->setName('signIn');
    $app->post('/sign-in', UserSessionController::class . ':signIn');
    $app->get('/sign-up', SignUpController::class . ':showSignUpForm')->setName('signUp');
    $app->post('/sign-up', SignUpController::class . ':signUp');
    $app->get('/log-out', UserSessionController::class . ':logOut');
    $app->get('/profile', ProfileController::class . ':showProfilePage')->setName('profile');
    $app->post('/profile', ProfileController::class . ':updateProfile');
    $app->get('/profile/changePassword', ProfileController::class . ':showUpdatePassPage');
    $app->post('/profile/changePassword', ProfileController::class . ':updatePassword');
    $app->get('/user/wallet', UserController::class . ':showWalletPage');
    $app->post('/user/wallet', UserController::class . ':addMoneyWallet');
    $app->get('/user/membership', UserController::class . ':membershipPage');
    $app->post('/user/membership', UserController::class . ':changePlan');
    $app->get('/explore', PortfolioController::class . ':explorePage');
    $app->get('/portfolio', PortfolioController::class . ':portfolioPage');
    $app->post('/portfolio', PortfolioController::class . ':newPortfolio');
    $app->get('/portfolio/album', PortfolioController::class . ':newAlbumPage');
    $app->post('/portfolio/album', PortfolioController::class . ':addAlbum');
    $app->get('/portfolio/album/{id}', PortfolioController::class . ':showAlbumPage');
    $app->post('/portfolio/album/{id}', PortfolioController::class . ':addPhotoToAlbum');
    $app->delete('/portfolio/album/{id}', PortfolioController::class . ':deleteAlbum');
}
