<?php

declare(strict_types=1);

namespace Salle\PixSalle\Model;

class Photo
{

  private int $id;
  private string $url;
  private int $idAlbum;
  private Datetime $publishDate;

  public function __construct(
    string $url,
    int $idAlbum
  ) {
    $this->url = $url;
    $this->idAlbum = $idAlbum;
  }

  public function id()
  {
    return $this->id;
  }

  public function url()
  {
    return $this->url;
  }

  public function idAlbum()
  {
    return $this->idAlbum;
  }

  public function publishDate()
  {
    return $this->publishDate;
  }

}
