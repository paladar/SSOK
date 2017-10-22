<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * SchoolClass
 *
 * @ORM\Table(name="school_classes")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\SchoolClassRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class SchoolClass {

    /**
     * @var int $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var ArrayCollection|Student[] $students
     *
     * @ORM\OneToMany(targetEntity="Student", mappedBy="schoolClass", cascade={"all"}, orphanRemoval=true)
     * @ORM\OrderBy({"id"="ASC"})
     */
    protected $students;

    /**
     * @var ArrayCollection|Lesson[] $lessons
     *
     * @ORM\OneToMany(targetEntity="Lesson", mappedBy="schoolClass", cascade={"all"}, orphanRemoval=true)
     * @ORM\OrderBy({"id"="ASC"})
     */
    protected $lessons;

    /**
     * @var ArrayCollection|Replecement[] $replecements
     *
     * @ORM\OneToMany(targetEntity="Replecement", mappedBy="class", cascade={"all"}, orphanRemoval=true)
     * @ORM\OrderBy({"id"="ASC"})
     */
    protected $replecements;

    /**
     * @var Teacher $teacher
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Teacher", cascade={"persist"}, mappedBy="class")
     */
    protected $teacher;

    /**
     * @var string $letter
     *
     * @ORM\Column(name="letter", type="string", length=5, nullable=false)
     */
    protected $letter;

    /**
     * @var string $number
     *
     * @ORM\Column(name="number", type="string", length=5, nullable=false)
     */
    protected $number;

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
        $this->students = new \Doctrine\Common\Collections\ArrayCollection();
        $this->lessons = new \Doctrine\Common\Collections\ArrayCollection();
        $this->replecements = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString() {
        return $this->getNumber() . ' ' . $this->getLetter();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set letter
     *
     * @param string $letter
     *
     * @return SchoolClass
     */
    public function setLetter($letter) {
        $this->letter = $letter;

        return $this;
    }

    /**
     * Get letter
     *
     * @return string
     */
    public function getLetter() {
        return $this->letter;
    }

    /**
     * Set number
     *
     * @param string $number
     *
     * @return SchoolClass
     */
    public function setNumber($number) {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number
     *
     * @return string
     */
    public function getNumber() {
        return $this->number;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return SchoolClass
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
     * @return SchoolClass
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
     * @return SchoolClass
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
     * Add student
     *
     * @param \AppBundle\Entity\Student $student
     *
     * @return SchoolClass
     */
    public function addStudent(\AppBundle\Entity\Student $student) {
        $this->students[] = $student;

        return $this;
    }

    /**
     * Remove student
     *
     * @param \AppBundle\Entity\Student $student
     */
    public function removeStudent(\AppBundle\Entity\Student $student) {
        $this->students->removeElement($student);
    }

    /**
     * Get students
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getStudents() {
        return $this->students;
    }

    /**
     * Add lesson
     *
     * @param \AppBundle\Entity\Lesson $lesson
     *
     * @return SchoolClass
     */
    public function addLesson(\AppBundle\Entity\Lesson $lesson) {
        $this->lessons[] = $lesson;

        return $this;
    }

    /**
     * Remove lesson
     *
     * @param \AppBundle\Entity\Lesson $lesson
     */
    public function removeLesson(\AppBundle\Entity\Lesson $lesson) {
        $this->lessons->removeElement($lesson);
    }

    /**
     * Get lessons
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLessons() {
        return $this->lessons;
    }

    /**
     * Add replecement
     *
     * @param \AppBundle\Entity\Replecement $replecement
     *
     * @return SchoolClass
     */
    public function addReplecement(\AppBundle\Entity\Replecement $replecement) {
        $this->replecements[] = $replecement;

        return $this;
    }

    /**
     * Remove replecement
     *
     * @param \AppBundle\Entity\Replecement $replecement
     */
    public function removeReplecement(\AppBundle\Entity\Replecement $replecement) {
        $this->replecements->removeElement($replecement);
    }

    /**
     * Get replecements
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReplecements() {
        return $this->replecements;
    }

    /**
     * Set teacher
     *
     * @param \AppBundle\Entity\Teacher $teacher
     *
     * @return SchoolClass
     */
    public function setTeacher(\AppBundle\Entity\Teacher $teacher = null) {
        $this->teacher = $teacher;

        return $this;
    }

    /**
     * Get teacher
     *
     * @return \AppBundle\Entity\Teacher
     */
    public function getTeacher() {
        return $this->teacher;
    }

}
