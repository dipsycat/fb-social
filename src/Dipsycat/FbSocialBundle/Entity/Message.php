<?php

namespace Dipsycat\FbSocialBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Message
 *
 * @ORM\Table(name="message")
 * @ORM\Entity(repositoryClass="Dipsycat\FbSocialBundle\Repository\MessageRepository")
 */
class Message {

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string text
     * @ORM\Column(type="string")
     */
    private $text;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Conversation", inversedBy="messages")
     * @ORM\JoinColumn(name="conversation_id", referencedColumnName="id")
     */
    private $conversation;

    /**
     * @var DateTime $createdAt
     * 
     * @ORM\Column(type="datetime", name="created_at")
     */
    private $createdAt;

    public function __construct() {
        $this->setCreatedAt(new \DateTime('now'));
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set text
     *
     * @param string $text
     * @return Message
     */
    public function setText($text) {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string 
     */
    public function getText() {
        return $this->text;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Message
     */
    public function setCreatedAt($createdAt) {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt() {
        return $this->createdAt;
    }

    /**
     * Set user
     *
     * @param \Dipsycat\FbSocialBundle\Entity\User $user
     * @return Message
     */
    public function setUser(\Dipsycat\FbSocialBundle\Entity\User $user = null) {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Dipsycat\FbSocialBundle\Entity\User 
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * Set conversation
     *
     * @param \Dipsycat\FbSocialBundle\Entity\Conversation $conversation
     * @return Message
     */
    public function setConversation(\Dipsycat\FbSocialBundle\Entity\Conversation $conversation = null) {
        $this->conversation = $conversation;

        return $this;
    }

    /**
     * Get conversation
     *
     * @return \Dipsycat\FbSocialBundle\Entity\Conversation 
     */
    public function getConversation() {
        return $this->conversation;
    }

    public function getCreatedAtAgo() {
        $now = new \DateTime('now');
        $createdAt = $this->getCreatedAt();
        $diff = $now->diff($createdAt);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $period = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($period as $key => &$value) {
            if ($diff->$key) {
                $value = $diff->$key . ' ' . $value . ($diff->$key > 1 ? 's' : '');
            } else {
                unset($period[$key]);
            }
        }
        $period = array_slice($period, 0, 1);
        return $period ? implode(', ', $period) . ' ago' : 'just now';
    }

}
