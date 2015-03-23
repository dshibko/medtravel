<?php

namespace Application\Form\Filter;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;

class ClientsInputFilter extends InputFilter
{
    function __construct()
    {
        $factory = new InputFactory();

        $this->add($factory->createInput(array(
            'name'     => 'fio',
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
            'name'     => 'diagnosis',
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
            'name'     => 'contacts',
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
            'name'     => 'dos',
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
            'name'     => 'status',
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
            'name'     => 'country',
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
            'name'     => 'contactType',
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
            'name'     => 'conclusion',
            'required' => false,
            'validators' => array(
                array(
                    'break_chain_on_failure' => true,
                    'name' => '\Zend\Validator\File\Extension',
                    'options' => array(
                        'extension' => 'pdf',
                        'messages' => array(
                            \Zend\Validator\File\Extension::FALSE_EXTENSION => 'Файл должен быть в формате PDF',
                        ),
                    ),
                ),
            )
        )));

        $this->add($factory->createInput(array(
            'name'     => 'informed',
            'required' => false,
        )));
    }
}