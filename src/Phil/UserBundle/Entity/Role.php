<?php
// src/Acme/Bundle/UserBundle/Entity/Role.php
namespace Phil\UserBundle\Entity;

use Symfony\Component\Security\Core\Role\RoleInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

use Phil\CMSBundle\Entity\Page;
use Phil\UserBundle\Entity\User;

/**
 * @ORM\Entity(repositoryClass="Phil\UserBundle\Entity\Repository\RoleRepository")
 * @ORM\Table(name="roles")
 * @ORM\HasLifecycleCallbacks
 */
class Role implements RoleInterface
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="name", type="string", length=30)
     */
    private $name;

    /**
     * @ORM\Column(name="role", type="string", length=20, unique=true)
     */
    private $role;

    /**
     * @ORM\ManyToMany(targetEntity="User", mappedBy="roles")
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity="\Phil\CMSBundle\Entity\Page", mappedBy="viewRole")
     */
    private $viewPages;

    /**
     * @ORM\OneToMany(targetEntity="\Phil\CMSBundle\Entity\Page", mappedBy="editRole")
     */
    private $editPages;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->viewPages = new ArrayCollection();
        $this->editPages = new ArrayCollection();
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
     * Set name
     *
     * @param string $name
     * @return Role
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set role
     *
     * @param string $role
     * @return Role
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @see RoleInterface
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Add users
     *
     * @param \Phil\UserBundle\Entity\User $users
     * @return Role
     */
    public function addUser(User $users)
    {
        $this->users[] = $users;

        return $this;
    }

    /**
     * Remove users
     *
     * @param \Phil\UserBundle\Entity\User $users
     */
    public function removeUser(User $users)
    {
        $this->users->removeElement($users);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Add viewPages
     *
     * @param \Phil\CMSBundle\Entity\Page $viewPages
     * @return Role
     */
    public function addViewPage(Page $viewPages)
    {
        $this->viewPages[] = $viewPages;

        return $this;
    }

    /**
     * Remove viewPages
     *
     * @param \Phil\CMSBundle\Entity\Page $viewPages
     */
    public function removeViewPage(Page $viewPages)
    {
        $this->viewPages->removeElement($viewPages);
    }

    /**
     * Get viewPages
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getViewPages()
    {
        return $this->viewPages;
    }

    /**
     * Add editPages
     *
     * @param \Phil\CMSBundle\Entity\Page $editPages
     * @return Role
     */
    public function addEditPage(Page $editPages)
    {
        $this->editPages[] = $editPages;

        return $this;
    }

    /**
     * Remove editPages
     *
     * @param \Phil\CMSBundle\Entity\Page $editPages
     */
    public function removeEditPage(Page $editPages)
    {
        $this->editPages->removeElement($editPages);
    }

    /**
     * Get editPages
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEditPages()
    {
        return $this->editPages;
    }
}
