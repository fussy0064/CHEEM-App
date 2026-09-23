<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use app\models\ServiceRequest;

/** @var ServiceRequest $model */

$this->title = 'New Request';

$this->registerCssFile('https://unpkg.com/leaflet@1.9.4/dist/leaflet.css');
$this->registerJsFile('https://unpkg.com/leaflet@1.9.4/dist/leaflet.js', ['position' => \yii\web\View::POS_HEAD]);
?>
<h2>Report / Request a Service</h2>

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

<?= $form->field($model, 'category')->dropDownList([
    'water' => 'Water & Sanitation',
    'waste' => 'Waste Management',
    'pest' => 'Pest Control',
    'safety' => 'Health & Safety Hazard',
    'risk' => 'Risk / Scenario Assessment',
    'economic' => 'Economic Assessment',
], ['prompt' => 'Select category']) ?>

<?= $form->field($model, 'location')->textInput(['placeholder' => 'Site / area name']) ?>

<div class="mb-3">
    <label class="form-label">Pin your exact location <span class="text-muted small">(drag the marker, or use the button below)</span></label>
    <button type="button" id="locateMeBtn" class="btn btn-sm btn-outline-secondary mb-2"><i class="fas fa-location-crosshairs"></i> Use My Current Location</button>
    <div id="requestMap" style="height:300px;border-radius:8px;border:1px solid #ccc"></div>
</div>

<?= $form->field($model, 'latitude')->hiddenInput(['id' => 'lat-input'])->label(false) ?>
<?= $form->field($model, 'longitude')->hiddenInput(['id' => 'lng-input'])->label(false) ?>

<?= $form->field($model, 'urgency')->dropDownList([
    'low' => 'Low', 'medium' => 'Medium', 'high' => 'High',
]) ?>

<?= $form->field($model, 'description')->textarea(['rows' => 4, 'placeholder' => 'Describe the issue']) ?>

<?= $form->field($model, 'photoFile')->fileInput() ?>

<div class="d-grid">
    <?= Html::submitButton('Submit Request', ['class' => 'btn btn-danger btn-lg']) ?>
</div>

<?php ActiveForm::end(); ?>

<?php
$defaultLat = json_encode((float)($model->latitude ?: -6.7924)); // Dar es Salaam fallback
$defaultLng = json_encode((float)($model->longitude ?: 39.2083));
$js = <<<JS
var lat = {$defaultLat};
var lng = {$defaultLng};
var map = L.map('requestMap').setView([lat, lng], 13);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
}).addTo(map);

var marker = L.marker([lat, lng], { draggable: true }).addTo(map);

function updateInputs(latlng) {
    document.getElementById('lat-input').value = latlng.lat.toFixed(7);
    document.getElementById('lng-input').value = latlng.lng.toFixed(7);
}
updateInputs(marker.getLatLng());

marker.on('dragend', function () {
    updateInputs(marker.getLatLng());
});

map.on('click', function (e) {
    marker.setLatLng(e.latlng);
    updateInputs(e.latlng);
});

document.getElementById('locateMeBtn').addEventListener('click', function () {
    if (!navigator.geolocation) {
        alert('Geolocation is not supported by your browser.');
        return;
    }
    navigator.geolocation.getCurrentPosition(function (pos) {
        var ll = { lat: pos.coords.latitude, lng: pos.coords.longitude };
        map.setView(ll, 16);
        marker.setLatLng(ll);
        updateInputs(ll);
    }, function () {
        alert('Could not get your location. Please drag the pin manually.');
    });
});
JS;
$this->registerJs($js, \yii\web\View::POS_END);
?>

