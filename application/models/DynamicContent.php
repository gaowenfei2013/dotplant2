<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "dynamic_content".
 *
 * @property integer $id
 * @property string $route
 * @property string $name
 * @property string $content_block_name
 * @property string $content
 * @property integer $append_content
 * @property string $title
 * @property integer $append_title
 * @property string $h1
 * @property integer $append_h1
 * @property string $meta_description
 * @property integer $append_meta_description
 * @property integer $apply_if_last_category_id
 * @property string $apply_if_params
 */
class DynamicContent extends ActiveRecord
{
    private static $identity_map = [];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%dynamic_content}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['route', 'name', 'content', 'title', 'h1', 'meta_description', 'apply_if_params'], 'string'],
            [
                ['append_content', 'append_title', 'append_h1', 'append_meta_description', 'apply_if_last_category_id'],
                'integer'
            ],
            [['content_block_name'], 'string', 'max' => 80],
            [['content_block_name'], 'default', 'value' => 'bottom_text'],
            [['route'], 'default', 'value' => 'product/list'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'route' => Yii::t('app', 'Route'),
            'name' => Yii::t('app', 'Name'),
            'content_block_name' => Yii::t('app', 'Content Block Name'),
            'content' => Yii::t('app', 'Content'),
            'append_content' => Yii::t('app', 'Append Content'),
            'title' => Yii::t('app', 'Title'),
            'append_title' => Yii::t('app', 'Append Title'),
            'h1' => Yii::t('app', 'H1'),
            'append_h1' => Yii::t('app', 'Append H1'),
            'meta_description' => Yii::t('app', 'Meta Description'),
            'append_meta_description' => Yii::t('app', 'Append Meta Description'),
            'apply_if_last_category_id' => Yii::t('app', 'Apply If Last Category ID'),
            'apply_if_params' => Yii::t('app', 'Apply If Params'),
        ];
    }

    /**
     * Search tasks
     * @param $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        /* @var $query \yii\db\ActiveQuery */
        $query = self::find();
        $dataProvider = new ActiveDataProvider(
            [
                'query' => $query,
                'pagination' => [
                    'pageSize' => 10,
                ],
            ]
        );
        if (!($this->load($params))) {
            return $dataProvider;
        }
        $query->andFilterWhere(['id' => $this->id]);
        $query->andFilterWhere(['like', 'name', $this->name]);
        $query->andFilterWhere(['like', 'route', $this->route]);
        $query->andFilterWhere(['like', 'title', $this->title]);
        $query->andFilterWhere(['like', 'content_block_name', $this->content_block_name]);
        $query->andFilterWhere(['like', 'h1', $this->h1]);
        $query->andFilterWhere(['like', 'meta_description', $this->meta_description]);
        return $dataProvider;
    }

    public static function findById($id)
    {
        if (!isset(static::$identity_map[$id])) {
            static::$identity_map[$id] = DynamicContent::findOne($id);
        }
        return static::$identity_map[$id];
    }
}
