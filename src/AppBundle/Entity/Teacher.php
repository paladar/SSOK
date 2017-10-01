<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\User;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Teacher
 *
 * @ORM\Table(name="teachers")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\TeacherRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Teacher {

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
     * @ORM\OneToOne(targetEntity="User", cascade={"persist"}, mappedBy="teacher")
     */
    protected $user;

    /**
     * @var ArrayCollection|Subjects[] $subjects
     *
     * @ORM\ManyToMany(targetEntity="Subject", mappedBy="teachers")
     */
    protected $subjects;

    /**
     * @var SchoolClass $class
     *
     * @ORM\OneToOne(targetEntity="SchoolClass", inversedBy="teacher", fetch="EAGER")
     * @ORM\JoinColumn(name="id_class", referencedColumnName="id", nullable=true)
     */
    protected $class;
    
    /**
     * @var ArrayCollection|StudentComment[] $studentComments
     *
     * @ORM\OneToMany(targetEntity="StudentComment", mappedBy="teacher", cascade={"all"}, orphanRemoval=true)
     * @ORM\OrderBy({"id"="ASC"})
     */
    protected $studentComments;
    

    /**
     * @var ArrayCollection|Lesson[] $lessons
     *
     * @ORM\OneToMany(targetEntity="Lesson", mappedBy="teacher", cascade={"all"}, orphanRemoval=true)
     * @ORM\OrderBy({"id"="ASC"})
     */
    protected $lessons;

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
        $this->subjects = new \Doctrine\Common\Collections\ArrayCollection();
        $this->studentComments = new \Doctrine\Common\Collections\ArrayCollection();
        $this->lessons = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set email
     *
     * @param string $email
     *
     * @return Teacher
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
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
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Teacher
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set surname
     *
     * @param string $surname
     *
     * @return Teacher
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * Get surname
     *
     * @return string
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return Teacher
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Teacher
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
     * @return Teacher
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
     * @return Teacher
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
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Teacher
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Add subject
     *
     * @param \AppBundle\Entity\Subject $subject
     *
     * @return Teacher
     */
    public function addSubject(\AppBundle\Entity\Subject $subject)
    {
        $this->subjects[] = $subject;

        return $this;
    }

    /**
     * Remove subject
     *
     * @param \AppBundle\Entity\Subject $subject
     */
    public function removeSubject(\AppBundle\Entity\Subject $subject)
    {
        $this->subjects->removeElement($subject);
    }

    /**
     * Get subjects
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSubjects()
    {
        return $this->subjects;
    }

    /**
     * Set class
     *
     * @param \AppBundle\Entity\SchoolClass $class
     *
     * @return Teacher
     */
    public function setClass(\AppBundle\Entity\SchoolClass $class = null)
    {
        $this->class = $class;

        return $this;
    }

    /**
     * Get class
     *
     * @return \AppBundle\Entity\SchoolClass
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Add studentComment
     *
     * @param \AppBundle\Entity\StudentComment $studentComment
     *
     * @return Teacher
     */
    public function addStudentComment(\AppBundle\Entity\StudentComment $studentComment)
    {
        $this->studentComments[] = $studentComment;

        return $this;
    }

    /**
     * Remove studentComment
     *
     * @param \AppBundle\Entity\StudentComment $studentComment
     */
    public function removeStudentComment(\AppBundle\Entity\StudentComment $studentComment)
    {
        $this->studentComments->removeElement($studentComment);
    }

    /**
     * Get studentComments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getStudentComments()
    {
        return $this->studentComments;
    }

    /**
     * Add lesson
     *
     * @param \AppBundle\Entity\Lesson $lesson
     *
     * @return Teacher
     */
    public function addLesson(\AppBundle\Entity\Lesson $lesson)
    {
        $this->lessons[] = $lesson;

        return $this;
    }

    /**
     * Remove lesson
     *
     * @param \AppBundle\Entity\Lesson $lesson
     */
    public function removeLesson(\AppBundle\Entity\Lesson $lesson)
    {
        $this->lessons->removeElement($lesson);
    }

    /**
     * Get lessons
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLessons()
    {
        return $this->lessons;
    }
}
