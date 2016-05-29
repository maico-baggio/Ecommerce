<?php

namespace Admin\Controller;

use Admin\Entity\Categoria;
use Admin\Form\CategoriaForm;
use Admin\Validator\CategoriaValidator;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;

/**
 * Controlador para cadastrar novas categorias.
 *
 * @category Admin
 * @package Controller
 * @author  Maico <e-amil>
 */
class CategoriasController extends AbstractActionController {

    public function indexAction() {

        $page = (int) $_GET['page'];
        $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $select = $entityManager->createQueryBuilder()
                ->select('Categoria')
                ->from('Admin\Entity\Categoria', 'Categoria')
                ->orderBy('Categoria.descricao', 'ASC');

        $adapter = new DoctrineAdapter(new ORMPaginator($select));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(ItensPerPage);

        if ($page > 0)
            $paginator->setCurrentPageNumber($page);

        return new ViewModel(
                array('categorias' => $paginator)
        );
    }

    public function createAction() {
        $form = new CategoriaForm();
        $request = $this->getRequest();
        if ($request->isPost()) {
            $validator = new CategoriaValidator();
            $form->setInputFilter($validator);
            $values = $request->getPost();
            $form->setData($values);

            if ($form->isValid()) {
                $values = $form->getData();
                $categoria = new Categoria();
                $categoria->descricao = $values['descricao'];
                $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
                $entityManager->persist($categoria);
                $entityManager->flush();

                return $this->redirect()->toUrl('/admin/categorias');
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
            $categoria = $entityManager->find('\Admin\Entity\Categoria', $id);
            $categoria->descricao = $values['descricao'];
            $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
            $entityManager->persist($categoria);
            $entityManager->flush();

            return $this->redirect()->toUrl('/admin/categorias');
        }

        if ($id > 0) {
            $form = new CategoriaForm();
            $categoria = $entityManager->find('\Admin\Entity\Categoria', $id);
            $form->bind($categoria);

            return new ViewModel(array('form' => $form));
        }

        $this->request->setStatusCode(404);

        return $this->request;
    }

    public function deleteAction() {
        $id = $this->params()->fromRoute('id', 0);
        $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $categoria = $entityManager->find('\Admin\Entity\Categoria', $id);
        $entityManager->remove($categoria);

        try {
            $entityManager->flush();
            $this->flashMessenger()->addSuccessMessage('Categoria excluída com sucesso.');
            return $this->redirect()->toUrl('/admin/categorias');
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage('Erro ao excluir categoria, verifique os vinculos ou contate o administrador.');
            return $this->redirect()->toUrl('/admin/categorias');
        }
        
    }

}
