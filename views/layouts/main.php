<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
    use app\models\Project;
    use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
    use yii\helpers\Url;
    use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
        NavBar::begin([
            'brandLabel' => Yii::$app->name,
            'brandUrl' => Yii::$app->homeUrl,
            'options' => [
                'class' => 'navbar-inverse navbar-fixed-top',
            ],
        ]);

        $projectDropdownItems = function($projectsList = []){
            foreach (Project::find()->orderBy(['id' => SORT_DESC])->all() as $project) {
                $projectsList[] = '<li>' . Html::img(Url::toRoute(
                        ['project/get', 'id' => $project->id]),
                        ['width' => 24, 'class' => 'img-circle', 'style' => 'display: inline-block; padding-left: 4px;']) .
                Html::a($project->name, ['version/index', 'project_id' => $project->id], ['style' => 'display: inline-block;']) . '</li>';
            }
            $projectsList[] = '<li class="divider"></li>';
            $projectsList[] = ['label' => 'Create project', 'url' => Url::toRoute('project/create')];

            return $projectsList;
        };

        $menuItems = [
            [
                'label' => 'Projects',
                'items' => $projectDropdownItems()
            ],
            ['label' => 'Home', 'url' => ['/site/index']],
            ['label' => 'About', 'url' => ['/site/about']],
            ['label' => 'Contact', 'url' => ['/site/contact']],
        ];
        if (Yii::$app->user->isGuest) {
            $menuItems[] = ['label' => 'Signup', 'url' => ['/site/signup']];
            $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
        } else {
            $menuItems[] = '<li>'
                . Html::beginForm(['/site/logout'], 'post')
                . Html::submitButton(
                    'Logout (' . Yii::$app->user->identity->username . ')',
                    ['class' => 'btn btn-link logout']
                )
                . Html::endForm()
                . '</li>';
        }
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'items' => $menuItems,
        ]);
        NavBar::end();
    ?>

    <div class="container-fluid" style="margin-top: 52px;">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
