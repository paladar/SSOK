<?php

namespace AppBundle\Entity;


use AppBundle\Entity\Student;
use AppBundle\Entity\StudentParent;
use AppBundle\Entity\Teacher;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var Student $student
     *
     * @ORM\OneToOne(targetEntity="Student", cascade={"persist"}, inversedBy="user")
     * @ORM\JoinColumn(name="id_student", referencedColumnName="id", nullable=true)
     */
    protected $student;

    /**
     * @var StudentParent $studentParent
     *
     * @ORM\OneToOne(targetEntity="StudentParent", cascade={"persist"}, inversedBy="user")
     * @ORM\JoinColumn(name="id_student_parent", referencedColumnName="id", nullable=true)
     */
    protected $studentParent;

    /**
     * @var Teacher $teacher
     *
     * @ORM\OneToOne(targetEntity="Teacher", cascade={"persist"}, inversedBy="user")
     * @ORM\JoinColumn(name="id_teacher", referencedColumnName="id", nullable=true)
     */
    protected $teacher;

    public function __construct() {
        parent::__construct();
        // your own logic
    }


    /**
     * Set student
     *
     * @param \AppBundle\Entity\Student $student
     *
     * @return User
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

    /**
     * Set studentParent
     *
     * @param \AppBundle\Entity\StudentParent $studentParent
     *
     * @return User
     */
    public function setStudentParent(\AppBundle\Entity\StudentParent $studentParent = null)
    {
        $this->studentParent = $studentParent;

        return $this;
    }

    /**
     * Get studentParent
     *
     * @return \AppBundle\Entity\StudentParent
     */
    public function getStudentParent()
    {
        return $this->studentParent;
    }

    /**
     * Set teacher
     *
     * @param \AppBundle\Entity\Teacher $teacher
     *
     * @return User
     */
    public function setTeacher(\AppBundle\Entity\Teacher $teacher = null)
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
}
