<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\models\Issue;
use app\models\Sprint;
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

app\assets\AppAsset::register($this);


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

        $leftMenuItems = [];
        $rightMenuItems = [];

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

        $sprintDropdownItems = function($sprintList = []){
            $sprints = Sprint::find();

            if (Yii::$app->controller->id == 'version' and isset(Yii::$app->controller->actionParams['id'])){
                $sprints->where(['project_id' => Version::findOne(['id' => Yii::$app->controller->actionParams['id']])->project_id])
                ->andWhere(['version_id' => Yii::$app->controller->actionParams['id']]);
            }elseif (Yii::$app->controller->id == 'issue' and isset(Yii::$app->controller->actionParams['id'])){
                $sprints->where(['version_id' => Issue::findOne(['id' => Yii::$app->controller->actionParams['id']])->resolved_version_id]);
            }elseif (Yii::$app->controller->id == 'project' and isset(Yii::$app->controller->actionParams['id'])){
                $sprints->where(['project_id' => Yii::$app->controller->actionParams['id']]);
            }
            foreach ($sprints->orderBy(['id' => SORT_DESC])->all() as $sprint) {
                $sprintList[] = ['label' => $sprint->index() . ' ' . $sprint->name, 'url' => ['sprint/view', 'id' => $sprint->id]];
            }
            $sprintList[] = '<li class="divider"></li>';
            $sprintList[] = '<li>' . Html::a('Create sprint', ['sprint/create'], ['data-pjax' => 'sprints', 'class' => 'sprint-actions']) . '</li>';

            return $sprintList;
        };

        $versionDropdownItems = function ($versionsList = []) {

            $versions = Version::find();

            if (Yii::$app->controller->id == 'version' and isset(Yii::$app->controller->actionParams['id'])){
                $versions->where(['project_id' => Version::findOne(['id' => Yii::$app->controller->actionParams['id']])->project_id]);
            }elseif (Yii::$app->controller->id == 'issue' and isset(Yii::$app->controller->actionParams['id'])){
                $versions->where(['project_id' => Issue::findOne(['id' => Yii::$app->controller->actionParams['id']])->project_id]);
            }elseif (Yii::$app->controller->id == 'project' and isset(Yii::$app->controller->actionParams['id'])){
                $versions->where(['project_id' => Yii::$app->controller->actionParams['id']]);
            }else {
                return false;
            }

            $countAllVersion = $versions->orderBy(['id' => SORT_DESC]);

            foreach ($versions->limit(6)->all() as $version) {
                $versionsList[] = '<li>' .
                    Html::a($version->getStatusIcon() .  ' ' . $version->name, ['version/view', 'id' => $version->id], ['style' => 'display: inline-block;']) . '</li>';
            }
            $versionsList[] = '<li class="divider"></li>';
            if ($countAllVersion->count() > 6) {
                $versionsList[] = '<li>' .
                    Html::a('More...', ['project/view', 'id' => $version->project_id], ['style' => 'display: inline-block;']) . '</li>';
            }

            $versionsList[] = '<li>' . Html::a('Create version', ['version/create'], ['data-pjax' => 'versions', 'class' => 'version-actions']) . '</li>';

            return $versionsList;
        };

        if (isset($this->params['titleItems'])) $leftMenuItems[] = $this->params['titleItems'];

        $leftMenuItems[] = [
                'label' => 'Projects',
                'items' => @$projectDropdownItems()
        ];
        $leftMenuItems[] = [
                'label' => 'Sprints',
                'items' => @$sprintDropdownItems()
        ];

        if ($versionDropdownItemsList = $versionDropdownItems() and $versionDropdownItemsList !== false) {
            $leftMenuItems[] = [
                'label' => 'Versions',
                'items' => $versionDropdownItemsList
            ];
        }

        if (Yii::$app->user->isGuest) {
            $rightMenuItems[] = ['label' => 'Signup', 'url' => ['/site/signup']];
            $rightMenuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
        } else {
            $rightMenuItems[] = [
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
            'options' => ['class' => 'navbar-nav'],
            'items' => $leftMenuItems,
        ]);

        echo '<div class="navbar-form navbar-left">' . Html::a('Create issue', ['issue/create'], ['class' => 'btn btn-default']) . '</div>';

        echo Nav::widget([
            'encodeLabels' => false,
            'options' => ['class' => 'navbar-nav navbar-right'],
            'items' => $rightMenuItems,
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


<?php Pjax::begin(['enablePushState' => false, 'id' => 'userSettings', 'linkSelector'=>'a.user-settings']); ?>
<?php Pjax::end(); ?>

<?php Pjax::begin(['enablePushState' => false, 'id' => 'versions', 'linkSelector'=>'.version-actions']); ?>
<?php Pjax::end(); ?>

<?php Pjax::begin(['enablePushState' => false, 'id' => 'sprints', 'linkSelector'=>'.sprint-actions']); ?>
<?php Pjax::end(); ?>

<?php Pjax::begin(['enablePushState' => false,  'id' => 'prototypes', 'linkSelector'=>'.prototype-actions']); ?>
<?php Pjax::end(); ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
