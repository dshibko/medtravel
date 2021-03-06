<?php

namespace Application\Form\Filter;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;

class LoginInputFilter extends InputFilter
{
    function __construct()
    {
        $factory = new InputFactory();
        $this->add($factory->createInput(array(
            'name'     => 'email',
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
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
                array(
                    'break_chain_on_failure' => true,
                    'name'    => 'EmailAddress',
                    'options' => array(),
                ),
            )
        )));

        $this->add($factory->createInput(array(
            'name'     => 'password',
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
				array(
					'name' => 'StringLength',
					'options' => array(
						'encoding' => 'UTF-8',
					),
				),
            )
        )));
/*
        $this->add($factory->createInput(array(
            'name'     => 'rememberme',
            'required' => false,
        )));
*/
    }
}