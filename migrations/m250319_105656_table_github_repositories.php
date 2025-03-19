<?php

use yii\db\Migration;

class m250319_105656_table_github_repositories extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%github_repositories}}', [
          'id'         => $this->primaryKey()->notNull(),
          'repo_id'    => $this->integer()->notNull()->unique(),
          'name'       => $this->string(255)->notNull(),
          'owner'      => $this->string(255)->notNull(),
          'url'        => $this->string(255)->notNull(),
          'updated_at' => $this->dateTime()->notNull(),
          'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);
        $this->createIndex(
          'idx-github_repositories-updated_at',
          '{{%github_repositories}}',
          'updated_at'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex(
          'idx-github_repositories-updated_at',
          '{{%github_repositories}}'
        );
        $this->dropTable('{{%github_repositories}}');
    }
}
