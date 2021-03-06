<?php

/* @var $this yii\web\View */
/* @var string $type */
/* @var \app\data\models\ImportModel $model */
/* @var array $fields */
/* @var \app\models\Object $object */
/* @var array $availablePropertyGroups */

use app\backend\widgets\BackendWidget;
use kartik\helpers\Html;
use kartik\icons\Icon;
use kartik\widgets\ActiveForm;

$this->title = $object->name . ' ' .
    ($importMode ? Yii::t('app', 'Import') : Yii::t('app', 'Export'));

$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Data'),
    'url' => ['index']
];
$this->params['breadcrumbs'][] = $this->title;

?>

<?php if (isset($fields['object']) && !empty($fields['object'])) : ?>
    <?php
        $form = ActiveForm::begin([
            'id' => 'form-data',
            'type'=>ActiveForm::TYPE_HORIZONTAL,
            'options' => [
                'enctype' => 'multipart/form-data',
            ],
        ]);
    ?>
    <?php
        BackendWidget::begin(
            [
                'icon' => 'list',
                'title'=> $object->name . ' - ' . Yii::t('app', 'Fields'),
                'footer' => Html::submitButton(
                    Icon::show('save') . Yii::t('app', 'Submit'),
                    ['class' => 'btn btn-primary']
                ),
            ]
        );
    ?>
    <?= \yii\helpers\Html::activeHiddenInput($model, 'object') ?>
    <div class="form-group row">
        <div class="col-md-6">
            <?= \yii\helpers\Html::button(
                Yii::t('app', 'Select All'),
                [
                    'id' => 'select_all',
                    'class' => 'btn btn-success btn-sm'
                ]
            ) ?>
            <?= \yii\helpers\Html::button(
                Yii::t('app', 'Unselect All'),
                [
                    'id' => 'unselect_all',
                    'class' => 'btn btn-warning btn-sm'
                ]
            ) ?>
        </div>
    </div>

    <div class="form-group row fields-to-import">
        <div class="col-md-4">
            <?= $this->render('_objectFields',[
                'form' => $form,
                'fields' => $fields,
                'model' => $model,
            ]) ?>
        </div>
    <?php if (isset($fields['property']) && !empty($fields['property'])): ?>
        <div class="col-md-4">
            <?= $this->render('_propertyFields',[
                'form' => $form,
                'fields' => $fields,
                'model' => $model,
                'availablePropertyGroups' => $availablePropertyGroups,
            ]) ?>
        </div>
    <?php endif; ?>
    <?php if ($importMode === false): ?>
        <div class="col-md-4">
            <?= $this->render('_additionalFields',[
                'form' => $form,
                'fields' => $fields,
                'model' => $model,
            ]) ?>
        </div>
    <?php endif;?>
    </div>
    <?php if ($importMode === true): ?>
    <div class="form-group row">
        <div class="col-md-12">
            <?= $form->field($model, 'file')->fileInput() ?>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-12">
            <fieldset>
                <legend>
                    <?= Yii::t('app', 'Add property groups to each new object: ') ?>
                </legend>
                <?=
                    $form->field(
                        $model,
                        'addPropertyGroups'
                    )
                    ->checkboxList(
                        \yii\helpers\ArrayHelper::map(
                                \app\models\PropertyGroup::getForObjectId($object->id),
                                'id',
                                'name'
                            )
                        )
                    ->label('')
                ?>
            </fieldset>
        </div>
    </div>
    <?php endif; ?>

    <div class="form-group row">
        <div class="col-md-12">
            <fieldset>
                <legend><?= Yii::t('app', 'Settings') ?></legend>
                <?php if ($importMode === true): ?>
                    <?= $form->field($model, 'createIfNotExists')->checkbox() ?>
                <?php endif; ?>
                <?= $form->field($model, 'multipleValuesDelimiter') ?>
            </fieldset>
        </div>
    </div>

    <?php if (!$importMode): ?>
        <div class="form-group row">
            <div class="col-md-12">
                <?= \yii\helpers\Html::activeDropDownList(
                    $model,
                    'type',
                    \app\data\models\ImportModel::knownTypes(),
                    [
                        'class' => 'form-control'
                    ]
                ) ?>
            </div>
        </div>
    <?php endif; ?>

    <?php BackendWidget::end(); ?>
    <?php ActiveForm::end() ?>
<?php endif; ?>

<script>
    $(function() {
        $('#select_all').on('click', function() {
            $('.fields-to-import input:checkbox').prop('checked', true);
        });
        $('#unselect_all').on('click', function() {
            $('.fields-to-import input:checkbox').prop('checked', false);
        });
    });
</script>