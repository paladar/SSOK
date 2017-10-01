<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * PresenceType
 *
 * @ORM\Table(name="presenceTypes")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\PresenceTypeRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class PresenceType {

    /**
     * @var int $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var ArrayCollection|Presence[] $presences
     *
     * @ORM\OneToMany(targetEntity="Presence", mappedBy="presenceType", cascade={"all"}, orphanRemoval=true)
     * @ORM\OrderBy({"id"="ASC"})
     */
    protected $presences;

    /**
     * @var string $label
     *
     * @ORM\Column(name="label", type="string", length=255, nullable=false)
     */
    private $label;

    /**
     * @var string $description
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @var boolean $countAsAbsence
     *
     * @ORM\Column(name="count_as_absence", type="boolean")
     */
    private $countAsAbsence = true;

    /**
     * @var \Datetime $createdAt
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;

    /**
     * @var \Datetime $createdAt
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated_at", type="datetime")
     */
    protected $updatedAt;

    /**
     * @var \DateTime $deletedAt
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $deletedAt;

    /**
     * Constructor
     */
    public function __construct() {
        $this->presences = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set label
     *
     * @param string $label
     *
     * @return PresenceType
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return PresenceType
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
     * Set countAsAbsence
     *
     * @param boolean $countAsAbsence
     *
     * @return PresenceType
     */
    public function setCountAsAbsence($countAsAbsence)
    {
        $this->countAsAbsence = $countAsAbsence;

        return $this;
    }

    /**
     * Get countAsAbsence
     *
     * @return boolean
     */
    public function getCountAsAbsence()
    {
        return $this->countAsAbsence;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return PresenceType
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return PresenceType
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set deletedAt
     *
     * @param \DateTime $deletedAt
     *
     * @return PresenceType
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * Get deletedAt
     *
     * @return \DateTime
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * Add presence
     *
     * @param \AppBundle\Entity\Presence $presence
     *
     * @return PresenceType
     */
    public function addPresence(\AppBundle\Entity\Presence $presence)
    {
        $this->presences[] = $presence;

        return $this;
    }

    /**
     * Remove presence
     *
     * @param \AppBundle\Entity\Presence $presence
     */
    public function removePresence(\AppBundle\Entity\Presence $presence)
    {
        $this->presences->removeElement($presence);
    }

    /**
     * Get presences
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPresences()
    {
        return $this->presences;
    }
}
