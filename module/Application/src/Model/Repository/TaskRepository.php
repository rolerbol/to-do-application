<?php
namespace Application\Model\Repository;

use Doctrine\ORM\EntityRepository;

class TaskRepository extends EntityRepository
{
    const TASK_STATUS_CREATED = 'CREATED';
    const TASK_STATUS_IN_PROCESSING = 'IN_PROCESSING';
    const TASK_STATUS_CLOSED = 'CLOSED';
    const TASK_STATUS_COMPLETED = 'COMPLETED';
}

