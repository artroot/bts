<?php
/**
 * Created by PhpStorm.
 * User: art
 * Date: 4/28/2018
 * Time: 1:15 PM
 *
 * @pro
 */

namespace app\components;

use app\models\Comment;
use app\models\Issue;
use app\models\Issuepriority;
use app\models\Issuetype;
use app\models\Project;
use app\models\Sprint;
use app\models\Users;
use app\models\Version;
use app\modules\admin\models\State;
use app\modules\admin\models\Telegram;
use Yii;
use yii\helpers\Url;

/**
 * Class TelegramBot
 * @package app\components
 *
 * @property int $update_id
 * @property string $callback_data
 * @property int $chat_id
 * @property string $text
 * @property string $out_text
 * @property int $message_id
 * @property array $cached_data
 * @property Users|null $user
 */
class TelegramBot
{
    public $update_id;
    public $callback_data;
    public $chat_id;
    public $text;
    public $message_id;
    public $cached_data;
    public $user;

    private $out_text = ' ';
    /**
     * @var TelegramBot
     */
    private static $instance;

    private function __construct($output)
    {
        $this->setAttributes($output);
        if($this->callback_data) $this->parseCallback();
        else $this->parseCommand();
    }

    public static function parseOutput($output):TelegramBot
    {
        if (null === static::$instance) {
            static::$instance = new static($output);
        }

        return static::$instance;
    }

    private function setAttributes($output)
    {
        $this->update_id = $output['update_id'] ?: null;
        $this->callback_data = @$output['callback_query']['data'] ?: null;
        $this->chat_id = $this->callback_data ? @$output['callback_query']['message']['chat']['id'] : @$output['message']['chat']['id'];
        $this->text = $this->callback_data ? @$output['callback_query']['message']['text'] : @$output['message']['text'];
        $this->message_id = $this->callback_data ? @$output['callback_query']['message']['message_id'] : @$output['message']['message_id'];

        $this->user = Users::find()->where(['telegram_key' => base64_encode($this->chat_id),'telegram_notify' => true])->one() ?: null;
        $this->cached_data = Yii::$app->cache->get('telegram_action_user_' . $this->user->id);
    }

