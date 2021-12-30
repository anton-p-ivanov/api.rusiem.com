<?php

namespace App\Controller;

use App\Service\RepositoryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation as Http;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class BaseController
 */
class BaseController extends AbstractController
{
    /**
     * @var string
     */
    protected string $repositoryClass;

    /**
     * @var Http\Request|null
     */
    protected ?Http\Request $request;

    /**
     * BaseController constructor.
     *
     * @param Http\RequestStack $requestStack
     */
    public function __construct(Http\RequestStack $requestStack)
    {
        $this->request = $requestStack->getCurrentRequest();

        if ($className = $this->request->get('repositoryClass')) {
            $this->repositoryClass = (string)$className;
        }
    }

    /**
     * @return Http\Response
     */
    public function preflight(): Http\Response
    {
        return new Http\Response();
    }

    /**
     * @param RepositoryService $service
     * @param Http\Request $request
     *
     * @return Http\JsonResponse
     */
    public function list(RepositoryService $service, Http\Request $request): Http\JsonResponse
    {
        $page = (int)$request->get('page', 1);
        $limit = (int)$request->get('size', 10);

        $results = $service->list($this->repositoryClass);

        $headers = [
            'X-Pagination-Total' => $results->count(),
            'X-Pagination-Page' => $page,
            'X-Pagination-Size' => $limit,
        ];

        if ($this->getDoctrine()->getRepository($this->repositoryClass)->isResultsFiltered) {
            $headers['X-Results-Filtered'] = true;
        }

        return $this->json($results, Http\Response::HTTP_OK, $headers);
    }

    /**
     * @param RepositoryService $service
     *
     * @return Http\JsonResponse
     */
    public function lookup(RepositoryService $service): Http\JsonResponse
    {
        return $this->json(
            $service->lookup($this->repositoryClass)
        );
    }

    /**
     * @param RepositoryService $service
     *
     * @return Http\Response
     */
    public function view(RepositoryService $service): Http\Response
    {
        $object = $service->read($this->repositoryClass);
        if (!$object) {
            throw new NotFoundHttpException(sprintf('No entity found for class %s', $this->repositoryClass));
        }

        return $this->json($object);
    }

    /**
     * @param RepositoryService $service
     *
     * @return Http\Response
     */
    public function create(RepositoryService $service): Http\Response
    {
        $object = $service->create($this->repositoryClass);
        if (($violations = $service->getViolations()) && $violations->count()) {
            return $this->json($violations, Http\Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->json($object, Http\Response::HTTP_CREATED);
    }

    /**
     * @param RepositoryService $service
     *
     * @return Http\Response
     */
    public function update(RepositoryService $service): Http\Response
    {
        $object = $service->update($this->repositoryClass);
        if (($violations = $service->getViolations()) && $violations->count()) {
            return $this->json($violations, Http\Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->json($object);
    }

    /**
     * @param RepositoryService $service
     *
     * @return Http\Response
     */
    public function delete(RepositoryService $service): Http\Response
    {
        $object = $service->delete($this->repositoryClass);
        if (($violations = $service->getViolations()) && $violations->count()) {
            return $this->json($violations, Http\Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->json($object);
    }
}
