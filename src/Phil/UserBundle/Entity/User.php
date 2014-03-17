<?php
// src/Phil/UserBundle/Entity/User.php
namespace Phil\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

use Phil\CMSBundle\Entity\Page;
use Phil\UserBundle\Entity\Role;

/**
 * @ORM\Entity(repositoryClass="Phil\UserBundle\Entity\Repository\UserRepository")
 * @ORM\Table(name="users")
 * @ORM\HasLifecycleCallbacks
 * @UniqueEntity(fields="email", message="Email address already registered. Select a unique email address.")
 * @UniqueEntity(fields="username", message="Username already registered. Select a unique username.")
 */
class User implements AdvancedUserInterface, \Serializable
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=32, unique=true)
     * @Assert\NotBlank(message = "You must enter a username.")
     * @Assert\Length(max = 32)
     * @Assert\Length(min = 3)
     * @Assert\Regex(pattern="/[^A-Za-z0-9]/", match=false, message="Your username may only contain letters and numbers.")
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=32)
     * @Assert\NotBlank(message = "You must enter a first name")
     * @Assert\Length(max = 32)
     * @Assert\Length(min = 3)
     * @Assert\Regex(pattern="/[^A-Za-z ]/", match=false, message="First name may only contain letters.")
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=32)
     * @Assert\NotBlank(message = "You must enter a last name")
     * @Assert\Length(max = 32)
     * @Assert\Length(min = 3)
     * @Assert\Regex(pattern="/[^A-Za-z ]/", match=false, message="Last name may only contain letters.")
     */
    private $lastname;

    /**
     * @ORM\Column(type="date")
     * @Assert\Date()
     */
    private $birthday;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $password;

    /**
     * @Assert\NotBlank(message = "You must enter a password")
     * @Assert\Length(max = 64)
     * @Assert\Length(min = 6)
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="string", length=64, unique=true)
     * @Assert\NotBlank(message = "You must enter an email")
     * @Assert\Length(max = 64)
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="boolean")
     */
    private $emailVerified;

    /**
     * @ORM\Column(type="boolean")
     */
    private $enabled;

    /**
     * @ORM\Column(type="boolean")
     */
    private $expired;
    
    /**
     * @ORM\Column(type="boolean")
     */
    private $locked;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private $activationCode;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $resetTime;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private $resetCode;

    /**
     * @ORM\Column(type="boolean")
     */
    private $passwordExpired;

    /**
     * @ORM\ManyToMany(targetEntity="Role", inversedBy="users")
     */
    private $roles;

    /**
     * @ORM\OneToMany(targetEntity="Phil\CMSBundle\Entity\Page", mappedBy="owner")
     */
    protected $pages;

    public function __construct()
    {
        $this->roles = new ArrayCollection();
        $this->pages = new ArrayCollection();
        $this->enabled = false;
        $this->expired = false;
        $this->locked = false;
        $this->created = new \DateTime();
        $this->resetTime = null;
        $this->emailVerified = false;
        $this->passwordExpired = false;
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
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = trim(strtolower($username));

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     * @return User
     */
    public function setFirstname($firstname)
    {
        $this->firstname = trim(strtolower($firstname));

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string 
     */
    public function getFirstname()
    {
        return ucwords($this->firstname);
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     * @return User
     */
    public function setLastname($lastname)
    {
        $this->lastname = trim(strtolower($lastname));

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string 
     */
    public function getLastname()
    {
        return ucwords($this->lastname);
    }

    /**
     * Set birthday
     *
     * @param \DateTime $birthday
     * @return User
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * Get birthday
     *
     * @return \DateTime 
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set encrypted password
     *
     * @param string $password
     * @return User
     * @ORM\PrePersist
     */
    public function setPassword()
    {
        $this->password = password_hash($this->plainPassword, PASSWORD_BCRYPT, array('cost' => 15));

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set plaintext password, not persisted
     *
     * @param string $password
     * @return User
     */
    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     * @return User
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled
     *
     * @return boolean 
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * @inheritDoc
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set expired
     *
     * @param boolean $expired
     * @return User
     */
    public function setExpired($expired)
    {
        $this->expired = $expired;

        return $this;
    }

    /**
     * Get expired
     *
     * @return boolean 
     */
    public function getExpired()
    {
        return $this->expired;
    }

    /**
     * Is expired
     *
     * @return boolean 
     */
    public function isExpired()
    {
        return $this->expired;
    }

    /**
     * Set locked
     *
     * @param boolean $locked
     * @return User
     */
    public function setLocked($locked)
    {
        $this->locked = $locked;

        return $this;
    }

    /**
     * Get locked
     *
     * @return boolean 
     */
    public function getLocked()
    {
        return $this->locked;
    }

    /**
     * Is locked
     *
     * @return boolean 
     */
    public function isLocked()
    {
        return $this->locked;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return User
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set activationCode
     *
     * @param string $activationCode
     * @return User
     */
    public function setActivationCode($activationCode)
    {
        $this->activationCode = $activationCode;

        return $this;
    }

    /**
     * Get activationCode
     *
     * @return string 
     */
    public function getActivationCode()
    {
        return $this->activationCode;
    }

    /**
     * Add roles
     *
     * @param \Phil\UserBundle\Entity\Role $roles
     * @return User
     */
    public function addRole(Role $roles)
    {
        $this->roles[] = $roles;

        return $this;
    }

    /**
     * Remove roles
     *
     * @param \Phil\UserBundle\Entity\Role $roles
     */
    public function removeRole(Role $roles)
    {
        $this->roles->removeElement($roles);
    }

    /**
     * @inheritDoc
     */
    public function getRoles()
    {
        return $this->roles->toArray();
    }

    /**
     * Add pages
     *
     * @param \Phil\CMSBundle\Entity\Page $pages
     * @return User
     */
    public function addPage(Page $pages)
    {
        $this->pages[] = $pages;

        return $this;
    }

    /**
     * Remove pages
     *
     * @param \Phil\CMSBundle\Entity\Page $pages
     */
    public function removePage(Page $pages)
    {
        $this->pages->removeElement($pages);
    }

    /**
     * Get pages
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPages()
    {
        return $this->pages;
    }

    /**
     * Set resetTime
     *
     * @param \DateTime $resetTime
     * @return User
     */
    public function setResetTime($resetTime)
    {
        $this->resetTime = $resetTime;

        return $this;
    }

    /**
     * Get resetTime
     *
     * @return \DateTime 
     */
    public function getResetTime($format = null)
    {
        if (is_null($format))
        {
            return $this->resetTime;
        }

        return $this->resetTime->format($format);
    }

    /**
     * Set resetCode
     *
     * @param string $resetCode
     * @return User
     */
    public function setResetCode($resetCode)
    {
        $this->resetCode = $resetCode;

        return $this;
    }

    /**
     * Get resetCode
     *
     * @return string 
     */
    public function getResetCode()
    {
        return $this->resetCode;
    }

    public function resetResetCode()
    {
        $this->setResetCode($this->generateToken());
    }

    /**
     * Set emailVerified
     *
     * @param boolean $emailVerified
     * @return User
     */
    public function setEmailVerified($emailVerified)
    {
        $this->emailVerified = $emailVerified;

        return $this;
    }

    /**
     * Get emailVerified
     *
     * @return boolean 
     */
    public function getEmailVerified()
    {
        return $this->emailVerified;
    }

    /**
     * Is emailVerified
     *
     * @return boolean 
     */
    public function isEmailVerified()
    {
        return $this->emailVerified;
    }

    /**
     * Set passwordExpired
     *
     * @param boolean $passwordExpired
     * @return User
     */
    public function setPasswordExpired($passwordExpired)
    {
        $this->passwordExpired = $passwordExpired;

        return $this;
    }

    /**
     * Get passwordExpired
     *
     * @return boolean 
     */
    public function getPasswordExpired()
    {
        return $this->passwordExpired;
    }

    /**
     * Is passwordExpired
     *
     * @return boolean 
     */
    public function isPasswordExpired()
    {
        return $this->passwordExpired;
    }

/**
 * Utility Functions
 */
    private function generateToken()
    {
        $token = (time() + mt_rand(-1, 1) * mt_rand(0, time()) + mt_rand(-1, 1) * mt_rand(0, time()) + mt_rand(-1, 1) * mt_rand(0, time()) + mt_rand(-1, 1) * mt_rand(0, time())) * $this->getId();
        $token = password_hash($token, PASSWORD_BCRYPT, array('cost' => mt_rand(5, 15)));
        $token = hash("sha256", $token, false);
        return $token;
    }

    public function activate()
    {
        $this->setEnabled(true);
        $this->setActivationCode(null);
    }

    public function getResetExpiryTime($format = null)
    {
        $date = new \DateTime("@" . ($this->getResetTime('U') + 3600));

        if (is_null($format))
        {
            return $date;
        }

        return $date->format($format);
    }

    public function expirePassword()
    {
        $this->setPasswordExpired(true);
        $this->setResetTime(new \DateTime());
        $this->resetResetCode();
    }

    public function clearPasswordReset()
    {
        $this->setResetTime(null);
        $this->setResetCode(null);
        $this->setPasswordExpired(false);
    }

    public function initializeActivationCode()
    {
        $this->activationCode = $this->generateToken();
    }

/**
 *  Methods required for AdvancedUserInterface
 */

    /**
     * @inheritDoc
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
    }

    /**
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
        ));
    }

    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
        ) = unserialize($serialized);
    }

    /**
     * @inheritDoc
     */
    public function isAccountNonExpired()
    {
        return (false === $this->isExpired());
    }

    /**
     * @inheritDoc
     */
    public function isAccountNonLocked()
    {
        return (false === $this->isLocked());
    }

    /**
     * @inheritDoc
     */
    public function isCredentialsNonExpired()
    {
        return true;
    }
}
