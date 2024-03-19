<?php

namespace App\Repository;

use App\Entity\Wallet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Wallet>
 *
 * @method Wallet|null find($id, $lockMode = null, $lockVersion = null)
 * @method Wallet|null findOneBy(array $criteria, array $orderBy = null)
 * @method Wallet[]    findAll()
 * @method Wallet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WalletRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Wallet::class);
    }


        public function getAverageDetainedAssets()
        {
            $rsm = new ResultSetMapping();
            $rsm->addScalarResult('moyenne_nbAssets', 'avg_assets_nb');
            return $this->getEntityManager()
                ->createNativeQuery('
                    SELECT CEILING(AVG(nbAssets)) AS moyenne_nbAssets 
                    FROM (
                        SELECT wallet.id, COUNT(asset.id) AS nbAssets 
                        FROM asset 
                        INNER JOIN wallet ON wallet.id = asset.wallet_id 
                        GROUP BY asset.wallet_id
                    ) AS subquery;
                ', $rsm)
                ->getResult();
        }

    public function getAverageCapital(): array
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('moyenne_capital', 'avg_capital');
        return $this->getEntityManager()
            ->createNativeQuery('
                    SELECT CEILING(AVG(somme)) AS moyenne_capital
                    FROM (
                        SELECT wallet.id,sum(asset.volume * currency.value) as somme FROM asset
                        INNER JOIN wallet ON wallet.id = asset.wallet_id 
                        INNER JOIN currency ON currency.id = asset.currency_id 
                        GROUP BY asset.wallet_id
                    ) as subquery;
                ', $rsm)
            ->getResult();

    }

    //    public function findOneBySomeField($value): ?Wallet
    //    {
    //        return $this->createQueryBuilder('w')
    //            ->andWhere('w.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
