<?php

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController {

    public function indexAction() {
        
        //$cep = $this->params()->fromRoute('id', 0);
        $cep = (int) $this->params()->fromRoute('cep', 0);
        
        var_dump($cep);exit;

        // Possíveis formatos (json, xml, query, object, array)
        // null = \InfanaticaCepModule\Response\EnderecoResponse
        $formato = 'json';
        $serviceLocator = $this->getServiceLocator();
        $cepService = $serviceLocator->get('InfanaticaCepModule\Service\CepService');
        $endereco = $cepService->getEnderecoByCep($cep, $formato);
        $this->response->setContent($endereco);

        return $this->response;
    }

    public function getCepAction() {
//        $cep = $this->params()->fromRoute('id', 0);
//
//        // Possíveis formatos (json, xml, query, object, array)
//        // null = \InfanaticaCepModule\Response\EnderecoResponse
//        $formato = 'json';
//        $serviceLocator = $this->getServiceLocator();
//        $cepService = $serviceLocator->get('InfanaticaCepModule\Service\CepService');
//        $endereco = $cepService->getEnderecoByCep($cep, $formato);
//        $this->response->setContent($endereco);
//
//        return $this->response;
    }

}
