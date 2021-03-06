{use class='yii\helpers\Html'}
{$this->beginPage()}
<!DOCTYPE html>
<html lang="{Yii::$app->language}">
<head>
    <meta charset="{Yii::$app->charset}"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {Html::csrfMetaTags()}
    <title>{Html::encode($this->title)}</title>
    <link href="/css/freedom.ico" rel="shortcut icon">

    {$this->registerCssFile('/vendor/foundation/css/foundation.min.css')}
    {$this->registerCssFile('/vendor/semantic/semantic.min.css')}
    {$this->registerCssFile('/css/jquery.datetimepicker.css')}
    {$this->registerCssFile('/css/index.css')}
    {$this->registerJsFile('/js/jquery.min.js')}
    {$this->registerJsFile('/js/jquery.datetimepicker.js')}
    {$this->registerJsFile('/js/jquery.animate.colors.min.js')}
    {$this->registerJsFile('/js/index.js')}

    {$this->head()}
</head>
<body>
{$this->beginBody()}
{include file="./header.tpl"}
<div id="content">
    {$content}
</div>
{include file="./footer.tpl"}
{$this->endBody()}
</body>
</html>
{$this->endPage()}
