<?php

declare(strict_types = 1);

namespace app\commands;

use app\models\GithubRepository;
use Exception;
use Faker\Provider\UserAgent;
use app\jobs\SyncGithubJob;
use Yii;
use yii\console\Controller;
use yii\helpers\BaseConsole;
use yii\helpers\Console;
use yii\helpers\Json;

class SyncGithubReposController extends Controller
{
    public string $file_path = '@app/data/repos.txt'; // файл  с названиями репозиториев
    public int $frequency = 600;

    public function options($actionID): array
    {
        return ['frequency'];
    }

    public function optionAliases(): array
    {
        return ['f' => 'frequency'];
    }
    /**
     * @throws \yii\db\Exception
     * @throws \Exception
     */
    public function actionIndex()
    {
        $githubUsers = $this->getReposNames(Yii::getAlias($this->file_path));
        $repos       = [];

        foreach ($githubUsers as $user) {
            $url      = "https://api.github.com/users/{$user}/repos?per_page=100";
            $response = $this->fetchFromGithub($url);

            if ($response) {
                foreach ($response as $repo) {
                    $repos[] = [
                      'repo_id'    => $repo['id'],
                      'name'       => $repo['name'],
                      'owner'      => $repo['owner']['login'],
                      'url'        => $repo['html_url'],
                      'updated_at' => date('Y-m-d H:i:s', strtotime($repo['updated_at']))
                    ];
                }
            }
        }

        usort($repos, fn($a, $b) => strtotime($b['updated_at']) - strtotime($a['updated_at']));
        $repos = array_slice($repos, 0, 10);

        $this->updateRepositories($repos);
        echo $this->ansiFormat('Репозитории обновились успешно', BaseConsole::FG_RED).PHP_EOL;
        $jobId = uniqid('sync-github-', true);
        Yii::$app->queue->delay($this->frequency)->push(new SyncGithubJob(['jobId' => $jobId]));
    }

    private function fetchFromGithub($url)
    {
        $token = $_ENV['GITHUB_TOKEN'];
        $curl  = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_USERAGENT, 'git-sync-app');
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
          'Authorization: token '.$token,
          'User-Agent: request'
        ]);
        $response = curl_exec($curl);
        curl_close($curl);

        return $response ? Json::decode($response) : null;
    }

    /**
     * @throws \yii\db\Exception
     */
    private function updateRepositories($repos)
    {
        GithubRepository::deleteAll();

        foreach ($repos as $repo) {
            $model             = new GithubRepository();
            $model->attributes = $repo;
            $model->save();
        }
    }

    /**
     * @throws \Exception
     */
    public function getReposNames($filePath)
    {
        if (!file_exists($filePath)) {
            throw new Exception("Файл не найден: {$filePath}");
        }

        return file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    }
}