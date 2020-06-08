<?php
namespace App\Services;

use App\Entity\Image;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageHandler
{

    private $path;
    public function __construct($path)
    {
        $this->path = $path . '/public/images';
        // dd($this->path);
    }


    public function handle(Image $image)
    {
        // Récupère le file soumis
        /** @var UploadedFile $file */
        $file = $image->getFile();

        // Créer un nom unique
        $name = $this->createName($file);

        $image->setName($name);
        // Déplace le fichier
        $file->move($this->path, $name);
    }

    private function createName(UploadedFile $file) : string
    {
        return md5(uniqid()) . $file->getClientOriginalName() . '.' . $file->guessExtension();

    }
}