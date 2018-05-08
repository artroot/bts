<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "prototype".
 *
 * @property int $id
 * @property string $name
 * @property string $path
 * @property int $issue_id
 * @property resource $resource
 * @property string $index_file_name
 * @property array $tree
 *
 * @property Issue $issue
 */
class Prototype extends \yii\db\ActiveRecord
{
    public $resource;
    public $indexFileName;
    public $tree = [];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'prototype';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['issue_id'], 'integer'],
            [['resource'], 'file', 'extensions' => ['zip']],
            [['name', 'path', 'index_file_name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'path' => 'Path',
            'issue_id' => 'Issue ID',
            'resource' => 'Upload the *.zip archive with prototype',
            'index_file_name' => 'Index file (your prototype startup file)',
        ];
    }

    public static function delTree($path) {
        $files = array_diff(scandir($path), array('.','..'));
        foreach ($files as $file) {
            (is_dir("$path/$file")) ? self::delTree("$path/$file") : unlink("$path/$file");
        }
        return rmdir($path);
    }

    public function getPrototypeFiles()
    {
        return array_diff(scandir(Yii::$app->basePath . '/web/prototypes/' . implode('/', $this->getTree())), array('.','..'));
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIssue()
    {
        return $this->hasOne(Issue::className(), ['id' => 'issue_id']);
    }

    public function getTree()
    {
        if (empty($this->tree)) {
            $this->tree = explode('/', $this->index_file_name);
            array_pop($this->tree);
            $root = explode('/', $this->path);
            $root = array_pop($root);
            array_unshift($this->tree, $root);
        }
        return $this->tree;
    }

    public function setTree($folder)
    {
        $folder = explode('/', $folder);
        foreach ($folder as $f) array_push($this->tree, $f);
        return $this->tree;
    }

    public function index()
    {
        return sprintf('Prototype-%d', $this->id);
    }
}
