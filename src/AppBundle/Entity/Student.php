<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\User;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Student
 *
 * @ORM\Table(name="students")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\StudentRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Student {

    /**
     * @var int $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var User $user
     *
     * @ORM\OneToOne(targetEntity="User", cascade={"persist"}, mappedBy="student")
     */
    protected $user;

    /**
     * @var StudentParent $studentParent
     *
     * @ORM\OneToOne(targetEntity="StudentParent", cascade={"persist"}, inversedBy="student")
     * @ORM\JoinColumn(name="id_studentStudentParent", referencedColumnName="id")
     */
    protected $studentParent;

    /**
     * @var ArrayCollection|Grade[] $grades
     *
     * @ORM\OneToMany(targetEntity="Grade", mappedBy="student", cascade={"all"}, orphanRemoval=true)
     * @ORM\OrderBy({"id"="ASC"})
     */
    protected $grades;

    /**
     * @var ArrayCollection|Presence[] $presences
     *
     * @ORM\OneToMany(targetEntity="Presence", mappedBy="student", cascade={"all"}, orphanRemoval=true)
     * @ORM\OrderBy({"id"="ASC"})
     */
    protected $presences;

    /**
     * @var ArrayCollection|StudentComment[] $studentComments
     *
     * @ORM\OneToMany(targetEntity="StudentComment", mappedBy="student", cascade={"all"}, orphanRemoval=true)
     * @ORM\OrderBy({"id"="ASC"})
     */
    protected $studentComments;

    /**
     * @var SchoolClass $schoolClass
     *
     * @ORM\ManyToOne(targetEntity="SchoolClass", inversedBy="students", fetch="EAGER")
     * @ORM\JoinColumn(name="id_class", referencedColumnName="id", nullable=true)
     */
    protected $schoolClass;

    /**
     * @var string $email
     *
     * @ORM\Column(name="email", type="string", length=50, nullable=true)
     */
    protected $email;

    /**
     * @var string $firstName
     *
     * @ORM\Column(name="first_name", type="string", length=255, nullable=false)
     */
    protected $firstName;

    /**
     * @var string $surname
     *
     * @ORM\Column(name="surname", type="string", length=255, nullable=false)
     */
    protected $surname;

    /**
     * @var string $phone
     *
     * @ORM\Column(name="phone", type="string", length=255, nullable=true)
     */
    protected $phone;

    /**
     * @var string $address
     *
     * @ORM\Column(name="address", type="string", length=255, nullable=true)
     */
    protected $address;

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
        $this->studentParent = new StudentParent();
        $this->grades = new \Doctrine\Common\Collections\ArrayCollection();
        $this->presences = new \Doctrine\Common\Collections\ArrayCollection();
        $this->studentComments = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString() {
        return $this->getFirstName() . ' ' . $this->getSurname();
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
     * Set email
     *
     * @param string $email
     *
     * @return Student
     */
    public function setEmail($email) {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Student
     */
    public function setFirstName($firstName) {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName() {
        return $this->firstName;
    }

    /**
     * Set surname
     *
     * @param string $surname
     *
     * @return Student
     */
    public function setSurname($surname) {
        $this->surname = $surname;

        return $this;
    }

    /**
     * Get surname
     *
     * @return string
     */
    public function getSurname() {
        return $this->surname;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return Student
     */
    public function setPhone($phone) {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone() {
        return $this->phone;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return Student
     */
    public function setAddress($address) {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress() {
        return $this->address;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Student
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
     * @return Student
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
     * @return Student
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
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Student
     */
    public function setUser(\AppBundle\Entity\User $user = null) {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * Set studentParent
     *
     * @param \AppBundle\Entity\StudentParent $studentParent
     *
     * @return Student
     */
    public function setStudentParent(\AppBundle\Entity\StudentParent $studentParent = null) {
        $this->studentParent = $studentParent;

        return $this;
    }

    /**
     * Get studentParent
     *
     * @return \AppBundle\Entity\StudentParent
     */
    public function getStudentParent() {
        return $this->studentParent;
    }

    /**
     * Add grade
     *
     * @param \AppBundle\Entity\Grade $grade
     *
     * @return Student
     */
    public function addGrade(\AppBundle\Entity\Grade $grade) {
        $this->grades[] = $grade;

        return $this;
    }

    /**
     * Remove grade
     *
     * @param \AppBundle\Entity\Grade $grade
     */
    public function removeGrade(\AppBundle\Entity\Grade $grade) {
        $this->grades->removeElement($grade);
    }

    /**
     * Get grades
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGrades() {
        return $this->grades;
    }

    /**
     * Add presence
     *
     * @param \AppBundle\Entity\Presence $presence
     *
     * @return Student
     */
    public function addPresence(\AppBundle\Entity\Presence $presence) {
        $this->presences[] = $presence;

        return $this;
    }

    /**
     * Remove presence
     *
     * @param \AppBundle\Entity\Presence $presence
     */
    public function removePresence(\AppBundle\Entity\Presence $presence) {
        $this->presences->removeElement($presence);
    }

    /**
     * Get presences
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPresences() {
        return $this->presences;
    }

    /**
     * Add studentComment
     *
     * @param \AppBundle\Entity\StudentComment $studentComment
     *
     * @return Student
     */
    public function addStudentComment(\AppBundle\Entity\StudentComment $studentComment) {
        $this->studentComments[] = $studentComment;

        return $this;
    }

    /**
     * Remove studentComment
     *
     * @param \AppBundle\Entity\StudentComment $studentComment
     */
    public function removeStudentComment(\AppBundle\Entity\StudentComment $studentComment) {
        $this->studentComments->removeElement($studentComment);
    }

    /**
     * Get studentComments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getStudentComments() {
        return $this->studentComments;
    }

    /**
     * Set class
     *
     * @param \AppBundle\Entity\SchoolClass $schoolClass
     *
     * @return Student
     */
    public function setSchoolClass(\AppBundle\Entity\SchoolClass $schoolClass) {
        $this->schoolClass = $schoolClass;

        return $this;
    }

    /**
     * Get class
     *
     * @return \AppBundle\Entity\SchoolClass
     */
    public function getSchoolClass() {
        return $this->schoolClass;
    }

}
