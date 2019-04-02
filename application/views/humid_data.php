<?php echo $callback;?>([
<?php foreach ($device_data as $datum):?>
    <?php echo "[new Date(".($datum->unixTime*1000)."), ".$datum->humidity."]";?>,

<?php endforeach;?>
]);