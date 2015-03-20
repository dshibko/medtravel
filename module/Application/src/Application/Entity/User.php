<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/** @ORM\Entity
 *  @ORM\Table(name="user")
 */
class User {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /** @ORM\Column(name="display_name", type="string") */
    protected $displayName;

    /** @ORM\Column(name="email", type="string") */
    protected $email;

    /** @ORM\Column(name="password", type="string") */
    protected $password;

    /**
     * @var Role
     *
     * @ORM\ManyToOne(targetEntity="Role")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="role_id", referencedColumnName="id")
     * })
     */
    protected $role;

    /**
     * Set email
     *
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
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
     * Set password
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }
    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }
    /**
     * Set password
     *
     * @param string $password
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
    }
    /**
     * Get password
     *
     * @return string
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }
}