<?php

namespace AppBundle\Entity;



use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;


class User extends BaseUser implements \Azine\EmailBundle\Entity\RecipientInterface
{
    
    protected $id;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }
}
