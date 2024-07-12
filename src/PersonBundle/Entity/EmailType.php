<?php

namespace PersonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use CoreBundle\Traits\Timestampable;
use CoreBundle\Traits\TranslationTrait;
use CoreBundle\Traits\EntityTrait;

/**
 * EmailType
 *
 *
 * @ORM\Table(name="email_type")
 * @ORM\Entity(repositoryClass="PersonBundle\Repository\EmailTypeRepository")
 * @Gedmo\SoftDeleteable(fieldName="deleted_at", timeAware=false)
 */
class EmailType
{
    /**
     * @var int
     *
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     *
     * @ORM\Column(name="name", type="string")
     */
    private $name;

    /**
     *
     * @ORM\Column(name="strategy", type="string")
     */
    private $strategy;

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
     * Set name
     *
     * @param string $name
     *
     * @return EmailType
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Set strategy
     *
     * @param string $strategy
     *
     * @return EmailType
     */
    public function setStrategy($strategy)
    {
        $this->strategy = $strategy;

        return $this;
    }

    /**
     * Get strategy
     *
     * @return string
     */
    public function getStrategy()
    {
        return $this->strategy;
    }
}