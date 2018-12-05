<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends BaseFixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(10, 'main_users', function($i) {
            $user = new User();
            $user->setEmail(sprintf('spacebar%d@example.com', $i));
            $user->setFirstName($this->faker->firstName);
            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                'foo'
            ));

            return $user;
        });

        $this->createMany(3, 'admin_users', function($i) {
            $adminUser = new User();
            $adminUser->setEmail(sprintf('admin%d@example.com', $i));
            $adminUser->setFirstName($this->faker->firstName);
            $adminUser->setPassword($this->passwordEncoder->encodePassword(
                $adminUser,
                'foo'
            ));
            $adminUser->setRoles(['ROLE_ADMIN']);

            return $adminUser;
        });

        $manager->flush();
    }
}
