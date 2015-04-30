<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/** @ORM\Entity
 *  @ORM\Table(name="clients")
 */
class Clients {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /** @ORM\Column(name="fio", type="string") */
    protected $fio;

    /**
     * @var Service
     *
     * @ORM\ManyToOne(targetEntity="Service")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="service_id", referencedColumnName="id")
     * })
     */
    protected $service;

    /** @ORM\Column(name="diagnosis", type="string") */
    protected $diagnosis;

    /** @ORM\Column(name="contacts", type="string") */
    protected $contacts;

    /** @ORM\Column(name="dos", type="datetime") */
    protected $dos;

    /** @ORM\Column(name="status", type="string", columnDefinition="ENUM('Не обработан','В работе','Согласование','Думает','Архив','Пролечен','Записан в календарь')") */
    protected $status;

    /** @ORM\Column(name="comments", type="string") */
    protected $comments;

    /**
     * @var Country
     *
     * @ORM\ManyToOne(targetEntity="Country")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="country_id", referencedColumnName="id")
     * })
     */
    protected $country;

    /** @ORM\Column(name="contact_type", type="datetime") */
    protected $contactType;

    /** @ORM\Column(name="attachments", type="string") */
    protected $attachments;

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
     * @var Doctor
     *
     * @ORM\ManyToOne(targetEntity="Doctor")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="doctor_id", referencedColumnName="id")
     * })
     */
    protected $doctor;

    /** @ORM\Column(name="conclusion", type="string") */
    protected $conclusion;

    /** @ORM\Column(name="payment", type="string") */
    protected $payment;

    /** @ORM\Column(name="date_added", type="datetime") */
    protected $dateAdded;

    /**
     * @var Manager
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="manager_id", referencedColumnName="id")
     * })
     */
    protected $manager;

    /** @ORM\Column(name="informed", type="integer") */
    protected $informed;


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
     * Set fio
     *
     * @param string $fio
     */
    public function setFio($fio)
    {
        $this->fio = $fio;
    }
    /**
     * Get fio
     *
     * @return string
     */
    public function getFio()
    {
        return $this->fio;
    }

    /**
     * Set service
     *
     * @param \Application\Entity\Service $service
     * @return Clients
     */
    public function setService(Service $service = null)
    {
        $this->service = $service;

        return $this;
    }

    /**
     * Get service
     *
     * @return \Application\Entity\Service
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * Set diagnosis
     *
     * @param string $diagnosis
     */
    public function setDiagnosis($diagnosis)
    {
        $this->diagnosis = $diagnosis;
    }
    /**
     * Get $diagnosis
     *
     * @return string
     */
    public function getDiagnosis()
    {
        return $this->diagnosis;
    }

    /**
     * Set contacts
     *
     * @param string $contacts
     */
    public function setContacts($contacts)
    {
        $this->contacts = $contacts;
    }
    /**
     * Get contacts
     *
     * @return string
     */
    public function getContacts()
    {
        return $this->contacts;
    }

    /**
     * Set dos
     *
     * @param string $dos
     */
    public function setDOS($dos)
    {
        $this->dos = $dos;
    }
    /**
     * Get dos
     *
     * @return string
     */
    public function getDOS()
    {
        return $this->dos;
    }

    /**
     * Set status
     *
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }
    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set comments
     *
     * @param string $comments
     */
    public function setComments($comments)
    {
        $this->comments = $comments;
    }
    /**
     * Get comments
     *
     * @return string
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set country
     *
     * @param \Application\Entity\Country $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }
    /**
     * Get country
     *
     * @return \Application\Entity\Country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set contactType
     *
     * @param string $contactType
     */
    public function setContactType($contactType)
    {
        $this->contactType = $contactType;
    }
    /**
     * Get contactType
     *
     * @return string
     */
    public function getContactType()
    {
        return $this->contactType;
    }

    /**
     * Set attachments
     *
     * @param string $attachments
     */
    public function setAttachments($attachments)
    {
        $this->attachments = $attachments;
    }
    /**
     * Get attachments
     *
     * @return string
     */
    public function getAttachments()
    {
        return $this->attachments;
    }

    /**
     * Set clinic
     *
     * @param \Application\Entity\Clinic $clinic
     * @return Clients
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

    /**
     * Set doctor
     *
     * @param \Application\Entity\Doctor $doctor
     * @return Clients
     */
    public function setDoctor(Doctor $doctor = null)
    {
        $this->doctor = $doctor;

        return $this;
    }

    /**
     * Get doctor
     *
     * @return \Application\Entity\Doctor
     */
    public function getDoctor()
    {
        return $this->doctor;
    }

    /**
     * Set conclusion
     *
     * @param string $conclusion
     */
    public function setConclusion($conclusion)
    {
        $this->conclusion = $conclusion;
    }
    /**
     * Get conclusion
     *
     * @return string
     */
    public function getConclusion()
    {
        return $this->conclusion;
    }

    /**
     * Set payment
     *
     * @param string $payment
     */
    public function setPayment($payment)
    {
        $this->payment = $payment;
    }
    /**
     * Get payment
     *
     * @return string
     */
    public function getPayment()
    {
        return $this->payment;
    }

    /**
     * Set dateAdded
     *
     * @param string $dateAdded
     */
    public function setDateAdded($dateAdded)
    {
        $this->dateAdded = $dateAdded;
    }
    /**
     * Get dateAdded
     *
     * @return string
     */
    public function getDateAdded()
    {
        return $this->dateAdded;
    }

    /**
     * Set manager
     *
     * @param \Application\Entity\User $manager
     * @return Doctor
     */
    public function setManager(User $manager = null)
    {
        $this->manager = $manager;

        return $this;
    }

    /**
     * Get manager
     *
     * @return \Application\Entity\User
     */
    public function getManager()
    {
        return $this->manager;
    }

    /**
     * Set informed
     *
     * @param integer $informed
     */
    public function setInformed($informed)
    {
        $this->informed = $informed;
    }
    /**
     * Get informed
     *
     * @return integer
     */
    public function getInformed()
    {
        return $this->informed;
    }
}