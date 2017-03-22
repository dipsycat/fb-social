<?php

namespace Dipsycat\FbSocialBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends EntityRepository {

    public function searchUsers($searchText) {
        $users = $this->createQueryBuilder('u')
            ->where('u.username LIKE :search')
            ->orWhere('u.surname LIKE :search')
            ->setParameter('search', '%' . $searchText . '%')
            ->getQuery();
        return $users->getResult();
    }
}
