<?php
namespace Auth\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table (name = "users")
 *
 * @author  Maico <email@email.com>
 * @category Auth
 * @package Entity
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    protected $id_user;

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    protected $nome;

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    protected $email;


    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    protected $login;

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    protected $password;

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    protected $role;//role

    /**
     * @ORM\Column(type="boolean")
     *
     * @var string
     */
    protected $active;

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    public function __get($name)
    {
        return $this->$name;
    }
}