    private function parseCallback()
    {
        if (preg_match('/([a-z_\+\-]+)+([0-9]+)/', $this->callback_data, $parsedData) and @$parsedData[1] and @$parsedData[2]) {
            $action = $parsedData[1];
            $id = $parsedData[2];
        }else{
            $action = $parsedData;
            $id = false;
        }

        switch ($action){
            case 'projects':
                $this->out_text = 'Choose a project from the list below:';

                $items = [];
                foreach (Project::find()->all() as $project) $items[sprintf('p_sw_%d', @$project->id)] = @$project->name;

                $this->editMessage($this->inlineKeyboard($items));
                break;
            case 'p_sw_':
                $project = Project::findOne(['id' => $id]);
                $this->out_text = sprintf('Project <b>%s</b>' . "\r\n" . '<code>%s</code>' . "\r\n" . 'Select a chapter:', $project->name, $project->description);

                $this->editMessage($this->inlineKeyboard([
                    'projects' . $id => '« Back',
                    'v_p_sw_' . $id => 'Versions',
                    's_p_sw_' . $id => 'Sprints',
                    'i_cr_p_' . $id => 'Create Issues'
                ]));
                break;
            case 's_p_sw_':
                $project = Project::findOne(['id' => $id]);
                $this->out_text = sprintf('Project <b>%s</b>' . "\r\n" . 'Select sprint: ', $project->name);

                $items = [];
                $items['p_sw_' . $id] = '« Back';
                foreach (Sprint::find()->where(['project_id' => $id])->all() as $sprint)
                    $items[sprintf('s_sw_%d', @$sprint->id)] = @$sprint->name;

                $this->editMessage($this->inlineKeyboard($items));
                break;
            case 's_sw_':
                $sprint = Sprint::findOne(['id' => $id]);

                $text = [];

                $text[] = sprintf('Sprint: <b>%s</b>', $sprint->name);
                $text[] = '----------------';

                $text[] = sprintf('<b>Progress:</b> %s', round($sprint->getCompleteProgressPercent()) . '%');

                $spentTimeAll = 0;

                if ($all = Issue::find()->where(['sprint_id' => $id])->all() and $all){
                    $text[] = sprintf('<b>Issues in sprint:</b> (%d)', count($all));
                    foreach ($all as $issue) $spentTimeAll += $issue->progress_time;
                }

                if ($spentTimeAll) {
                    $diff = (new \DateTime())->diff((new \DateTime())->modify('-' . $spentTimeAll . ' hour'));
                    $msg = null;
                    if ($diff->format('%y')) $msg .= $diff->format('%y') . ' Year ';
                    if ($diff->format('%m')) $msg .= $diff->format('%m') . ' Month ';
                    if ($diff->format('%d')) $msg .= $diff->format('%d') . ' Day ';
                    if ($diff->format('%h')) $msg .= $diff->format('%h') . ' Hour ';
                }else{
                    $msg = '0 Days';
                }

                if ($todo = Issue::getTodo(['sprint_id' => $id])->all() and $todo){
                    $text[] = sprintf('<b>TODO:</b> (%d)', count($todo));
                }
                if ($inProgress = Issue::getInProgress(['sprint_id' => $id])->all() and $inProgress){
                    $text[] = sprintf('<b>In Progress:</b> (%d)', count($inProgress));
                }
                if ($done = Issue::getDone(['sprint_id' => $id])->all() and $done){
                    $text[] = sprintf('<b>Done:</b> (%d)', count($done));
                }

                $text[] = sprintf('<b>Spent time on Sprint:</b> %s', $msg);

                $text[] = sprintf('<b>Time left:</b> %s', $sprint->getState());

                $this->out_text = implode("\r\n", $text);

                $this->editMessage($this->inlineKeyboard(['s_p_sw_' . $sprint->project_id => '« Back']));
                break;
            case 'v_p_sw_':
                $project = Project::findOne(['id' => $id]);
                $this->out_text = sprintf('Project <b>%s</b>' . "\r\n" . 'Select action:', $project->name);

                $this->editMessage($this->inlineKeyboard([
                    'p_sw_' . $id => '« Back',
                    'v_r_p_sw_' . $id => 'Released',
                    'v_u_p_sw_' . $id => 'Unreleased'
                ]));
                break;
            case 'v_r_p_sw_':
                $project = Project::findOne(['id' => $id]);
                $this->out_text = sprintf('Project <b>%s</b>' . "\r\n" . 'Select Released version: ', $project->name);

                Yii::$app->cache->delete('telegram_action_user_' . $this->user->id);
                Yii::$app->cache->add('telegram_action_user_' . $this->user->id, [
                    'prefix' => 'r'
                ], 2000);

                $items = [];
                $items['v_p_sw_' . $id] = '« Back';
                foreach (Version::find()->where(['project_id' => $id, 'status' => true])->all() as $version)
                    $items[sprintf('v_sw_%d', @$version->id)] = @$version->name;

                $this->editMessage($this->inlineKeyboard($items));
                break;
            case 'v_u_p_sw_':
                $project = Project::findOne(['id' => $id]);
                $this->out_text = sprintf('Project <b>%s</b>' . "\r\n" . 'Select Unreleased version: ', $project->name);

                Yii::$app->cache->delete('telegram_action_user_' . $this->user->id);
                Yii::$app->cache->add('telegram_action_user_' . $this->user->id, [
                    'prefix' => 'u'
                ], 2000);

                $items = [];
                $items['v_p_sw_' . $id] = '« Back';
                foreach (Version::find()->where(['project_id' => $id, 'status' => false])->all() as $version)
                    $items[sprintf('v_sw_%d', @$version->id)] = @$version->name;

                $this->editMessage($this->inlineKeyboard($items));
                break;
            case 'v_sw_':
                $version = Version::findOne(['id' => $id]);
                Yii::$app->cache->delete('telegram_action_user_' . $this->user->id);
                $this->out_text = sprintf('<b>%s</b>' . "\r\n" . 'Version Dashboard: ', @$version->index());
                
                $this->editMessage($this->inlineKeyboard([
                    'v_' . ($this->cached_data['prefix']) . '_p_sw_' . @$version->project_id => '« Back',
                    'i_a_v_' . $id => sprintf('%s (%d)', 'Issues in version', Issue::find()->where(['resolved_version_id' => $id])->count()),
                    'i_d_v_' . $id => sprintf('%s (%d)', @State::getState(State::DONE)->label, @Issue::getDone(['resolved_version_id' => $id])->count()),
                    'i_t_v_' . $id => sprintf('%s (%d)', @State::getState(State::TODO)->label, @Issue::getTodo(['resolved_version_id' => $id])->count()),
                    'i_i_v_' . $id => sprintf('%s (%d)', @State::getState(State::IN_PROGRESS)->label, @Issue::getInProgress(['resolved_version_id' => $id])->count()),
                ]));
                break;
            case 'i_a_v_+_':
            case 'i_a_v_-_':
            case 'i_a_v_':
                $data['v_sw_' . $id] = '« Back';
                if (isset($this->cached_data['offset']))
                    $offset = $action == 'i_a_v_+_' ? (@$this->cached_data['offset']+10) : (@$this->cached_data['offset']-10);
                else
                    $offset = 0;
                
                if ($offset > 0) {
                    $data['i_a_v_-_' . $id] = '<';
                }

                if($offset+10 < Issue::find()->where(['resolved_version_id' => $id])->count()){
                    $data['i_a_v_+_' . $id] = '>';
                }

                $issueList = [];
                $i = $offset;

                foreach (Issue::find()->where(['resolved_version_id' => $id])->limit(10)->offset($offset)->all() as $issue){
                    $data['i_ed_new_' . $issue->id] = $issue->name;
                    $issueList[] = sprintf('%d. [<b>%s</b>] [<b>%s</b>] <b>%s</b> %s', ++$i, substr(@$issue->getPriority()->name, 0, 1), $issue->getType()->name, $issue->index(), $issue->name);
                }

                Yii::$app->cache->delete('telegram_action_user_' . $this->user->id);
                Yii::$app->cache->add('telegram_action_user_' . $this->user->id, [
                    'offset' => $offset
                ], 2000);

                $this->out_text = implode("\r\n", $issueList) . "\r\n" . 'Issues in version:';

                $this->editMessage($this->inlineKeyboard($data));
                break;
            case 'i_d_v_+_':
            case 'i_d_v_-_':
            case 'i_d_v_':
                $data['v_sw_' . $id] = '« Back';
                if (isset($this->cached_data['offset']))
                    $offset = $action == 'i_d_v_+_' ? (@$this->cached_data['offset']+10) : (@$this->cached_data['offset']-10);
                else
                    $offset = 0;

                if ($offset > 0) {
                    $data['i_d_v_-_' . $id] = '<';
                }

                if($offset+10 < Issue::getDone(['resolved_version_id' => $id])->count()){
                    $data['i_d_v_+_' . $id] = '>';
                }
                $issueList = [];
                $i = $offset;

                foreach (Issue::getDone(['resolved_version_id' => $id])->limit(10)->offset($offset)->all() as $issue){
                    $data['i_ed_new_' . $issue->id] = $issue->name;
                    $issueList[] = sprintf('%d. [<b>%s</b>] [<b>%s</b>] <b>%s</b> %s', ++$i, substr(@$issue->getPriority()->name, 0, 1), $issue->getType()->name, $issue->index(), $issue->name);
                }

                Yii::$app->cache->delete('telegram_action_user_' . $this->user->id);
                Yii::$app->cache->add('telegram_action_user_' . $this->user->id, [
                    'offset' => $offset
                ], 2000);

                $this->out_text = implode("\r\n", $issueList) . "\r\n" . 'Done Issues:';

                $this->editMessage($this->inlineKeyboard($data));
                break;
            case 'i_t_v_+_':
            case 'i_t_v_-_':
            case 'i_t_v_':
                $data['v_sw_' . $id] = '« Back';
                if (isset($this->cached_data['offset']))
                    $offset = $action == 'i_t_v_+_' ? (@$this->cached_data['offset']+10) : (@$this->cached_data['offset']-10);
                else
                    $offset = 0;

                if ($offset > 0) {
                    $data['i_t_v_-_' . $id] = '<';
                }

                if($offset+10 < Issue::getTodo(['resolved_version_id' => $id])->count()){
                    $data['i_t_v_+_' . $id] = '>';
                }

                $issueList = [];
                $i = $offset;

                foreach (Issue::getTodo(['resolved_version_id' => $id])->limit(10)->offset($offset)->all() as $issue){
                    $data['i_ed_new_' . $issue->id] = $issue->name;
                    $issueList[] = sprintf('%d. [<b>%s</b>] [<b>%s</b>] <b>%s</b> %s', ++$i, substr(@$issue->getPriority()->name, 0, 1), $issue->getType()->name, $issue->index(), $issue->name);
                }

                Yii::$app->cache->delete('telegram_action_user_' . $this->user->id);
                Yii::$app->cache->add('telegram_action_user_' . $this->user->id, [
                    'offset' => $offset
                ], 2000);

                $this->out_text = implode("\r\n", $issueList) . "\r\n" . 'TODO Issues:';

                $this->editMessage($this->inlineKeyboard($data));
                break;
            case 'i_i_v_+_':
            case 'i_i_v_-_':
            case 'i_i_v_':
                $data['v_sw_' . $id] = '« Back';
                if (isset($this->cached_data['offset']))
                    $offset = $action == 'i_i_v_+_' ? (@$this->cached_data['offset']+10) : (@$this->cached_data['offset']-10);
                else
                    $offset = 0;

                if ($offset > 0) {
                    $data['i_i_v_-_' . $id] = '<';
                }

                if($offset+10 < Issue::getInProgress(['resolved_version_id' => $id])->count()){
                    $data['i_i_v_+_' . $id] = '>';
                }

                $issueList = [];
                $i = $offset;

                foreach (Issue::getInProgress(['resolved_version_id' => $id])->limit(10)->offset($offset)->all() as $issue){
                    $data['i_ed_new_' . $issue->id] = $issue->name;
                    $issueList[] = sprintf('%d. [<b>%s</b>] [<b>%s</b>] <b>%s</b> %s', ++$i, substr(@$issue->getPriority()->name, 0, 1), $issue->getType()->name, $issue->index(), $issue->name);
                }

                Yii::$app->cache->delete('telegram_action_user_' . $this->user->id);
                Yii::$app->cache->add('telegram_action_user_' . $this->user->id, [
                    'offset' => $offset
                ], 2000);

                $this->out_text = implode("\r\n", $issueList) . "\r\n" . 'In Progress Issues:';

                $this->editMessage($this->inlineKeyboard($data));
                break;
            case 'i_c_add_':
                if ($id and $issue = Issue::findOne(['id' => $id]) and $issue){
                    Yii::$app->cache->delete('telegram_action_user_' . $this->user->id);
                    Yii::$app->cache->add('telegram_action_user_' . $this->user->id, [
                        'action' => 'AddComment',
                        'issue' => $issue
                    ], 2000);

                    $this->out_text = 'Write a comment message for issue: ' . "\r\n" . '<b>' . $issue->index() . '</b>';

                    $this->sendReplyMessage();
                }
                break;
            case 'i_ed_sb_':
                if ($id and $issue = Issue::findOne(['id' => $id]) and $issue){
                    Yii::$app->cache->delete('telegram_action_user_' . $this->user->id);
                    Yii::$app->cache->add('telegram_action_user_' . $this->user->id, [
                        'action' => 'EditSubject',
                        'issue' => $issue
                    ], 2000);

                    $this->out_text = 'Write a new subject for issue: ' . "\r\n" . '<b>' . $issue->index() . '</b>';

                    $this->sendReplyMessage();
                }
                break;
            case 'i_ed_ds_':
                if ($id and $issue = Issue::findOne(['id' => $id]) and $issue){
                    Yii::$app->cache->delete('telegram_action_user_' . $this->user->id);
                    Yii::$app->cache->add('telegram_action_user_' . $this->user->id, [
                        'action' => 'EditDescription',
                        'issue' => $issue
                    ], 2000);

                    $this->out_text = 'Write a new description for issue: ' . "\r\n" . '<b>' . $issue->index() . '</b>';

                    $this->sendReplyMessage();
                }
                break;
            case 'i_ed_pr_':
                if ($id and $issue = Issue::findOne(['id' => $id]) and $issue){

                    Yii::$app->cache->delete('telegram_action_user_' . $this->user->id);
                    Yii::$app->cache->add('telegram_action_user_' . $this->user->id, [
                        'action' => 'UpdatePriority',
                        'issue' => $issue
                    ], 2000);

                    $this->out_text = 'Select the new priority for issue: ' . "\r\n" . '<b>' . $issue->index() . '</b>';

                    $data['i_ed_' . $issue->id] = '« Back';

                    foreach (Issuepriority::find()->where(['!=', 'id', $issue->issuepriority_id])->all() as $priority){
                        $data['i_up_pr_' . $priority->id] = $priority->name;
                    }

                    $this->editMessage($this->inlineKeyboard($data));
                }
                break;
            case 'i_up_pr_':
                if ($id and $priority = Issuepriority::findOne(['id' => $id]) and $priority){
                    Yii::$app->cache->delete('telegram_action_user_' . $this->user->id);

                    $issue = Issue::findOne(['id' => $this->cached_data['issue']->id]);
                    $issue->updateModel($this->user, [
                        'issuepriority_id' => $id
                    ]);
                }
                break;
            case 'i_ed_tp_':
                if ($id and $issue = Issue::findOne(['id' => $id]) and $issue){

                    Yii::$app->cache->delete('telegram_action_user_' . $this->user->id);
                    Yii::$app->cache->add('telegram_action_user_' . $this->user->id, [
                        'action' => 'UpdateType',
                        'issue' => $issue
                    ], 2000);

                    $this->out_text = 'Select the new type for issue: ' . "\r\n" . '<b>' . $issue->index() . '</b>';

                    $data['i_ed_' . $issue->id] = '« Back';

                    foreach (Issuetype::find()->where(['!=', 'id', $issue->issuetype_id])->all() as $type){
                        $data['i_up_tp_' . $type->id] = $type->name;
                    }

                    $this->editMessage($this->inlineKeyboard($data));
                }
                break;
            case 'i_up_tp_':
                if ($id and $type = Issuetype::findOne(['id' => $id]) and $type){
                    Yii::$app->cache->delete('telegram_action_user_' . $this->user->id);

                    $issue = Issue::findOne(['id' => $this->cached_data['issue']->id]);
                    $issue->updateModel($this->user, [
                        'issuetype_id' => $id
                    ]);
                }
                break;
            case 'i_ed_per_':
                if ($id and $issue = Issue::findOne(['id' => $id]) and $issue){

                    Yii::$app->cache->delete('telegram_action_user_' . $this->user->id);
                    Yii::$app->cache->add('telegram_action_user_' . $this->user->id, [
                        'action' => 'UpdatePerformer',
                        'issue' => $issue
                    ], 2000);

                    $this->out_text = 'Select the performer for issue: ' . "\r\n" . '<b>' . $issue->index() . '</b>';

                    $data['i_ed_' . $issue->id] = '« Back';

                    foreach (Users::find()->all() as $performer){
                        $data['i_up_per_' . $performer->id] = sprintf('%s (%s)', $performer->index(), $performer->username);
                    }

                    $this->editMessage($this->inlineKeyboard($data));
                }
                break;
            case 'i_up_per_':
                if ($id and $performer = Users::findOne(['id' => $id]) and $performer){
                    Yii::$app->cache->delete('telegram_action_user_' . $this->user->id);

                    $issue = Issue::findOne(['id' => $this->cached_data['issue']->id]);
                    $issue->updateModel($this->user, [
                        'performer_id' => $id
                    ]);
                }
                break;
            case 'i_ed_own_':
                if ($id and $issue = Issue::findOne(['id' => $id]) and $issue){

                    Yii::$app->cache->delete('telegram_action_user_' . $this->user->id);
                    Yii::$app->cache->add('telegram_action_user_' . $this->user->id, [
                        'action' => 'UpdateOwner',
                        'issue' => $issue
                    ], 2000);

                    $this->out_text = 'Select the owner for issue: ' . "\r\n" . '<b>' . $issue->index() . '</b>';

                    $data['i_ed_' . $issue->id] = '« Back';

                    foreach (Users::find()->all() as $owner){
                        $data['i_up_own_' . $owner->id] = sprintf('%s (%s)', $owner->index(), $owner->username);
                    }

                    $this->editMessage($this->inlineKeyboard($data));
                }
                break;
            case 'i_up_own_':
                if ($id and $owner = Users::findOne(['id' => $id]) and $owner){
                    Yii::$app->cache->delete('telegram_action_user_' . $this->user->id);

                    $issue = Issue::findOne(['id' => $this->cached_data['issue']->id]);
                    $issue->updateModel($this->user, [
                        'owner_id' => $id
                    ]);
                }
                break;
            case 'i_ed_dv_':
                if ($id and $issue = Issue::findOne(['id' => $id]) and $issue){

                    Yii::$app->cache->delete('telegram_action_user_' . $this->user->id);
                    Yii::$app->cache->add('telegram_action_user_' . $this->user->id, [
                        'action' => 'UpdateDetectedVersion',
                        'issue' => $issue,
                        'prefix' => 'dv'
                    ], 2000);

                    $this->out_text = 'Select the detected version state for issue: ' . "\r\n" . '<b>' . $issue->index() . '</b>';

                    $this->editMessage($this->inlineKeyboard([
                        'i_ed_' . $issue->id => '« Back',
                        'i_up_v_r_' . $id => 'Released',
                        'i_up_v_u_' . $id => 'Unreleased'
                    ]));
                }
                break;
            case 'i_ed_rv_':
                if ($id and $issue = Issue::findOne(['id' => $id]) and $issue){

                    Yii::$app->cache->delete('telegram_action_user_' . $this->user->id);
                    Yii::$app->cache->add('telegram_action_user_' . $this->user->id, [
                        'action' => 'UpdateDetectedVersion',
                        'issue' => $issue,
                        'prefix' => 'rv'
                    ], 2000);

                    $this->out_text = 'Select the detected version state for issue: ' . "\r\n" . '<b>' . $issue->index() . '</b>';

                    $this->editMessage($this->inlineKeyboard([
                        'i_ed_' . $issue->id => '« Back',
                        'i_up_v_r_' . $id => 'Released',
                        'i_up_v_u_' . $id => 'Unreleased'
                    ]));
                }
                break;
            case 'i_up_v_r_':
                if ($id and $issue = Issue::findOne(['id' => $id]) and $issue){

                    $this->out_text = 'Select the released detected version for issue: ' . "\r\n" . '<b>' . $issue->index() . '</b>';

                    $data['i_ed_' . @$this->cached_data['prefix'] . '_' . $issue->id] = '« Back';

                    foreach (Version::find()->where(['project_id' => $issue->project_id, 'status' => true])->all() as $version){
                        $data['i_up_' . @$this->cached_data['prefix'] . '_' . $version->id] = $version->name;
                    }

                    $this->editMessage($this->inlineKeyboard($data));
                }
                break;
            case 'i_up_v_u_':
                if ($id and $issue = Issue::findOne(['id' => $id]) and $issue){

                    $this->out_text = 'Select the unreleased detected version for issue: ' . "\r\n" . '<b>' . $issue->index() . '</b>';

                    $data['i_ed_' . @$this->cached_data['prefix'] . '_' . $issue->id] = '« Back';

                    foreach (Version::find()->where(['project_id' => $issue->project_id, 'status' => false])->all() as $version){
                        $data['i_up_' . @$this->cached_data['prefix'] . '_' . $version->id] = $version->name;
                    }

                    $this->editMessage($this->inlineKeyboard($data));
                }
                break;
            case 'i_up_dv_':
                if ($id and $version = Version::findOne(['id' => $id]) and $version){
                    Yii::$app->cache->delete('telegram_action_user_' . $this->user->id);

                    $issue = Issue::findOne(['id' => $this->cached_data['issue']->id]);
                    $issue->updateModel($this->user, [
                        'detected_version_id' => $id
                    ]);
                }
                break;
            case 'i_up_rv_':
                if ($id and $version = Version::findOne(['id' => $id]) and $version){
                    Yii::$app->cache->delete('telegram_action_user_' . $this->user->id);

                    $issue = Issue::findOne(['id' => $this->cached_data['issue']->id]);
                    $issue->updateModel($this->user, [
                        'resolved_version_id' => $id
                    ]);
                }
                break;
            case 'i_ed_new_':
            case 'i_ed_':
                $issue = Issue::findOne(['id' => $id]);

                $params = [];
                foreach ($issue->attributeLabels() as $key => $label) {
                    $fc = 'get' . ucfirst(str_replace('_id', '', $key));
                    if (isset($issue->{$key})) {
                        if (method_exists($issue, $fc) and $object = $issue->$fc() and isset($object->name)) $params[] = sprintf('<b>%s</b>: <i>%s</i>', $label, @$object->name);
                        elseif (method_exists($issue, $fc) and $object = $issue->$fc() and method_exists($object, 'index')) $params[] = sprintf('<b>%s</b>: <i>%s</i>', $label, @$object->index());
                        else  $params[] = sprintf('<b>%s</b>: <i>%s</i>' . "\r\n", $label, @$issue->{$key});
                    }
                }

                $this->out_text = sprintf('<b>%s</b>' . "\r\n" . '%s' . "\r\n\r\n" . 'Select what do you want to edit:', @$issue->index(), implode("\r\n", $params));

                if ($action == 'i_ed_new_'){
                    $this->sendReplyMessage($this->inlineKeyboard([
                        'i_ed_sb_' . $id => 'Subject',
                        'i_ed_ds_' . $id => 'Description',
                        'i_ed_pr_' . $id => 'Priority',
                        'i_ed_tp_' . $id => 'Type',
                        'i_ed_per_' . $id => 'Performer',
                        'i_ed_own_' . $id => 'Owner',
                        'i_ed_dv_' . $id => 'Detected Version',
                        'i_ed_rv_' . $id => 'Resolved Version'
                    ]));
                }else {
                    $this->editMessage($this->inlineKeyboard([
                        'i_ed_sb_' . $id => 'Subject',
                        'i_ed_ds_' . $id => 'Description',
                        'i_ed_pr_' . $id => 'Priority',
                        'i_ed_tp_' . $id => 'Type',
                        'i_ed_per_' . $id => 'Performer',
                        'i_ed_own_' . $id => 'Owner',
                        'i_ed_dv_' . $id => 'Detected Version',
                        'i_ed_rv_' . $id => 'Resolved Version'
                    ]));
                }
                break;
        }
    }

