<?php
use yii\helpers\Html;

/** @var \Throwable $exception */
$this->title = 'Error';
?>
<h2>An error occurred</h2>
<p><?= Html::encode($exception->getMessage()) ?></p>
