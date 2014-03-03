<?php
// src/Phil/CMSBundle/Entity/Category.php

namespace Phil\CMSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="Phil\CMSBundle\Entity\Repository\CategoryRepository")
 * @ORM\Table(name="categories")
 * @ORM\HasLifecycleCallbacks
 */
class Category
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
    private $name;

    /**
     * @ORM\Column(type="string", length=20, unique=true)
     */
    private $slug;

    /**
     * @ORM\Column(type="integer")
     */
    private $menuorder;

    /**
     * @ORM\Column(type="boolean")
     */
    private $visible;

    /**
     * @ORM\OneToMany(targetEntity="Page", mappedBy="category")
     */
    private $pages;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pages = new ArrayCollection();
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
     * Set name
     *
     * @param string $name
     * @return Category
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
     * Set slug
     *
     * @param string $slug
     * @return Category
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
     * Set visible
     *
     * @return Category
     */
    public function setVisible()
    {
        $this->visible = true;

        return $this;
    }

    /**
     * Set invisible
     *
     * @return Category
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
     * Add pages
     *
     * @param \Phil\CMSBundle\Entity\Page $pages
     * @return Category
     */
    public function addPage(\Phil\CMSBundle\Entity\Page $pages)
    {
        $this->pages[] = $pages;

        return $this;
    }

    /**
     * Remove pages
     *
     * @param \Phil\CMSBundle\Entity\Page $pages
     */
    public function removePage(\Phil\CMSBundle\Entity\Page $pages)
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
     * Set menuorder
     *
     * @param integer $menuorder
     * @return Category
     */
    public function setMenuorder($menuorder)
    {
        $this->menuorder = $menuorder;

        return $this;
    }

    /**
     * Get menuorder
     *
     * @return integer 
     */
    public function getMenuorder()
    {
        return $this->menuorder;
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
