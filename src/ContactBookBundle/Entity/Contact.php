<?php

namespace ContactBookBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Contact
 *
 * @ORM\Table(name="contact")
 * @ORM\Entity(repositoryClass="ContactBookBundle\Repository\ContactRepository")
 */
class Contact
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity="ContactBookBundle\Entity\ContactGroup", inversedBy="contacts" )
     * @ORM\JoinTable("contact_contact_group")
     */
    private $groups;

    /**
     * @ORM\OneToMany(targetEntity="ContactBookBundle\Entity\Address", mappedBy="contact")
     */

    private $addresses;

    /**
     * @ORM\OneToMany(targetEntity="ContactBookBundle\Entity\Phone", mappedBy="contact")
     *
     */
    private $phones;

    public function __construct()
    {
        $this->addresses = new ArrayCollection();
        $this->phones = new ArrayCollection();
        $this->emails = new ArrayCollection();
        $this->groups = new ArrayCollection();
    }

    /**
     * @ORM\OneToMany(targetEntity="ContactBookBundle\Entity\Email", mappedBy="contact")
     */
    private $emails;

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
     * @return Contact
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
     * Set description
     *
     * @param string $description
     * @return Contact
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
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
     * Add addresses
     *
     * @param \ContactBookBundle\Entity\Address $addresses
     * @return Contact
     */
    public function addAddress(\ContactBookBundle\Entity\Address $addresses)
    {
        $this->addresses[] = $addresses;

        return $this;
    }

    /**
     * Remove addresses
     *
     * @param \ContactBookBundle\Entity\Address $addresses
     */
    public function removeAddress(\ContactBookBundle\Entity\Address $addresses)
    {
        $this->addresses->removeElement($addresses);
    }

    /**
     * Get addresses
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAddresses()
    {
        return $this->addresses;
    }

    /**
     * Add phones
     *
     * @param \ContactBookBundle\Entity\Phone $phones
     * @return Contact
     */
    public function addPhone(\ContactBookBundle\Entity\Phone $phones)
    {
        $this->phones[] = $phones;

        return $this;
    }

    /**
     * Remove phones
     *
     * @param \ContactBookBundle\Entity\Phone $phones
     */
    public function removePhone(\ContactBookBundle\Entity\Phone $phones)
    {
        $this->phones->removeElement($phones);
    }

    /**
     * Get phones
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPhones()
    {
        return $this->phones;
    }

    /**
     * Add emails
     *
     * @param \ContactBookBundle\Entity\Email $emails
     * @return Contact
     */
    public function addEmail(\ContactBookBundle\Entity\Email $emails)
    {
        $this->emails[] = $emails;

        return $this;
    }

    /**
     * Remove emails
     *
     * @param \ContactBookBundle\Entity\Email $emails
     */
    public function removeEmail(\ContactBookBundle\Entity\Email $emails)
    {
        $this->emails->removeElement($emails);
    }

    /**
     * Get emails
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEmails()
    {
        return $this->emails;
    }

    /**
     * Add groups
     *
     * @param \ContactBookBundle\Entity\ContactGroup $groups
     * @return Contact
     */
    public function addGroup(\ContactBookBundle\Entity\ContactGroup $groups)
    {
        $this->groups[] = $groups;

        return $this;
    }

    /**
     * Remove groups
     *
     * @param \ContactBookBundle\Entity\ContactGroup $groups
     */
    public function removeGroup(\ContactBookBundle\Entity\ContactGroup $groups)
    {
        $this->groups->removeElement($groups);
    }

    /**
     * Get groups
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGroups()
    {
        return $this->groups;
    }
}
