<?php

namespace App\Service;

use App\Entity\Ilot;
use App\Entity\OrdreFab;
use DateTime;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    private $targetDirectory;

    public function __construct($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    public function upload(UploadedFile $file, OrdreFab $ordreFab, Ilot $ilot): string
    {
        date_default_timezone_set('Europe/Paris');

        $dt = new DateTime();
        $date = $dt->format('Ymd');
        $time = $dt->format('His');

        $fileName = $ordreFab->getNumero() . '_' . $ilot->getNomURL() . '_' . $date . '_' . $time . '.' . $file->guessExtension();

        try {
            $file->move($this->getTargetDirectory(), $fileName);
        } catch (FileException $e) {
            echo $e->getMessage();
        }

        return $fileName;
    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }
}