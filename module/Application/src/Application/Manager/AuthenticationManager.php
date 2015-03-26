<?php

namespace Application\Manager;

use \Application\DAO\UserDAO;
use Zend\ServiceManager\ServiceLocatorInterface;

class AuthenticationManager extends BasicManager {

    /**
     * @var ApplicationManager
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return AuthenticationManager
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface) {
        if (self::$instance == null) {
            self::$instance = new AuthenticationManager();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    /**
     * @var \Zend\Authentication\AuthenticationService
     */
    protected $authService;

    /**
     * @return \Zend\Authentication\AuthenticationService
     */
    public function getAuthService() {
        if ($this->authService == null)
            $this->authService = $this->getServiceLocator()->get('AuthService');
        return $this->authService;
    }

    /**
     * @param string $identity
     * @param string $pwd
     * @param bool $remember
     * @return \Zend\Authentication\Result
     */
    public function authenticate($identity, $pwd, $remember = true) {
        $this->getAuthService()->getAdapter()
            ->setIdentityValue($identity)
            ->setCredentialValue($pwd);
        $result = $this->getAuthService()->authenticate();
        if ($result->isValid()) {
            $this->signIn($identity,$remember);
        }
        return $result;
    }

    public function signIn($identity, $remember = true)
    {
		if ($remember){
            $this->getAuthService()->getStorage()->setRememberMe(1, 10800);
        }
        $this->getAuthService()->getStorage()->write($identity);
    }
    /**
     * @return void
     */
    public function logout() {
        $this->getAuthService()->clearIdentity();
    }

    /**
     * @param string $email
     * @return \Application\Entity\User
     */
    public function findUserByEmail($email) {
        return UserDAO::getInstance($this->getServiceLocator())->findOneByIdentity($email);
    }
}