    private function parseCommand()
    {
        switch ($this->text){
            case '/start':
                $this->out_text = sprintf('<code>%s</code> Insert this key into your bug tracking system profile.', base64_encode($this->chat_id));
                $this->sendReplyMessage();
                break;
            case '/help':
                if(!$this->user) return false;

                $this->out_text = implode("\r\n", [
                    'Get conjugation key - /start',
                    'Show all projects list - /projects'
                ]);
                $this->sendReplyMessage();
                break;
            case '/projects':
                if(!$this->user) return false;

                $this->out_text = 'Choose a project from the list below:';

                $items = [];
                foreach (Project::find()->all() as $project) $items[sprintf('p_sw_%d', @$project->id)] = @$project->name;

                $this->sendReplyMessage($this->inlineKeyboard($items));
                break;
            default :
                if (!$this->parseCachedActions()){
                    $this->out_text = '<code>' . $this->text . '</code>' . "\r\n";
                    $this->out_text .= 'What do you want to do?';

                    $this->sendReplyMessage($this->inlineKeyboard([
                        'i_cr' => 'Create Issue',
                        'c_cr' => 'Add Comment'
                    ]));
                }
                break;
        }
    }

    private function parseCachedActions()
    {
        Yii::$app->cache->delete('telegram_action_user_' . $this->user->id);
        switch (@$this->cached_data['action']){
            case 'AddComment':
                if (!$this->user) return false;
                $issue = $this->cached_data['issue'];
                $issue = Issue::findOne(['id' => @$issue->id]);

                Comment::create($this->user, [
                    'issue_id' => $issue->id,
                    'text' => $this->text,
                    'user_id' => $this->user->id,
                    'create_date' => date('Y-m-d H:i:s')
                ]);
                break;
            case 'EditSubject':
                $issue = Issue::findOne(['id' => $this->cached_data['issue']->id]);
                $issue->updateModel($this->user, [
                   'name' => $this->text
                ]);
                break;
            case 'EditDescription':
                $issue = Issue::findOne(['id' => $this->cached_data['issue']->id]);
                $issue->updateModel($this->user, [
                   'description' => $this->text
                ]);
                break;
            default:
                return false;
                break;
        }
        return true;
    }

