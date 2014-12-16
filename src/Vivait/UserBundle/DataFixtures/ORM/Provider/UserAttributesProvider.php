<?php

namespace Vivait\UserBundle\DataFixtures\ORM\Provider;

class UserAttributesProvider
{
    public function createInitials($name)
    {
        $names = explode(' ', $name);
        $initials = "";
        foreach($names as $name){
            $initials .= $name[0];
        }
        return $initials;
    }

    public function createUsername($name)
    {
        return preg_replace('/[^[:alnum:]]/u', '', $name);
    }
}