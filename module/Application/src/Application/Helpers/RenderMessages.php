<?php

namespace Application\Helpers;

use Zend\View\Helper\AbstractHelper;
use \Zend\Mvc\Controller\Plugin\FlashMessenger;

class RenderMessages extends AbstractHelper
{
    /**
     * @param bool $admin
     * @param bool $current
     * @return string
     */
    public function __invoke($admin = false, $current = false)
    {
        $prefix = $this->getHTMLPrefix($admin);
        $html = '';
        $flashMessenger = new FlashMessenger();
        foreach ($current ? $flashMessenger->getCurrentErrorMessages() : $flashMessenger->getErrorMessages() as $message)
            $html .= $admin ? $this->renderAdminMessage($message, 'alert-error') : $this->renderAppMessage($message);
        foreach ($current ? $flashMessenger->getCurrentSuccessMessages() : $flashMessenger->getSuccessMessages() as $message)
            $html .= $admin ? $this->renderAdminMessage($message, 'alert-success') : $this->renderAppMessage($message);
        foreach ($current ? $flashMessenger->getCurrentMessages() : $flashMessenger->getMessages() as $message)
            $html .= $admin ? $this->renderAdminMessage($message, 'alert-info') : $this->renderAppMessage($message);
        $postfix = $this->getHTMLPostfix($admin);
        return !empty($html) ? ($prefix . $html . $postfix) : '';
    }

    private function renderAdminMessage($message, $class) {
        return '<div class="alert ' . $class . ' hide" style="display: block;">
            <button class="close" data-dismiss="alert"></button>
            <span>' . $message . '</span>
        </div>';
    }

    private function renderAppMessage($message) {
        return '<li>' . $message . '</li>';
    }

    private function getHTMLPrefix($admin) {
        return $admin ? '' : '<ul>';
    }
    
    private function getHTMLPostfix($admin) {
        return $admin ? '' : '</ul>';
    }
    
}