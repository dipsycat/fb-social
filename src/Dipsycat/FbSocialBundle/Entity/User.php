<?php

namespace Dipsycat\FbSocialBundle\Entity;

use Dipsycat\FbSocialBundle\Service\MailerEntityInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="Dipsycat\FbSocialBundle\Repository\UserRepository")
 * @UniqueEntity("username")
 */
class User implements UserInterface, \Serializable, MailerEntityInterface {

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
     * @var string username
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $username;

    /**
     *
     * @var string surname
     * @ORM\Column(type="string", length=255)
     */
    private $surname;

    /**
     *
     * @var string password
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @var string salt
     * @ORM\Column(type="string", length=255)
     */
    private $salt;

    /**
     *
     * @var string facebookId
     * @ORM\Column(type="string", nullable=true)
     * @Assert\Blank()
     */
    private $facebookId;

    /**
     * @var ArrayCollection $userRoles
     *
     * @ORM\ManyToMany(targetEntity="Role")
     * @ORM\JoinTable(name="user_role",
     *     joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")}
     * )
     */
    private $userRoles;

    /**
     * @var ArrayCollection $userConversations
     *
     * @ORM\ManyToMany(targetEntity="Conversation", inversedBy="users")
     * @ORM\JoinTable(name="user_conversations")
     * )
     */
    private $userConversations;

    /**
     * @ORM\ManyToMany(targetEntity="User", mappedBy="myFriends")
     * */
    private $friendsWithMe;

    /**
     * @ORM\ManyToMany(targetEntity="User", inversedBy="friendsWithMe")
     * @ORM\JoinTable(name="friends",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="friend_user_id", referencedColumnName="id")}
     *      )
     * */
    private $myFriends;
    
    /**
     * @Assert\File(maxSize = "5M")
     * @Assert\Image(
     *      maxSize = "5M",
     *      mimeTypes = {"image/jpeg", "image/gif", "image/png", "image/tiff"},
     *      maxSizeMessage = "The maxmimum allowed file size is 5MB.",
     *      mimeTypesMessage = "Only the filetypes image are allowed.")
     */
    private $avatar;
    
