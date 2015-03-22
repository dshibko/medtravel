<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/** @ORM\Entity
 *  @ORM\Table(name="doctor")
 */
class Doctor {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /** @ORM\Column(name="name", type="string") */
    protected $name;

    /**
     * @var Clinic
     *
     * @ORM\ManyToOne(targetEntity="Clinic")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="clinic_id", referencedColumnName="id")
     * })
     */
    protected $clinic;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * Set email
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
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
     * Set clinic
     *
     * @param \Application\Entity\Clinic $clinic
     * @return Doctor
     */
    public function setClinic(Clinic $clinic = null)
    {
        $this->clinic = $clinic;

        return $this;
    }

    /**
     * Get clinic
     *
     * @return \Application\Entity\Clinic
     */
    public function getClinic()
    {
        return $this->clinic;
    }
}