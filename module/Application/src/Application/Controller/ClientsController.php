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
use Application\DAO\CountryDAO;
use Application\DAO\DoctorDAO;
use Application\DAO\ServiceDAO;
use Application\DAO\UserDAO;
use Application\Entity\Clients;
use Application\Entity\Country;
use Application\Entity\Doctor;
use Application\Entity\Clinic;
use Application\Form\ClientsForm;
use Application\Manager\ApplicationManager;
use Zend\Mvc\Controller\AbstractActionController;

class ClientsController extends AbstractActionController
{

    public function indexAction() {
        $clients = array();
        try {
            $clientsDAO = ClientsDAO::getInstance($this->getServiceLocator());
            $clients = $clientsDAO->getAllClients(true);

            $statuses = array(
                'Не обработан' => '#00C8FF',
                'В работе' => '#FF2A00',
                'Согласование' => '#F6FF00',
                'Думает' => '#9EFFA1',
                'Архив' => '#D6D6D6',
                'Пролечен' => '#00FF08',
                'Записан в календарь' => '#FFFFFF',
            );
            if (!empty($clients)) {
                foreach ($clients as $key => $client) {
                    foreach ($statuses as $status => $color) {
                        if ($client['status'] == $status) {
                            $clients[$key]['color'] = $color;
                        }
                    }
                }
            }
        } catch(\Exception $e) {}

        return array('clients' => $clients);
    }

    public function editAction() {
        $config = $this->getServiceLocator()->get('config');

        $applicationManager = ApplicationManager::getInstance($this->getServiceLocator());

        $form = new ClientsForm(
            array('services' => $applicationManager->prepareFormServices(),
                'clinics' => $applicationManager->prepareFormClinics(),
                'doctors' => $applicationManager->prepareFormDoctors(),
                'countries' => $applicationManager->prepareFormCountries()
            )
        );
        $clientId = (int)$this->params()->fromRoute('id', '');
        $request = $this->getRequest();
        $view = $request->getQuery()->view;
        $clientsDAO = ClientsDAO::getInstance($this->getServiceLocator());
        if (!empty($clientId)) {
            $editableClient = $clientsDAO->findOneById($clientId);
            if ($editableClient !== null) {
                $attachments = $editableClient->getAttachments();
                if ($attachments) {
                    $attachments = unserialize($attachments);
                } else {
                    $attachments = array();
                }

                $conclusion = $editableClient->getConclusion();
                if ($conclusion) {
                    $conclusions = unserialize($conclusion);
                } else {
                    $conclusions = array();
                }
                if (!$request->isPost()) {
                    $clientData = array(
                        'fio' => $editableClient->getFio(),
                        'diagnosis' => $editableClient->getDiagnosis(),
                        'contacts' => $editableClient->getContacts(),
                        'dos' => $editableClient->getDOS()->format('d-M-Y'),
                        'clinic' => $editableClient->getClinic()->getId(),
                        'doctor' => $editableClient->getDoctor()->getId(),
                        'country' => $editableClient->getCountry()->getID(),
                        'status' => $editableClient->getStatus(),
                        'service' => $editableClient->getService()->getId(),
                        'comments' => $editableClient->getComments(),
                        'contactType' => $editableClient->getContactType(),
                        'payment' => $editableClient->getPayment(),
                        'informed' => $editableClient->getInformed(),
                    );
                    $form->setData($clientData);
                    $form->setAttribute('action', '/clients/edit/'.$editableClient->getId());
                }
            } else {
                return $this->redirect()->toRoute('clients');
            }
        }

        if ($request->isPost()) {
            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            $form->setData($post);
            if ($form->isValid()) {
                $data = $form->getData();

                $dateTime = new \DateTime();
                $dateTime->setTimestamp(strtotime($data['dos']));
                $data['dos'] = $dateTime;

                if (!empty($data['newClinic'])) {
                    $newClinic = new Clinic();
                    $newClinic->setName($data['newClinic']);
                    ClinicDAO::getInstance($this->getServiceLocator())->save($newClinic);
                    $data['clinic'] = $newClinic->getId();
                }

                if (!empty($data['newDoctor'])) {
                    $newDoctor = new Doctor();
                    $newDoctor->setName($data['newDoctor']);
                    DoctorDAO::getInstance($this->getServiceLocator())->save($newDoctor);
                    $data['doctor'] = $newDoctor->getId();
                }

                if (!empty($data['newCountry'])) {
                    $newCountry = new Country();
                    $newCountry->setName($data['newCountry']);
                    CountryDAO::getInstance($this->getServiceLocator())->save($newCountry);
                    $data['country'] = $newCountry->getId();
                }

                $attachmentNames = array();
                if (!empty($post['attachments'])) {
                    foreach ($post['attachments'] as $attach) {
                        if (!empty($attach['name'])) {
                            $attach['name'] = str_replace(' ', '_', $attach['name']);
                            move_uploaded_file($attach['tmp_name'], $config['app']['uploads_path'].$attach['name']);
                            $attachmentNames[] = 'uploads/'.$attach['name'];
                        }
                    }
                }

                $conclusionNames = array();
                if (!empty($post['conclusions'])) {
                    foreach ($post['conclusions'] as $conclusion) {
                        if (!empty($conclusion['name'])) {
                            $conclusion['name'] = str_replace(' ', '_', $conclusion['name']);
                            move_uploaded_file($conclusion['tmp_name'], $config['app']['uploads_path'].$conclusion['name']);
                            $conclusionNames[] = 'uploads/'.$conclusion['name'];
                        }
                    }
                }

                if (!empty($post['oldAttachments'])) {
                    $attachmentNames = !empty($attachmentNames) ? array_merge($attachmentNames, $post['oldAttachments']) : $post['oldAttachments'];
                }

                if (!empty($post['oldConclusions'])) {
                    $conclusionNames = !empty($conclusionNames) ? array_merge($conclusionNames, $post['oldConclusions']) : $post['oldConclusions'];
                }

                $client = $editableClient;
                $client->setFio($data['fio']);
                $client->setService(ServiceDAO::getInstance($this->getServiceLocator())->findOneById($data['service']));
                $client->setDiagnosis($data['diagnosis']);
                $client->setContacts($data['contacts']);
                $client->setDOS($data['dos']);
                $client->setStatus($data['status']);
                $client->setComments($data['comments']);
                $client->setCountry(CountryDAO::getInstance($this->getServiceLocator())->findOneById($data['country']));
                $client->setContactType($data['contactType']);
                $client->setAttachments(serialize(array_unique($attachmentNames)));
                $client->setClinic(ClinicDAO::getInstance($this->getServiceLocator())->findOneById($data['clinic']));
                $client->setDoctor(DoctorDAO::getInstance($this->getServiceLocator())->findOneById($data['doctor']));
                $client->setConclusion(serialize(array_unique($conclusionNames)));
                $client->setPayment($data['payment']);
                $client->setInformed((int)$data['informed']);

                $clientsDAO->save($client);
                return $this->redirect()->toRoute('clients');
            }
        }
        return array(
            'form' => $form,
            'attachments' => $attachments,
            'conclusions' => $conclusions,
            'view' => $view
        );
    }

