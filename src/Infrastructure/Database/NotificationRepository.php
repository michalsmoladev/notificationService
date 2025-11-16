<?php

namespace App\Infrastructure\Database;

use App\Domain\Entity\Notification;
use App\Domain\Entity\NotificationRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Uid\Uuid;

class NotificationRepository implements NotificationRepositoryInterface
{
    private EntityRepository $repository;

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
        $this->repository = $this->entityManager->getRepository(Notification::class);
    }

    public function save(Notification $notification): void
    {
        $this->entityManager->persist($notification);
        $this->entityManager->flush();
    }

    public function findById(Uuid $id): ?Notification
    {
        return $this->repository->findOneBy(['id' => $id]);
    }
}
