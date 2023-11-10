# TODO

# yii2-dataedit

Single Data editor widget for Yii2

## Step 1 : in View

- Using Model

```php
//Example minimal used : Textbox input (default)
echo DataEdit::widget([
    'model' => $model,
    'attribute' => 'attribute_name',
]);

echo DataEdit::widget([
    'title' => 'Edit :',
    'type' => DataEdit::TYPE_TEXTAREA, 
    'model' => $model,
    'attribute' => 'attribute_name',
]);
```

- Without Model
-- value and primary_key_value are required

```php
echo DataEdit::widget([
    'title' => 'Edit Brand :',
    'type' => DataEdit::TYPE_TEXTBOX,
    'attribute' => 'attribute_name',
    'value' => $model_attribute_value, 
    'primary_key_value' => $model_primary_key_value, 
]);
```

## Step 2 : In Controller

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
