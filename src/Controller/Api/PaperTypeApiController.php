<?php

namespace App\Controller\Api;

use App\Entity\PaperType;
use App\Repository\PaperTypeRepository;
use App\Repository\ProviderRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/paper-types')]
class PaperTypeApiController extends AbstractController
{
    public function __construct(
        private PaperTypeRepository $repository,
        private ProviderRepository $providerRepository,
        private ManagerRegistry $doctrine
    ) {
    }

    #[Route('', name: 'api_paper_type_list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $items = $this->repository->findBy([], ['name' => 'ASC']);
        return $this->json(array_map([$this, 'normalize'], $items));
    }

    #[Route('/{id}', name: 'api_paper_type_show', methods: ['GET'], requirements: ['id' => '[0-9a-f-]{36}'])]
    public function show(string $id): JsonResponse|Response
    {
        $entity = $this->repository->find($id);
        if (!$entity) {
            return $this->json(['error' => 'Not found'], Response::HTTP_NOT_FOUND);
        }
        return $this->json($this->normalize($entity));
    }

    #[Route('', name: 'api_paper_type_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse|Response
    {
        $data = $this->decodeBody($request);
        if ($data === null) {
            return $this->json(['error' => 'Invalid JSON'], Response::HTTP_BAD_REQUEST);
        }
        if (empty($data['providerId'])) {
            return $this->json(['error' => 'providerId is required'], Response::HTTP_BAD_REQUEST);
        }
        $entity = new PaperType();
        $err = $this->hydrate($entity, $data);
        if ($err !== null) {
            return $this->json($err, Response::HTTP_BAD_REQUEST);
        }
        $em = $this->doctrine->getManager();
        $em->persist($entity);
        $em->flush();
        return $this->json($this->normalize($entity), Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'api_paper_type_update', methods: ['PUT', 'PATCH'], requirements: ['id' => '[0-9a-f-]{36}'])]
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
        $err = $this->hydrate($entity, $data);
        if ($err !== null) {
            return $this->json($err, Response::HTTP_BAD_REQUEST);
        }
        $this->doctrine->getManager()->flush();
        return $this->json($this->normalize($entity));
    }

    #[Route('/{id}', name: 'api_paper_type_delete', methods: ['DELETE'], requirements: ['id' => '[0-9a-f-]{36}'])]
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

    private function normalize(PaperType $p): array
    {
        return [
            'id' => $p->getId(),
            'name' => $p->getName(),
            'rollSize' => $p->getRollSize(),
            'buster' => $p->getBuster(),
            'providerId' => $p->getProvider()?->getId(),
        ];
    }

    /** @return array{error: string}|null */
    private function hydrate(PaperType $entity, array $data): ?array
    {
        if (isset($data['name'])) {
            $entity->setName((string) $data['name']);
        }
        if (isset($data['rollSize'])) {
            $entity->setRollSize((float) $data['rollSize']);
        }
        if (isset($data['buster'])) {
            $entity->setBuster((float) $data['buster']);
        }
        if (isset($data['providerId'])) {
            $provider = $this->providerRepository->find($data['providerId']);
            if (!$provider) {
                return ['error' => 'Provider not found'];
            }
            $entity->setProvider($provider);
        }
        return null;
    }
}
