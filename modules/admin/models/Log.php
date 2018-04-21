<?php

namespace app\modules\admin\models;

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
            [['model_id', 'user_id'], 'integer'],
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
        ];
    }

    /**
     * @param ActiveRecord $model
     * @param string $action
     * @param boolean|ActiveRecord $oldModel
     */
    public static function add($model, $action, $oldModel = false)
    {
        $log = new self();
        $data_new = [];
        $data_old = [];
        foreach (array_keys($model->attributeLabels()) as $key) {
            unset($objectOld, $objectNew);
            $fc = 'get' . ucfirst(str_replace('_id', '', $key));
            if ($oldModel !== false && isset($oldModel->{$key})) {
                if (method_exists($oldModel, $fc)) $objectOld = $oldModel->$fc();
                $data_old[$key] = (@$objectOld instanceof ActiveRecord && isset($objectOld->name)) ? $objectOld->name : $oldModel->{$key};
            }
            if (isset($model->{$key})) {
                if (method_exists($model, $fc)) $objectNew = $model->$fc();
                $data_new[$key] = (@$objectNew instanceof ActiveRecord && isset($objectNew->name)) ? $objectNew->name : $model->{$key};
            }
        }

        $log->setAttributes([
            'action' => $action,
            'model' => $model->className(),
            'model_id' => $model->id,
            'data_old' => serialize(array_diff($data_old, $data_new)),
            'data_new' => serialize(array_diff($data_new, $data_old)),
            'user_id' => Yii::$app->user->identity->getId()
        ]);

        $log->save();
    }
}
