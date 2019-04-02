<?php echo $callback;?>(
{
"temperature": [<?php foreach ($device_data as $datum):?>
    <?php echo "[".($datum->unixTime*1000).", ".$datum->temperature."]";?>,
<?php endforeach;?>
],
"humidity": [<?php foreach ($device_data as $datum):?>
    <?php echo "[".($datum->unixTime*1000).", ".$datum->humidity."]";?>,
<?php endforeach;?>
],
"soil": [<?php foreach ($device_data as $datum):?>
    <?php echo "[".($datum->unixTime*1000).", ".$datum->soil."]";?>,
<?php endforeach;?>
]
});