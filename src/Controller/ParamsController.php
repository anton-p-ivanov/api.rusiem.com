<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserParam;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation as Http;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class ParamsController
 */
class ParamsController extends AbstractController
{
    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * ParamsController constructor.
     *
     * @param SerializerInterface $serializer
     */
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @return Http\Response
     */
    public function view(): Http\Response
    {
        return $this->json($this->getUser()->getParams());
    }

    /**
     * @param Http\Request $request
     *
     * @return Http\Response
     */
    public function update(Http\Request $request): Http\Response
    {
        $object = $this->serializer->deserialize($request->getContent(), UserParam::class, 'json');
        if (!$object instanceof UserParam) {
            $data = $request->toArray();
            $object = (new UserParam())
                ->setUser($this->getUser())
                ->setParam($data["param"])
                ->setValue($data["value"]);
        }

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($object);
        $manager->flush();

        return $this->json($object);
    }

    /**
     * @param Http\Request $request
     *
     * @return Http\Response
     */
    public function delete(Http\Request $request): Http\Response
    {
        $object = $this->serializer->deserialize($request->getContent(), UserParam::class, 'json');
        if (!$object instanceof UserParam) {
            return $this->json(true);
        }

        $manager = $this->getDoctrine()->getManager();
        $manager->remove($object);
        $manager->flush();

        return $this->json(!$manager->contains($object));
    }

    /**
     * @return User
     */
    protected function getUser(): User
    {
        $user = parent::getUser();
        if (!$user instanceof User) {
            throw new HttpException(
                Http\Response::HTTP_INTERNAL_SERVER_ERROR,
                'Could not get user from token.'
            );
        }

        return $user;
    }
}
