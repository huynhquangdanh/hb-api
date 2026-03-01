<?php

namespace App\Controller\Api;

use App\Entity\Customer;
use App\Repository\CustomerRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/customers')]
class CustomerApiController extends AbstractController
{
    public function __construct(
        private CustomerRepository $repository,
        private ManagerRegistry $doctrine
    ) {
    }

    #[Route('', name: 'api_customer_list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $items = $this->repository->findBy([], ['name' => 'ASC']);
        return $this->json(array_map([$this, 'normalize'], $items));
    }

    #[Route('/{id}', name: 'api_customer_show', methods: ['GET'], requirements: ['id' => '[0-9a-f-]{36}'])]
    public function show(string $id): JsonResponse|Response
    {
        $entity = $this->repository->find($id);
        if (!$entity) {
            return $this->json(['error' => 'Not found'], Response::HTTP_NOT_FOUND);
        }
        return $this->json($this->normalize($entity));
    }

    #[Route('', name: 'api_customer_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse|Response
    {
        $data = $this->decodeBody($request);
        if ($data === null) {
            return $this->json(['error' => 'Invalid JSON'], Response::HTTP_BAD_REQUEST);
        }
        $entity = new Customer();
        $this->hydrate($entity, $data);
        $em = $this->doctrine->getManager();
        $em->persist($entity);
        $em->flush();
        return $this->json($this->normalize($entity), Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'api_customer_update', methods: ['PUT', 'PATCH'], requirements: ['id' => '[0-9a-f-]{36}'])]
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

    #[Route('/{id}', name: 'api_customer_delete', methods: ['DELETE'], requirements: ['id' => '[0-9a-f-]{36}'])]
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

    private function normalize(Customer $c): array
    {
        return [
            'id' => $c->getId(),
            'name' => $c->getName(),
            'code' => $c->getCode(),
            'taxCode' => $c->getTaxCode(),
            'phone' => $c->getPhone(),
            'email' => $c->getEmail(),
            'address' => $c->getAddress(),
        ];
    }

    private function hydrate(Customer $entity, array $data): void
    {
        if (isset($data['name'])) {
            $entity->setName((string) $data['name']);
        }
        if (isset($data['code'])) {
            $entity->setCode((string) $data['code']);
        }
        if (isset($data['taxCode'])) {
            $entity->setTaxCode((string) $data['taxCode']);
        }
        if (isset($data['phone'])) {
            $entity->setPhone((string) $data['phone']);
        }
        if (isset($data['email'])) {
            $entity->setEmail((string) $data['email']);
        }
        if (array_key_exists('address', $data)) {
            $entity->setAddress($data['address'] === null ? null : (string) $data['address']);
        }
    }
}
