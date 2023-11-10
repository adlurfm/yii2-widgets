<?php
namespace adlurfm\widgets;

use yii\web\View;
use yii\helpers\Html;
use kartik\date\DatePicker;
use yii\widgets\InputWidget;

/**
 * @author Adlur Rahman Ali Omar <adlurfm@gmail.com>
 */
class DateTimePicker extends InputWidget
{
    public $closeButton = [];
    public $minuteStep = 1;
    public $error = '';
    public $startDate = '0'; //minimun date is today
    public $class = 'form-control form-control-sm';
    public $_id = '123';
    public $current_value = null;
    public $double_line = false;
    private $current_date = null;
    private $current_hour = '00';
    private $current_minute = '00';

    private function HourList(){
        $hours = [];
        for($i=0;$i<24;$i++){
            $hour = str_pad($i, 2, "0", STR_PAD_LEFT);
            $hours[$hour]=$hour;
        }
        return $hours;
    }

    private function MinuteList(){
        $minutes = [];
        for($i=0;$i<=59;$i+=$this->minuteStep){
            $minute = str_pad($i, 2, "0", STR_PAD_LEFT);
            $minutes[$minute]=$minute;
        }
        return $minutes;
    }

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $this->_id = uniqid().'_';

        $appendClass = isset($this->options['class']) ? ' ' . $this->options['class'] : '';
        echo $this->renderInputHtml('text');
        $this->registerClientScript();
    }
    
    //Render input html
    protected function renderInputHtml($type)
    {
        
        if(!empty($this->error)) $class = $this->class.' is-invalid';

        $result = '';
        
        if ($this->hasModel()) {
            
            

            $errorList = $this->model->getErrors($this->attribute);
            if(!empty($errorList)) $this->error = $errorList[0]; //get only the first error

            if(!empty($this->error)) $this->class = $this->class.' is-invalid';
            $this->name = Html::getInputId($this->model, $this->attribute);
            
            $this->current_value = $this->model[$this->attribute];
            $this->setCurrentValue();

            /* $current_date = null;
            $current_hour = '00';
            $current_minute = '00';
            if(!empty($current_value)) {
                $current_value = date('Y-m-d H:i',strtotime($current_value));
                $current_date = date('Y-m-d',strtotime($current_value));
                $current_hour = date('H',strtotime($current_value));
                $current_minute = date('i',strtotime($current_value));
            } */

            $result .= '<div class="row">';
            $lbl = 'position: absolute;left: 1.5em;font-size: 8px;top: 0.2em;';
            //date input
            $result .= '<div class="'.($this->double_line?'col-12 mb-2':'col-6').'">';
            $result .= Html::hiddenInput(Html::getInputName($this->model, $this->attribute),$this->current_value,['id' => $this->_id.$this->name]);
            $result .= '<span style="'.$lbl.'">Date</span>';
            $result .=  DatePicker::widget([
                'id' => $this->_id.$this->name.'_date',
                'name' => $this->name.'_date',
                'type' => DatePicker::TYPE_COMPONENT_APPEND,
                'value'=>$this->current_date,
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd',
                    'todayHighlight' => true,
                    'startDate' => $this->startDate
                ],
                'options'=>[
                    'class'=>$this->class,
                    'placeholder' => 'Date'
                ]
            ]);
            //$result .= Html::input($type, $this->name.'_date', $this->value, ['class'=>'form-control']);

            $result .= '</div>';
            
            $result .= '<div class="'.($this->double_line?'col-6':'col-3').'">';
            $result .= '<span style="'.$lbl.'">Hour</span>';
            $result .= Html::dropDownList($this->name.'_hour', $this->current_hour, $this->HourList(), $options = ['class'=>$this->class.' text-center','id'=>$this->_id.$this->name.'_hour']);
            $result .= '</div>';

            $result .= '<div class="'.($this->double_line?'col-6':'col-3').'">';
            $result .= '<span style="'.$lbl.'">Minute</span>';
            $result .= Html::dropDownList($this->name.'_minute', $this->current_minute , $this->MinuteList(), $options = ['class'=>$this->class.' text-center','id'=>$this->_id.$this->name.'_minute']);
            $result .= '</div>';

            $result .= '</div>';
            if(!empty($this->error)) $result .= '<div style="color:#dc3545;font-size:80%;">'.$this->error.'</div>';
            
        }else{
            $this->setCurrentValue();
            $result .= '<div class="row">';
            $lbl = 'position: absolute;left: 1.5em;font-size: 8px;top: 0.2em;';
            //date input
            $result .= '<div class="'.($this->double_line?'col-12 mb-2':'col-6').'">';
            $result .= Html::hiddenInput($this->name,$this->current_value,['id' => $this->_id.$this->name]);
            $result .= '<span style="'.$lbl.'">Date</span>';
            $result .=  DatePicker::widget([
                'id' => $this->_id.$this->name.'_date',
                'name' => $this->name.'_date',
                'type' => DatePicker::TYPE_COMPONENT_APPEND,
                'value'=>$this->current_date,
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd',
                    'todayHighlight' => true,
                    'startDate' => $this->startDate
                ],
                'options'=>[
                    'class'=>$this->class,
                    'placeholder' => 'Date'
                ]
            ]);
            //$result .= Html::input($type, $this->name.'_date', $this->value, ['class'=>'form-control']);

            $result .= '</div>';
            
            $result .= '<div class="'.($this->double_line?'col-6':'col-3').'">';
            $result .= '<span style="'.$lbl.'">Hour</span>';
            $result .= Html::dropDownList($this->name.'_hour', $this->current_hour, $this->HourList(), $options = ['class'=>$this->class.' text-center','id'=>$this->_id.$this->name.'_hour']);
            $result .= '</div>';

            $result .= '<div class="'.($this->double_line?'col-6':'col-3').'">';
            $result .= '<span style="'.$lbl.'">Minute</span>';
            $result .= Html::dropDownList($this->name.'_minute', $this->current_minute, $this->MinuteList(), $options = ['class'=>$this->class.' text-center','id'=>$this->_id.$this->name.'_minute']);
            $result .= '</div>';

            $result .= '</div>';
            if(!empty($this->error)) $result .= '<div style="color:#dc3545;font-size:80%;">'.$this->error.'</div>';

        }
        //NOT NOW return Html::input($type, $this->name, $this->value, $this->options);

        
        return $result;
    }

    private function setCurrentValue(){
        $this->current_date = null;
        $this->current_hour = '00';
        $this->current_minute = '00';
        if(!empty($this->current_value)) {
            $this->current_value = date('Y-m-d H:i',strtotime($this->current_value));
            $this->current_date = date('Y-m-d',strtotime($this->current_value));
            $this->current_hour = date('H',strtotime($this->current_value));
            $this->current_minute = date('i',strtotime($this->current_value));
        }
    }

    /**
     * Registers the needed client script and options.
     */
    public function registerClientScript()
    {
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
        $thisView->registerJs($script, View::POS_READY);
    }
}
