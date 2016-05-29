<?php

namespace Admin\Controller;

use Admin\Entity\Marca;
use Admin\Form\MarcaForm;
use Admin\Validator\MarcaValidator;
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
 * @author  Maico <e-mail>
 */
class MarcasController extends AbstractActionController {

    public function indexAction() {
        $page = (int) $_GET['page'];
        $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $select = $entityManager->createQueryBuilder()
                ->select('Marca')
                ->from('Admin\Entity\Marca', 'Marca')
                ->orderBy('Marca.descricao', 'ASC');

        $adapter = new DoctrineAdapter(new ORMPaginator($select));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(ItensPerPage);

        if ($page > 0)
            $paginator->setCurrentPageNumber($page);

        return new ViewModel(
                array('marcas' => $paginator)
        );
    }

    public function saveAction() {

        $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        //$session = $this->getService('Session');

        $id = (int) $this->params()->fromRoute('id', 0);

        $form = new MarcaForm();
        $request = $this->getRequest();

        if ($request->isPost()) {

            $validator = new MarcaValidator();
            $form->setInputFilter($validator);
            $values = $request->getPost();
            $form->setData($values);

            if ($form->isValid()) {

                $values = $form->getData();

                if ($id) {
                    $marca = $entityManager->find('Admin\Entity\Marca', $id);
                } else {
                    $marca = new Marca();
                }

                $marca->descricao = $values['descricao'];
                $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
                $entityManager->persist($marca);
                try {
                    $entityManager->flush();
                    $this->flashMessenger()->addSuccessMessage('Marca cadastrada com sucesso.');
                    return $this->redirect()->toUrl('/admin/marcas/index');
                } catch (\Exception $e) {
                    $this->flashMessenger()->addErrorMessage('Erro ao cadastrar marca.');
                }
            }
        }

        if ($id > 0) {
            try {
                $form = new MarcaForm();
                $marca = $entityManager->find('Admin\Entity\Marca', $id);
                $form->bind($marca);
            } catch (\Exception $e) {
                $this->flashMessenger()->addErrorMessage('Erro ao tentar editar a marca');
                $this->redirect()->toUrl('/admin/marcas/index');
            }
        }

        return new ViewModel(array(
            'form' => $form
        ));

//-------------------------------------
//        $form = new MarcaForm();
//        $request = $this->getRequest();
//
//        if ($request->isPost()) {
//            $validator = new MarcaValidator();
//            $form->setInputFilter($validator);
//            $values = $request->getPost();
//            $form->setData($values);
//
//            if ($form->isValid()) {
//                $values = $form->getData();
//                $marca = new Marca();
//                $marca->descricao = $values['descricao'];
//                $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
//                $entityManager->persist($marca);
//
//                try {
//                    $entityManager->flush();
//                    $this->flashMessenger()->addSuccessMessage('Marca cadastrada com sucesso.');
//                    return $this->redirect()->toUrl('/admin/marcas/index');
//                } catch (\Exception $e) {
//                    $this->flashMessenger()->addErrorMessage('Erro ao cadastrar marca.');
//                }
//
//                //return $this->redirect()->toUrl('/Admin/marcas');
//            }
//        }
//        return new ViewModel(array(
//            'form' => $form
//        ));
    }

//    public function updateAction() {
//        $id = $this->params()->fromRoute('id', 0);
//        $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
//        $request = $this->getRequest();
//
//        if ($request->isPost()) {
//            $values = $request->getPost();
//            $marca = $entityManager->find('\Admin\Entity\Marca', $id);
//            $marca->descricao = $values['descricao'];
//            $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
//            $entityManager->persist($marca);
//            $entityManager->flush();
//
//            return $this->redirect()->toUrl('/Admin/marcas');
//        }
//
//        if ($id > 0) {
//            $form = new MarcaForm();
//            $marca = $entityManager->find('\Admin\Entity\Marca', $id);
//            $form->bind($marca);
//
//            return new ViewModel(array('form' => $form));
//        }
//
//        $this->request->setStatusCode(404);
//        return $this->request;
//    }

    public function deleteAction() {
        $id = $this->params()->fromRoute('id', 0);
        $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $marca = $entityManager->find('\Admin\Entity\Marca', $id);
        $entityManager->remove($marca);

        try {
            $entityManager->flush();
            $this->flashMessenger()->addSuccessMessage('Marca excluída com sucesso.');
            return $this->redirect()->toUrl('/admin/marcas/index');
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage('Erro ao excluir marca, verifique os vinculos ou contate o administrador.');
            return $this->redirect()->toUrl('/admin/marcas/index');
        }
    }

}
