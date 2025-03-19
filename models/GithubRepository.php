<?php

declare(strict_types = 1);

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class GithubRepository extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'github_repositories';
    }

    public function rules(): array
    {
        return [
          [['repo_id', 'name', 'owner', 'url', 'updated_at'], 'required'],
          [['repo_id'], 'integer'],
          [['updated_at'], 'datetime', 'format' => 'php:Y-m-d H:i:s'],
          [['name', 'owner'], 'string', 'max' => 255],
          [['url'], 'string', 'max' => 500],
          [['repo_id'], 'unique'],
        ];
    }
}