    /**
     *
     * @var Avatar path 
     * 
     * @ORM\Column(type="string", nullable=true)
     * @Assert\Blank()
     */
    private $avatarPath;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=4096)
     */
    private $plainPassword;

    /**
     * Constructor
     */
    public function __construct() {
        $this->userRoles = new \Doctrine\Common\Collections\ArrayCollection();
        $this->friendsWithMe = new \Doctrine\Common\Collections\ArrayCollection();
        $this->myFriends = new \Doctrine\Common\Collections\ArrayCollection();
        $this->userConversations = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username) {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password) {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return User
     */
    public function setSalt($salt) {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get salt
     *
     * @return string
     */
    public function getSalt() {
        return $this->salt;
    }

    /**
     * Add userRoles
     *
     * @param \Dipsycat\FbSocialBundle\Entity\Role $userRoles
     * @return User
     */
    public function addUserRole(\Dipsycat\FbSocialBundle\Entity\Role $userRoles) {
        $this->userRoles[] = $userRoles;

        return $this;
    }

    /**
     * Remove userRoles
     *
     * @param \Dipsycat\FbSocialBundle\Entity\Role $userRoles
     */
    public function removeUserRole(\Dipsycat\FbSocialBundle\Entity\Role $userRoles) {
        $this->userRoles->removeElement($userRoles);
    }

    /**
     * Get userRoles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUserRoles() {
        return $this->userRoles;
    }

    public function eraseCredentials() {
        //$this->password = null;
    }

    /**
     * Get User Roles
     * @return array
     */
    public function getRoles() {
        $roles = [];
        foreach ($this->getUserRoles() as $role) {
            $roles[] = $role->getName();
        }
        return $roles;
    }

    /**
     * @param UserInterface $user The user
     * @return boolean True if equal, false othwerwise.
     */
    public function equals(UserInterface $user) {
        return md5($this->getUsername()) == md5($user->getUsername());
    }

    /**
     * Set surname
     *
     * @param string $surname
     * @return User
     */
    public function setSurname($surname) {
        $this->surname = $surname;

        return $this;
    }

    /**
     * Get surname
     *
     * @return string
     */
    public function getSurname() {
        return $this->surname;
    }

    public function getAllFriends() {
        $friendsWithMe = $this->getFriendsWithMe()->toArray();
        $myFriends = $this->getMyFriends()->toArray();
        $allFriends = array_merge($myFriends, $friendsWithMe);
        return new \Doctrine\Common\Collections\ArrayCollection($allFriends);
    }

    /**
     * Add friendsWithMe
     *
     * @param \Dipsycat\FbSocialBundle\Entity\User $friendsWithMe
     * @return User
     */
    public function addFriendsWithMe(\Dipsycat\FbSocialBundle\Entity\User $friendsWithMe) {
        $this->friendsWithMe[] = $friendsWithMe;

        return $this;
    }

    /**
     * Remove friendsWithMe
     *
     * @param \Dipsycat\FbSocialBundle\Entity\User $friendsWithMe
     */
    public function removeFriendsWithMe(\Dipsycat\FbSocialBundle\Entity\User $friendsWithMe) {
        $this->friendsWithMe->removeElement($friendsWithMe);
    }

    /**
     * Get friendsWithMe
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFriendsWithMe() {
        return $this->friendsWithMe;
    }

    /**
     * Add myFriends
     *
     * @param \Dipsycat\FbSocialBundle\Entity\User $myFriends
     * @return User
     */
    public function addMyFriend(\Dipsycat\FbSocialBundle\Entity\User $myFriends) {
        $this->myFriends[] = $myFriends;

        return $this;
    }

    /**
     * Remove myFriends
     *
     * @param \Dipsycat\FbSocialBundle\Entity\User $myFriends
     */
    public function removeMyFriend(\Dipsycat\FbSocialBundle\Entity\User $myFriends) {
        $this->myFriends->removeElement($myFriends);
    }

    /**
     * Get myFriends
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMyFriends() {
        return $this->myFriends;
    }

    /**
     * Add userConversations
     *
     * @param \Dipsycat\FbSocialBundle\Entity\Conversation $userConversations
     * @return User
     */
    public function addUserConversation(\Dipsycat\FbSocialBundle\Entity\Conversation $userConversations) {
        $this->userConversations[] = $userConversations;

        return $this;
    }

    /**
     * Remove userConversations
     *
     * @param \Dipsycat\FbSocialBundle\Entity\Conversation $userConversations
     */
    public function removeUserConversation(\Dipsycat\FbSocialBundle\Entity\Conversation $userConversations) {
        $this->userConversations->removeElement($userConversations);
    }

    /**
     * Get userConversations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUserConversations() {
        return $this->userConversations;
    }


    /**
     * Set facebookId
     *
     * @param string $facebookId
     *
     * @return User
     */
    public function setFacebookId($facebookId) {
        $this->facebookId = $facebookId;

        return $this;
    }

    /**
     * Get facebookId
     *
     * @return string
     */
    public function getFacebookId() {
        return $this->facebookId;
    }

    public function getPlainPassword() {
        return $this->plainPassword;
    }

    public function setPlainPassword($password) {
        $this->plainPassword = $password;
        return $this;
    }
    
    /**
     * Set avatar
     *
     * @param $avatar
     *
     * @return User
     */
    public function setAvatar(UploadedFile $avatar = null)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar
     *
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }
    
    public function serialize() {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
        ));
    }

    public function unserialize($serialized) {
        list (
            $this->id,
            $this->username,
            $this->password,
        ) = unserialize($serialized);
    }


    /**
     * Set avatarPath
     *
     * @param string $avatarPath
     *
     * @return User
     */
    public function setAvatarPath($avatarPath)
    {
        $this->avatarPath = $avatarPath;

        return $this;
    }

    /**
     * Get avatarPath
     *
     * @return string
     */
    public function getAvatarPath()
    {
        return $this->avatarPath;
    }

    public function getEmail() {
        return 'maxim.sapronenkov@gmail.com';
    }

}