    public function addAction() {
        $config = $this->getServiceLocator()->get('config');

        $applicationManager = ApplicationManager::getInstance($this->getServiceLocator());

        $form = new ClientsForm(
            array('services' => $applicationManager->prepareFormServices(),
                  'clinics' => $applicationManager->prepareFormClinics(),
                  'doctors' => $applicationManager->prepareFormDoctors(),
                  'countries' => $applicationManager->prepareFormCountries()
            )
        );

        $request = $this->getRequest();
        if ($request->isPost()) {
            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            $form->setData($post);

            if ($form->isValid()) {
                $data = $form->getData();
                $dateTime = new \DateTime();

                $dateTime->setTimestamp(strtotime($data['dos']));
                $data['dos'] = $dateTime;
                $dateTime->setTimestamp(time());
                $now = $dateTime;

                if (!empty($data['newClinic'])) {
                    $newClinic = new Clinic();
                    $newClinic->setName($data['newClinic']);
                    ClinicDAO::getInstance($this->getServiceLocator())->save($newClinic);
                    $data['clinic'] = $newClinic->getId();
                }

                if (!empty($data['newDoctor'])) {
                    $newDoctor = new Doctor();
                    $newDoctor->setName($data['newDoctor']);
                    DoctorDAO::getInstance($this->getServiceLocator())->save($newDoctor);
                    $data['doctor'] = $newDoctor->getId();
                }

                if (!empty($data['newCountry'])) {
                    $newCountry = new Country();
                    $newCountry->setName($data['newCountry']);
                    CountryDAO::getInstance($this->getServiceLocator())->save($newCountry);
                    $data['country'] = $newDoctor->getId();
                }

                if (!empty($post['attachments'])) {
                    foreach ($post['attachments'] as $attach) {
                        if (!empty($attach['name'])) {
                            $attach['name'] = str_replace(' ', '_', $attach['name']);
                            move_uploaded_file($attach['tmp_name'], $config['app']['uploads_path'].$attach['name']);
                            $attachmentNames[] = 'uploads/'.$attach['name'];
                        }
                    }
                }

                if (!empty($post['conclusions'])) {
                    foreach ($post['conclusions'] as $conclusion) {
                        if (!empty($conclusion['name'])) {
                            $conclusion['name'] = str_replace(' ', '_', $conclusion['name']);
                            move_uploaded_file($conclusion['tmp_name'], $config['app']['uploads_path'].$conclusion['name']);
                            $conclusionNames[] = 'uploads/'.$conclusion['name'];
                        }
                    }
                } else {
                    $conclusionNames = array();
                }

                $client = new Clients();
                $client->setFio($data['fio']);
                $client->setService(ServiceDAO::getInstance($this->getServiceLocator())->findOneById($data['service']));
                $client->setDiagnosis($data['diagnosis']);
                $client->setContacts($data['contacts']);
                $client->setDOS($data['dos']);
                $client->setStatus($data['status']);
                $client->setComments($data['comments']);
                $client->setCountry(CountryDAO::getInstance($this->getServiceLocator())->findOneById($data['country']));
                $client->setContactType($data['contactType']);
                $client->setAttachments(serialize($attachmentNames));
                $client->setClinic(ClinicDAO::getInstance($this->getServiceLocator())->findOneById($data['clinic']));
                $client->setDoctor(DoctorDAO::getInstance($this->getServiceLocator())->findOneById($data['doctor']));
                $client->setConclusion(serialize(array_unique($conclusionNames)));
                $client->setPayment($data['payment']);
                $client->setInformed((int)$data['informed']);
                $client->setDateAdded($now);
                $client->setManager($applicationManager->getCurrentUser());

                ClientsDAO::getInstance($this->getServiceLocator())->save($client);

                return $this->redirect()->toRoute('clients');
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
