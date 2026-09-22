<?php

use yii\helpers\Html;
use app\models\ServiceRequest;

/** @var ServiceRequest $model */

$this->title = 'Location: ' . $model->reference_number;

$this->registerCssFile('https://unpkg.com/leaflet@1.9.4/dist/leaflet.css');
$this->registerJsFile('https://unpkg.com/leaflet@1.9.4/dist/leaflet.js', ['position' => \yii\web\View::POS_HEAD]);

$statusColors = ['pending' => '#e67e22', 'in_progress' => '#f1c40f', 'resolved' => '#27ae60'];
$statusColor = $statusColors[$model->status] ?? '#999';
?>
<h2>
    <?= Html::encode(ucfirst($model->category)) ?> Request
    <span class="badge" style="background: <?= $statusColor ?>"><?= $model->getStatusLabel() ?></span>
</h2>
<p class="text-muted">
    Ref: <?= Html::encode($model->reference_number) ?> &middot;
    Urgency: <span class="badge badge-<?= $model->urgency ?>"><?= $model->getUrgencyLabel() ?></span> &middot;
    Location: <?= Html::encode($model->location) ?>
</p>
<p><?= Html::encode($model->description) ?></p>

<div id="routeInfo" class="alert alert-info"><i class="fas fa-location-crosshairs"></i> Getting your location to calculate the route...</div>

<div id="officerMap" style="height:420px;border-radius:8px;border:1px solid #ccc"></div>

<div class="mt-3">
    <?php if ($model->status !== 'in_progress'): ?>
        <?= Html::beginForm(['request/update-status', 'id' => $model->id], 'post', ['style' => 'display:inline']) ?>
            <?= Html::hiddenInput('status', 'in_progress') ?>
            <?= Html::submitButton('Mark In Progress', ['class' => 'btn btn-warning']) ?>
        <?= Html::endForm() ?>
    <?php endif; ?>
    <?php if ($model->status !== 'resolved'): ?>
        <?= Html::beginForm(['request/update-status', 'id' => $model->id], 'post', ['style' => 'display:inline']) ?>
            <?= Html::hiddenInput('status', 'resolved') ?>
            <?= Html::submitButton('Mark Resolved', ['class' => 'btn btn-success']) ?>
        <?= Html::endForm() ?>
    <?php endif; ?>
    <?= Html::a('Back to Kanban', ['request/manage'], ['class' => 'btn btn-outline-secondary']) ?>
</div>

<?php
$destLat = json_encode((float) $model->latitude);
$destLng = json_encode((float) $model->longitude);
$destLabel = json_encode($model->location);

$js = <<<JS
var destLat = {$destLat};
var destLng = {$destLng};

var map = L.map('officerMap').setView([destLat, destLng], 14);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
}).addTo(map);

var destMarker = L.marker([destLat, destLng]).addTo(map)
    .bindPopup({$destLabel})
    .openPopup();

var routeInfo = document.getElementById('routeInfo');

function drawRoute(originLat, originLng) {
    var originMarker = L.marker([originLat, originLng], {
        icon: L.icon({
            iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
            iconSize: [20, 33],
            className: 'origin-marker'
        })
    }).addTo(map).bindPopup('You are here');

    var url = 'https://router.project-osrm.org/route/v1/driving/' +
        originLng + ',' + originLat + ';' + destLng + ',' + destLat +
        '?overview=full&geometries=geojson';

    fetch(url)
        .then(function (res) { return res.json(); })
        .then(function (data) {
            if (data.code !== 'Ok' || !data.routes || !data.routes.length) {
                routeInfo.className = 'alert alert-warning';
                routeInfo.textContent = 'Could not calculate a route. Showing locations only.';
                map.fitBounds(L.latLngBounds([[originLat, originLng], [destLat, destLng]]));
                return;
            }
            var route = data.routes[0];
            var coords = route.geometry.coordinates.map(function (c) { return [c[1], c[0]]; });
            var line = L.polyline(coords, { color: '#0b5d6e', weight: 5 }).addTo(map);
            map.fitBounds(line.getBounds(), { padding: [30, 30] });

            var km = (route.distance / 1000).toFixed(1);
            var mins = Math.round(route.duration / 60);
            routeInfo.className = 'alert alert-success';
            routeInfo.innerHTML = '<i class="fas fa-car"></i> Shortest route: <strong>' + km + ' km</strong>, about <strong>' + mins + ' min</strong> by road.';
        })
        .catch(function () {
            routeInfo.className = 'alert alert-warning';
            routeInfo.textContent = 'Could not reach the routing service. Showing locations only.';
            map.fitBounds(L.latLngBounds([[originLat, originLng], [destLat, destLng]]));
        });
}

if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function (pos) {
        drawRoute(pos.coords.latitude, pos.coords.longitude);
    }, function () {
        routeInfo.className = 'alert alert-warning';
        routeInfo.textContent = 'Could not get your location. Enable location access to see the route.';
    });
} else {
    routeInfo.className = 'alert alert-warning';
    routeInfo.textContent = 'Geolocation is not supported by your browser.';
}
JS;
$this->registerJs($js, \yii\web\View::POS_END);
?>
