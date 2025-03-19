<?php

declare(strict_types = 1);

namespace app\controllers;

use app\models\GithubRepository;
use Yii;
use yii\web\Controller;

class GithubController extends Controller
{
    /**
     * @throws \yii\db\Exception
     */
    public function actionIndex(): string
    {
        $lastSync = Yii::$app->db->createCommand("
        SELECT pushed_at FROM queue 
        WHERE job LIKE '%SyncGithubJob%' 
        ORDER BY pushed_at DESC LIMIT 1
    ")->queryScalar();
        
        $repos = GithubRepository::find()
                                 ->orderBy(['updated_at' => SORT_DESC])
                                 ->limit(10)
                                 ->all();

        return $this->render('index', ['lastSync' => $lastSync, 'repos' => $repos]);
    }
}