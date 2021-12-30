<?php

namespace App\Service;

use App\Entity\Context;
use App\Entity\Media\File;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation as Http;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * Class UploadService
 */
class UploadService
{
    /**
     * @var ContainerInterface
     */
    private ContainerInterface $container;

    /**
     * @var SluggerInterface
     */
    private SluggerInterface $slugger;

    /**
     * @param ContainerInterface $container
     * @param SluggerInterface $slugger
     */
    public function __construct(ContainerInterface $container, SluggerInterface $slugger)
    {
        $this->container = $container;
        $this->slugger = $slugger;
    }

    /**
     * @param Http\File\UploadedFile $file
     * @param Context $context
     *
     * @return File|null
     */
    public function upload(Http\File\UploadedFile $file, Context $context): ?File
    {
        $originalFilename = $file->getClientOriginalName();
        $newFilename = $this->getStoredFileName($originalFilename, $file);

        try {
            $mediaFile = $this->createMediaFileObject($newFilename, $file, $context);
            $renamePath = $this->getMediaPath($context);
            if (!file_exists($renamePath)) {
                mkdir($renamePath, 0755, true);
            }
            rename($file->getPathname(), $renamePath . '/' . $newFilename);
        } catch (Http\File\Exception\FileException $e) {
            $mediaFile = null;
        }

        return $mediaFile;
    }

    /**
     * @param string $newFilename
     * @param Http\File\UploadedFile $file
     * @param Context $context
     *
     * @return File
     */
    private function createMediaFileObject(string $newFilename, Http\File\UploadedFile $file, Context $context): File
    {
        $imageSize = getimagesize($file->getPathname());

        $file = (new File())
            ->setOriginalName($file->getClientOriginalName())
            ->setStoredName($newFilename)
            ->setContext($context)
            ->setSize($file->getSize())
            ->setMimeType($file->getMimeType());

        if ($imageSize) {
            $file
                ->setImageWidth($imageSize[0])
                ->setImageHeight($imageSize[1]);
        }

        return $file;
    }

    /**
     * @param Context $context
     *
     * @return string
     */
    public function getMediaPath(Context $context): string
    {
        return rtrim($this->container->getParameter('media_dir'), '/') . '/' . $context->getSlug();
    }

    /**
     * @param string $fileName
     *
     * @return string
     */
    private function getSafeFileName(string $fileName): string
    {
        return $this->slugger->slug(pathinfo($fileName, PATHINFO_FILENAME));
    }

    /**
     * @param string $originalFilename
     * @param Http\File\UploadedFile|null $file
     *
     * @return string
     */
    public function getStoredFileName(string $originalFilename, ?Http\File\UploadedFile $file = null): string
    {
        $safeFilename = $this->getSafeFileName($originalFilename);

        try {
            $uniqueName = $safeFilename . bin2hex(random_bytes(10));
        } catch (\Exception $exception) {
            $uniqueName = $safeFilename . uniqid();
        }

        $extension = $file ? $file->guessExtension() : pathinfo($originalFilename, PATHINFO_EXTENSION);

        return implode('.', [sha1($uniqueName), $extension]);
    }
}