<?php

namespace Vivait\UserBundle\Context\Dictionary;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\SecurityContext;
use Vivait\UserBundle\Model\BaseUser;

trait UserDictionary
{

    /**
     * @Transform :user
     * @Transform :staff
     * @param string $user
     * @throws \Exception
     * @return BaseUser
     */
    public function castUsernameToUser($user)
    {
        $username = $user;
        $em = $this->getContainer()->get('doctrine')->getManager();

        $user = $em
            ->getRepository('Vivait\UserBundle\Model\BaseUser')
            ->findOneBy(
                [
                    'username' => $user
                ]
            );

        if (!$user) {
            throw new \Exception(sprintf('User "%s" could not be found', $username));
        }

        return $user;
    }

    /**
     * @param $quantity
     * @param string $user_type
     * @return array|null|object
     * @throws \Exception
     */
    public function randomUsers($quantity, $user_type = 'all')
    {
        /** @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine')->getManager();

        switch($user_type){
            case 'customers':
                /** @var EntityRepository $repo */
                $repo = $em->getRepository('Vivait\UserBundle\Adapter\Customer');
                break;
            case 'users':
                $repo = $em->getRepository('Vivait\UserBundle\Model\User'); break;
            case 'staff':
                $repo = $em->getRepository('Vivait\UserBundle\Model\Staff');
                break;
            case 'all':
                $repo = $em->getRepository('Vivait\UserBundle\Model\BaseUser'); break;
            default:
                throw new \Exception(sprintf('Could not find the "%s" user type', $user_type));
        }

        $ids = $repo->createQueryBuilder('e')->select('e.id')->getQuery()->getScalarResult();
        $ids = array_map('current', $ids);

        if($quantity > count($ids)){
            throw new \Exception("There are not enough users");
        }

        if ($quantity == 1) {
            $id = array_rand($ids, 1);
            return $repo->find($id);
        } else {
            $random_ids = [];
            foreach(array_rand($ids, $quantity) as $key){
                $random_ids[] = $ids[$key];
            }

            $users = $repo->createQueryBuilder('u')
                          ->select('u')
                          ->andWhere('u.id IN(:ids)')
                          ->setParameter('ids', $random_ids)
                          ->getQuery()
                          ->getResult();
            return $users;
        }
    }

    public function getCurrentUser(SecurityContext $context = null)
    {
        if(!$context){
            /** @var SecurityContext $context */
            $context = $this->getContainer()->get('security.context');
        }

        return $context->getToken()->getUser();
    }
} 