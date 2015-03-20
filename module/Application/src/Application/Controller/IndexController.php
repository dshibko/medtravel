<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Application\Form\LoginForm;
use Application\Manager\ApplicationManager;
use Application\Manager\AuthenticationManager;
use Zend\Authentication\Result;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction() {
        return array('a');
    }

    public function loginAction() {
        $user = ApplicationManager::getInstance($this->getServiceLocator())->getCurrentUser();
        if ($user){
            return $this->redirect()->toRoute('home');
        }

        $request = $this->getRequest();
        $form = new LoginForm();

        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $data = $form->getData();
                $identity = $data['email'];
                $password = $data['password'];
                $result = AuthenticationManager::getInstance($this->getServiceLocator())->authenticate($identity, $password);
                if (in_array($result->getCode(), array(Result::FAILURE_IDENTITY_NOT_FOUND, Result::FAILURE_CREDENTIAL_INVALID))) {
                    $this->flashmessenger()->addErrorMessage('Направильный Email или пароль');
                }
                if ($result->isValid()) {
                    return $this->redirect()->toRoute('dashboard');
                }
            } else {
                $this->formErrors($form, $this);
            }
        }

        $viewModel = new ViewModel(array(
            'form' => $form,
        ));
        $viewModel->setTemplate('layout/login-layout');
        $viewModel->setTerminal(true);
        return $viewModel;
    }

    public function logoutAction()
    {
        try {
            AuthenticationManager::getInstance($this->getServiceLocator())->logout();
            return $this->redirect()->toRoute('login');
        } catch (\Exception $e) {
        }
    }
}
