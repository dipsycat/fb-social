<?php

namespace Dipsycat\FbSocialBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Dipsycat\FbSocialBundle\Entity\Role;


class LoadUserData implements FixtureInterface {
    
    public function load(ObjectManager $manager) {
        $role = new Role();
        $role->setName('ROLE_ADMIN');
        $manager->persist($role);

        $role = new Role();
        $role->setName('ROLE_CONFIRM');
        $manager->persist($role);
        
        $manager->flush();
    }

}
