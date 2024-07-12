<?php

namespace PersonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use CoreBundle\Traits\Timestampable;
use PersonBundle\EntityTrait\EmailEntityTrait;

/**
 * Email
 *
 * @ORM\Table(name="email")
 * @ORM\Entity(repositoryClass="PersonBundle\Repository\EmailRepository")
 * @Gedmo\SoftDeleteable(fieldName="deleted_at", timeAware=false)
 */
class Email
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     *
     * @ORM\ManyToOne(targetEntity="EmailType", cascade={"persist"})
     */
    private $type;

    /**
     * @ORM\Column(name="value", type="string", nullable=true)
     */
    private $value;

    /**
     * @ORM\ManyToOne(targetEntity="Person", inversedBy="emails")
     */
    private $person;

    public function __toString()
    {
        return "{$this->getValue()}";
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
     * Set value
     *
     * @param string $value
     *
     * @return Email
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    // /**
    //  * Set person
    //  *
    //  * @param \PersonBundle\Entity\Person $person
    //  *
    //  * @return Email
    //  */
    // public function setPerson(\PersonBundle\Entity\Person $person = null)
    // {
    //     $this->person = $person;

    //     return $this;
    // }

    // /**
    //  * Get person
    //  *
    //  * @return \PersonBundle\Entity\Person
    //  */
    // public function getPerson()
    // {
    //     return $this->person;
    // }

    /**
     * Set type
     *
     * @param \PersonBundle\Entity\EmailType $type
     *
     * @return Email
     */
    public function setType(\PersonBundle\Entity\EmailType $type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \PersonBundle\Entity\EmailType
     */
    public function getType()
    {
        return $this->type;
    }
}