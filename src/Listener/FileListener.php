<?php

namespace App\Listener;

use App\Entity\Media\File;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class FileListener
 */
class FileListener
{
    /***
     * @var ContainerInterface
     */
    private ContainerInterface $container;

    /**
     * @var string
     */
    private string $filePath;

    /**
     * @var string
     */
    private string $previewPath;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param File $file
     */
    public function preRemove(File $file): void
    {
        $this->filePath = $this->container->getParameter('media_dir') . '/' . $file->getRelativePath();
        $this->previewPath = $this->container->getParameter('media_dir') . '/.preview/' . $file->getRelativePath();
    }

    /**
     * @return void
     */
    public function postRemove(): void
    {
        foreach ([$this->filePath, $this->previewPath] as $file) {
            if (is_file($file) || is_link($file)) {
                unlink($file);
            }
        }
    }
}