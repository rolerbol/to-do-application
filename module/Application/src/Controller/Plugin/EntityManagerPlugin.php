<?php
namespace Application\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Doctrine\ORM\EntityManager;

class EntityManagerPlugin extends AbstractPlugin
{
    /**
     * @var EntityManager
     */
    private $entityManager;
    
    /**
     * @param EntityManager $entityManager
     * @return EntityManagerPlugin
     */
    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        return $this;
    }
    
    public function getEntityManager()
    {
        return $this->entityManager;
    }
    
    /**
     * @return EntityManager
     */
    public function __invoke()
    {
        return $this->getEntityManager();
    }
}

