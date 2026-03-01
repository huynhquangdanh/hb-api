<?php

namespace App\Controller\Api;

use App\Entity\QualityPreset;
use App\Repository\QualityPresetRepository;
use App\Repository\CustomerRepository;
use App\Repository\PaperTypeRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/quality-presets')]
class QualityPresetApiController extends AbstractController
{
    public function __construct(
        private QualityPresetRepository $repository,
        private CustomerRepository $customerRepository,
        private PaperTypeRepository $paperTypeRepository,
        private ManagerRegistry $doctrine
    ) {
    }

    #[Route('', name: 'api_quality_preset_list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $items = $this->repository->findBy([], ['name' => 'ASC']);
        return $this->json(array_map([$this, 'normalize'], $items));
    }

    #[Route('/{id}', name: 'api_quality_preset_show', methods: ['GET'], requirements: ['id' => '[0-9a-f-]{36}'])]
    public function show(string $id): JsonResponse|Response
    {
        $entity = $this->repository->find($id);
        if (!$entity) {
            return $this->json(['error' => 'Not found'], Response::HTTP_NOT_FOUND);
        }
        return $this->json($this->normalize($entity));
    }

    #[Route('', name: 'api_quality_preset_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse|Response
    {
        $data = $this->decodeBody($request);
        if ($data === null) {
            return $this->json(['error' => 'Invalid JSON'], Response::HTTP_BAD_REQUEST);
        }
        foreach (['customerId', 'facePaperId', 'basePaperId', 'fluteId', 'assemblyId'] as $key) {
            if (empty($data[$key])) {
                return $this->json(['error' => $key . ' is required'], Response::HTTP_BAD_REQUEST);
            }
        }
        $entity = new QualityPreset();
        $err = $this->hydrate($entity, $data);
        if ($err !== null) {
            return $this->json($err, Response::HTTP_BAD_REQUEST);
        }
        $em = $this->doctrine->getManager();
        $em->persist($entity);
        $em->flush();
        return $this->json($this->normalize($entity), Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'api_quality_preset_update', methods: ['PUT', 'PATCH'], requirements: ['id' => '[0-9a-f-]{36}'])]
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

    #[Route('/{id}', name: 'api_quality_preset_delete', methods: ['DELETE'], requirements: ['id' => '[0-9a-f-]{36}'])]
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

    private function normalize(QualityPreset $q): array
    {
        return [
            'id' => $q->getId(),
            'name' => $q->getName(),
            'customerId' => $q->getCustomer()?->getId(),
            'facePaperId' => $q->getFacePaper()?->getId(),
            'basePaperId' => $q->getBasePaper()?->getId(),
            'fluteId' => $q->getFlute()?->getId(),
            'assemblyId' => $q->getAssembly()?->getId(),
        ];
    }

    /** @return array{error: string}|null */
    private function hydrate(QualityPreset $entity, array $data): ?array
    {
        if (isset($data['name'])) {
            $entity->setName((string) $data['name']);
        }
        if (isset($data['customerId'])) {
            $customer = $this->customerRepository->find($data['customerId']);
            if (!$customer) {
                return ['error' => 'Customer not found'];
            }
            $entity->setCustomer($customer);
        }
        if (isset($data['facePaperId'])) {
            $paper = $this->paperTypeRepository->find($data['facePaperId']);
            if (!$paper) {
                return ['error' => 'facePaperId not found'];
            }
            $entity->setFacePaper($paper);
        }
        if (isset($data['basePaperId'])) {
            $paper = $this->paperTypeRepository->find($data['basePaperId']);
            if (!$paper) {
                return ['error' => 'basePaperId not found'];
            }
            $entity->setBasePaper($paper);
        }
        if (isset($data['fluteId'])) {
            $paper = $this->paperTypeRepository->find($data['fluteId']);
            if (!$paper) {
                return ['error' => 'fluteId not found'];
            }
            $entity->setFlute($paper);
        }
        if (isset($data['assemblyId'])) {
            $paper = $this->paperTypeRepository->find($data['assemblyId']);
            if (!$paper) {
                return ['error' => 'assemblyId not found'];
            }
            $entity->setAssembly($paper);
        }
        return null;
    }
}
