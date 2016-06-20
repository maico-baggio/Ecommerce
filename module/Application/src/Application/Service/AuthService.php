<?php
namespace Application\Service;

use Zend\Authentication\AuthenticationService;

class AuthService
{
    protected $sm;

    public function __construct($sm)
    {
        $this->sm = $sm;
    }

    public function authenticate($params)
    {
        $password =  md5($params['password']);
        $authService = $this->sm->get('Zend\Authentication\AuthenticationService');
        $authAdapter = $authService->getAdapter();
        $authAdapter->setIdentity($params['login'])->setCredential($password);
        $result = $authService->authenticate();

        if (!$result->isValid())
            throw new \Exception("Login ou senha inválidos");

        $session = $this->sm->get('Session');
        $session->offsetSet('user', $result->getIdentity());
        $session->offsetSet('role', $result->getIdentity()->role);

        return true;
    }

    public function logout()
    {
        $auth = new AuthenticationService();
        $session = $this->sm->get('Session');
        $session->offsetUnset('user');
        $session->offsetUnset('role');
        $auth->clearIdentity();

        return true;
    }

    public function authorize($controllerName, $actionName)
    {
        $auth = new AuthenticationService();
        $role = 'UNAUTHENTICATED';

        if ($auth->hasIdentity()) {
            $session = $this->sm->get('Session');

            if (!$session->offsetGet('role'))
                $role = 'UNAUTHENTICATED';
            else
                $role = $session->offsetGet('role');
        }

        $resource = $controllerName . '.' . $actionName;
        $acl = $this->sm->get('ACLService')->build();

        if ($acl->isAllowed($role, $resource))
            return true;

        return false;
    }

}