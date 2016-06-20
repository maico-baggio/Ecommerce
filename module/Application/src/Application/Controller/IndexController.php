<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    // protected $sm;

    // public function __construct($sm)
    // {
    //     $this->sm = $sm;
    // }

    public function indexAction()
    {
        // $session = $this->sm->get('Session');

        // var_dump($session->offsetGet('user'));exit;


         // /$this->layout('layout/teste');
        //return $this->redirect()->toUrl('/application/auth/login');

        return new ViewModel(array(
        ));
    }

        public function logadoAction()
    {
        // $session = $this->sm->get('Session');

        // var_dump($session->offsetGet('user'));exit;


         // /$this->layout('layout/teste');
        //return $this->redirect()->toUrl('/application/auth/login');

        return new ViewModel(array(
        ));
    }
}
