<?php
/**
 * Created by PhpStorm.
 * User: WestSIDE
 * Date: 9/3/2018
 * Time: 11:36 PM
 */

namespace tests\unit\entities;


use Codeception\Test\Unit;
use common\models\User;

class SignupTest extends Unit
{
    public function testSuccess()
    {
        $user = User::create(
            $username = 'username',
            $email = 'email@site.com',
            $password = 'password'
        );

        $this->assertEquals($username, $user->username);
        $this->assertEquals($email, $user->email);
        $this->assertNotEmpty($user->password_hash);
        $this->assertNotEquals($password, $user->password_hash);
        $this->assertNotEmpty($user->created_at);
        $this->assertNotEmpty($user->auth_key);
        $this->assertEquals($user->isActive());
    }
}