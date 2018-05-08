<?php

namespace app\modules\admin\models;

use app\models\Users;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "log".
 *
 * @property int $id
 * @property string $action
 * @property string $model
 * @property int $model_id
 * @property string $data_old
 * @property string $data_new
 * @property string $date
 * @property int $user_id
 * @property int $issue_id
 */
class Log extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['model_id', 'user_id', 'issue_id'], 'integer'],
            [['data_old', 'data_new'], 'string'],
            [['date'], 'safe'],
            [['action', 'model'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'action' => 'Action',
            'model' => 'Model',
            'model_id' => 'Model ID',
            'data_old' => 'Data Old',
            'data_new' => 'Data New',
            'date' => 'Date',
            'user_id' => 'User ID',
            'issue_id' => 'Issue ID',
        ];
    }

    /**
     * @param ActiveRecord $model
     * @param string $action
     * @param boolean|ActiveRecord $oldModel
     * @param boolean|Users $owner
     */
    public static function add($model, $action, $issue_id = NULL, $oldModel = false, $owner = false)
    {
        $log = new self();
        $data_new = self::getNewValues($model);
        $data_old = self::getOldValues($model, $oldModel);


        $log->setAttributes([
            'action' => $action,
            'model' => $model->className(),
            'model_id' => $model->id,
            'data_old' => serialize(array_diff_assoc($data_old, $data_new)),
            'data_new' => serialize(array_diff_assoc($data_new, $data_old)),
            'user_id' => $owner ? $owner->id: Yii::$app->user->identity->getId(),
            'issue_id' => $issue_id
        ]);

        $log->save();
    }

    /**
     * @param ActiveRecord $model
     * @return array
     */
    public static function getNewValues($model)
    {
        $data_new = [];
        foreach (array_keys($model->attributeLabels()) as $key) {
            unset($objectNew);
            $fc = 'get' . ucfirst(str_replace('_id', '', $key));
            if (isset($model->{$key})) {
                if (method_exists($model, $fc)) $objectNew = $model->$fc();

                if(@$objectNew instanceof ActiveRecord && isset($objectNew->name))
                    $data_new[$key] = $objectNew->name;
                elseif(@$objectNew instanceof ActiveRecord && method_exists($objectNew, 'index'))
                    $data_new[$key] = $objectNew->index();
                else
                    $data_new[$key] = $model->{$key};
            }
        }
        return $data_new;
    }

    /**
     * @param ActiveRecord $model
     * @param bool|ActiveRecord $oldModel
     * @return array
     */
    public static function getOldValues($model, $oldModel = false)
    {
        $data_old = [];
        if ($oldModel instanceof ActiveRecord) {
            foreach (array_keys($model->attributeLabels()) as $key) {
                unset($objectOld);
                $fc = 'get' . ucfirst(str_replace('_id', '', $key));
                if ($oldModel !== false && isset($oldModel->{$key})) {
                    if (method_exists($oldModel, $fc)) $objectOld = $oldModel->$fc();

                    if (@$objectOld instanceof ActiveRecord && isset($objectOld->name))
                        $data_old[$key] = $objectOld->name;
                    elseif (@$objectOld instanceof ActiveRecord && method_exists($objectOld, 'index'))
                        $data_old[$key] = $objectOld->index();
                    else
                        $data_old[$key] = $oldModel->{$key};
                }
            }
        }
        return $data_old;
    }

    /**
     * @param ActiveRecord $model
     * @param bool|ActiveRecord $oldModel
     * @return array
     */
    public static function getChanges($model, $oldModel = false)
    {
        $changes = [];

        $data_new = self::getNewValues($model);
        $data_old = self::getOldValues($model, $oldModel);
        foreach (array_diff_assoc($data_new, $data_old) as $key => $value) {
            if (isset($model->attributeLabels()[$key]) and isset($data_old[$key])) {
                $changes[] = sprintf('%s: %s -> %s', $model->attributeLabels()[$key], @$data_old[$key], $value);
            }
        }

        return $changes;
    }
}
