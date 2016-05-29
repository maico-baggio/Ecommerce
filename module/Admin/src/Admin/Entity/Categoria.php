<?php

namespace Admin\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Model Categoria
 * @category Admin
 * @package Entity
 * @author Maico <e-mail>
 * 
 * @ORM\Entity
 * @ORM\Table (name = "categoria")
 */
class Categoria {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     *
     * @var int id_categoria
     */
    protected $id_categoria;

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    protected $descricao;

    /**
     * @ORM\OneToMany(targetEntity="Produto", mappedBy="categoria")
     *
     * @var ArrayCollection $produtos
     */
    protected $produtos;

    public function __construct() {
        $this->produtos = new ArrayCollection();
    }

    public function getArrayCopy() {
        return get_object_vars($this);
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }

    public function __get($name) {
        return $this->$name;
    }

}
