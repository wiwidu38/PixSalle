<?php

declare(strict_types=1);

namespace Salle\PixSalle\Repository;

use Salle\PixSalle\Model\Photo;

interface PhotoRepository
{
  public function addPhoto(Photo $photo);
  public function addAlbum(string $name, int $idUser);
  public function getPhotos(int $nbPhotos, int $offset);
  public function getNbPhotos();
  public function getAlbumsByUser(int $idUser);
  public function getPhotosByAlbum(int $idAlbum, int $nbPhotos, int $offset);
  public function deleteAlbum(int $idAlbum);
}