    public static function inlineKeyboard($items = [], $inline = false)
    {
        $reply_markup = $btns = $dataX = [];
        $i = 0;
        foreach ($items as $callback_data => $text){
            if ($inline) $btns[] = ['text' => $text, 'callback_data' => $callback_data];
            else {
                if ($i % 2) {
                    $dataX[] = ['text' => $text, 'callback_data' => $callback_data];
                    $btns[] = $dataX;
                    $dataX = [];
                }else {
                    $dataX[] = ['text' => $text, 'callback_data' => $callback_data];
                    if ($i == count($items)-1) $btns[] = $dataX;
                }
                $i++;
            }
            //else $btns[][] = ['text' => $text, 'callback_data' => $callback_data];
        }
        if ($inline) $reply_markup['inline_keyboard'][] = $btns;
        else $reply_markup['inline_keyboard'] = $btns;

        return $reply_markup;
    }

    private function sendReplyMessage($reply_markup = false)
    {
        if ($reply_markup)
            Telegram::sendMessage($this->chat_id, $this->out_text, $this->message_id, $reply_markup);
        else
            Telegram::sendMessage($this->chat_id, $this->out_text, $this->message_id);
    }

    private function editMessage($reply_markup = false, $reply_msg_id = false)
    {
        if ($reply_markup)
            Telegram::editMessage($this->chat_id, $this->message_id, $this->out_text, $reply_msg_id, $reply_markup);
        else
            Telegram::editMessage($this->chat_id, $this->message_id, $this->out_text, $reply_msg_id);
    }

}
