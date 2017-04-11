<?php

namespace Dipsycat\FbSocialBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Session
 *
 * @ORM\Table(name="sessions")
 * @ORM\Entity()
 */
class Session {

    /**
     * @var string
     *
     * @ORM\Column(name="sess_id", type="string", length=128)
     * @ORM\Id
     */
    private $sess_id;
    
    /**
     * @var int
     *
     * @ORM\Column(name="sess_data", type="blob")
     */
    private $sess_data;
    
    /**
     * @var int
     *
     * @ORM\Column(name="sess_time", type="integer", options={"unsigned"=true})
     */
    private $sess_time;
    
    /**
     * @var int
     *
     * @ORM\Column(name="sess_lifetime", type="integer")
     */
    private $sess_lifetime;

}
