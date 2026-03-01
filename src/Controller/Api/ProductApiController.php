<?php

namespace App\Controller\Api;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Repository\OrderRepository;
use App\Repository\BoxTypeRepository;
use App\Repository\QualityPresetRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/products')]
class ProductApiController extends AbstractController
{
    public function __construct(
        private ProductRepository $repository,
        private OrderRepository $orderRepository,
        private BoxTypeRepository $boxTypeRepository,
        private QualityPresetRepository $qualityPresetRepository,
        private ManagerRegistry $doctrine
    ) {
    }

    #[Route('', name: 'api_product_list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $items = $this->repository->findBy([], ['id' => 'ASC']);
        return $this->json(array_map([$this, 'normalize'], $items));
    }

    #[Route('/{id}', name: 'api_product_show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(int $id): JsonResponse|Response
    {
        $entity = $this->repository->find($id);
        if (!$entity) {
            return $this->json(['error' => 'Not found'], Response::HTTP_NOT_FOUND);
        }
        return $this->json($this->normalize($entity));
    }

    #[Route('', name: 'api_product_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse|Response
    {
        $data = $this->decodeBody($request);
        if ($data === null) {
            return $this->json(['error' => 'Invalid JSON'], Response::HTTP_BAD_REQUEST);
        }
        foreach (['orderId', 'typeBoxId', 'presetId'] as $key) {
            if (empty($data[$key])) {
                return $this->json(['error' => $key . ' is required'], Response::HTTP_BAD_REQUEST);
            }
        }
        $entity = new Product();
        $err = $this->hydrate($entity, $data);
        if ($err !== null) {
            return $this->json($err, Response::HTTP_BAD_REQUEST);
        }
        $em = $this->doctrine->getManager();
        $em->persist($entity);
        $em->flush();
        return $this->json($this->normalize($entity), Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'api_product_update', methods: ['PUT', 'PATCH'], requirements: ['id' => '\d+'])]
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

    #[Route('/{id}', name: 'api_product_delete', methods: ['DELETE'], requirements: ['id' => '\d+'])]
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

    private function normalize(Product $p): array
    {
        return [
            'id' => $p->getId(),
            'name' => $p->getName(),
            'code' => $p->getCode(),
            'size' => $p->getSize(),
            'orderId' => $p->getOrder()?->getId(),
            'typeBoxId' => $p->getBoxType()?->getId(),
            'presetId' => $p->getPreset()?->getId(),
        ];
    }

    /** @return array{error: string}|null */
    private function hydrate(Product $entity, array $data): ?array
    {
        if (isset($data['name'])) {
            $entity->setName((string) $data['name']);
        }
        if (isset($data['code'])) {
            $entity->setCode((string) $data['code']);
        }
        if (isset($data['size'])) {
            $entity->setSize((float) $data['size']);
        }
        if (isset($data['orderId'])) {
            $order = $this->orderRepository->find($data['orderId']);
            if (!$order) {
                return ['error' => 'Order not found'];
            }
            $entity->setOrder($order);
        }
        if (isset($data['typeBoxId'])) {
            $boxType = $this->boxTypeRepository->find($data['typeBoxId']);
            if (!$boxType) {
                return ['error' => 'Box type not found'];
            }
            $entity->setBoxType($boxType);
        }
        if (isset($data['presetId'])) {
            $preset = $this->qualityPresetRepository->find($data['presetId']);
            if (!$preset) {
                return ['error' => 'Quality preset not found'];
            }
            $entity->setPreset($preset);
        }
        return null;
    }
}
