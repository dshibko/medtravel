<?php

namespace Application\Form;


use Zend\Form\Element;
use Zend\Form\Fieldset;
use Zend\Form\Form;
use Zend\Mvc\Controller\Plugin\FlashMessenger;

abstract class AbstractForm extends Form {

    public function __construct($name = null) {
        parent::__construct($name);
    }

    public function handleErrorMessages($messages, FlashMessenger $messenger, $parentEl = null, $prefix = '') {
        if ($parentEl === null) $parentEl = $this;
        foreach ($messages as $name => $message) {
            if (is_string($message))
                $messenger->addErrorMessage($message);
            else {
                $targetEl = $parentEl->get($name);
                if ($targetEl instanceof Fieldset)
                    $this->handleErrorMessages($message, $messenger, $targetEl, $prefix . $targetEl->getName() . ", ");
                else if ($targetEl instanceof Element)
                    $messenger->addErrorMessage($prefix . $targetEl->getLabel() . ": " . implode(", ", $message));
            }
        }
    }
} 