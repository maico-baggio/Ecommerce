<?php

namespace Admin\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Model Modelo
 * @category Admin
 * @package Entity
 * @author Maico
 */

/**
 * @ORM\Entity
 * @ORM\Table (name = "modelo")
 *
 * @author  Maico.baggio <maico.baggio@unochapeco.edu.br
 * @category Admin
 * @package Entity
 */
class Modelo {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    protected $id_modelo;

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    protected $descricao;

    /**
     * @ORM\OneToMany(targetEntity="Produto", mappedBy="modelo")
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
