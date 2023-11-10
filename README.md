# YII2-WIDGETS

## How to install

```php
composer require adlurfm/yii2-widgets
```

---

## YII2-DATAEDIT WIDGET

Single Data editor widget for Yii2

### Require

- PHP 7.4
- yiisoft/yii2
- yiisoft/yii2-bootstrap4
- kartik-v/yii2-widget-datepicker

### Step 1 : in View

- Example Using Model

```php
//Example 1
echo DataEdit::widget([
    'model' => $model,
    'attribute' => 'attribute_name',
]);

//Example 2
echo DataEdit::widget([
    'title' => 'Edit :',
    'type' => DataEdit::TYPE_TEXTAREA, 
    'model' => $model,
    'attribute' => 'attribute_name',
]);
```

- Example Without Model
-- value and primary_key_value are required

```php
echo DataEdit::widget([
    'title' => 'Edit :',
    'type' => DataEdit::TYPE_TEXTBOX,
    'attribute' => 'attribute_name',
    'value' => $value, 
    'primary_key_value' => $table_primary_key_value, 
]);
```

### Step 2 : In Controller

```php
$DataEditPost = DataEdit::GetPostData();
if($DataEditPost){

    //do some validation here if needed

    //get the model if needed
    $model = ModelName::findOne($DataEditPost->id);
    if($model)
    {
        $model->setAttributes([
            $DataEditPost->attr => $DataEditPost->val
        ]);
        if($model->save())
            return $this->refresh();
        else
            Yii::$app->session->setFlash('danger', "Error!");
    }
}  
```

### Widget Options

| Option            | Type | Mandatory | Descriptions |
|----------         |--------------|-|-|
| title             |string|Optional (Default : "Edit {attribute label} :")| Modal Title
| model             |Active Record|Conditional (Required if value and primary_key_value is empty| Yii2 Active Record Model |
| attribute         |string|Mandatory| Attribute Name |
| value             |mix|Conditional (Required if model value is empty)| Attribute Value |
| primary_key_value |mix|Conditional (Required if model value is empty)| Table Primary Key Value |
| type              | TYPE_TEXTBOX, TYPE_TEXTAREA, TYPE_DATETIME, TYPE_NUMBER, TYPE_DROPDOWN | Optional (Default : TYPE_TEXTBOX) | Input type|
| display_type      |DISPLAY_TYPE_BUTTON, DISPLAY_TYPE_BUTTON_WITH_VALUE, DISPLAY_TYPE_UNDERLINE, ~~DISPLAY_TYPE_INLINE~~| Optional (Default : DISPLAY_TYPE_BUTTON)|How to display|
| input_options     |array|Optional|set custom input options if needed|
| dropdown_items    |array|Conditional (Required if type = TYPE_DROPDOWN)| List of dropdown items|
| number_min        |integer|Optional (Default : 0)| minimum number|
| number_max        |integer|Optional (Default : 999)| maximum number|
| button_icon       |string|Optional (Default : \<i class="fa fa-pencil"></i>)| button icon, default is using FontAwesome 5.|
| button_style      |string|Optional (Default :"border-bottom:1px dotted;")| Set custom button style|

---
Author : Adlur Rahman
