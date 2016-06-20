<?php

namespace Auth\Form;

use Zend\Form\Element;
use Zend\Form\Form;

class LoginForm extends Form {

    public function __construct() {
        parent::__construct('Login');
        $this->setAttribute('method', 'POST');
        $this->setAttribute('action', '/auth/index/login');
        $this->setAttribute('class', 'form-signin');

        $this->add(array(
            'name' => 'email',
            'attributes' => array(
                'type' => 'Zend\Form\Element\Email',
                'placeholder' => 'Endereço de Email'
            ),
        ));

        $this->add(array(
            'name' => 'senha',
            'attributes' => array(
                'type' => 'password',
                'placeholder' => 'Senha'
            ),
        ));

        $this->add(array(
            'name' => 'Submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Entrar',
                'class' => 'btn submit'
            ),
                )
        );
    }

}

?>
