<?php

namespace Application\DAO;

use \Doctrine\ORM\Query\ResultSetMapping;
use Zend\ServiceManager\ServiceLocatorInterface;

class UserDAO extends AbstractDAO {

    /**
     * @var UserDAO
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return UserDAO
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface) {
        if (self::$instance == null) {
            self::$instance = new UserDAO();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    /**
     * @return string
     */
    function getRepositoryName() {
        return '\Application\Entity\User';
    }

    /**
     * @param $identity
     * @param bool $hydrate
     * @param bool $skipCache
     * @return \Application\Entity\User
     * @throws \Exception
     */
    public function findOneByIdentity($identity, $hydrate = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('u, r')
            ->from($this->getRepositoryName(), 'u')
            ->join('u.role', 'r')
            ->where('u.email = :identity')
            ->setParameter('identity', $identity);
        return $qb->getQuery()->getOneOrNullResult($hydrate ? \Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY : null);
    }

    /**
     * @param int $id
     * @param bool $hydrate
     * @param bool $skipCache
     * @return \Application\Entity\User
     * @throws \Exception
     */
    public function findOneById($id, $hydrate = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('u, r')
            ->from($this->getRepositoryName(), 'u')
            ->join('u.role', 'r')
            ->where('u.id = :id')
            ->setParameter('id', $id);
        return $qb->getQuery()->getOneOrNullResult($hydrate ? \Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY : null);
    }


    public function getUsersByRoles(array $roles, $hydrate = false)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('u, r')
            ->from($this->getRepositoryName(), 'u')
            ->join('u.role', 'r')
            ->where($qb->expr()->in('r.name',':roles'))->setParameter('roles', $roles)
            ->orderBy('u.role','ASC');
        return $qb->getQuery()->getResult($hydrate ? \Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY : null);
    }

    public function getAllUsers($hydrate = false)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('u, r')
            ->from($this->getRepositoryName(), 'u')
            ->join('u.role', 'r')
            ->orderBy('u.id','ASC');
        return $qb->getQuery()->getResult($hydrate ? \Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY : null);
    }

}
