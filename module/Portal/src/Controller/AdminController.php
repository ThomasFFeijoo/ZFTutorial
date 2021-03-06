<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Portal\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use ZFT\Migrations\Migrations;
use ZFT\User;

class AdminController extends AbstractActionController
{
    /** @var User\Repository */
    //private $userRepository;

    /** @var Migrations  */
    private $migrations;

//    public function __construct(User\Repository $userRepository) {
//        $this->userRepository = $userRepository;
//    }

    public function __construct(Migrations $migrations) {
        $this->migrations = $migrations;
    }

    public function indexAction()
    {
        //$user = new User();
        //$user = $this->userRepository->getUserById(5);

        return [
            'needsMigration' => $this->migrations->needsUpdate()
        ];
    }

    public function runmigrationsAction() {
        $this->migrations->run();
    }
}
