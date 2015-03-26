<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Application\DAO\RoleDAO;
use Application\DAO\UserDAO;
use Application\Entity\User;
use Application\Form\UserForm;
use Zend\Mvc\Controller\AbstractActionController;

class UsersController extends AbstractActionController
{

    public function indexAction() {
        $userDAO = UserDAO::getInstance($this->getServiceLocator());
        $users = $userDAO->getAllUsers();

        return array('users' => $users);
    }

    public function editAction() {
        $form = new UserForm();
        $userId = (int)$this->params()->fromRoute('id', '');
        $request = $this->getRequest();
        $view = $request->getQuery()->view;
        $userDAO = UserDAO::getInstance($this->getServiceLocator());
        if (!empty($userId)) {
            $editableUser = $userDAO->findOneById($userId);
            if ($editableUser !== null) {
                if (!$request->isPost()) {
                    $userData = array(
                        'displayName' => $editableUser->getDisplayName(),
                        'email' => $editableUser->getEmail(),
                        'password' => $editableUser->getPassword(),
                        'role' => $editableUser->getRole()->getId(),
                    );
                    $form->setData($userData);
                    $form->setAttribute('action', '/users/edit/'.$editableUser->getId());
                }
            } else {
                return $this->redirect()->toRoute('users');
            }
        }

        if ($request->isPost()) {
            $post = $request->getPost()->toArray();
            $post['password'] = $editableUser !== null ? $editableUser->getPassword() : md5($post['password']);
            $form->setData($post);

            if ($form->isValid()) {
                $data = $form->getData();

                $userData = $editableUser !== null ? $editableUser : new User();
                $userData->setDisplayName($data['displayName']);
                $userData->getEmail($data['email']);
                $userData->setPassword($data['password']);
                $userData->setRole(RoleDAO::getInstance($this->getServiceLocator())->findOneById($data['role']));

                $userDAO->save($userData);
                return $this->redirect()->toRoute('users');
            } else {
                $form->getMessages();
            }
        }

        if ($editableUser) {
            $form->get('password')->setAttribute('disabled', 'disabled');
            $form->get('password')->setAttribute('type', 'password');
        }

        return array('form' => $form, 'view' => $view);
    }

    public function addAction() {
        $form = new UserForm();
        $request = $this->getRequest();
        $userDAO = UserDAO::getInstance($this->getServiceLocator());

        if ($request->isPost()) {
            $post = $request->getPost()->toArray();
            $form->setData($post);

            if ($form->isValid()) {
                $data = $form->getData();

                $userData = new User();
                $userData->setDisplayName($data['displayName']);
                $userData->setEmail($data['email']);
                $userData->setPassword(md5($data['password']));
                $userData->setRole(RoleDAO::getInstance($this->getServiceLocator())->findOneById($data['role']));

                $userDAO->save($userData);

                return $this->redirect()->toRoute('users');
            } else {
                $form->getMessages();
            }
        }

        return array('form' => $form);
    }

    public function deleteAction() {
        $userId = (int)$this->params()->fromRoute('id', '');
        if ($userId) {
            $userDAO = UserDAO::getInstance($this->getServiceLocator());
            $removableUser = $userDAO->findOneById($userId);

            if ($removableUser !== null) {
                $userDAO->removeById($userId);
            }
        }

        return $this->redirect()->toRoute('users');
    }
}
