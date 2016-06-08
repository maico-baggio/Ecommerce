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
 * @package Controller
 * @author  Maico
 */
class EnderecosController extends AbstractActionController {

    public function indexAction() {

        $page = (int) $_GET['page'];
        $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $select = $entityManager->createQueryBuilder()
                ->select('Endereco')
                ->from('Admin\Entity\Endereco', 'Endereco')
                //->join('Produto.marca', 'Marca')
                //->join('Produto.subcategoria', 'SubCategoria')
                //->join('Produto.modelo', 'Modelo')
                //->join('Produto.categorias', 'Categoria')
                ->orderBy('Endereco.cidade', 'ASC');

        $adapter = new DoctrineAdapter(new ORMPaginator($select));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(ItensPerPage);

        if ($page > 0)
            $paginator->setCurrentPageNumber($page);

        return new ViewModel(
                array('enderecos' => $paginator)
        );
    }

    public function createAction() {
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $form = new EnderecoForm($em);
        $request = $this->getRequest();

        if ($request->isPost()) {

            $validator = new EnderecoValidator();
            $form->setInputFilter($validator);
            $values = $request->getPost();
            $form->setData($values);

            if ($form->isValid()) {
                $values = $form->getData();

                $endereco = new Endereco();

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
                $endereco->tipo_endereco = $em->find('\Admin\Entity\TipoEndereco', $values['tipo_endereco']);

                $em->persist($endereco);

                try {
                    $em->flush();
                    $this->flashMessenger()->addSuccessMessage('Endereço inserido com sucesso');
                    $this->view->resp = "Produto,  " . $endereco->nome_do_destinatario . ", enviado com sucesso!";

                    return $this->redirect()->toUrl('/admin/enderecos/index');
                } catch (\Exception $e) {
                    $this->flashMessenger()->addErrorMessage('Erro ao inserir endereço');
                }
            }
        }

        return new ViewModel(array(
            'form' => $form
        ));
    }

    public function updateAction() {
        $id = $this->params()->fromRoute('id', 0);
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $request = $this->getRequest();

        if ($request->isPost()) {
            $values = $request->getPost();
            $produto = $em->find('\Admin\Entity\Produto', $id);

            $produto->marca = $em->find('\Admin\Entity\Marca', $values['marca']);
            $produto->nome = $values['nome'];
            $produto->descricao = $values['descricao'];
            $produto->valor = $values['valor'];
            $produto->margem_de_lucro = $values['margem_de_lucro'];
            $produto->modelo = $em->find('\Admin\Entity\Modelo', $values['modelo']);
            $produto->subcategoria = $em->find('\Admin\Entity\SubCategoria', $values['subcategoria']);

            $produto->categorias->clear();

            foreach ($values['categorias'] as $categoria)
                $produto->categorias->add($em->find('\Admin\Entity\Categoria', $categoria));

            //$em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
            $em->persist($produto);


            try {
                $em->flush();
                $this->flashMessenger()->addSuccessMessage('Produto editado com sucesso');
                //$this->view->resp = "Produto,  " . $produto->nome. ", enviado com sucesso!";

                return $this->redirect()->toUrl('/admin/produtos/index');
            } catch (\Exception $e) {
                $this->flashMessenger()->addErrorMessage('Erro ao editar produto');
            }
            //$em->flush();
            //return $this->redirect()->toUrl('/admin/produtos');
        }

        if ($id > 0) {
            $form = new ProdutoForm($em);
            $produto = $em->find('\Admin\Entity\Produto', $id);
            $form->bind($produto);

            return new ViewModel(array('form' => $form));
        }

        $this->request->setStatusCode(404);

        return $this->request;
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

    public function dataAction() {
        
        //$cep = $this->params()->fromRoute('id', 0);
        $cep = $this->params()->fromRoute('cep', 0);
        // Possíveis formatos (json, xml, query, object, array)
        // null = \InfanaticaCepModule\Response\EnderecoResponse
        $formato = 'json';
        $serviceLocator = $this->getServiceLocator();
        $cepService = $serviceLocator->get('InfanaticaCepModule\Service\CepService');
        $endereco = $cepService->getEnderecoByCep($cep, $formato);

        $this->response->setContent($endereco);
        //var_dump();exit;

        return $this->response;
    }

}
