<?php

namespace adlurfm\widgets;

use yii\base\Widget;
use yii\helpers\Html;


/**
 * @author Adlur Rahman Ali Omar <adlurfm@gmail.com>
 */
class DataEdit extends Widget
{
    const TYPE_TEXTBOX = 'textbox';
    const TYPE_TEXTAREA = 'textarea';
    const TYPE_DATETIME = 'datetime';
    const TYPE_NUMBER = 'number';
    const TYPE_DROPDOWN = 'dropdown';

    
    //TODO const MODE_AJAX = 'ajax';
    //TODO const MODE_NORMAL = 'normal';

    const DISPLAY_TYPE_BUTTON = 'button';
    const DISPLAY_TYPE_BUTTON_WITH_VALUE = 'button-with-value';
    const DISPLAY_TYPE_UNDERLINE = 'underline';
    const DISPLAY_TYPE_INLINE = 'inline';

    public $title = null;
    public $model = null;
    public $attribute = null;
    public $value = null;
    public $primary_key_value = null;
    public $type = self::TYPE_TEXTBOX;
    public $display_type = self::DISPLAY_TYPE_BUTTON;
    public $input_options = null; //any input options
    
    //for dropdown
    public $dropdown_items = []; 

    //for numbers
    public $number_min = 0; //for number
    public $number_max = 999; //for number
    
    //button options
    public $button_icon = '<i class="fa fa-pencil"></i>';
    public $button_style = 'border-bottom:1px dotted;'; //style

    //TODO public $mode = self::MODE_NORMAL; 

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


    public $inline_template = '<form method="post"><@model_body@></form>';

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $this->setId("modal_de_" . uniqid());

        //set value and primary key value if model data is given
        if($this->model!=null){
            $this->value = $this->model->getAttribute($this->attribute);
            $this->primary_key_value = $this->model->primaryKey;
        }

        //set default title
        if($this->title==null){
            $this->title = "Edit :";
            if($this->model!=null){
                $label = $this->model->getAttributeLabel($this->attribute);
                $this->title = "Edit $label:";
            }
        }

        echo $this->renderHtml();
    }

    
    protected function renderHtml()
    {

        $result = $this->RenderValue();

        if($this->display_type == self::DISPLAY_TYPE_INLINE)
        {
            $result .= $this->RenderInline();
        }else{
            $result .= $this->RenderBootstrapModal();
        }

        return $result;
    }

    private function RenderValue()
    {
        if ($this->display_type == self::DISPLAY_TYPE_BUTTON_WITH_VALUE) {
            return '<span>'.$this->value.'</span>' . 
                Html::a($this->button_icon, '#', [
                'class' => 'px-1 mx-1',
                'style' => $this->button_style,
                'data' => [
                    'toggle' => 'modal',
                    'target' => '#' . $this->getId(),
                ],
            ]);
        } 
        elseif ($this->display_type == self::DISPLAY_TYPE_BUTTON) {
            return Html::a($this->button_icon, '#', [
                'class' => 'px-1 mx-1',
                'style' => $this->button_style,
                'data' => [
                    'toggle' => 'modal',
                    'target' => '#' . $this->getId(),
                ],
            ]);
        }
        
        elseif ($this->display_type == self::DISPLAY_TYPE_UNDERLINE) {
            return Html::a($this->value, '#', [
                'class' => '',
                'style' => 'border-bottom:1px dotted;',
                'data' => [
                    'toggle' => 'modal',
                    'target' => '#' . $this->getId(),
                ],
            ]);
        }

        return '';
    }

    private function RenderBootstrapModal()
    {
        $body = $this->RenderInput();
        $body .= Html::hiddenInput("dataedit[id]", $this->primary_key_value);
        $body .= Html::hiddenInput("dataedit[attr]", $this->attribute);
        
        $footer = Html::a('<i class="far fa-times-circle"></i> CANCEL', '#', ["class" => "btn btn-outline-danger btn-xs m-2", "data-dismiss" => "modal"]);
        $footer .= Html::submitButton('<i class="far fa-check-circle"></i> SAVE', ["class" => "btn btn-outline-success btn-xs m-2"]);

        return str_replace(['<@modal_id@>', '<@model_title@>', '<@model_body@>', '<@model_footer@>'], [
            $this->getId(),
            $this->title,
            $body,
            $footer,
        ], $this->bootstrap_modal_template);
    }

    private function RenderInline()
    {
        $body = $this->RenderInput();
        $body .= Html::hiddenInput("dataedit[id]", $this->primary_key_value);
        $body .= Html::hiddenInput("dataedit[attr]", $this->attribute);
        
        return str_replace('<@model_body@>', $body, $this->inline_template);
    }


    private function RenderInput()
    {
        //----------------------------------------------------------------
        $class = 'form-control';
        $style = '';
        if($this->display_type == self::DISPLAY_TYPE_INLINE){
            $class = '';
            $style = 'display:none;';
        }

        if ($this->type == self::TYPE_DATETIME) {

            return DateTimePicker::widget([
                'name' => "dataedit[val]",
                'minuteStep'=>5,
                'current_value'=>$this->value,
                'double_line'=>true
            ]); 

        } elseif ($this->type == self::TYPE_TEXTAREA) {

            $input_options = $this->input_options ?? ['class' => $class, 'style'=>$style, 'rows' => 3];
            return Html::textarea("dataedit[val]", $this->value, $input_options);

        } elseif ($this->type == self::TYPE_TEXTBOX) {

            $input_options = $this->input_options ?? ['class' => $class, 'style'=>$style];
            return Html::textInput("dataedit[val]", $this->value, $input_options);

        } elseif ($this->type == self::TYPE_DROPDOWN) {

            $input_options = $this->input_options ?? ['class' => $class, 'style'=>$style];
            return Html::dropDownList("dataedit[val]", $this->value, $this->dropdown_items, $input_options);

        } elseif ($this->type == self::TYPE_NUMBER) {

            $input_options = $this->input_options ?? ['class' => $class, 'style'=>$style, 'type' => 'number', 'min' => $this->number_min, 'max' => $this->number_max];
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