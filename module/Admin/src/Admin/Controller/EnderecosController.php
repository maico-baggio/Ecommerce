<?php
namespace Admin\Controller;

use Admin\Entity\Endereco;
use Admin\Form\EnderecoForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Admin\Validator\EnderecoValidator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;

/**
 * Controlador para cadastrar novos endereços
 *
 * @category Admin
 * @package  Controller
 * @author   Maico
 */
class EnderecosController extends AbstractActionController
{

    public function indexAction()
    {
        $page = (int) $_GET['page'];
        $entityManager = $this->getServiceLocator()
            ->get('Doctrine\ORM\EntityManager');
        $select = $entityManager->createQueryBuilder()
            ->select('Endereco')
            ->from('Admin\Entity\Endereco', 'Endereco')
            ->orderBy('Endereco.cidade', 'ASC');

        $adapter = new DoctrineAdapter(new ORMPaginator($select));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(ItensPerPage);

        if ($page > 0){
            $paginator->setCurrentPageNumber($page);
        }

        return new ViewModel(
            array(
                'enderecos' => $paginator
            )
        );
    }

        public function saveAction() 
    {
        $entityManager = $this->getServiceLocator()
            ->get('Doctrine\ORM\EntityManager');
        //$session = $this->getService('Session');

        $id = (int) $this->params()->fromRoute('id', 0);

        $form = new EnderecoForm($entityManager);
        $request = $this->getRequest();

        if ($request->isPost()) {

            $validator = new EnderecoValidator();
            $form->setInputFilter($validator);
            $values = $request->getPost();
            $form->setData($values);

            if ($form->isValid()) {

                $values = $form->getData();

                if ($id) {
                    $endereco = $entityManager
                        ->find('Admin\Entity\Endereco', $id);
                } else {
                    $endereco = new Endereco();
                }
                $endereco->nome_do_destinatario = $values['nome_do_destinatario'];
                $endereco->telefone = $values['telefone'];
                $endereco->cep = $values['cep'];
                $endereco->numero = $values['numero'];
                $endereco->endereco = $values['endereco'];
                $endereco->complemento = $values['complemento'];
                $endereco->bairro = $values['bairro'];
                $endereco->informacao_referencia = $values['informacao_referencia'];
                $endereco->cidade = $values['cidade'];
                $endereco->estado = $values['estado'];
                $endereco->tipo_endereco = $entityManager
                    ->find('\Admin\Entity\TipoEndereco', $values['tipo_endereco']);
                
                $entityManager = $this->getServiceLocator()
                    ->get('Doctrine\ORM\EntityManager');
                $entityManager->persist($endereco);
                
                try {
                    $entityManager->flush();
                    $this->flashMessenger()
                        ->addSuccessMessage('Endereço cadastrado com sucesso.');
                    return $this->redirect()->toUrl('/admin/enderecos/index');
                } catch (\Exception $e) {
                    $this->flashMessenger()
                        ->addErrorMessage('Erro ao cadastrar endereco.');
                }
            }
        }

        if ($id > 0) {
            try {
                $form = new EnderecoForm($entityManager);
                $endereco = $entityManager
                    ->find('Admin\Entity\Endereco', $id);

                $form->bind($endereco);
            } catch (\Exception $e) {
                $this->flashMessenger()
                    ->addErrorMessage('Erro ao tentar editar o endereco');
                $this->redirect()->toUrl('/admin/enderecos/index');
            }
        }
        return new ViewModel(
            array
            (
                'form' => $form
            )
        );
    }
    
    public function deleteAction() {
        $id = $this->params()->fromRoute('id', 0);
        $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $endereco = $entityManager->find('\Admin\Entity\Endereco', $id);
        $entityManager->remove($endereco);

        try {
            $entityManager->flush();
            $this->flashMessenger()->addSuccessMessage('Endereço excluido com sucesso');
            return $this->redirect()->toUrl('/admin/enderecos/index');
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage('Erro ao excluir endereço');
        }
    }

    public function dataAction()
    {
        $cep = $this->params()->fromRoute('cep', 0);
        $formato = 'json';
        $serviceLocator = $this->getServiceLocator();
        $cepService = $serviceLocator->get('InfanaticaCepModule\Service\CepService');
        $endereco = $cepService->getEnderecoByCep($cep, $formato);

        $this->response->setContent($endereco);
        return $this->response;
    }
}