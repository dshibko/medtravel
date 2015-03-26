<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Application\DAO\ClientsDAO;
use Application\DAO\UserDAO;
use Application\Form\LoginForm;
use Application\Manager\ApplicationManager;
use Application\Manager\AuthenticationManager;
use Zend\Authentication\Result;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction() {
        if (ApplicationManager::getInstance($this->getServiceLocator())->getCurrentUser()) {
            return $this->redirect()->toRoute('dashboard');
        } else {
            return $this->redirect()->toRoute('login');
        }
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
                    $form->setMessages(array('email' => array('Нeправильный Email или пароль')));
                }
                if ($result->isValid()) {
                    return $this->redirect()->toRoute('dashboard');
                }
            } else {
                $form->setMessages(array('email' => array('Нeправильный Email или пароль')));
            }
        }

        $viewModel = new ViewModel(array(
            'form' => $form,
        ));
        $viewModel->setTemplate('layout/login-layout');
        $viewModel->setTerminal(true);
        return $viewModel;
    }

    public function logoutAction() {
        try {
            AuthenticationManager::getInstance($this->getServiceLocator())->logout();
            return $this->redirect()->toRoute('login');
        } catch (\Exception $e) {}
    }

    public function dashboardAction() {
        $clients = ClientsDAO::getInstance($this->getServiceLocator())->getAllClients();

        $stats['date'] = array();
        $stats['status'] = array(
            'Не обработан' => 0,
            'В работе' => 0,
            'Согласование' => 0,
            'Архив' => 0,
            'Пролечен' => 0,
            'Записан в календарь' => 0,
            'Сорвался' => 0);
        $users = UserDAO::getInstance($this->getServiceLocator())->getAllUsers();

        foreach ($users as $user) {
            $stats['manager'][$user->getDisplayName()] = 0;
        }

        if (!empty($clients)) {
            foreach ($clients as $client) {
                $formattedDate = $client->getDateAdded()->format('Y-m-d');
                $stats['date'][$formattedDate] = isset($stats['date'][$formattedDate]) ? $stats['date'][$formattedDate] + 1 : 1;
                $stats['manager'][$client->getManager()->getDisplayName()] += 1;
                $stats['status'][$client->getStatus()] += 1;
            }
        }

        return array('stats' => $stats);
    }
}
