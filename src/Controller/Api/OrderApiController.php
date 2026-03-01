<?php

namespace App\Controller\Api;

use App\Entity\Order;
use App\Repository\OrderRepository;
use App\Repository\CustomerRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/orders')]
class OrderApiController extends AbstractController
{
    public function __construct(
        private OrderRepository $repository,
        private CustomerRepository $customerRepository,
        private ManagerRegistry $doctrine
    ) {
    }

    #[Route('', name: 'api_order_list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $items = $this->repository->findBy([], ['orderDate' => 'DESC']);
        return $this->json(array_map([$this, 'normalize'], $items));
    }

    #[Route('/{id}', name: 'api_order_show', methods: ['GET'], requirements: ['id' => '[0-9a-f-]{36}'])]
    public function show(string $id): JsonResponse|Response
    {
        $entity = $this->repository->find($id);
        if (!$entity) {
            return $this->json(['error' => 'Not found'], Response::HTTP_NOT_FOUND);
        }
        return $this->json($this->normalize($entity));
    }

    #[Route('', name: 'api_order_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse|Response
    {
        $data = $this->decodeBody($request);
        if ($data === null) {
            return $this->json(['error' => 'Invalid JSON'], Response::HTTP_BAD_REQUEST);
        }
        if (empty($data['customerId'])) {
            return $this->json(['error' => 'customerId is required'], Response::HTTP_BAD_REQUEST);
        }
        $entity = new Order();
        $err = $this->hydrate($entity, $data);
        if ($err !== null) {
            return $this->json($err, Response::HTTP_BAD_REQUEST);
        }
        $em = $this->doctrine->getManager();
        $em->persist($entity);
        $em->flush();
        return $this->json($this->normalize($entity), Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'api_order_update', methods: ['PUT', 'PATCH'], requirements: ['id' => '[0-9a-f-]{36}'])]
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

    #[Route('/{id}', name: 'api_order_delete', methods: ['DELETE'], requirements: ['id' => '[0-9a-f-]{36}'])]
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

    private function normalize(Order $o): array
    {
        return [
            'id' => $o->getId(),
            'orderNumber' => $o->getOrderNumber(),
            'orderDate' => $o->getOrderDate()?->format('Y-m-d'),
            'deliveryDate' => $o->getDeliveryDate()?->format('Y-m-d'),
            'historyOfReleasing' => $o->getHistoryOfReleasing(),
            'customerId' => $o->getCustomer()?->getId(),
        ];
    }

    /** @return array{error: string}|null */
    private function hydrate(Order $entity, array $data): ?array
    {
        if (isset($data['orderNumber'])) {
            $entity->setOrderNumber((string) $data['orderNumber']);
        }
        if (isset($data['orderDate'])) {
            $entity->setOrderDate(new \DateTimeImmutable($data['orderDate']));
        }
        if (isset($data['deliveryDate'])) {
            $entity->setDeliveryDate(new \DateTimeImmutable($data['deliveryDate']));
        }
        if (isset($data['historyOfReleasing']) && is_array($data['historyOfReleasing'])) {
            $entity->setHistoryOfReleasing($data['historyOfReleasing']);
        }
        if (isset($data['customerId'])) {
            $customer = $this->customerRepository->find($data['customerId']);
            if (!$customer) {
                return ['error' => 'Customer not found'];
            }
            $entity->setCustomer($customer);
        }
        return null;
    }
}
