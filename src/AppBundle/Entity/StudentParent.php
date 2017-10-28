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
     * @var string $email
     *
     * @ORM\Column(name="email", type="string", length=50, nullable=true)
     */
    protected $email;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    protected $name;

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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    function getUser() {
        return $this->user;
    }

    function getStudent() {
        return $this->student;
    }

    function getEmail() {
        return $this->email;
    }

    function getName() {
        return $this->name;
    }

    function getPhone() {
        return $this->phone;
    }

    function getCreatedAt() {
        return $this->createdAt;
    }

    function getUpdatedAt() {
        return $this->updatedAt;
    }

    function getDeletedAt() {
        return $this->deletedAt;
    }

    function setUser(User $user) {
        $this->user = $user;
    }

    function setStudent(Student $student) {
        $this->student = $student;
    }

    function setEmail($email) {
        $this->email = $email;
    }

    function setName($name) {
        $this->name = $name;
    }

    function setPhone($phone) {
        $this->phone = $phone;
    }

    function setCreatedAt(\Datetime $createdAt) {
        $this->createdAt = $createdAt;
    }

    function setUpdatedAt(\Datetime $updatedAt) {
        $this->updatedAt = $updatedAt;
    }

    function setDeletedAt(\DateTime $deletedAt) {
        $this->deletedAt = $deletedAt;
    }


}
