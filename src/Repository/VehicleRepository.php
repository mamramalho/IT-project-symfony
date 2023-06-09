<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Vehicle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;
use function PHPUnit\Framework\equalTo;
use function PHPUnit\Framework\equalToIgnoringCase;

/**
 * @extends ServiceEntityRepository<Vehicle>
 *
 * @method Vehicle|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vehicle|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vehicle[]    findAll()
 * @method Vehicle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VehicleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vehicle::class);
    }

    public function search(string $text = null, string $city = null, UserInterface $user = null): array
    {
        $queryBuilder = $this->createQueryBuilder('v');
 
        if ($text) {
             $queryBuilder->andWhere('v.name = :text OR v.company = :text OR v.model = :text')
                 ->setParameter('text', $text);
        }

         if ($city && $city != 'Poland') {
             $queryBuilder->andWhere('v.city = :city')
                 ->setParameter('city', $city);
         }

         if ($user) {
            $queryBuilder->andWhere('v.user = :user')
                ->setParameter('user', $user);
        }

         return $queryBuilder->getQuery()->getResult();
    } 

    public function save(Vehicle $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Vehicle $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
//    public function findOneBySomeField($value): ?Vehicle
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
