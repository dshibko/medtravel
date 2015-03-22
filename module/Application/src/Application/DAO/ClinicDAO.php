<?php

namespace Application\DAO;

use \Doctrine\ORM\Query\ResultSetMapping;
use Zend\ServiceManager\ServiceLocatorInterface;

class ClinicDAO extends AbstractDAO {

    /**
     * @var ClinicDAO
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return ClientsDAO
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface) {
        if (self::$instance == null) {
            self::$instance = new ClinicDAO();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    /**
     * @return string
     */
    function getRepositoryName() {
        return '\Application\Entity\Clinic';
    }

    /**
     * @param int $id
     * @param bool $hydrate
     * @param bool $skipCache
     * @return \Application\Entity\Clinic
     * @throws \Exception
     */
    public function findOneById($id, $hydrate = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('c')
            ->from($this->getRepositoryName(), 'c')
            ->where('c.id = :id')
            ->setParameter('id', $id);
        return $qb->getQuery()->getOneOrNullResult($hydrate ? \Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY : null);
    }

    public function getAllClinics($hydrate = false)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('c')
            ->from($this->getRepositoryName(), 'c')
            ->orderBy('c.id','ASC');
        return $qb->getQuery()->getResult($hydrate ? \Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY : null);
    }

}
