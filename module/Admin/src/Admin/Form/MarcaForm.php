<?php

namespace Admin\Form;

use Zend\Form\Form;

/**
 * Form para cadastrar marca
 * @category Admin
 * @package form
 * @author Maico <e-mail>
 */
class MarcaForm extends Form {

    public function __construct() {
        parent::__construct('MarcaForm');
        $this->setAttribute('method', 'POST');
        $this->add(
                array(
                    'name' => 'id',
                    'type' => 'hidden'
                )
        );
        $this->add(
                array(
                    'name' => 'descricao',
                    'type' => 'text',
                    'options' => array(
                        'label' => 'Marca*:'
                    ),
                    'attributes' => array(
                        'placeholder' => 'Informe uma marca aqui',
                        'class' => 'form-control'
                    ),
                )
        );
        $this->add(array(
            'name' => 'salvar',
            'type' => 'submit',
            'attributes' => array(
                'value' => 'Salvar',
                'class' => 'btn btn-primary'
            )
        ));

        $this->add(array(
            'type' => 'button',
            'name' => 'cancelar',
            'attributes' => array(
                'value' => 'Cancelar',
                'class' => 'btn',
                'onclick' => "location.href='/admin/marcas/index'",
                'title' => 'Cancelar'
            ),
            'options' => array(
                'label' => 'Cancelar'
            )
        ));
    }

}
