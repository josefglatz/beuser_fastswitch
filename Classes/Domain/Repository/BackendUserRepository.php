<?php

namespace JosefGlatz\BeuserFastswitch\Domain\Repository;

use TYPO3\CMS\Beuser\Domain\Model\BackendUser;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

class BackendUserRepository extends \TYPO3\CMS\Beuser\Domain\Repository\BackendUserRepository
{
    protected $objectType = BackendUser::class;

    protected $typo3Version;

    public function __construct(Typo3Version $typo3Version)
    {
        $this->typo3Version = $typo3Version->getBranch();
    }

    /**
     * @param string $search
     * @return array|QueryResultInterface
     * @throws InvalidQueryException
     */
    public function findByMultipleProperties(string $search): QueryResultInterface
    {
        $this->objectType = BackendUser::class;

        $queryBuilder = $this->createQuery();

        if ($this->typo3Version === '12.0') {
            $queryBuilder->matching(
                $queryBuilder->logicalAnd(
                    $queryBuilder->equals('admin', 0),
                    $queryBuilder->equals('deleted', 0),
                    $queryBuilder->logicalOr(
                        $queryBuilder->like('username', "%$search%"),
                        $queryBuilder->like('realName', "%$search%"),
                        $queryBuilder->like('email', "%$search%"),
                        $queryBuilder->equals('uid', (int)$search)
                    )
                )
            );
        } else {
            $queryBuilder->matching(
                $queryBuilder->logicalAnd([
                    $queryBuilder->equals('admin', 0),
                    $queryBuilder->equals('deleted', 0),
                    $queryBuilder->logicalOr([
                        $queryBuilder->like('username', "%$search%"),
                        $queryBuilder->like('realName', "%$search%"),
                        $queryBuilder->like('email', "%$search%"),
                        $queryBuilder->equals('uid', (int)$search)
                    ])
                ])
            );
        }

        $queryBuilder->setOrderings([
            'lastlogin' => QueryInterface::ORDER_DESCENDING,
        ]);

        return $queryBuilder->execute();
    }

    /**
     * @param array $uids
     * @return QueryResultInterface
     * @throws InvalidQueryException
     */
    public function findNonAdmins($uids = []): QueryResultInterface
    {
        $this->objectType = BackendUser::class;
        $queryBuilder = $this->createQuery();

        if ($this->typo3Version === '12.0') {
            // @todo Rework conditional "$queryBuilder->in()" constraint if possible
            if (!empty($uids)) {
                $queryBuilder->matching(
                    $queryBuilder->logicalAnd(
                        $queryBuilder->equals('admin', 0),
                        $queryBuilder->equals('deleted', 0),
                        $queryBuilder->in('uid', $uids)
                    )
                );
            } else {
                $queryBuilder->matching(
                    $queryBuilder->logicalAnd(
                        $queryBuilder->equals('admin', 0),
                        $queryBuilder->equals('deleted', 0),
                    )
                );
            }
        } else {
            $constraints[] = $queryBuilder->equals('admin', 0);
            $constraints[] = $queryBuilder->equals('deleted', 0);
            if (!empty($uids)) {
                $constraints[] = $queryBuilder->in('uid', $uids);
            }
            $queryBuilder->matching(
                $queryBuilder->logicalAnd($constraints)
            );
        }


        $queryBuilder->setOrderings([
            'lastlogin' => QueryInterface::ORDER_DESCENDING,
        ]);

        return $queryBuilder->execute();
    }
}
