<?php

namespace Application\Form\Filter;

use Zend\InputFilter\Factory as InputFactory;

class UserInputFilter extends LoginInputFilter
{
    function __construct()
    {
        parent::__construct();
        $factory = new InputFactory();

        $this->add($factory->createInput(array(
            'name'     => 'role',
            'required' => true,
            'validators' => array(
                array(
                    'break_chain_on_failure' => true,
                    'name' => 'NotEmpty',
                    'options' => array(
                        'messages' => array(
                            \Zend\Validator\NotEmpty::IS_EMPTY => 'Обязательное поле',
                        ),
                    ),
                ),
            )
        )));

        $this->add($factory->createInput(array(
            'name'     => 'displayName',
            'required' => true,
            'validators' => array(
                array(
                    'break_chain_on_failure' => true,
                    'name' => 'NotEmpty',
                    'options' => array(
                        'messages' => array(
                            \Zend\Validator\NotEmpty::IS_EMPTY => 'Обязательное поле',
                        ),
                    ),
                ),
            )
        )));
    }
}