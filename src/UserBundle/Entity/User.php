<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;
use PersonBundle\Entity\RealPerson;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\UserRepository")
 * @Gedmo\SoftDeleteable(fieldName="deleted_at", timeAware=false)
 */
class User implements UserInterface
{

    const PASS_PLAIN = "Agente001";
    const PASS_HASH = '$2y$10$Ly.UGI1d72/VDeZdZTj9p.LwGA8QDpoobw8/oXTyE/GsJfHGOKgK6';

    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="PersonBundle\Entity\Email", cascade={"persist"})
     */
    private $email;

    /**
     * @ORM\Column(name="username", type="string")
     */
    private $username;

    /**
     * @ORM\Column(name="password", type="string")
     */
    private $password;

    /**
     * @ORM\ManyToOne(targetEntity="Rol", inversedBy="users", cascade={"persist"})
     */
    private $rol;

    /**
     * @ORM\Column(name="locale", type="string", nullable=true)
     */
    private $locale;

    /**
     * @var Collection
     * @ORM\Column(type="json")
     */
    private $roles;

    /**
     * Set userEmail
     *
     * @param \PersonBundle\Entity\Email $userEmail
     *
     * @return User
     */

    // public function setEmail(\PersonBundle\Entity\Email $email = null)
    // {
    //     if ($email) {
    //         $email->setPerson($this);
    //     }

    //     $this->email = $email;

    //     return $this;
    // }

    /**
     * Get email
     *
     * @param string $email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set username
     *
     * @param string $username
     *
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set rol
     *
     * @param \UserBundle\Entity\Rol $rol
     *
     * @return User
     */
    public function setRol(\UserBundle\Entity\Rol $rol = null)
    {
        $this->rol = $rol;

        return $this;
    }

    /**
     * Get rol
     *
     * @return \UserBundle\Entity\Rol
     */
    public function getRol()
    {
        return $this->rol;
    }

    public function getRoles(): array
    {
        return $this->roles->toArray();
    }

    public function getUserIdentifier(): string
    {
        return $this->username;
    }

    public function eraseCredentials()
    {
        return null;
    }

    public function getSalt()
    {
        // The bcrypt algorithm doesn't require a separate salt.
        // You *may* need a real salt if you choose a different encoder.
        return null;
    }

    /**
     * Set locale
     *
     * @param string $locale
     *
     * @return User
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * Get locale
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }
}