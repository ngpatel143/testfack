<?php

namespace Ibillmaker\Hub\CoreBundle\Repository;

use Sylius\Bundle\CoreBundle\Doctrine\ORM\UserRepository as BaseUserRepository;
use Symfony\Component\Security\Core\SecurityContextInterface;

class UserRepository extends BaseUserRepository 
{

    protected $user;
    protected $adminUser;

    public function setUserViaSecurityContext(SecurityContextInterface $securityContext) 
    {
        $this->user = $securityContext->getToken()->getUser();
        $this->adminUser = $this->user->getAdmin();
        if ($this->adminUser == NULL) {        
                $this->adminUser = $this->user;
        }
    }

    /**
     * Create filter paginator.
     *
     * @param array $criteria
     * @param array $sorting
     *
     * @return PagerfantaInterface
     */
    public function createFilterPaginator($criteria = array(), $sorting = array(), $deleted = false) 
    {
        $queryBuilder = parent::getCollectionQueryBuilder();

        if ($deleted) {
            $this->_em->getFilters()->disable('softdeleteable');
        }
        

        if (isset($criteria['query'])) {
            $queryBuilder
                    ->where('o.username LIKE :query')
                    ->orWhere('o.email LIKE :query')
                    ->innerJoin('o.admin', 'admin')
                    ->andWhere('admin = :admin')
                    ->setParameter('admin', $this->user)
                    ->setParameter('query', '%' . $criteria['query'] . '%')

            ;
        } else {
            $queryBuilder
                        ->innerJoin('o.admin', 'admin')
                        ->andWhere('admin = :admin')
                        ->setParameter('admin', $this->adminUser)
                ;
        }

        if (isset($criteria['enabled'])) {
            $queryBuilder
                    ->andWhere('o.enabled = :enabled')
                    ->innerJoin('o.admin', 'admin')
                    ->andWhere('admin = :admin')
                    ->setParameter('admin', $this->adminUser)
                    ->setParameter('enabled', $criteria['enabled'])
            ;
        }

        if (empty($sorting)) {
            if (!is_array($sorting)) {
                $sorting = array();
            }
            $sorting['updatedAt'] = 'desc';
        }

        // $this->applySorting($queryBuilder, $sorting);

        return $this->getPaginator($queryBuilder);
    }

    /**
     * Get the user data for the details page.
     *
     * @param integer $id
     */
    public function findForDetailsPage($id) {
        $queryBuilder = $this->getQueryBuilder();

        $this->_em->getFilters()->disable('softdeleteable');

        $queryBuilder
                ->andWhere($queryBuilder->expr()->eq('o.id', ':id'))
                ->innerJoin('o.admin', 'admin')
                ->andWhere('admin = :admin')
                ->setParameter('admin', $this->adminUser)
                ->setParameter('id', $id)
        ;

        $result = $queryBuilder
                ->getQuery()
                ->getOneOrNullResult()
        ;

        return $result;
    }

    public function countBetweenDates(\DateTime $from, \DateTime $to, $status = null) {
        $queryBuilder = $this->getCollectionQueryBuilderBetweenDates($from, $to);
        if (null !== $status) {
            $queryBuilder
                    ->innerJoin('o.admin', 'admin')
                    ->andWhere('admin = :admin')
                    ->setParameter('admin', $this->adminUser)
                    ->andWhere('o.status = :status')
                    ->setParameter('status', $status)
            ;
        }

        return $queryBuilder
                        ->select('count(o.id)')
                        ->innerJoin('o.admin', 'admin')
                        ->andWhere('admin = :admin')
                        ->setParameter('admin', $this->adminUser)
                        ->getQuery()
                        ->getSingleScalarResult()
        ;
    }

    protected function getCollectionQueryBuilderBetweenDates(\DateTime $from, \DateTime $to) {
        $queryBuilder = $this->getCollectionQueryBuilder();
        return $queryBuilder
            ->andWhere($queryBuilder->expr()->gte('o.createdAt', ':from'))
            ->andWhere($queryBuilder->expr()->lte('o.createdAt', ':to'))
            ->setParameter('from', $from)
            ->setParameter('to', $to)
        ;
//        return $queryBuilder
//                        ->andWhere($queryBuilder->expr()->gte('o.createdAt', ':from'))
//                        ->andWhere($queryBuilder->expr()->lte('o.createdAt', ':to'))
//                        ->innerJoin('o.admin', 'admin')
//                        ->andWhere('admin = :admin')
//                        ->setParameter('admin', $this->adminUser)
//                        ->setParameter('from', $from)
//                        ->setParameter('to', $to)
//        ;
    }

}
