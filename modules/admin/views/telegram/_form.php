<?php

    use app\modules\admin\models\Telegram;
    use yii\helpers\Html;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Telegram */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="telegram-form">
    <br>
    <?php if (@$msg): ?>
        <div class="alert alert-success" role="alert"><?= $msg ?></div>
    <?php endif; ?>

    <?php $form = ActiveForm::begin(['action' =>['telegram/update?id=' . $model->token], 'options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'status')->checkbox([
        'value' => 1,
        'checked' => $model->status,
        'onchange' => '$(\'.telegram-form-inputs\').toggle(\'fast\');'
    ]) ?>

    <div class="telegram-form-inputs" style="display: <?= !$model->status ? 'none' : 'block' ?>">

        <label>WebHook status <br>
            <?= @$webHookStatus ?>
        </label>
        <br>
        <br>

    <?= $form->field($model, 'token')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'base_url')->textInput(['maxlength' => true, '']) ?>

        <div class="alert alert-info" role="alert">
            <svg width="32" height="32" xmlns="http://www.w3.org/2000/svg">
                <g>
                    <title>Layer 1</title>
                    <rect stroke="#555555" id="svg_4" height="17.372192" width="30.557562" y="7.563863" x="0.721219" stroke-width="1.5" fill="#999999"/>
                    <line stroke="#555555" stroke-linecap="null" stroke-linejoin="null" id="svg_6" y2="10.688358" x2="14.889952" y1="10.688358" x1="6.121921" fill-opacity="null" stroke-opacity="null" stroke-width="1.5" fill="none"/>
                    <line stroke="#555555" stroke-linecap="null" stroke-linejoin="null" id="svg_9" y2="10.688358" x2="19.223836" y1="10.688358" x1="16.517326" fill-opacity="null" stroke-opacity="null" stroke-width="1.5" fill="none"/>
                    <line stroke="#555555" stroke-linecap="null" stroke-linejoin="null" id="svg_10" y2="10.688358" x2="25.507531" y1="10.688358" x1="20.863833" fill-opacity="null" stroke-opacity="null" stroke-width="1.5" fill="none"/>
                    <line stroke="#555555" stroke-linecap="null" stroke-linejoin="null" id="svg_11" y2="13.176333" x2="6.13228" y1="13.176333" x1="3.783225" fill-opacity="null" stroke-opacity="null" stroke-width="1.5" fill="none"/>
                    <line stroke="#555555" stroke-linecap="null" stroke-linejoin="null" id="svg_12" y2="13.176332" x2="17.113018" y1="13.176332" x1="8.584647" fill-opacity="null" stroke-opacity="null" stroke-width="1.5" fill="none"/>
                    <line stroke="#555555" stroke-linecap="null" stroke-linejoin="null" id="svg_13" y2="13.176333" x2="21.612292" y1="13.176333" x1="18.905781" fill-opacity="null" stroke-opacity="null" stroke-width="1.5" fill="none"/>
                    <line stroke="#555555" stroke-linecap="null" stroke-linejoin="null" id="svg_14" y2="13.176333" x2="27.298873" y1="13.176333" x1="22.655175" fill-opacity="null" stroke-opacity="null" stroke-width="1.5" fill="none"/>
                    <line stroke="#555555" stroke-linecap="null" stroke-linejoin="null" id="svg_15" y2="22.038841" x2="12.326051" y1="22.038841" x1="3.79768" fill-opacity="null" stroke-opacity="null" stroke-width="1.5" fill="none"/>
                    <ellipse ry="4.403715" rx="4.403715" id="svg_16" cy="20.476033" cx="22.568254" stroke-width="1.5" stroke="#555555" fill="#555555"/>
                    <path stroke="#555555" id="svg_18" d="m20.182378,21.802836l-3.082284,6.574362l1.998856,-1.243261l0.322932,2.331863l3.082284,-6.574635l-2.321788,-1.08833zm0.472417,2.013016c-0.153025,-0.071884 -0.219191,-0.254043 -0.147307,-0.407069c0.072156,-0.153025 0.254316,-0.219191 0.407341,-0.147307s0.219191,0.254316 0.147307,0.407341s-0.254043,0.218919 -0.407341,0.147035z" stroke-width="1.5" fill="#555555"/>
                    <line stroke="#555555" stroke-linecap="null" stroke-linejoin="null" id="svg_20" y2="19.414034" x2="6.826452" y1="19.414034" x1="3.79768" fill-opacity="null" stroke-opacity="null" stroke-width="1.5" fill="none"/>
                    <line stroke="#555555" stroke-linecap="null" stroke-linejoin="null" id="svg_21" y2="19.414033" x2="12.326051" y1="19.414033" x1="7.609901" fill-opacity="null" stroke-opacity="null" stroke-width="1.5" fill="none"/>
                    <path transform="rotate(-51.498443603515625 25.55190277099609,25.634317398071293) " stroke="#555555" id="svg_22" d="m25.932149,21.802836l-3.082284,6.574362l1.998856,-1.243261l0.322932,2.331863l3.082284,-6.574634l-2.321788,-1.08833zm0.472417,2.013015c-0.153025,-0.071884 -0.21919,-0.254043 -0.147307,-0.407068c0.072156,-0.153026 0.254316,-0.219191 0.407341,-0.147308s0.219191,0.254316 0.147307,0.407341s-0.254043,0.218919 -0.407341,0.147035z" stroke-width="1.5" fill="#555555"/>
                </g>
            </svg>

        <?= $form->field($model, 'certificate')->fileInput() ?>
            <i>You must attach your SSL certificate if it is self-signed or it can not be verify.</i>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
