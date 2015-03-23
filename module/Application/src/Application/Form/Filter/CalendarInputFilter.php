<?php

namespace Application\Form\Filter;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;

class CalendarInputFilter extends InputFilter
{
    function __construct()
    {
        $factory = new InputFactory();
        $this->add($factory->createInput(array(
            'name'     => 'title',
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
            )
        )));

        $this->add($factory->createInput(array(
            'name'     => 'description',
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
            'name'     => 'date',
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