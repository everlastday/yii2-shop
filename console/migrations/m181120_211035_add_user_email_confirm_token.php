<?php

use yii\db\Migration;

/**
 * Class m181120_211035_add_user_email_confirm_token
 */
class m181120_211035_add_user_email_confirm_token extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'email_confirm_token', $this->string()->unique()->after('email'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('{{%user}}', 'email_confirm_token');
    }

}
