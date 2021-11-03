<?php

namespace JosefGlatz\BeuserFastswitch\Controller;

use JosefGlatz\BeuserFastswitch\Domain\Repository\BackendUserRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Http\HtmlResponse;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Fluid\View\StandaloneView;

class BackendController extends ActionController
{
    /**
     * @param string $search
     * @return QueryResultInterface
     * @throws InvalidQueryException
     * @throws \TYPO3\CMS\Extbase\Object\Exception
     */
    protected function findUserBySearchWord(string $search): QueryResultInterface
    {
        $beusersRepository = GeneralUtility::makeInstance(ObjectManager::class)->get(BackendUserRepository::class);
        return $beusersRepository->findByMultipleProperties($search);
    }

    /**
     * @return QueryResultInterface
     * @throws InvalidQueryException
     * @throws \TYPO3\CMS\Extbase\Object\Exception
     */
    protected function findUsers(): QueryResultInterface
    {
        $beusersRepository = GeneralUtility::makeInstance(ObjectManager::class)->get(BackendUserRepository::class);
        return $beusersRepository->findNonAdmins();
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface|null $response
     * @return ResponseInterface
     * @throws InvalidQueryException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\InvalidExtensionNameException
     * @throws \TYPO3\CMS\Extbase\Object\Exception
     *
     * @noinspection PhpUnused
     */
    public function userLookupAction(ServerRequestInterface $request, ResponseInterface $response = null): ResponseInterface
    {
        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $view->setLayoutRootPaths([
            GeneralUtility::getFileAbsFileName('EXT:beuser_fastswitch/Resources/Private/Layouts'),
        ]);
        $view->setTemplateRootPaths([
            GeneralUtility::getFileAbsFileName('EXT:beuser_fastswitch/Resources/Private/Templates'),
        ]);
        $view->setPartialRootPaths([
            GeneralUtility::getFileAbsFileName('EXT:beuser_fastswitch/Resources/Private/Partials'),
        ]);
        $view->getRequest()->setControllerExtensionName('BeuserFastswitch');
        $view->getRenderingContext()->setControllerName(__CLASS__);
        $view->getRenderingContext()->setControllerAction('userLookup');

        $params = $request->getQueryParams();
        if (isset($params['search']) && !empty($params['search'])) {
            $userList = $this->findUserBySearchWord($params['search']);
        } else {
            $userList = $this->findUsers();
        }

        $view->assignMultiple(
            [
                'users' => $userList,
            ]
        );

        return new HtmlResponse($view->render());
    }
}
