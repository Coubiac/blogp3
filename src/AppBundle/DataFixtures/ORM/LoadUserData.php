<?php

namespace AppBundle\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadUserData
    extends AbstractFixture
    implements OrderedFixtureInterface, ContainerAwareInterface
{

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * Load data fixtures with the passed EntityManager
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $userManager = $this->container->get('fos_user.user_manager');
        
        //Admin

        $admin = $userManager->createUser();
        $admin->setEmail('admin@admin.com');
        $admin->setUsername('admin');
        $admin->setPlainPassword('admin');
        $admin->setEnabled(true);
        $admin->addRole('ROLE_ADMIN');
        $admin->setAvatar('2e48978123d2a4bbd020083f5dadb220.png');
        $this->addReference('admin', $admin);
        
        //User 1
        $user1 = $userManager->createUser();
        $user1->setEmail('user1@user1.com');
        $user1->setUsername('user1');
        $user1->setPlainPassword('user1');
        $user1->setEnabled(true);
        $user1->addRole('ROLE_USER');
        $user1->setAvatar('2e48978123d2a4bbd020083f5dadb220.png');
        $this->addReference('user1', $user1);

        //User 2
        $user2 = $userManager->createUser();
        $user2->setEmail('user2@user2.com');
        $user2->setUsername('user2');
        $user2->setPlainPassword('user2');
        $user2->setEnabled(true);
        $user2->addRole('ROLE_USER');
        $user2->setAvatar('2e48978123d2a4bbd020083f5dadb220.png');
        $this->addReference('user2', $user2);

        //User 3
        $user3 = $userManager->createUser();
        $user3->setEmail('user3@user3.com');
        $user3->setUsername('user3');
        $user3->setPlainPassword('user3');
        $user3->setEnabled(true);
        $user3->addRole('ROLE_USER');
        $user3->setAvatar('2e48978123d2a4bbd020083f5dadb220.png');
        $this->addReference('user3', $user3);





        $userManager->updateUser($admin);
        $userManager->updateUser($user1);
        $userManager->updateUser($user2);
        $userManager->updateUser($user3);
    }

    /**
     * Sets the container.
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Get the order of this fixture
     * @return integer
     */
    public function getOrder()
    {
        return 1;
    }

}