<?php

namespace JosefGlatz\BeuserFastswitch\Controller;

use JosefGlatz\BeuserFastswitch\Domain\Repository\BackendUserRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Backend\View\BackendViewFactory;
use TYPO3\CMS\Core\Http\HtmlResponse;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

class BackendController extends ActionController
{
    /*
     * @var BackendUserRepository
     */
    private $backendUserRepository;

    public function __construct(
        BackendUserRepository $backendUserRepository,
        private readonly BackendViewFactory $backendViewFactory,
    )
    {
        $this->backendUserRepository = $backendUserRepository;
    }

    /**
     * @param string $search
     * @return QueryResultInterface
     */
    protected function findUserBySearchWord(string $search): QueryResultInterface
    {
        return $this->backendUserRepository->findByMultipleProperties($search);
    }

    /**
     * @return QueryResultInterface
     */
    protected function findUsers(): QueryResultInterface
    {
        return $this->backendUserRepository->findNonAdmins();
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface|null $response
     * @return ResponseInterface
     *
     * @noinspection PhpUnused
     */
    public function userLookupAction(ServerRequestInterface $request, ResponseInterface $response = null): ResponseInterface
    {
        $view = $this->backendViewFactory->create($request, ['josefglatz/beuser-fastswitch']);

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

        return new HtmlResponse($view->render('UserLookup.html'));
    }
}
