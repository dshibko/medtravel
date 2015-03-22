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
use Application\DAO\ClinicDAO;
use Application\DAO\DoctorDAO;
use Application\DAO\RoleDAO;
use Application\DAO\ServiceDAO;
use Application\DAO\UserDAO;
use Application\Entity\Clients;
use Application\Entity\User;
use Application\Form\ClientsForm;
use Application\Form\UserForm;
use Application\Manager\ApplicationManager;
use Zend\Filter\File\RenameUpload;
use Zend\Mvc\Controller\AbstractActionController;

class ClientsController extends AbstractActionController
{

    public function indexAction() {
        try{
        $clientsDAO = ClientsDAO::getInstance($this->getServiceLocator());
        $clients = $clientsDAO->getAllClients();
        }catch(\Exception $e) {
            pr($e->getMessage());
        }
        return array('clients' => $clients);
    }

    public function editAction() {
        $form = new UserForm();
        $userId = (int)$this->params()->fromRoute('id', '');
        $request = $this->getRequest();
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

        return array('form' => $form);
    }

    public function addAction() {
        $applicationManager = ApplicationManager::getInstance($this->getServiceLocator());
        $doctors = $applicationManager->prepareFormDoctors();

        foreach ($doctors as $clinic) {
            foreach ($clinic as $id=>$name) {
                $formDoctors[$id] = $name;
            }
        }

        $form = new ClientsForm(
            array('services' => $applicationManager->prepareFormServices(),
                  'clinics' => $applicationManager->prepareFormClinics(),
                  'doctors' => $formDoctors
            )
        );

        $request = $this->getRequest();
        if ($request->isPost()) {
            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );
pr($post);
            $form->setData($post);

            if ($form->isValid()) {
                $data = $form->getData();
                $dateTime = new \DateTime();

                $dateTime->setTimestamp(strtotime($data['dos']));
                $data['dos'] = $dateTime;
                $dateTime->setTimestamp(time());
                $now = $dateTime;

                if (!empty($post['attachments'])) {
                    foreach ($post['attachments'] as $attach) {
                        if (!empty($attach['name'])) {
                            $attach['name'] = str_replace(' ', '_', $attach['name']);
                            move_uploaded_file($attach['tmp_name'], '/home/dmitry/public_html/zend.loc/public/uploads/'.$attach['name']);
                            $attachmentNames[] = 'uploads/'.$attach['name'];
                        }
                    }
                }

                $conclusion = '';
                if (!empty($post['conclusion']['name'])) {
                    $post['conclusion']['name'] = str_replace(' ', '_', $post['conclusion']['name']);
                    move_uploaded_file($post['conclusion']['tmp_name'], '/home/dmitry/public_html/zend.loc/public/uploads/'.$post['conclusion']['name']);
                    $conclusion = 'uploads/'.$post['conclusion']['name'];
                }

                $client = new Clients();
                $client->setFio($data['fio']);
                $client->setService(ServiceDAO::getInstance($this->getServiceLocator())->findOneById($data['service']));
                $client->setDiagnosis($data['diagnosis']);
                $client->setContacts($data['contacts']);
                $client->setDOS($data['dos']);
                $client->setStatus($data['status']);
                $client->setComments($data['comments']);
                $client->setCountry($data['country']);
                $client->setContactType($data['contactType']);
                $client->setAttachments(serialize($attachmentNames));
                $client->setClinic(ClinicDAO::getInstance($this->getServiceLocator())->findOneById($data['clinic']));
                $client->setDoctor(DoctorDAO::getInstance($this->getServiceLocator())->findOneById($data['doctor']));
                $client->setConclusion($conclusion);
                $client->setPayment($data['payment']);
                $client->setInformed((int)$data['informed']);
                $client->setDateAdded($now);
                $client->setManager($applicationManager->getCurrentUser());

                ClientsDAO::getInstance($this->getServiceLocator())->save($client);

                return $this->redirect()->toRoute('clients');
            } else {
                $form->getMessages();
            }
        }

        return array('form' => $form, 'doctors' => $doctors);
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
