<?php
/**
 * Created by PhpStorm.
 * User: WestSIDE
 * Date: 11/27/2018
 * Time: 1:22 AM
 */

namespace common\repositories;


use common\entities\User;

class UserRepository
{
    public function getByEmailConfirmToken($token)
    {
        return $this->getBy(['email_confirm_token' => $token]);
    }

    public function getByEmail(string $email): User
    {
        if (!$user = User::findOne(['email' => $email])) {
            throw new \DomainException('User is not found.');
        }
        return $user;
    }

    public function existsByPasswordResetToken(string $token)
    {
        return (bool)User::findByPasswordResetToken($token);
    }

    public function getByPasswordResetToken(string $token)
    {

        if (!$user = User::findByPasswordResetToken($token)) {
            throw new \DomainException('User is not found');
        }
        return $user;
    }

    public function save(User $user): void
    {
        if (!$user->save()) {
            throw new \RuntimeException('Saving error');
        }
    }

    private function getBy(array $condition)
    {
        if (!$user = User::find()->andWhere($condition)->limit(1)->one()) {
            throw new NotFoundException('User not found');
        }
        return $user;
    }
}