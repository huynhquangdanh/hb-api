<?php

namespace App\Controller\Api;

use App\Entity\BoxType;
use App\Repository\BoxTypeRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/box-types')]
class BoxTypeApiController extends AbstractController
{
    public function __construct(
        private BoxTypeRepository $repository,
        private ManagerRegistry $doctrine
    ) {
    }

    #[Route('', name: 'api_box_type_list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $items = $this->repository->findBy([], ['name' => 'ASC']);
        return $this->json(array_map([$this, 'normalize'], $items));
    }

    #[Route('/{id}', name: 'api_box_type_show', methods: ['GET'], requirements: ['id' => '[0-9a-f-]{36}'])]
    public function show(string $id): JsonResponse|Response
    {
        $entity = $this->repository->find($id);
        if (!$entity) {
            return $this->json(['error' => 'Not found'], Response::HTTP_NOT_FOUND);
        }
        return $this->json($this->normalize($entity));
    }

    #[Route('', name: 'api_box_type_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse|Response
    {
        $data = $this->decodeBody($request);
        if ($data === null) {
            return $this->json(['error' => 'Invalid JSON'], Response::HTTP_BAD_REQUEST);
        }
        $entity = new BoxType();
        $this->hydrate($entity, $data);
        $em = $this->doctrine->getManager();
        $em->persist($entity);
        $em->flush();
        return $this->json($this->normalize($entity), Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'api_box_type_update', methods: ['PUT', 'PATCH'], requirements: ['id' => '[0-9a-f-]{36}'])]
    public function update(string $id, Request $request): JsonResponse|Response
    {
        $entity = $this->repository->find($id);
        if (!$entity) {
            return $this->json(['error' => 'Not found'], Response::HTTP_NOT_FOUND);
        }
        $data = $this->decodeBody($request);
        if ($data === null) {
            return $this->json(['error' => 'Invalid JSON'], Response::HTTP_BAD_REQUEST);
        }
        $this->hydrate($entity, $data);
        $this->doctrine->getManager()->flush();
        return $this->json($this->normalize($entity));
    }

    #[Route('/{id}', name: 'api_box_type_delete', methods: ['DELETE'], requirements: ['id' => '[0-9a-f-]{36}'])]
    public function delete(string $id): JsonResponse|Response
    {
        $entity = $this->repository->find($id);
        if (!$entity) {
            return $this->json(['error' => 'Not found'], Response::HTTP_NOT_FOUND);
        }
        $this->doctrine->getManager()->remove($entity);
        $this->doctrine->getManager()->flush();
        return new Response('', Response::HTTP_NO_CONTENT);
    }

    private function decodeBody(Request $request): ?array
    {
        $content = $request->getContent();
        if ($content === '') {
            return [];
        }
        $data = json_decode($content, true);
        return is_array($data) ? $data : null;
    }

    private function normalize(BoxType $b): array
    {
        return [
            'id' => $b->getId(),
            'name' => $b->getName(),
            'numberOfLayer' => $b->getNumberOfLayer(),
            'type' => $b->getType(),
            'calculation' => $b->getCalculation(),
        ];
    }

    private function hydrate(BoxType $entity, array $data): void
    {
        if (isset($data['name'])) {
            $entity->setName((string) $data['name']);
        }
        if (isset($data['numberOfLayer'])) {
            $entity->setNumberOfLayer((int) $data['numberOfLayer']);
        }
        if (isset($data['type'])) {
            $entity->setType((string) $data['type']);
        }
        if (isset($data['calculation'])) {
            $entity->setCalculation((string) $data['calculation']);
        }
    }
}
