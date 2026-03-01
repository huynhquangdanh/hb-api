<?php

namespace App\Controller\Api;

use App\Entity\Release;
use App\Repository\ReleaseRepository;
use App\Repository\ProductRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/releases')]
class ReleaseApiController extends AbstractController
{
    public function __construct(
        private ReleaseRepository $repository,
        private ProductRepository $productRepository,
        private ManagerRegistry $doctrine
    ) {
    }

    #[Route('', name: 'api_release_list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $items = $this->repository->findBy([], ['releaseDate' => 'DESC']);
        return $this->json(array_map([$this, 'normalize'], $items));
    }

    #[Route('/{id}', name: 'api_release_show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(int $id): JsonResponse|Response
    {
        $entity = $this->repository->find($id);
        if (!$entity) {
            return $this->json(['error' => 'Not found'], Response::HTTP_NOT_FOUND);
        }
        return $this->json($this->normalize($entity));
    }

    #[Route('', name: 'api_release_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse|Response
    {
        $data = $this->decodeBody($request);
        if ($data === null) {
            return $this->json(['error' => 'Invalid JSON'], Response::HTTP_BAD_REQUEST);
        }
        if (empty($data['itemId'])) {
            return $this->json(['error' => 'itemId is required'], Response::HTTP_BAD_REQUEST);
        }
        if (!isset($data['releaseDate']) || $data['releaseDate'] === '') {
            return $this->json(['error' => 'releaseDate is required'], Response::HTTP_BAD_REQUEST);
        }
        if (!array_key_exists('quantity', $data)) {
            return $this->json(['error' => 'quantity is required'], Response::HTTP_BAD_REQUEST);
        }
        $entity = new Release();
        $err = $this->hydrate($entity, $data);
        if ($err !== null) {
            return $this->json($err, Response::HTTP_BAD_REQUEST);
        }
        $em = $this->doctrine->getManager();
        $em->persist($entity);
        $em->flush();
        return $this->json($this->normalize($entity), Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'api_release_update', methods: ['PUT', 'PATCH'], requirements: ['id' => '\d+'])]
    public function update(int $id, Request $request): JsonResponse|Response
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

    #[Route('/{id}', name: 'api_release_delete', methods: ['DELETE'], requirements: ['id' => '\d+'])]
    public function delete(int $id): JsonResponse|Response
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

    private function normalize(Release $r): array
    {
        return [
            'id' => $r->getId(),
            'itemId' => $r->getProduct()?->getId(),
            'releaseDate' => $r->getReleaseDate()?->format('Y-m-d'),
            'quantity' => $r->getQuantity(),
        ];
    }

    /** @return array{error: string}|null */
    private function hydrate(Release $entity, array $data): ?array
    {
        if (isset($data['releaseDate'])) {
            $entity->setReleaseDate(new \DateTimeImmutable($data['releaseDate']));
        }
        if (isset($data['quantity'])) {
            $entity->setQuantity((int) $data['quantity']);
        }
        if (isset($data['itemId'])) {
            $product = $this->productRepository->find($data['itemId']);
            if (!$product) {
                return ['error' => 'Product not found'];
            }
            $entity->setProduct($product);
        }
        return null;
    }
}
