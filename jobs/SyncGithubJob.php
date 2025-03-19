<?php

declare(strict_types = 1);

namespace app\jobs;

use Yii;
use yii\base\BaseObject;
use yii\base\InvalidRouteException;
use yii\console\Exception;
use yii\queue\JobInterface;

class SyncGithubJob extends BaseObject implements JobInterface
{
    public string $jobId;
    /**
     * @inheritDoc
     */
    public function execute($queue)
    {
        Yii::info('Запуск обновления репозиториев с ID: ' . $this->jobId, __METHOD__);
        try {
            Yii::$app->runAction('sync-github-repos');
        } catch (InvalidRouteException|Exception $e) {
            Yii::error('Ошибка при обработке задачи: '.$e->getMessage(), __METHOD__);
        }
    }
}