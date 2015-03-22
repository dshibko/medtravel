<?php

namespace Application\DAO;

use \Doctrine\ORM\Query\ResultSetMapping;
use Zend\ServiceManager\ServiceLocatorInterface;

class ClientsDAO extends AbstractDAO {

    /**
     * @var ClientsDAO
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return ClientsDAO
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface) {
        if (self::$instance == null) {
            self::$instance = new ClientsDAO();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    /**
     * @return string
     */
    function getRepositoryName() {
        return '\Application\Entity\Clients';
    }

    /**
     * @param int $id
     * @param bool $hydrate
     * @param bool $skipCache
     * @return \Application\Entity\Clients
     * @throws \Exception
     */
    public function findOneById($id, $hydrate = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('cli, c, d, s')
            ->from($this->getRepositoryName(), 'cli')
            ->join('cli.service', 's')
            ->join('cli.doctor', 'd')
            ->join('cli.clinic', 'c')
            ->where('cli.id = :id')
            ->setParameter('id', $id)
            ->orderBy('cli.dos','ASC');
        return $qb->getQuery()->getOneOrNullResult($hydrate ? \Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY : null);
    }


    public function getClientsByManager($managerId, $hydrate = false)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('cli, c, d, s')
            ->from($this->getRepositoryName(), 'cli')
            ->join('cli.service', 's')
            ->join('cli.doctor', 'd')
            ->join('cli.clinic', 'c')
            ->where($qb->expr()->eq('cli.manager',':managerId'))->setParameter('managerId', $managerId)
            ->orderBy('cli.dos','ASC');
        return $qb->getQuery()->getResult($hydrate ? \Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY : null);
    }

    public function getAllClients($hydrate = false)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('cli, c, d, s')
            ->from($this->getRepositoryName(), 'cli')
            ->join('cli.service', 's')
            ->join('cli.doctor', 'd')
            ->join('cli.clinic', 'c')
            ->orderBy('cli.dos','ASC');
        return $qb->getQuery()->getResult($hydrate ? \Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY : null);
    }

}
