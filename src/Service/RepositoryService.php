<?php

namespace App\Service;

use App\Entity\EntityInterface;
use App\Entity\Workflow\Workflow;
use App\Entity\Workflow\WorkflowInterface;
use App\Repository\RepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\MappingException;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpFoundation as Http;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class RepositoryService
 */
class RepositoryService
{
    /**
     * @var Http\Request|null
     */
    protected ?Http\Request $request;

    /**
     * @var SerializerInterface
     */
    protected SerializerInterface $serializer;

    /**
     * @var ValidatorInterface
     */
    protected ValidatorInterface $validator;

    /**
     * @var EntityManagerInterface
     */
    protected EntityManagerInterface $manager;

    /**
     * @var ConstraintViolationListInterface
     */
    protected ConstraintViolationListInterface $violations;

    /**
     * @var bool
     */
    protected bool $useSoftDelete = true;

    /**
     * @var UserPasswordHasherInterface
     */
    private UserPasswordHasherInterface $passwordHasher;

    /**
     * @var UserInterface|PasswordAuthenticatedUserInterface|null
     */
    private ?UserInterface $user;

    /**
     * RepositoryService constructor.
     *
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     * @param Http\RequestStack $requestStack
     * @param EntityManagerInterface $manager
     * @param UserPasswordHasherInterface $passwordHasher
     * @param Security $security
     */
    public function __construct(
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        Http\RequestStack $requestStack,
        EntityManagerInterface $manager,
        UserPasswordHasherInterface $passwordHasher,
        Security $security
    ) {
        $this->manager = $manager;
        $this->validator = $validator;
        $this->serializer = $serializer;
        $this->request = $requestStack->getCurrentRequest();
        $this->passwordHasher = $passwordHasher;
        $this->user = $security->getUser();

        if ($this->request->attributes->has('useSoftDelete')) {
            $this->useSoftDelete = (bool)$this->request->attributes->get('useSoftDelete');
        }

        $this->violations = new ConstraintViolationList();
    }

    /**
     * @param string $type
     *
     * @return Paginator
     */
    public function list(string $type): Paginator
    {
        $repository = $this->manager->getRepository($type);
        if (!$repository instanceof RepositoryInterface) {
            throw new Http\Exception\BadRequestException(
                'Repository must implement RepositoryInterface'
            );
        }

        $query = $repository->getQueryBuilder();

        return new Paginator($query);
    }

    /**
     * @param string $type
     *
     * @return \ArrayIterator
     */
    public function lookup(string $type): \ArrayIterator
    {
        $repository = $this->manager->getRepository($type);
        if (!$repository instanceof RepositoryInterface) {
            throw new Http\Exception\BadRequestException(
                'Repository must implement RepositoryInterface'
            );
        }

        $builder = $repository->getLookupQueryBuilder();

        return new \ArrayIterator($builder->getQuery()->getResult());
    }

    /**
     * @param string $type
     *
     * @return EntityInterface|null
     */
    public function create(string $type): ?EntityInterface
    {
        return $this->process(new $type());
    }

    /**
     * @param string $type
     *
     * @return EntityInterface|null
     */
    public function read(string $type): ?EntityInterface
    {
        return $this->getObjectFromRequest($type);
    }

    /**
     * @param string $type
     *
     * @return EntityInterface|null
     */
    public function update(string $type): ?EntityInterface
    {
        return $this->process($this->getObjectFromRequest($type));
    }

    /**
     * @param string $type
     *
     * @return EntityInterface|null
     */
    public function delete(string $type): ?EntityInterface
    {
        if ($this->validatePassword() === false) {
            return null;
        }

        $object = $this->getObjectFromRequest($type);

        if ($this->useSoftDelete && $object instanceof WorkflowInterface) {
            $object->setWorkflow(
                ($object->getWorkflow() ?? new Workflow())->setIsDeleted(true)
            );

            $this->manager->persist($object);
        } else {
            $this->manager->remove($object);
        }

        $this->manager->flush();

        return $object;
    }

    /**
     * @return ConstraintViolationListInterface
     */
    public function getViolations(): ConstraintViolationListInterface
    {
        return $this->violations;
    }

    /**
     * @return bool
     */
    protected function validatePassword(): bool
    {
        $data = json_decode($this->request->getContent(), true);
        $password = $data['password'] ?? '';

        $isValid = $this->passwordHasher->isPasswordValid($this->user, $password);
        if (false === $isValid) {
            $this->violations->add(
                new ConstraintViolation('Invalid password', null, [], null, 'password', $password)
            );
        }

        return $isValid;
    }

    /**
     * @param object $previousObject
     *
     * @return EntityInterface|null
     */
    protected function process(object $previousObject): ?EntityInterface
    {
        $object = $this->serializer->deserialize($this->request->getContent(), get_class($previousObject), 'json', [
            AbstractNormalizer::OBJECT_TO_POPULATE => $previousObject,
            AbstractNormalizer::IGNORED_ATTRIBUTES => ['workflow']
        ]);

        $violations = $this->validator->validate($object);
        if ($violations->count()) {
            $this->violations = $violations;
            return null;
        }

        $this->manager->persist($object);
        $this->manager->flush();
        $this->manager->refresh($object);

        return $object;
    }

    /**
     * @param string $type
     *
     * @return EntityInterface|null
     */
    protected function getObjectFromRequest(string $type): ?EntityInterface
    {
        $classMetadata = $this->manager->getClassMetadata($type);

        try {
            $identifier = $this->request->get($classMetadata->getSingleIdentifierFieldName());
            $object = $this->manager
                ->getRepository($type)
                ->find($identifier);
        } catch (MappingException $e) {
            $object = null;
        }

        return $object;
    }
}
