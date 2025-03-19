<?php
/**
 * @var yii\web\View $this
 * @var app\models\GithubRepository[] $repos
 */

$this->title = 'Последние обновленные репозитории';
?>
<h1><?=$this->title?></h1>
<?php if ($lastSync): ?>
    <p>Дата последнего обновления: <?= Yii::$app->formatter->asDatetime($lastSync, 'php:d-m-Y H:i:s') ?></p>
<?php else: ?>
    <p>Задачи синхронизации еще не выполнены.</p>
<?php endif; ?>
<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover">
        <thead class="thead-dark">
        <tr>
            <th scope="col">Название</th>
            <th scope="col" class='text-center'>Владелец</th>
            <th scope="col" class='text-center'>Ссылка</th>
            <th scope="col">Последнее обновление</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($repos as $repo): ?>
            <tr>
                <td><?=htmlspecialchars($repo->name)?></td>
                <td class='text-center'><?=htmlspecialchars($repo->owner)?></td>
                <td class='text-center'><a href="<?=htmlspecialchars($repo->url)?>" target="_blank"
                       class="btn btn-sm btn-secondary">Перейти</a></td>
                <td><?=Yii::$app->formatter->asDatetime($repo->updated_at, 'php:Y-m-d H:i:s')?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>