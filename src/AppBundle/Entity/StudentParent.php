<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\User;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * StudentParent
 *
 * @ORM\Table(name="student_parents")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\StudentParentRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class StudentParent {

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
     * @ORM\OneToOne(targetEntity="User", cascade={"persist"}, mappedBy="studentParent")
     */
    protected $user;

    /**
     * @var Student $student
     *
     * @ORM\OneToOne(targetEntity="Student", cascade={"persist"}, mappedBy="studentParent")
     */
    protected $student;

    /**
     * @var string $motherEmail
     *
     * @ORM\Column(name="mother_email", type="string", length=50, nullable=true)
     */
    protected $motherEmail;

    /**
     * @var string $fatherEmail
     *
     * @ORM\Column(name="father_email", type="string", length=50, nullable=true)
     */
    protected $fatherEmail;

    /**
     * @var string $motherName
     *
     * @ORM\Column(name="mother_name", type="string", length=255, nullable=true)
     */
    protected $motherName;

    /**
     * @var string $fatherName
     *
     * @ORM\Column(name="father_name", type="string", length=255, nullable=true)
     */
    protected $fatherName;

    /**
     * @var string $motherPhone
     *
     * @ORM\Column(name="mother_phone", type="string", length=255, nullable=true)
     */
    protected $motherPhone;

    /**
     * @var string $fatherPhone
     *
     * @ORM\Column(name="father_phone", type="string", length=255, nullable=true)
     */
    protected $fatherPhone;

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
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set motherEmail
     *
     * @param string $motherEmail
     *
     * @return StudentParent
     */
    public function setMotherEmail($motherEmail)
    {
        $this->motherEmail = $motherEmail;

        return $this;
    }

    /**
     * Get motherEmail
     *
     * @return string
     */
    public function getMotherEmail()
    {
        return $this->motherEmail;
    }

    /**
     * Set fatherEmail
     *
     * @param string $fatherEmail
     *
     * @return StudentParent
     */
    public function setFatherEmail($fatherEmail)
    {
        $this->fatherEmail = $fatherEmail;

        return $this;
    }

    /**
     * Get fatherEmail
     *
     * @return string
     */
    public function getFatherEmail()
    {
        return $this->fatherEmail;
    }

    /**
     * Set motherName
     *
     * @param string $motherName
     *
     * @return StudentParent
     */
    public function setMotherName($motherName)
    {
        $this->motherName = $motherName;

        return $this;
    }

    /**
     * Get motherName
     *
     * @return string
     */
    public function getMotherName()
    {
        return $this->motherName;
    }

    /**
     * Set fatherName
     *
     * @param string $fatherName
     *
     * @return StudentParent
     */
    public function setFatherName($fatherName)
    {
        $this->fatherName = $fatherName;

        return $this;
    }

    /**
     * Get fatherName
     *
     * @return string
     */
    public function getFatherName()
    {
        return $this->fatherName;
    }

    /**
     * Set motherPhone
     *
     * @param string $motherPhone
     *
     * @return StudentParent
     */
    public function setMotherPhone($motherPhone)
    {
        $this->motherPhone = $motherPhone;

        return $this;
    }

    /**
     * Get motherPhone
     *
     * @return string
     */
    public function getMotherPhone()
    {
        return $this->motherPhone;
    }

    /**
     * Set fatherPhone
     *
     * @param string $fatherPhone
     *
     * @return StudentParent
     */
    public function setFatherPhone($fatherPhone)
    {
        $this->fatherPhone = $fatherPhone;

        return $this;
    }

    /**
     * Get fatherPhone
     *
     * @return string
     */
    public function getFatherPhone()
    {
        return $this->fatherPhone;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return StudentParent
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
     * @return StudentParent
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
     * @return StudentParent
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
     * @return StudentParent
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
     * Set student
     *
     * @param \AppBundle\Entity\Student $student
     *
     * @return StudentParent
     */
    public function setStudent(\AppBundle\Entity\Student $student = null)
    {
        $this->student = $student;

        return $this;
    }

    /**
     * Get student
     *
     * @return \AppBundle\Entity\Student
     */
    public function getStudent()
    {
        return $this->student;
    }
}
