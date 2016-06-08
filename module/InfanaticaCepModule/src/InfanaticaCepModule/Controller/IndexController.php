<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace InfanaticaCepModule\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController {

    public function indexAction() {
        
        $cep = $this->params()->fromRoute('id', 0);

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
