<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class OrderProcessor implements ProcessorInterface
{
    public function __construct(
        #[Autowire(service: 'api_platform.doctrine.orm.state.persist_processor')]
        private ProcessorInterface $persistProcessor,
        private EntityManagerInterface $entityManager
    ) {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        // Check if we are handling an Order entity and it's a POST request (creating a new order)
        if ($data instanceof Order && $operation instanceof \ApiPlatform\Metadata\Post && !$data->getManufacturingNumber()) {
            
            $month = date('m');
            
            // Find all orders ending with this month's suffix to find the absolute highest sequence number
            $ordersThisMonth = $this->entityManager->createQueryBuilder()
                ->select('o.manufacturingNumber')
                ->from(Order::class, 'o')
                ->where('o.manufacturingNumber LIKE :suffix')
                ->setParameter('suffix', '%/' . $month)
                ->getQuery()
                ->getArrayResult();

            $maxSequence = 0;
            foreach ($ordersThisMonth as $row) {
                if (!empty($row['manufacturingNumber'])) {
                    $parts = explode('/', $row['manufacturingNumber']);
                    $seq = (int)$parts[0];
                    if ($seq > $maxSequence) {
                        $maxSequence = $seq;
                    }
                }
            }

            $nextNumber = $maxSequence + 1;

            // Pad the sequence to at least 2 digits (1 -> 01, 2 -> 02, 100 -> 100)
            $paddedNumber = str_pad((string)$nextNumber, 2, '0', STR_PAD_LEFT);
            $manufacturingNumber = "$paddedNumber/$month";

            // Set the generated number on the entity
            $data->setManufacturingNumber($manufacturingNumber);
        }

        // Forward the processing to the standard Doctrine Persist Processor to actually save it!
        return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }
}
