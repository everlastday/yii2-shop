<?php

namespace frontend\services\auth;

use common\entities\User;
use frontend\forms\SignupForm;

class SignupService
{
    public function signup(SignupForm $form): User
    {
        $user = User::requestSignup(
            $form->username,
            $form->email,
            $form->password
        );

        $this->save($user);

        $sent = $this->mailer->compose(
            ['html' => 'emailConfirmToken-html', 'text' => 'emailConfirmToken-text'],
            ['user' => $user]
        )->setTo($form->email)
            ->setSubject('Signup confirm for ' . \Yii::$app->name)
            ->send();

        if (!$sent) {
            throw new \RuntimeException('Email sending error.');
        }
    }

    public function confirm($token): void
    {
        if (empty($token)) {
            throw new \DomainException('Empty confirm token');
        }

        $user = $this->getByEmailConfirmToken($token);

        $user->confirmSignup();

        $this->save($user);
    }


    private function getByEmailConfirmToken(string $token): User
    {
        $user = User::findOne(['email_confirm_token' => $token]);
        if (!$user) {
            throw new \DomainException('User not found.');
        }
        return $user;
    }

    private function save(User $user): void
    {
        if (!$user->save()) {
            throw new \RuntimeException('Saving error.');
        }
    }

}