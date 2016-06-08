<?php

namespace Admin\Controller;

use Admin\Entity\TipoEndereco;
use Admin\Form\TipoEnderecoForm;
use Admin\Validator\TipoEnderecoValidator;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;

/**
 * Controlador para cadastrar novas marcas.
 *
 * @category Admin
 * @package  Controller
 * @author   Maico <email@email.com>
 */
class TipoEnderecosController extends AbstractActionController
{
    public function indexAction()
    {
        $page = (int) $_GET['page'];

        $entityManager = $this->getServiceLocator()
            ->get('Doctrine\ORM\EntityManager');
        $select = $entityManager->createQueryBuilder()
            ->select('TipoEndereco')
            ->from('Admin\Entity\TipoEndereco', 'TipoEndereco')
            ->orderBy('TipoEndereco.descricao', 'ASC');

        $adapter = new DoctrineAdapter(new ORMPaginator($select));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(ItensPerPage);

        if ($page > 0) {
            $paginator->setCurrentPageNumber($page);
        }
        return new ViewModel(
            array
            (
                'tipoEnderecos' => $paginator
            )
        );
    }

    public function saveAction() 
    {
        $entityManager = $this->getServiceLocator()
            ->get('Doctrine\ORM\EntityManager');
        //$session = $this->getService('Session');

        $id = (int) $this->params()->fromRoute('id', 0);

        $form = new TipoEnderecoForm();
        $request = $this->getRequest();

        if ($request->isPost()) {

            $validator = new TipoEnderecoValidator();
            $form->setInputFilter($validator);
            $values = $request->getPost();
            $form->setData($values);

            if ($form->isValid()) {

                $values = $form->getData();

                if ($id) {
                    $tipoEndereco = $entityManager
                        ->find('Admin\Entity\TipoEndereco', $id);
                } else {
                    $tipoEndereco = new TipoEndereco();
                }
                $tipoEndereco->descricao = $values['descricao'];
                
                $entityManager = $this->getServiceLocator()
                    ->get('Doctrine\ORM\EntityManager');
                $entityManager->persist($tipoEndereco);
                
                try {
                    $entityManager->flush();
                    $this->flashMessenger()
                        ->addSuccessMessage('Tipo de endereço cadastrado com sucesso.');
                    return $this->redirect()->toUrl('/admin/tipo-enderecos/index');
                } catch (\Exception $e) {
                    $this->flashMessenger()
                        ->addErrorMessage('Erro ao cadastrar um tipo de endereço.');
                }
            }
        }

        if ($id > 0) {
            try {
                $form = new TipoEnderecoForm();
                $tipoEndereco = $entityManager->find('Admin\Entity\TipoEndereco', $id);
                $form->bind($tipoEndereco);
            } catch (\Exception $e) {
                $this->flashMessenger()
                    ->addErrorMessage('Erro ao tentar editar o tipo de endereço');
                $this->redirect()->toUrl('/admin/tipo-enderecos/index');
            }
        }

        return new ViewModel(
            array
            (
                'form' => $form
            )
        );
    }

    public function deleteAction()
    {
        $id = $this->params()->fromRoute('id', 0);
        $entityManager = $this->getServiceLocator()
            ->get('Doctrine\ORM\EntityManager');

        $tipoEndereco = $entityManager->find('\Admin\Entity\TipoEndereco', $id);
        $entityManager->remove($tipoEndereco);

        try {
            $entityManager->flush();
            $this->flashMessenger()
                ->addSuccessMessage('Tipo de endereço excluido com sucesso.');
            return $this->redirect()->toUrl('/admin/tipo-enderecos/index');
        } catch (\Exception $e) {
            $this->flashMessenger()
                ->addErrorMessage('Erro ao excluir um tipo de endereço.');
                return $this->redirect()->toUrl('/admin/tipo-enderecos/index');
        }
    }

}
