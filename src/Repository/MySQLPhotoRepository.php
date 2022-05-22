<?php

declare(strict_types=1);

namespace Salle\PixSalle\Repository;

use PDO;
use Salle\PixSalle\Model\Photo;
use Salle\PixSalle\Repository\PhotoRepository;

final class MySQLPhotoRepository implements PhotoRepository
{
    private PDO $databaseConnection;

    public function __construct(PDO $database)
    {
        $this->databaseConnection = $database;
    }

    public function addPhoto(Photo $photo): void {
          $query = <<<'QUERY'
          INSERT INTO photo(url, publishDate, idAlbum, nameAlbum, usernameUser)
          VALUES(:url, (SELECT NOW()), :idAlbum, (SELECT name FROM album WHERE id = :idAlbum), (SELECT username FROM users WHERE id = (SELECT idUser FROM album WHERE id = :idAlbum)))
          QUERY;

          $statement = $this->databaseConnection->prepare($query);

          $url = $photo->url();
          $idAlbum = $photo->idAlbum();

          $statement->bindParam('url', $url, PDO::PARAM_STR);
          $statement->bindParam('idAlbum', $idAlbum, PDO::PARAM_STR);

          $statement->execute();
    }

    public function addAlbum(string $name, int $idUser){
      $query = <<<'QUERY'
      INSERT INTO album(name, idUser)
      VALUES(:name, :idUser)
      QUERY;

      $statement = $this->databaseConnection->prepare($query);

      $statement->bindParam('name', $name, PDO::PARAM_STR);
      $statement->bindParam('idUser', $idUser, PDO::PARAM_STR);

      $statement->execute();

    }

    public function getPhotos(int $nbPhotos, int $offset){
      $query = "SELECT * FROM photo ORDER BY publishDate DESC LIMIT ". $nbPhotos ." OFFSET ". $offset;

      $statement = $this->databaseConnection->prepare($query);

      $statement->execute();

      $count = $statement->rowCount();
      if ($count > 0) {
          $arrayPhoto = $statement->fetchAll(PDO::FETCH_OBJ);
          return $arrayPhoto;
      }
      return null;
    }

    public function getNbPhotos(){
      $query = <<<'QUERY'
                SELECT COUNT(id) FROM photo
                QUERY;

      $statement = $this->databaseConnection->prepare($query);

      $statement->execute();

      $count = $statement->rowCount();
      if ($count > 0) {
          $counter = $statement->fetch(PDO::FETCH_OBJ);
          return $counter['count(id)'];
      }
      return null;
    }

    public function getAlbumsByUser(int $idUser){
      $query = <<<'QUERY'
                SELECT * FROM album WHERE idUser = :idUser
                QUERY;

      $statement = $this->databaseConnection->prepare($query);

      $statement->bindParam('idUser', $idUser, PDO::PARAM_STR);

      $statement->execute();

      $count = $statement->rowCount();
      if ($count > 0) {
          $arrayAlbum = $statement->fetchAll(PDO::FETCH_OBJ);
          return $arrayAlbum;
      }
      return null;
    }

    public function getPhotosByAlbum(int $idAlbum, int $nbPhotos, int $offset){
      $query = "SELECT * FROM photo WHERE idAlbum = ". $idAlbum ." ORDER BY publishDate DESC LIMIT ". $nbPhotos ." OFFSET ". $offset;

      $statement = $this->databaseConnection->prepare($query);

      $statement->execute();

      $count = $statement->rowCount();
      if ($count > 0) {
          $arrayPhoto = $statement->fetchAll(PDO::FETCH_OBJ);
          return $arrayPhoto;
      }
      return null;
    }

    public function deleteAlbum(int $idAlbum){
      $query = <<<'QUERY'
      DELETE FROM album WHERE idAlbum = :idAlbum
      QUERY;

      $statement = $this->databaseConnection->prepare($query);

      $statement->bindParam('idAlbum', $idAlbum, PDO::PARAM_STR);

      $statement->execute();
    }


}
