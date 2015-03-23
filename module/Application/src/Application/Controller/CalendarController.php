<?php

namespace Application\Controller;

use Application\DAO\CalendarDAO;
use Application\Entity\Calendar;
use Application\Form\CalendarForm;
use Application\Manager\AuthenticationManager;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class CalendarController extends AbstractActionController
{
    public function eventsAction() {
        $events = CalendarDAO::getInstance($this->getServiceLocator())->getAllEvents(true);

        $response = array();
        if (!empty($events)) {
            foreach ($events as $k=>$event) {
                $response[$k]['id'] = $event['id'];
                $response[$k]['title'] = $event['title'];
                $response[$k]['description'] = $event['description'];
                $response[$k]['datetime'] = $event['date']->format('Y-m-d');
            }
        }
        die(json_encode($response));
    }

    public function addAction() {
        $form = new CalendarForm();
        $request = $this->getRequest();
        $calendarDAO = CalendarDAO::getInstance($this->getServiceLocator());

        if ($request->isPost()) {
            $post = $request->getPost()->toArray();
            $form->setData($post);

            if ($form->isValid()) {
                $data = $form->getData();

                $dateTime = new \DateTime();
                $dateTime->setTimestamp(strtotime($data['date']));
                $data['date'] = $dateTime;

                $userData = new Calendar();
                $userData->setTitle($data['title']);
                $userData->setDescription($data['description']);
                $userData->setDate($data['date']);

                $calendarDAO->save($userData);

                echo 'Событие добавлено';
                die();
            } else {
                $form->getMessages();
            }
        }

        $viewModel = new ViewModel(array(
            'form' => $form,
        ));

        $viewModel->setTerminal(true);
        return $viewModel;
    }

    public function removeAction() {
        $eventId = (int)$this->params()->fromRoute('id', '');
        CalendarDAO::getInstance($this->getServiceLocator())->removeById($eventId);
        die();
    }
}
