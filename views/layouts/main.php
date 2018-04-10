<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\models\Issue;
use app\models\Version;
    use app\widgets\Alert;
    use app\models\Project;
    use yii\bootstrap\Modal;
    use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
    use yii\helpers\Url;
    use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
    use yii\widgets\Pjax;

    AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
        NavBar::begin([
            'brandLabel' => Yii::$app->params['logo']['small'],
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
                Html::a($project->name, ['project/view', 'id' => $project->id], ['style' => 'display: inline-block;']) . '</li>';
            }
            $projectsList[] = '<li class="divider"></li>';
            $projectsList[] = ['label' => 'Create project', 'url' => Url::toRoute('project/create')];

            return $projectsList;
        };

        $menuItems = [
            '<li>' . Html::a('Create issue', ['issue/create'], ['class' => 'btn btn-default']) . '</li>',
            /*[
                'label' => 'Create issue',
                'options' => [
                   ''
                ],
                'url' => Url::toRoute('issue/create')
            ],*/
            [
                'label' => 'Projects',
                'items' => @$projectDropdownItems()
            ]
        ];

        //if (@$model instanceof Version) {
        $query = [];
        if (Yii::$app->controller->id == 'version' and isset(Yii::$app->controller->actionParams['id'])){
            $query = ['project_id' => Version::findOne(['id' => Yii::$app->controller->actionParams['id']])->project_id];
        }elseif (Yii::$app->controller->id == 'issue' and isset(Yii::$app->controller->actionParams['id'])){
            $query = ['project_id' => Issue::findOne(['id' => Yii::$app->controller->actionParams['id']])->project_id];
        }elseif (Yii::$app->controller->id == 'project' and isset(Yii::$app->controller->actionParams['id'])){
            $query = ['project_id' => Yii::$app->controller->actionParams['id']];
        }
            $versionDropdownItems = function ($query, $versionsList = []) {
                if (empty($query)) return false;

                foreach (Version::find()->where($query)->orderBy(['id' => SORT_DESC])->limit(6)->all() as $version) {
                    $versionsList[] = '<li>' .
                        Html::a($version->getStatusIcon() .  ' ' . $version->name, ['version/view', 'id' => $version->id], ['style' => 'display: inline-block;']) . '</li>';
                }
                $versionsList[] = '<li class="divider"></li>';
                $versionsList[] = '<li>' .
                    Html::a('More...', ['project/view', 'id' => $query['project_id']], ['style' => 'display: inline-block;']) . '</li>';

                $versionsList[] = '<li>' . Html::a('Create version', ['version/create'], ['data-pjax' => 'versions', 'class' => 'version-actions']) . '</li>';

                return [
                    'label' => 'Version',
                    'items' => $versionsList
                ];
            };
        //}

        if (!empty(@$query)) $menuItems[] = @$versionDropdownItems($query);

       /* $menuItems[] = ['label' => 'Home', 'url' => ['/site/index']];
        $menuItems[] = ['label' => 'About', 'url' => ['/task/new']];
        $menuItems[] = ['label' => 'Contact', 'url' => ['/site/contact']];*/

        /*$menuItems[] = [
            ['label' => 'Home', 'url' => ['/site/index']],
            ['label' => 'About', 'url' => ['/task/new']],
            ['label' => 'Contact', 'url' => ['/site/contact']],
        ];*/
        if (Yii::$app->user->isGuest) {
            $menuItems[] = ['label' => 'Signup', 'url' => ['/site/signup']];
            $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
        } else {
            $menuItems[] = [
                'label' => '<span class="glyphicon glyphicon-user"></span> ' . Yii::$app->user->identity->username,
                'items' => [
                    '<li>'
                    . Html::beginForm(['/site/logout'], 'post')
                    . Html::submitButton(
                        'Logout',
                        ['class' => 'btn btn-logout']
                    )
                    . Html::endForm()
                    . '</li>',
                    '<li>' . Html::a('Profile',['settings/index'], ['data-pjax' => 'userSettings', 'class' => 'user-settings']) . '</li>'.
                    (@Yii::$app->user->identity->usertype_id == 1 ?
                        '<li>' . Html::a('Settings',['/admin/settings']) . '</li>'
                        : '')
                ]
            ];
        }
        echo Nav::widget([
            'encodeLabels' => false,
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

    <?php Pjax::begin(['enablePushState' => false, 'id' => 'userSettings', 'linkSelector'=>'a.user-settings']); ?>
    <?php Pjax::end(); ?>

    <?php Pjax::begin(['enablePushState' => false, 'id' => 'versions', 'linkSelector'=>'a.version-actions']); ?>
    <?php Pjax::end(); ?>


</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
