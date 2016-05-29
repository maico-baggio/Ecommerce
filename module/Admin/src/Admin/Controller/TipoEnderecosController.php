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
 * @package Controller
 * @author  Maico Baggio <maico.baggio@unochapeco.edu.br>
 */
class TipoEnderecosController extends AbstractActionController {

    public function indexAction() {
        $page = (int) $_GET['page'];

        $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
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
                array('tipoEnderecos' => $paginator)
        );
    }

    public function createAction() {
        $form = new TipoEnderecoForm();
        $request = $this->getRequest();

        if ($request->isPost()) {
            $validator = new TipoEnderecoValidator();
            $form->setInputFilter($validator);
            $values = $request->getPost();
            $form->setData($values);

            if ($form->isValid()) {
                $values = $form->getData();
                $tipoEndereco = new TipoEndereco();
                $tipoEndereco->descricao = $values['descricao'];
                $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
                $entityManager->persist($tipoEndereco);
                $entityManager->flush();

                return $this->redirect()->toUrl('/admin/tipo-enderecos');
            }
        }

        return new ViewModel(array(
            'form' => $form
        ));
    }

    public function updateAction() {
        $id = $this->params()->fromRoute('id', 0);
        $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $request = $this->getRequest();

        if ($request->isPost()) {
            $values = $request->getPost();
            $tipoEndereco = $entityManager->find('\Admin\Entity\TipoEndereco', $id);
            $tipoEndereco->descricao = $values['descricao'];
            $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
            $entityManager->persist($tipoEndereco);
            $entityManager->flush();

            return $this->redirect()->toUrl('/admin/tipo-enderecos');
        }

        if ($id > 0) {
            $form = new TipoEnderecoForm();
            $tipoEndereco = $entityManager->find('\Admin\Entity\TipoEndereco', $id);
            $form->bind($tipoEndereco);
            return new ViewModel(array('form' => $form));
        }

        $this->request->setStatusCode(404);
        return $this->request;
    }

    public function deleteAction() {
        $id = $this->params()->fromRoute('id', 0);
        $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $tipoEndereco = $entityManager->find('\Admin\Entity\TipoEndereco', $id);
        $entityManager->remove($tipoEndereco);
        $entityManager->flush();

        return $this->redirect()->toUrl('/admin/tipo-enderecos');
    }

}
