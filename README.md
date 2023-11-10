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

---
Author : Adlur Rahman <adlurfm@gmail.com>
