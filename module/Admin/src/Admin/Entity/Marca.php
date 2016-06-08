<?php

namespace Admin\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Entity Marca
 * @category   Admin
 * @package    Entity
 * @author     Maico <e-mail@email.com>
 * @ORM\Entity
 * @ORM\Table  (name = "marca")
 */
class Marca
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     *
     * @var int $id_marca
     */
    protected $id_marca;

    /**
     * @ORM\Column(type="string")
     *
     * @var string $descricao
     */
    protected $descricao;

    /**
     * @ORM\OneToMany(targetEntity="Produto", mappedBy="marca")
     *
     * @var ArrayCollection $produtos
     */
    protected $produtos;

    public function __construct()
    {
        $this->produtos = new ArrayCollection();
    }

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
