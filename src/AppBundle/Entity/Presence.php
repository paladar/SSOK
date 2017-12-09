<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Presence
 *
 * @ORM\Table(name="presences")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\PresenceRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Presence {

    /**
     * @var int $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Student $student
     *
     * @ORM\ManyToOne(targetEntity="Student", inversedBy="presences", fetch="EAGER")
     * @ORM\JoinColumn(name="id_student", referencedColumnName="id", nullable=false)
     */
    protected $student;

    /**
     * @var Lesson $lesson
     *
     * @ORM\ManyToOne(targetEntity="Lesson", inversedBy="presences", fetch="EAGER")
     * @ORM\JoinColumn(name="id_lesson", referencedColumnName="id", nullable=false)
     */
    protected $lesson;

    /**
     * @var PresenceType $presenceType
     *
     * @ORM\ManyToOne(targetEntity="PresenceType", inversedBy="presences", fetch="EAGER")
     * @ORM\JoinColumn(name="id_presence_type", referencedColumnName="id", nullable=false)
     */
    protected $presenceType;

    /**
     * @var boolean $checked
     *
     * @ORM\Column(name="checked", type="boolean")
     */
    private $checked = false;

    /**
     * @var \Datetime $date
     *
     * @ORM\Column(name="date", type="date")
     */
    protected $date;

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
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Presence
     */
    public function setCreatedAt($createdAt) {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt() {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Presence
     */
    public function setUpdatedAt($updatedAt) {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt() {
        return $this->updatedAt;
    }

    /**
     * Set deletedAt
     *
     * @param \DateTime $deletedAt
     *
     * @return Presence
     */
    public function setDeletedAt($deletedAt) {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * Get deletedAt
     *
     * @return \DateTime
     */
    public function getDeletedAt() {
        return $this->deletedAt;
    }

    /**
     * Set student
     *
     * @param \AppBundle\Entity\Student $student
     *
     * @return Presence
     */
    public function setStudent(\AppBundle\Entity\Student $student) {
        $this->student = $student;

        return $this;
    }

    /**
     * Get student
     *
     * @return \AppBundle\Entity\Student
     */
    public function getStudent() {
        return $this->student;
    }

    /**
     * Set lesson
     *
     * @param \AppBundle\Entity\Lesson $lesson
     *
     * @return Presence
     */
    public function setLesson(\AppBundle\Entity\Lesson $lesson) {
        $this->lesson = $lesson;

        return $this;
    }

    /**
     * Get lesson
     *
     * @return \AppBundle\Entity\Lesson
     */
    public function getLesson() {
        return $this->lesson;
    }

    /**
     * Set presenceType
     *
     * @param \AppBundle\Entity\PresenceType $presenceType
     *
     * @return Presence
     */
    public function setPresenceType(\AppBundle\Entity\PresenceType $presenceType) {
        $this->presenceType = $presenceType;

        return $this;
    }

    /**
     * Get presenceType
     *
     * @return \AppBundle\Entity\PresenceType
     */
    public function getPresenceType() {
        return $this->presenceType;
    }

}
