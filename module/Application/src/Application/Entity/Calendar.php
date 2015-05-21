<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/** @ORM\Entity
 *  @ORM\Table(name="calendar")
 */
class Calendar {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /** @ORM\Column(name="title", type="string") */
    protected $title;

    /** @ORM\Column(name="description", type="string") */
    protected $description;

    /** @ORM\Column(name="date", type="date") */
    protected $date;

    /**
     * @var Client
     *
     * @ORM\ManyToOne(targetEntity="Clients")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="client_id", referencedColumnName="id")
     * })
     */
    protected $client;

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
     * Set title
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
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
     * Set description
     *
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }
    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set date
     *
     * @param string $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }
    /**
     * Get date
     *
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set client
     *
     * @param \Application\Entity\Clients $client
     * @return Calendar
     */
    public function setClient(Clients $client = null)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client
     *
     * @return \Application\Entity\Clients
     */
    public function getClients()
    {
        return $this->client;
    }

}