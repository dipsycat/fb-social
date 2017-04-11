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


    /**
     * Set sessId
     *
     * @param string $sessId
     *
     * @return Session
     */
    public function setSessId($sessId)
    {
        $this->sess_id = $sessId;

        return $this;
    }

    /**
     * Get sessId
     *
     * @return string
     */
    public function getSessId()
    {
        return $this->sess_id;
    }

    /**
     * Set sessData
     *
     * @param string $sessData
     *
     * @return Session
     */
    public function setSessData($sessData)
    {
        $this->sess_data = $sessData;

        return $this;
    }

    /**
     * Get sessData
     *
     * @return string
     */
    public function getSessData()
    {
        return $this->sess_data;
    }

    /**
     * Set sessTime
     *
     * @param integer $sessTime
     *
     * @return Session
     */
    public function setSessTime($sessTime)
    {
        $this->sess_time = $sessTime;

        return $this;
    }

    /**
     * Get sessTime
     *
     * @return integer
     */
    public function getSessTime()
    {
        return $this->sess_time;
    }

    /**
     * Set sessLifetime
     *
     * @param integer $sessLifetime
     *
     * @return Session
     */
    public function setSessLifetime($sessLifetime)
    {
        $this->sess_lifetime = $sessLifetime;

        return $this;
    }

    /**
     * Get sessLifetime
     *
     * @return integer
     */
    public function getSessLifetime()
    {
        return $this->sess_lifetime;
    }
}
