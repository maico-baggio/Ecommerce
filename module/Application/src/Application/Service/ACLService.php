<?php
namespace Application\Service;

use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Role\GenericRole as Role;
use Zend\Permissions\Acl\Resource\GenericResource as Resource;

class ACLService
{
    protected $sm;

    public function __construct($sm)
    {
        $this->sm = $sm;
    }

    public function build()
    {
        $config = $this->sm->get('Config');
        $acl = new Acl();

        foreach ($config['acl']['roles'] as $role => $parent)
            $acl->addRole(new Role($role), $parent);

        foreach ($config['acl']['resources'] as $r)
            $acl->addResource(new Resource($r));

        foreach ($config['acl']['privilege'] as $role => $privilege) {

            if (isset($privilege['allow'])) {

                foreach ($privilege['allow'] as $p)
                    $acl->allow($role, $p);
            }

            if (isset($privilege['deny'])) {

                foreach ($privilege['deny'] as $p)
                    $acl->deny($role, $p);
            }
        }

        return $acl;
    }
}