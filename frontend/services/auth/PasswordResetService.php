<?php

namespace frontend\services\auth;

use common\entities\User;
use common\repositories\UserRepository;
use frontend\forms\PasswordResetRequestForm;
use frontend\forms\ResetPasswordForm;
use yii\mail\MailerInterface;


class PasswordResetService
{
    private $mailer;
    private $users;

    public function __construct(MailerInterface $mailer, UserRepository $users)
    {
        $this->users = $users;
        $this->mailer = $mailer;
    }


    public function request(PasswordResetRequestForm $form): void
    {
        $user = $this->users->getByEmail($form->email);

        if (!$user->isActive()) {
            throw new \DomainException('User is not active.');
        }

        $user->requestPasswordReset();
        $this->users->save($user);

        $sent = Yii::$app
            ->mailer
            ->compose(
                ['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'],
                ['user' => $user]
            )
            ->setTo($this->email)
            ->setSubject('Password reset for ' . Yii::$app->name)
            ->send();


        if (!$sent) {
            throw new \RuntimeException('Sending error');
        }


    }

    public function validateToken($token): void
    {
        if (empty($token) || !is_string($token)) {
            throw new \DomainException('Password reset token cannot be blank.');
        }

        if (!$this->users->existsByPasswordResetToken($token)) {
            throw new \DomainException('Wrong password reset token.');
        }
    }

    public function reset(string $token, ResetPasswordForm $form): void
    {
        $user = $this->users->getByPasswordResetToken($token);
        $user->resetPassword($form->password);
        $this->users->save($user);
    }
}

