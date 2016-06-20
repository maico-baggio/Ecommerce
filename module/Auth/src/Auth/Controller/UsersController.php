<?php

namespace Auth\Controller;

use Auth\Form\UserForm;
use Auth\Entity\User;
use Auth\Validator\UserValidator;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;

class UsersController extends AbstractActionController
{
    // protected $sl;

    // public function __construct($sl)
    // {
    //     $this->sl = $sl;
    // }

    public function indexAction()
    {
        
        $page = (int) $_GET['page'];
        $entityManager = $this->getServiceLocator()
            ->get('Doctrine\ORM\EntityManager');
        $select = $entityManager->createQueryBuilder()
            ->select('User')
            ->from('Auth\Entity\User', 'User')
            ->orderBy('User.nome', 'ASC');

        $adapter = new DoctrineAdapter(new ORMPaginator($select));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(ItensPerPage);

        if ($page > 0){
            $paginator->setCurrentPageNumber($page);
        }

        return new ViewModel(
            array(
                'users' => $paginator
            )
        );
    }

    public function saveAction()
    {
        $entityManager = $this->getServiceLocator()
            ->get('Doctrine\ORM\EntityManager');
        //$session = $this->getService('Session');

        $id = (int) $this->params()->fromRoute('id', 0);

        $form = new UserForm();
        $request = $this->getRequest();

        if ($request->isPost()) {

            $validator = new UserValidator();
            $form->setInputFilter($validator);
            $values = $request->getPost();
            $form->setData($values);

            if ($form->isValid()) {

                $values = $form->getData();

                if ($id) {
                    $user = $entityManager
                        ->find('Auth\Entity\User', $id);
                } else {
                    $user = new User();
                }
                $user->nome = $values['nome'];
                $user->login = $values['login'];
                $user->email = $values['email'];
                $user->password = md5($values['password']);
                $user->role = $values['role'];
                $user->active = true;

                
                $entityManager = $this->getServiceLocator()
                    ->get('Doctrine\ORM\EntityManager');
                $entityManager->persist($user);
                
                try {
                    $entityManager->flush();
                    $this->flashMessenger()
                        ->addSuccessMessage('Usuário cadastrado com sucesso.');
                    return $this->redirect()->toUrl('/auth/users/index');
                } catch (\Exception $e) {
                    $this->flashMessenger()
                        ->addErrorMessage('Erro ao cadastrar usuário.');
                }
            }
        }

        if ($id > 0) {
            try {
                $form = new UserForm();
                $user = $entityManager
                    ->find('Auth\Entity\User', $id);

                $form->bind($user);
            } catch (\Exception $e) {
                $this->flashMessenger()
                    ->addErrorMessage('Erro ao tentar editar usuário');
                $this->redirect()->toUrl('/auth/users/index');
            }
        }
        return new ViewModel(
            array
            (
                'form' => $form
            )
        );
    }

    // public function updateAction()
    // {

    //     $id_user = $this->params()->fromRoute('id', 0);
    //     $form = new UserForm();
    //     $request = $this->getRequest();

    //     if ($request->isPost()){
    //         $data = array_merge_recursive(
    //             $request->getPost()->toArray(),
    //             $request->getFiles()->toArray()
    //         );
    //         //var_dump($request->getFiles()); exit;
    //         $form->setData($data);

    //         if ($form->isValid()) {
    //             $data = $form->getData();
    //             $this->sl->get('UserService')
    //                 ->update($id_user, $data);

    //             return $this->redirect()->toUrl('/admin/users/index');
    //         }
    //     }

    //     $user = $this->sl->get('UserService')
    //         ->fetch($id_user);
    //     $form->bind($user);

    //     return new ViewModel(
    //         array(
    //             'form' => $form
    //         )
    //     );
    // }

}