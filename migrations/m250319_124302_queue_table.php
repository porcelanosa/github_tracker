<?php

use yii\db\Migration;

class m250319_124302_queue_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%queue}}', [
          'id'          => $this->primaryKey()->notNull(),
          'channel'     => $this->string(255)->notNull(),
          'job'         => $this->binary()->notNull(),
          'pushed_at'   => $this->integer(11)->notNull(),
          'ttr'         => $this->integer(11)->notNull(),
          'delay'       => $this->integer(11)->notNull()->defaultValue(0),
          'priority'    => $this->integer(11)->unsigned()->notNull()->defaultValue(1024),
          'reserved_at' => $this->integer(11)->defaultValue(null),
          'attempt'     => $this->integer(11)->defaultValue(null),
          'done_at'     => $this->integer(11)->defaultValue(null),
        ]);
        $this->createIndex(
          'idx-queue-channel',
          '{{%queue}}',
          'channel'
        );

        $this->createIndex(
          'idx-queue-reserved_at',
          '{{%queue}}',
          'reserved_at'
        );

        $this->createIndex(
          'idx-queue-priority',
          '{{%queue}}',
          'priority'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex(
          'idx-queue-channel',
          '{{%queue}}'
        );

        $this->dropIndex(
          'idx-queue-reserved_at',
          '{{%queue}}'
        );

        $this->dropIndex(
          'idx-queue-priority',
          '{{%queue}}'
        );

        $this->dropTable('{{%queue}}');
    }
}
