<?php

namespace AppBundle\Repository;

/**
 * CommentRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CommentRepository extends \Doctrine\ORM\EntityRepository
{
    public function findSignaled()
    {
        return $this->findBy(array(), array('signaled' => 'true'));
    }

    public function myfindAll()
    {
        $query = $this->_em->createQuery('SELECT c FROM AppBundle:Comment c ORDER BY c.signaled DESC, c.date DESC');

        $results = $query->getResult();
        return $results;
    }

}
