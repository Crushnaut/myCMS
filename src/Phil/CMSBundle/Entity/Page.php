<?php
// src/Phil/CMSBundle/Entity/Page.php

namespace Phil\CMSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Phil\UserBundle\Entity\User;
use Phil\UserBundle\Entity\Role;
use Phil\CMSBundle\Entity\Category;

/**
 * @ORM\Entity(repositoryClass="Phil\CMSBundle\Entity\Repository\PageRepository")
 * @ORM\Table(name="pages")
 * @ORM\HasLifecycleCallbacks
 */
class Page
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=20, unique=true)
     */
    private $slug;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="pages")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    private $category;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @ORM\Column(type="datetime")
     */
    private $editted;

    /**
     * @ORM\Column(type="boolean")
     */
    private $visible;

    /**
     * @ORM\ManyToOne(targetEntity="\Phil\UserBundle\Entity\User", inversedBy="pages")
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="id")
     */
    private $owner;

    /**
     * @ORM\ManyToOne(targetEntity="\Phil\UserBundle\Entity\Role", inversedBy="viewPages")
     * @ORM\JoinColumn(name="viewRole_id", referencedColumnName="id")
     */
    private $viewRole;

    /**
     * @ORM\ManyToOne(targetEntity="\Phil\UserBundle\Entity\Role", inversedBy="editPages")
     * @ORM\JoinColumn(name="editRole_id", referencedColumnName="id")
     */
    private $editRole;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    public function __construct()
    {
        $this->setCreated(new \DateTime());
        $this->setEditted(new \DateTime());
        $this->setVisible();
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
     * Set title
     *
     * @param string $title
     * @return Page
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Page
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Page
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
     * Set editted
     *
     * @param \DateTime $editted
     * @return Page
     */
    public function setEditted($editted)
    {
        $this->editted = $editted;

        return $this;
    }

    /**
     * Get editted
     *
     * @return \DateTime 
     */
    public function getEditted()
    {
        return $this->editted;
    }

    /**
     * Set visible
     *
     * @return Page
     */
    public function setVisible()
    {
        $this->visible = true;

        return $this;
    }

    /**
     * Set invisible
     *
     * @return Page
     */
    public function setInvisible()
    {
        $this->visible = false;

        return $this;
    }

    /**
     * Is visible
     *
     * @return boolean 
     */
    public function isVisible()
    {
        return $this->visible;
    }

    /**
     * Set category
     *
     * @param \Phil\CMSBundle\Entity\Category $category
     * @return Page
     */
    public function setCategory(Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \Phil\CMSBundle\Entity\Category 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Get visible
     *
     * @return boolean 
     */
    public function getVisible()
    {
        return $this->visible;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Page
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set owner
     *
     * @param \Phil\UserBundle\Entity\User $owner
     * @return Page
     */
    public function setOwner(User $owner = null)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner
     *
     * @return \Phil\UserBundle\Entity\User 
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Set viewRole
     *
     * @param \Phil\UserBundle\Entity\Role $viewRole
     * @return Page
     */
    public function setViewRole(Role $viewRole = null)
    {
        $this->viewRole = $viewRole;

        return $this;
    }

    /**
     * Get viewRole
     *
     * @return \Phil\UserBundle\Entity\Role 
     */
    public function getViewRole()
    {
        return $this->viewRole;
    }

    /**
     * Set editRole
     *
     * @param \Phil\UserBundle\Entity\Role $editRole
     * @return Page
     */
    public function setEditRole(Role $editRole = null)
    {
        $this->editRole = $editRole;

        return $this;
    }

    /**
     * Get editRole
     *
     * @return \Phil\UserBundle\Entity\Role 
     */
    public function getEditRole()
    {
        return $this->editRole;
    }
}
