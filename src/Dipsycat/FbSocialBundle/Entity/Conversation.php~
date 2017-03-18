<?php

namespace Dipsycat\FbSocialBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Conversation
 *
 * @ORM\Entity(repositoryClass="Dipsycat\FbSocialBundle\Repository\ConversationRepository")
 */
class Conversation {

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     *
     * @var string name
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity="User", mappedBy="userConversations")
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity="Message", mappedBy="conversation")
     */
    private $messages;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    public function __construct() {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
        $this->messages = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add users
     *
     * @param \Dipsycat\FbSocialBundle\Entity\User $users
     * @return Conversation
     */
    public function addUser(\Dipsycat\FbSocialBundle\Entity\User $users) {
        $this->users[] = $users;

        return $this;
    }

    /**
     * Remove users
     *
     * @param \Dipsycat\FbSocialBundle\Entity\User $users
     */
    public function removeUser(\Dipsycat\FbSocialBundle\Entity\User $users) {
        $this->users->removeElement($users);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsers() {
        return $this->users;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Conversation
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Add messages
     *
     * @param \Dipsycat\FbSocialBundle\Entity\Message $messages
     * @return Conversation
     */
    public function addMessage(\Dipsycat\FbSocialBundle\Entity\Message $messages) {
        $this->messages[] = $messages;

        return $this;
    }

    /**
     * Remove messages
     *
     * @param \Dipsycat\FbSocialBundle\Entity\Message $messages
     */
    public function removeMessage(\Dipsycat\FbSocialBundle\Entity\Message $messages) {
        $this->messages->removeElement($messages);
    }

    /**
     * Get messages
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMessages() {
        return $this->messages;
    }

}
