<?php

namespace Application\DAO;

use \Doctrine\ORM\Query\ResultSetMapping;
use Zend\ServiceManager\ServiceLocatorInterface;

class CalendarDAO extends AbstractDAO {

    /**
     * @var CalendarDAO
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return CalendarDAO
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface) {
        if (self::$instance == null) {
            self::$instance = new CalendarDAO();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    /**
     * @return string
     */
    function getRepositoryName() {
        return '\Application\Entity\Calendar';
    }

    /**
     * @param int $id
     * @param bool $hydrate
     * @param bool $skipCache
     * @return \Application\Entity\Service
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

    public function getEventByClientId($clientId, $hydrate = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('c')
            ->from($this->getRepositoryName(), 'c')
            ->where('c.client = :clientId')
            ->setParameter('clientId', $clientId);
        return $qb->getQuery()->getOneOrNullResult($hydrate ? \Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY : null);
    }

    public function getAllEvents($hydrate = false)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('c')
            ->from($this->getRepositoryName(), 'c')
            ->orderBy('c.date', 'ASC');
        return $qb->getQuery()->getResult($hydrate ? \Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY : null);
    }

}
