<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
* @implements PasswordUpgraderInterface<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

//    /**
//     * @return User[] Returns an array of User objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?User
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
public function findBySearchTermAndSort(string $searchTerm, string $sortOrder)
{
    $qb = $this->createQueryBuilder('u');

    if ($searchTerm) {
        $qb->andWhere('u.name LIKE :searchTerm')
            ->setParameter('searchTerm', '%'.$searchTerm.'%');
    }

    $qb->orderBy('u.id', $sortOrder); // Trier par nom

    return $qb->getQuery()->getResult();
}

public function countUsersByRole(): array
{
    $conn = $this->getEntityManager()->getConnection();
    $sql = "
        SELECT JSON_UNQUOTE(JSON_EXTRACT(users.roles, '$[0]')) AS role, COUNT(*) AS count
        FROM user users
        GROUP BY role
    ";
    $stmt = $conn->prepare($sql);
    $resultSet = $stmt->executeQuery();

    $counts = [];
    foreach ($resultSet->fetchAllAssociative() as $row) {
        // Ici, on mappe les rôles que tu souhaites afficher dans le template
        switch ($row['role']) {
            case 'ROLE_DOCTOR':
                $counts['ROLE_DOCTOR'] = $row['count'];
                break;
            case 'ROLE_PHARMACY':
                $counts['ROLE_PHARMACIEN'] = $row['count'];
                break;
            case 'ROLE_PATIENT':
                $counts['ROLE_PATIENT'] = $row['count'];
                break;
            default:
                // On peut ignorer les autres rôles comme 'ROLE_ADMIN' si on ne les utilise pas
                break;
        }
    }

    return $counts;
}
}