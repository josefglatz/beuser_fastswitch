<?php

namespace JosefGlatz\BeuserFastswitch\Controller;

use JosefGlatz\BeuserFastswitch\Domain\Repository\BackendUserRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Fluid\View\StandaloneView;

class BackendController extends ActionController
{
    protected function findUserBySearchWord(string $search): QueryResultInterface
    {
        $beusersRepository = GeneralUtility::makeInstance(ObjectManager::class)->get(BackendUserRepository::class);
        return $beusersRepository->findByUsernameOrRealName($search);
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function userLookupAction(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
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
        $view->getRenderingContext()->setControllerName(BackendController::class);
        $view->getRenderingContext()->setControllerAction('userLookup');

        $params = $request->getQueryParams();
        $userList = $this->findUserBySearchWord($params['search']);
        $view->assign('users', $userList);

        $response->getBody()->write($view->render());
        $response = $response->withHeader('Content-Type', 'text/html; charset=utf-8');
        return $response;
    }
}