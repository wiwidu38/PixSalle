<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Salle\PixSalle\Controller\SignUpController;
use Salle\PixSalle\Controller\UserSessionController;
use Salle\PixSalle\Controller\HomeController;
use Salle\PixSalle\Controller\UserController;
use Salle\PixSalle\Controller\PortfolioController;
use Salle\PixSalle\Controller\ProfileController;
use Salle\PixSalle\Repository\MySQLUserRepository;
use Salle\PixSalle\Repository\MySQLPhotoRepository;
use Salle\PixSalle\Repository\PDOConnectionBuilder;
use Slim\Views\Twig;

function addDependencies(ContainerInterface $container): void
{
    $container->set(
        'view',
        function () {
            return Twig::create(__DIR__ . '/../templates', ['cache' => false]);
        }
    );

    $container->set('db', function () {
        $connectionBuilder = new PDOConnectionBuilder();
        return $connectionBuilder->build(
            $_ENV['MYSQL_ROOT_USER'],
            $_ENV['MYSQL_ROOT_PASSWORD'],
            $_ENV['MYSQL_HOST'],
            $_ENV['MYSQL_PORT'],
            $_ENV['MYSQL_DATABASE']
        );
    });

    $container->set('user_repository', function (ContainerInterface $container) {
        return new MySQLUserRepository($container->get('db'));
    });

    $container->set('photo_repository', function (ContainerInterface $container) {
        return new MySQLPhotoRepository($container->get('db'));
    });

    $container->set(
        UserSessionController::class,
        function (ContainerInterface $c) {
            return new UserSessionController($c->get('view'), $c->get('user_repository'));
        }
    );

    $container->set(
        SignUpController::class,
        function (ContainerInterface $c) {
            return new SignUpController($c->get('view'), $c->get('user_repository'));
        }
    );

    $container->set(
        HomeController::class,
        function (ContainerInterface $c) {
            return new HomeController($c->get('view'), $c->get('user_repository'));
        }
    );

    $container->set(
        ProfileController::class,
        function (ContainerInterface $c) {
            return new ProfileController($c->get('view'), $c->get('user_repository'));
        }
    );

    $container->set(
        UserController::class,
        function (ContainerInterface $c) {
            return new UserController($c->get('view'), $c->get('user_repository'));
        }
    );

    $container->set(
        PortfolioController::class,
        function (ContainerInterface $c) {
            return new PortfolioController($c->get('view'), $c->get('user_repository'), $c->get('photo_repository'));
        }
    );
}
