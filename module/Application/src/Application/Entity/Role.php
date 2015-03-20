<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Role
 *
 * @ORM\Table(name="role")
 * @ORM\Entity
 */
class Role {

     const ADMIN = 'admin';
     const _MANAGER = 'manager';
     const USER = 'user';
     const GUEST = 'guest';

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=20, nullable=false)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Application\Entity\Role
     *
     * @ORM\ManyToOne(targetEntity="Role")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     * })
     */
    private $parent;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Permission", inversedBy="roles")
     * @ORM\JoinTable(name="role_permission",
     *   joinColumns={
     *     @ORM\JoinColumn(name="role_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="perm_id", referencedColumnName="id")
     *   }
     * )
     */
    private $permissions;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->permissions = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Set name
     *
     * @param string $name
     * @return \Application\Entity\Role
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set parent
     *
     * @param \Application\Entity\Role $parent
     * @return \Application\Entity\Role
     */
    public function setParent(\Application\Entity\Role $parent = null)
    {
        $this->parent = $parent;
    
        return $this;
    }

    /**
     * Get parent
     *
     * @return \Application\Entity\Role
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add permissions
     *
     * @param \Application\Entity\Permission $permissions
     * @return \Application\Entity\Role
     */
    public function addPermission($permissions)
    {
        $this->permissions[] = $permissions;
    
        return $this;
    }

    /**
     * Remove permissions
     *
     * @param \Application\Entity\Permission $permissions
     */
    public function removePermission($permissions)
    {
        $this->permissions->removeElement($permissions);
    }

    /**
     * Get permissions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPermissions()
    {
        return $this->permissions;
    }
}
