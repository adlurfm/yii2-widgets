<?php
namespace app\widgets;

use yii\base\Widget;
use yii\helpers\Html;

/**
 * @author Adlur Rahman Ali Omar <adlurfm@gmail.com>
 */
class DataEdit extends Widget
{
    const TYPE_TEXTBOX = 'textbox';
    const TYPE_TEXTAREA = 'textarea';
    const TYPE_DATETIME = 'datetime'; //TODO
    const TYPE_NUMBER = 'number';
    const TYPE_DROPDOWN = 'dropdown';

    const DISPLAY_TYPE_BUTTON = 'button';
    const DISPLAY_TYPE_UNDERLINE = 'underline';

    public $model = null;
    public $title = "Edit :";
    public $type = self::TYPE_TEXTBOX;
    public $attribute = null;
    public $value = null;
    public $primary_key_value = null;
    public $dropdown_items = []; //for dropdown
    public $number_min = 0; //for number
    public $number_max = 999; //for number
    public $display_type = self::DISPLAY_TYPE_BUTTON;
    public $input_options = null; //any input options

    // Template variables <@modal_id@> = modal id, <@model_title@> = title, <@model_body@> = content, <@model_footer@>
    public $bootstrap_modal_template = '<div class="modal fade" id="<@modal_id@>" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <form method="post">
                <div class="modal-body"><span class="font-weight-bold text-primary"><@model_title@></span><br/>
                    <@model_body@>
                </div>
                <div class="d-flex justify-content-center align-items-center border-top">
                    <@model_footer@>
                </div>
                </form>
            </div>
        </div>
        </div>';

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $this->setId("modal_de_" . uniqid());

        if($this->model!=null){
            $this->value = $this->model->getAttribute($this->attribute);
            $this->primary_key_value = $this->model->primaryKey;
        }

        echo $this->renderHtml();
    }

    //Render input html
    protected function renderHtml()
    {

        $result = '';
        $content = '';

        if ($this->display_type == self::DISPLAY_TYPE_BUTTON) {
            $result = Html::a('<i class="fa fa-pencil" aria-hidden="true"></i>', '#', [
                'class' => 'px-1 mx-1',
                'style' => 'border-bottom:1px dotted;',
                'data' => [
                    'toggle' => 'modal',
                    'target' => '#' . $this->getId(),
                ],
            ]);
        } elseif ($this->display_type == self::DISPLAY_TYPE_UNDERLINE) {
            $result = Html::a($this->value, '#', [
                'class' => '',
                'style' => 'border-bottom:1px dotted;',
                'data' => [
                    'toggle' => 'modal',
                    'target' => '#' . $this->getId(),
                ],
            ]);
        }

        $content .= $this->RenderInput();

        $content .= Html::hiddenInput("dataedit[id]", $this->primary_key_value);
        $content .= Html::hiddenInput("dataedit[attr]", $this->attribute);

        $footer = '';
        $footer .= Html::a('<i class="far fa-times-circle"></i> CANCEL', '#', ["class" => "btn btn-outline-danger btn-xs m-2", "data-dismiss" => "modal"]);
        $footer .= Html::submitButton('<i class="far fa-check-circle"></i> SAVE', ["class" => "btn btn-outline-success btn-xs m-2"]);

        $result .= str_replace(['<@modal_id@>', '<@model_title@>', '<@model_body@>', '<@model_footer@>'], [
            $this->getId(),
            $this->title,
            $content,
            $footer,
        ], $this->bootstrap_modal_template);

        return $result;
    }

    public function RenderInput()
    {
        //----------------------------------------------------------------
        if ($this->type == self::TYPE_DATETIME) {

        /*  $content .= DateTimePicker::widget([
        'name' => 'dataedit_value',
        'minuteStep'=>5,
        'current_value'=>$this->value,
        'double_line'=>true
        ]); */
            return '';

        } elseif ($this->type == self::TYPE_TEXTAREA) {

            $input_options = $this->input_options ?? ['class' => 'form-control', 'rows' => 3];
            return Html::textarea("dataedit[val]", $this->value, $input_options);

        } elseif ($this->type == self::TYPE_TEXTBOX) {

            $input_options = $this->input_options ?? ['class' => 'form-control'];
            return Html::textInput("dataedit[val]", $this->value, $input_options);

        } elseif ($this->type == self::TYPE_DROPDOWN) {

            $input_options = $this->input_options ?? ['class' => 'form-control'];
            return Html::dropDownList("dataedit[val]", $this->value, $this->dropdown_items, $input_options);

        } elseif ($this->type == self::TYPE_NUMBER) {

            $input_options = $this->input_options ?? ['class' => 'form-control', 'type' => 'number', 'min' => $this->number_min, 'max' => $this->number_max];
            return Html::textInput("dataedit[val]", $this->value, $input_options);

        }
        //----------------------------------------------------------------
    }

    //Check if have data edit post
    public static function GetPostData()
    {
        if (isset($_POST['dataedit'])) {
            $de = $_POST['dataedit'];
            if (!empty($de)) {
                if (!empty($de["id"]) && !empty($de["attr"]) && !empty($de["val"])) {
                    return (object) $de;
                }
            }
        }
        return false;
    }

    /**
     * Registers the needed client script and options.
     */
    public function registerClientScript()
    {
        /*
    $thisView = $this->getView();
    $_id = $this->_id.$this->name;
    $_id_date = $this->_id.$this->name.'_date';
    $_id_hour = $this->_id.$this->name.'_hour';
    $_id_minute = $this->_id.$this->name.'_minute';
    $function_name = "datetimepicker_combine".$this->_id.'a';
    $script = <<< JS
    $(document).ready(function(){
    function {$function_name}(){
    var _dt = $('#{$_id_date}').val();
    var _hr = $('#{$_id_hour} :selected').text();
    var _mt = $('#{$_id_minute} :selected').text();
    if(_hr==null||_hr=='') _hr = '00';
    if(_mt==null||_mt=='') _mt = '00';
    $('#{$_id}').val(_dt + ' ' + _hr + ':' + _mt);
    }
    $('#{$_id_date}').change(function(){ {$function_name}(); });
    $('#{$_id_hour}').change(function(){ {$function_name}(); });
    $('#{$_id_minute}').change(function(){ {$function_name}(); });
    });
    JS;
    $thisView->registerJs($script, View::POS_READY); */
    }
}
