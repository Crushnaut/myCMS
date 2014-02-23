<?php
// src/Phil/CMSBundle/Entity/Page.php

namespace Phil\CMSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Phil\CMSBundle\Entity\Repository\PageRepository")
 * @ORM\Table(name="page")
 * @ORM\HasLifecycleCallbacks
 */
class Page
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $title;

    /**
     * @ORM\Column(type="string", length=20)
     */
    protected $slug;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="pages")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    protected $category;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $editted;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $visible;

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
    public function setCategory(\Phil\CMSBundle\Entity\Category $category = null)
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
}
