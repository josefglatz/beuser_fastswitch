<?php

namespace JosefGlatz\BeuserFastswitch\Domain\Repository;

use TYPO3\CMS\Beuser\Domain\Model\BackendUser;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

class BackendUserRepository extends \TYPO3\CMS\Beuser\Domain\Repository\BackendUserRepository
{
    protected $objectType = BackendUser::class;

    public function initializeObject()
    {
        parent::initializeObject();
    }

    /**
     * @param string $search
     * @return array|QueryResultInterface
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException
     */
    public function findByUsernameOrRealName(string $search): QueryResultInterface
    {
        $this->objectType = BackendUser::class;

        $queryBuilder = $this->createQuery();

        $queryBuilder->matching(
            $queryBuilder->logicalAnd([
                $queryBuilder->equals('admin', 0),
                $queryBuilder->logicalOr([
                    $queryBuilder->like('username', "%$search%"),
                    $queryBuilder->like('realName', "%$search%")
                ])
            ])
        );

        return $queryBuilder->execute();
    }

    public function findNonAdminsByUids(array $uids): QueryResultInterface
    {
        $this->objectType = BackendUser::class;

        $queryBuilder = $this->createQuery();

        $queryBuilder->matching(
            $queryBuilder->logicalAnd([
                $queryBuilder->equals('admin', 0),
                $queryBuilder->in('uid', $uids)
            ])
        );

        return $queryBuilder->execute();
    }
}