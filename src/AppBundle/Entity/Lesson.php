<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Lesson
 *
 * @ORM\Table(name="lessons")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\LessonRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Lesson {

    /**
     * @var int $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Subject $subject
     *
     * @ORM\ManyToOne(targetEntity="Subject", inversedBy="lessons", fetch="EAGER")
     * @ORM\JoinColumn(name="id_subject", referencedColumnName="id", nullable=false)
     */
    protected $subject;

    /**
     * @var Teacher $teacher
     *
     * @ORM\ManyToOne(targetEntity="Teacher", inversedBy="lessons", fetch="EAGER")
     * @ORM\JoinColumn(name="id_teacher", referencedColumnName="id", nullable=false)
     */
    protected $teacher;
    
        /**
     * @var ArrayCollection|Presence[] $presences
     *
     * @ORM\OneToMany(targetEntity="Presence", mappedBy="lesson", cascade={"all"}, orphanRemoval=true)
     * @ORM\OrderBy({"id"="ASC"})
     */
    protected $presences;
    
    /**
     * @var \Datetime $start
     *
     * @ORM\Column(name="start", type="time")
     */
    protected $start;

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
     * Set start
     *
     * @param \DateTime $start
     *
     * @return Lesson
     */
    public function setStart($start)
    {
        $this->start = $start;

        return $this;
    }

    /**
     * Get start
     *
     * @return \DateTime
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Lesson
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
     * @return Lesson
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
     * @return Lesson
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
     * Set subject
     *
     * @param \AppBundle\Entity\Subject $subject
     *
     * @return Lesson
     */
    public function setSubject(\AppBundle\Entity\Subject $subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject
     *
     * @return \AppBundle\Entity\Subject
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set teacher
     *
     * @param \AppBundle\Entity\Teacher $teacher
     *
     * @return Lesson
     */
    public function setTeacher(\AppBundle\Entity\Teacher $teacher)
    {
        $this->teacher = $teacher;

        return $this;
    }

    /**
     * Get teacher
     *
     * @return \AppBundle\Entity\Teacher
     */
    public function getTeacher()
    {
        return $this->teacher;
    }

    /**
     * Add presence
     *
     * @param \AppBundle\Entity\Presence $presence
     *
     * @return Lesson
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
