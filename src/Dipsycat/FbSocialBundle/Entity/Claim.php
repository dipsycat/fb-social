<?php

namespace Dipsycat\FbSocialBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Claim
 *
 * @ORM\Table(name="claim")
 * @ORM\Entity(repositoryClass="Dipsycat\FbSocialBundle\Repository\ClaimRepository")
 */
class Claim {

    const STATUS_OPEN = 'open';
    const STATUS_IN_PROGRESS = 'in progress';
    const STATUS_CLOSED = 'closed';

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $comment
     * @ORM\Column(name="comment", type="string")
     */
    private $comment;
    
    /**
     * @var float latitude
     * @ORM\Column(name="latitude", type="float")
     */
    private $latitude;
    
    /**
     * @var float longitude
     * @ORM\Column(name="longitude", type="float")
     */
    private $longitude;
    
    /**
     * @var datetime createdAt
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(name="status", type="enumstatus")
     */
    private $status;

    /**
     * @var string photoPath
     * @ORM\Column(name="photo_path", type="string")
     */
    private $photoPath;
    
    public function __construct() {
        $this->setCreatedAt(new \DateTime());
    }

    
    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set comment
     *
     * @param string $comment
     *
     * @return Claim
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set latitude
     *
     * @param float $latitude
     *
     * @return Claim
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param float $longitude
     *
     * @return Claim
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Claim
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return Claim
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    public static function getAllStatuses() {
        return [
            self::STATUS_OPEN => self::STATUS_OPEN,
            self::STATUS_IN_PROGRESS => self::STATUS_IN_PROGRESS,
            self::STATUS_CLOSED => self::STATUS_CLOSED
        ];
    }

    /**
     * Set photoPath
     *
     * @param string $photoPath
     *
     * @return Claim
     */
    public function setPhotoPath($photoPath)
    {
        $this->photoPath = $photoPath;

        return $this;
    }

    /**
     * Get photoPath
     *
     * @return string
     */
    public function getPhotoPath()
    {
        return $this->photoPath;
    }
}
