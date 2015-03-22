<?php

namespace Application\Manager;

use Application\DAO\ClinicDAO;
use Application\DAO\DoctorDAO;
use Application\DAO\ServiceDAO;
use \Application\Entity\User;
use \Application\DAO\UserDAO;
use Zend\ServiceManager\ServiceLocatorInterface;


class ApplicationManager extends BasicManager {

    /**
     * @var ApplicationManager
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return ApplicationManager
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface) {
        if (self::$instance === null) {
            self::$instance = new ApplicationManager();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    /**
     * @var \Application\Entity\User
     */
    protected $currentUser;

    /**
     * @return \Application\\Entity\User
     */
    public function getCurrentUser()
    {
        if ($this->currentUser === null) {
            $identity = $this->getServiceLocator()->get('AuthService')->getIdentity();
            if ($identity === null) $this->currentUser = null;
            else
                if ($identity instanceof User)
                    $this->currentUser = $identity;
                else if (is_string($identity))
                    $this->currentUser = UserDAO::getInstance($this->getServiceLocator())->findOneByIdentity($identity, false, true);
        }
        return $this->currentUser;
    }

    public function encryptPassword($password, $salt = null)
    {
        return md5($password);
    }

    public function prepareFormServices() {
        $services = ServiceDAO::getInstance($this->getServiceLocator())->getAllServices(true);
        $result = array();
        foreach ($services as $service) {
            $result[$service['id']] = $service['name'];
        }

        return $result;
    }

    public function prepareFormClinics() {
        $clinics = ClinicDAO::getInstance($this->getServiceLocator())->getAllClinics(true);
        $result = array();
        foreach ($clinics as $clinic) {
            $result[$clinic['id']] = $clinic['name'];
        }

        return $result;
    }

    public function prepareFormDoctors() {
        $doctors = DoctorDAO::getInstance($this->getServiceLocator())->getDoctorsWithClinics();
        $result = array();
        foreach ($doctors as $doctor) {
            $result[$doctor->getClinic()->getId()][$doctor->getId()] = $doctor->getName();
        }

        return $result;
    }
}