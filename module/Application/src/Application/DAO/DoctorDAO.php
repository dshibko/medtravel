<?php

namespace Application\DAO;

use \Doctrine\ORM\Query\ResultSetMapping;
use Zend\ServiceManager\ServiceLocatorInterface;

class DoctorDAO extends AbstractDAO {

    /**
     * @var DoctorDAO
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return DoctorDAO
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface) {
        if (self::$instance == null) {
            self::$instance = new DoctorDAO();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    /**
     * @return string
     */
    function getRepositoryName() {
        return '\Application\Entity\Doctor';
    }

    /**
     * @param int $id
     * @param bool $hydrate
     * @param bool $skipCache
     * @return \Application\Entity\Doctor
     * @throws \Exception
     */
    public function findOneById($id, $hydrate = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('d')
            ->from($this->getRepositoryName(), 'd')
            ->where('d.id = :id')
            ->setParameter('id', $id);
        return $qb->getQuery()->getOneOrNullResult($hydrate ? \Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY : null);
    }

    public function getAllDoctors($hydrate = false)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('d')
            ->from($this->getRepositoryName(), 'd')
            ->orderBy('d.id','ASC');
        return $qb->getQuery()->getResult($hydrate ? \Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY : null);
    }

}
