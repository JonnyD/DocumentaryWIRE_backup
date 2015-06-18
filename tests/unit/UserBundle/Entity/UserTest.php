<?php

use EL\UserBundle\Entity\User;
use EL\ManagerBundle\Entity\Manager;
use Mockery as m;

class UserTest extends \Codeception\TestCase\Test
{
    protected function _before()
    {
        /* blank */
    }

    protected function _after()
    {
        m::close();
    }

    public function testManager()
    {
        $manager = new Manager();

        $user = new User();
        $user->setManager($manager);

        $this->assertNotNull($user->getManager());
        $this->assertEquals($user->getManager(), $manager);
    }
}