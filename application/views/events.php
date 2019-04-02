<?php echo $callback;?>([
<?php foreach ($device_events as $datum):?>
    <?php echo "[".($datum->unixTime*1000).", ".$datum->events."]";?>,

<?php endforeach;?>
]);