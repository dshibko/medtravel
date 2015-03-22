<?php
namespace Application\DAO;

use \Zend\ServiceManager\ServiceLocatorInterface;

abstract class AbstractDAO implements \Zend\ServiceManager\ServiceLocatorAwareInterface {

    private $serviceLocator;

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * @return \Zend\ServiceManager\ServiceLocatorInterface
     */
    public function getServiceLocator() {
        return $this->serviceLocator;
    }

    /**
     * @return \Doctrine\ORM\EntityRepository
     */
    public function getRepository() {
        return $this->getEntityManager()->getRepository($this->getRepositoryName());
    }

    public function flush() {
        $this->getEntityManager()->flush();
    }

    /**
     * @throws \Exception
     * @param object $entity
     * @param bool $flush
     */
    public function save($entity, $flush = true) {
        try {
            $this->getEntityManager()->persist($entity);
            if ($flush)
                $this->getEntityManager()->flush();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @throws \Exception
     * @param int $id
     */
    public function removeById($id) {
        try {
            $qb = $this->getEntityManager()->createQueryBuilder();
            $qb->delete($this->getRepositoryName(), 'e')
                ->where('e.id = :e_id')
                ->setParameter('e_id', $id);
            $qb->getQuery()->execute();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $id
     * @param bool $hydrate
     * @return mixed
     */
    public function findOneById($id, $hydrate = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('e')
            ->from($this->getRepositoryName(), 'e')
            ->where($qb->expr()->eq('e.id', ':id'))->setParameter('id',$id);
        return $qb->getQuery()->getOneOrNullResult($hydrate ? \Doctrine\ORM\Query::HYDRATE_ARRAY : null);
    }

    /**
     * @param bool $hydrate
     * @return array
     * @throws \Exception
     */
    public function findAll($hydrate = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('e')
            ->from($this->getRepositoryName(), 'e');
        return $qb->getQuery()->getResult($hydrate ? \Doctrine\ORM\Query::HYDRATE_ARRAY : null);
    }

    /**
     * @param array $fields
     * @param bool $hydrate
     * @return array
     * @throws \Exception
     */
    public function findAllByFields(array $fields, $hydrate = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $selectArr = array();
        foreach ($fields as $field)
            $selectArr[] = 'e.' . $field;
        $qb->select(implode(',', $selectArr))
            ->from($this->getRepositoryName(), 'e');
        return $qb->getQuery()->getResult($hydrate ? \Doctrine\ORM\Query::HYDRATE_ARRAY : null);
    }

    /**
     * @return int
     * @throws \Exception
     */
    public function count() {
        try {
            $qb = $this->getEntityManager()->createQueryBuilder();
            $qb->select('count(e.id)')
                ->from($this->getRepositoryName(), 'e');
            return $qb->getQuery()->getSingleScalarResult();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function beginTransaction() {
        $this->getEntityManager()->getConnection()->beginTransaction();
    }

    public function commit() {
        $this->getEntityManager()->getConnection()->commit();
    }

    /**
     * @abstract
     * @return string
     */
    abstract function getRepositoryName();

    /**
     * @param $class
     * @param $id
     * @return object
     */
    public function getReference($class, $id) {
        return $this->getEntityManager()->getReference($class, $id);
    }

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    protected function getEntityManager() {

        if( !isset($this->em) )
            $this->em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');

        return $this->em;

    }

} 