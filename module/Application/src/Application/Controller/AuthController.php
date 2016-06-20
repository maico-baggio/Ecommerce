<?php
namespace Application\Controller;

use Application\Form\Login;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class AuthController extends AbstractActionController
{
    protected $sm;

    public function __construct($sm)
    {
        $this->sm = $sm;
    }

    public function loginAction()
    {
        $this->layout('layout/teste'); 
        $form = new Login();
        $request = $this->getRequest();

        if ($request->isPost()) {
            $data = $request->getPost();
            $this->getServiceLocator()->get('AuthService')->authenticate($data);

            return $this->redirect()->toUrl('/application/index/index');
        }

        return new ViewModel(array(
            'form' => $form
        ));

    }

    public function logoutAction()
    {
        $this->getServiceLocator()->get('AuthService')->logout();

        return $this->redirect()->toUrl('/application/auth/login');
    }

    public function noauthorizeAction()
    {
        // $session = $this->sm->get('Session');

        // var_dump($session->offsetGet('user'));exit;

        // $session = new SessionContainer('Session');
        
        // if (!$session->user) {
            
        //     $this->flashMessenger()->addErrorMessage('Usuário não autenticado');
        //     return $this->redirect()->toUrl(BASE_URL.'/auth/index');
        // }
        
        // if(!$session->offsetGet('programas')){
        //     $this->flashMessenger()->addErrorMessage('Você não tem permissão para acessar este recurso');
        //     return $this->redirect()->toUrl(BASE_URL.'/auth/selecionar-perfil/index');
        // }

        $this->flashMessenger()->addErrorMessage('Você não tem permissão para acessar este recurso');
        return $this->redirect()->toUrl('/application/index/index');


        return new ViewModel(array());
    }

}