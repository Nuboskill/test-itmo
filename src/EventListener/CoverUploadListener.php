<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use App\Entity\Book;
use App\Service\FileUploader;

class CoverUploadListener
{
    private $uploader;

    public function __construct(FileUploader $uploader)
    {
        $this->uploader = $uploader;
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    public function preUpdate(PreUpdateEventArgs $args): void
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    public function postLoad(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        if (!$entity instanceof Book) {
            return;
        }

        if ($fileName = $entity->getCover()) {
            $entity->setCover(new File($this->uploader->getTargetDirectory().'/'.$fileName));
        }
    }

    public function preRemove (LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        if (!$entity instanceof Book) {
            return;
        }

        if ($file = $entity->getCover()) {
            unlink($file);
        }
    }

    private function uploadFile($entity): void
    {
        if (!$entity instanceof Book) {
            return;
        }

        $file = $entity->getCover();

        if ($file instanceof UploadedFile) {
            $fileName = $this->uploader->upload($file);
            $entity->setCover($fileName);
        } elseif ($file instanceof File) {
            $entity->setCover($file->getFilename());
        }
    }
}
