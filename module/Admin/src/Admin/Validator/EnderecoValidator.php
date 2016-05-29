<?php

namespace Admin\Validator;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;

class EnderecoValidator extends InputFilter {

    public function __construct() {
        $factory = new InputFactory();
        $this->add($factory->createInput(array(
            'name' => 'id',
            'required' => true,
            'filters' => array(
                array('name' => 'Int'),
                ),
            )));
        $this->add($factory->createInput(array(
         'name' => 'nome_do_destinatario',
         'required' => true,
         'filters' => array(
             array('name' => 'StripTags'),
             array('name' => 'StringTrim'),
             array('name' => 'StringToUpper',
                 'options' => array('encoding' => 'UTF-8')
                 ),
             ),
         'validators' => array(
             array(
                 'name' => 'StringLength',
                 'options' => array(
                     'encoding' => 'UTF-8',
                     'min' => 3,
                     'max' => 255,
                     ),
                 ),
             array(
                 'name' => 'NotEmpty',
                 )),
         )));

        $this->add($factory->createInput(array(
            'name' => 'tipo_endereco',
            'required' => false,
            'filters' => array(
                array('name' => 'Int'),
                ),
            )));

        $this->add($factory->createInput(array(
            'name' => 'cep',
            'required' => true,
            'filters' => array(
                array('name' => 'Int'),
                ),
            )));

        $this->add($factory->createInput(array(
            'name' => 'endereco',
            'required' => true,
            'filters' => array(
             array('name' => 'StripTags'),
             array('name' => 'StringTrim'),
             array('name' => 'StringToUpper',
                 'options' => array('encoding' => 'UTF-8')
                 ),
             ),
            'validators' => array(
             array(
                 'name' => 'StringLength',
                 'options' => array(
                     'encoding' => 'UTF-8',
                     'min' => 3,
                     'max' => 255,
                     ),
                 ),
             array(
                 'name' => 'NotEmpty',
                 )),
            )));
        $this->add($factory->createInput(array(
            'name' => 'numero',
            'required' => true,
            'filters' => array(
                array('name' => 'Int'),
                ),
            )));


        // $this->add($factory->createInput(array(
        //     'name' => 'numero',
        //     'required' => true,
        //     'filters' => array(
        //        array('name' => 'StripTags'),
        //        array('name' => 'StringTrim'),
        //        array('name' => 'StringToUpper',
        //            'options' => array('encoding' => 'UTF-8')
        //            ),
        //        ),
        //     'validators' => array(
        //        array(
        //            'name' => 'StringLength',
        //            'options' => array(
        //                'encoding' => 'UTF-8',
        //                'min' => 1,
        //                'max' => 10,
        //                ),
        //            ),
        //        array(
        //            'name' => 'NotEmpty',
        //            )),
        //     )));

        $this->add($factory->createInput(array(
            'name' => 'complemento',
            'required' => true,
            'filters' => array(
               array('name' => 'StripTags'),
               array('name' => 'StringTrim'),
               array('name' => 'StringToUpper',
                   'options' => array('encoding' => 'UTF-8')
                   ),
               ),
            'validators' => array(
               array(
                   'name' => 'StringLength',
                   'options' => array(
                       'encoding' => 'UTF-8',
                       'min' => 1,
                       'max' => 255,
                       ),
                   ),
               array(
                   'name' => 'NotEmpty',
                   )),
            )));
        $this->add($factory->createInput(array(
            'name' => 'informacao_referencia',
            'required' => false,
            'filters' => array(
               array('name' => 'StripTags'),
               array('name' => 'StringTrim'),
               array('name' => 'StringToUpper',
                   'options' => array('encoding' => 'UTF-8')
                   ),
               ),
            'validators' => array(
               array(
                   'name' => 'StringLength',
                   'options' => array(
                       'encoding' => 'UTF-8',
                       'min' => 0,
                       'max' => 255,
                       ),
                   ),
               array(
                   'name' => 'NotEmpty',
                   )),
            )));
        $this->add($factory->createInput(array(
            'name' => 'bairro',
            'required' => true,
            'filters' => array(
               array('name' => 'StripTags'),
               array('name' => 'StringTrim'),
               array('name' => 'StringToUpper',
                   'options' => array('encoding' => 'UTF-8')
                   ),
               ),
            'validators' => array(
               array(
                   'name' => 'StringLength',
                   'options' => array(
                       'encoding' => 'UTF-8',
                       'min' => 1,
                       'max' => 255,
                       ),
                   ),
               array(
                   'name' => 'NotEmpty',
                   )),
            )));

        $this->add($factory->createInput(array(
            'name' => 'cidade',
            'required' => true,
            'filters' => array(
               array('name' => 'StripTags'),
               array('name' => 'StringTrim'),
               array('name' => 'StringToUpper',
                   'options' => array('encoding' => 'UTF-8')
                   ),
               ),
            'validators' => array(
               array(
                   'name' => 'StringLength',
                   'options' => array(
                       'encoding' => 'UTF-8',
                       'min' => 1,
                       'max' => 255,
                       ),
                   ),
               array(
                   'name' => 'NotEmpty',
                   )),
            )));

        $this->add($factory->createInput(array(
            'name' => 'estado',
            'required' => true,
            'filters' => array(
             array('name' => 'StripTags'),
             array('name' => 'StringTrim'),
             array('name' => 'StringToUpper',
                 'options' => array('encoding' => 'UTF-8')
                 ),
             ),
            'validators' => array(
             array(
                 'name' => 'StringLength',
                 'options' => array(
                     'encoding' => 'UTF-8',
                     'min' => 1,
                     'max' => 255,
                     ),
                 ),
             array(
                 'name' => 'NotEmpty',
                 )),
            )));

        $this->add($factory->createInput(array(
            'name' => 'telefone',
            'required' => true,
            'filters' => array(
             array('name' => 'StripTags'),
             array('name' => 'StringTrim'),
             array('name' => 'StringToUpper',
                 'options' => array('encoding' => 'UTF-8')
                 ),
             ),
            'validators' => array(
             array(
                 'name' => 'StringLength',
                 'options' => array(
                     'encoding' => 'UTF-8',
                     'min' => 8,
                     'max' => 15,
                     ),
                 ),
             array(
                 'name' => 'NotEmpty',
                 )),
            )));
    }

}
