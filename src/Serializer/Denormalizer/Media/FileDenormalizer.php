<?php

namespace App\Serializer\Denormalizer\Media;

use App\Entity\Context;
use App\Entity\Media\File;
use App\Service\UploadService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

/**
 * Class FileDenormalizer
 */
class FileDenormalizer implements DenormalizerInterface
{
    /**
     * @var UploadService
     */
    private UploadService $uploader;

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $manager;

    /**
     * FileDenormalizer constructor.
     *
     * @param UploadService $service
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(UploadService $service, EntityManagerInterface $entityManager)
    {
        $this->uploader = $service;
        $this->manager = $entityManager;
    }

    /**
     * @inheritDoc
     */
    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        if (isset($data['uuid'])) {
            $object = $this->manager->getRepository(File::class)->find($data['uuid']);
        } else {
            $fileContext = $this->manager->getRepository(Context::class)->find('news');
            $uploadedFile = new UploadedFile(
                $data['src'],
                $data['name'],
                $data['type'],
            );

            $object = $this->uploader->upload($uploadedFile, $fileContext);
        }

        return $object;
    }

    /**
     * @inheritDoc
     */
    public function supportsDenormalization($data, string $type, string $format = null): bool
    {
        return $type === File::class && is_array($data);
    }
}