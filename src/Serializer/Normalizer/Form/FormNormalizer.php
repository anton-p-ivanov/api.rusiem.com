<?php

namespace App\Serializer\Normalizer\Form;

use App\Entity\Context;
use App\Entity\Form\Form;
use App\Entity\Form\Status;
use App\Entity\Mail\Template;
use App\Serializer\Normalizer\CollectionNormalizer;
use App\Serializer\Normalizer\WorkflowNormalizer;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation as Http;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Serializer;

/**
 * Class FormNormalizer
 */
class FormNormalizer implements NormalizerInterface
{
    /**
     * @var Http\Request|null
     */
    private ?Http\Request $request;

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $manager;

    /**
     * LookupNormalizer constructor.
     *
     * @param Http\RequestStack $requestStack
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(Http\RequestStack $requestStack, EntityManagerInterface $entityManager)
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->manager = $entityManager;
    }

    /**
     * @inheritDoc
     */
    public function normalize($object, string $format = null, array $context = [])
    {
        $encoder = new JsonEncoder();

        if ($this->request->headers->has('X-Lookup-Request')) {
            return [
                'value' => $object->getUuid(),
                'label' => $object->getTitle()
            ];
        }

        $context[AbstractNormalizer::IGNORED_ATTRIBUTES] = ['responses'];
        $context[AbstractNormalizer::CALLBACKS] = [
            'context' => fn(?Context $context) => $context ? $context->getSlug() : null,
            'template' => fn(?Template $template) => $template ? $template->getUuid() : null,
            'statuses' => fn(Collection $statuses) => $statuses->toArray(),
        ];

        $normalizers = [
            new WorkflowNormalizer(),
            new DateTimeNormalizer(),
            new StatusNormalizer(),
            new CollectionNormalizer(),
            new GetSetMethodNormalizer()
        ];

        $serializer = new Serializer($normalizers, [$encoder]);

        return $serializer->normalize($object, $format, $context);
    }

    /**
     * @inheritDoc
     */
    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof Form;
    }
}
