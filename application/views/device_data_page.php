<div id="container">

</div>
<script>
    $.getJSON('http://sense.joshmcculloch.nz/index.php/browse/data/<?php echo $id; ?>?callback=?&hours=96', function (data) {

        Highcharts.chart('container', {
            chart: {
                zoomType: 'x'
            },
            title: {
                text: 'Sensors'
            },
            subtitle: {
                text: document.ontouchstart === undefined ?
                    'Click and drag in the plot area to zoom in' : 'Pinch the chart to zoom in'
            },
            xAxis: {
                type: 'datetime'
            },
            yAxis: [{
                title: {
                    text: 'Degrees C'
                },
				max: 50,
                min: 0
            },{
                title: {
                    text: 'Humidity %'
                },
                opposite: true,
                max: 100,
                min: 0
            }],
            legend: {
                enabled: false
            },
            plotOptions: {
                area: {
                    fillColor: {
                        linearGradient: {
                            x1: 0,
                            y1: 0,
                            x2: 0,
                            y2: 1
                        },
                        stops: [
                            [0, "#FF0000"],
                            [1, "#000000"]
                        ]
                    },
                    marker: {
                        radius: 2
                    },
                    lineWidth: 1,
                    states: {
                        hover: {
                            lineWidth: 1
                        }
                    },
                    threshold: null
                }
            },

            series: [{
                    type: 'line',
                    name: 'Temperature',
                    data: data.temperature
                },
                {
                    type: 'line',
                    name: 'Humidity',
                    yAxis: 1,
                    data: data.humidity
                },
                {
                    type: 'line',
                    name: 'Soil',
                    yAxis: 1,
                    data: data.soil
                }]
        });
    });
</script>

<div id="container2">

</div>
<script>
    $.getJSON('http://sense.joshmcculloch.nz/index.php/browse/events24/<?php echo $id; ?>?callback=?&hours=96', function (data) {

        Highcharts.chart('container2', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Events'
            },
            xAxis: {
                type: 'datetime'
            },
            yAxis: [{
                title: {
                    text: 'Events'
                },
                max: 100,
                min: 0
            }],
            plotOptions: {
                area: {
                    fillColor: {
                        linearGradient: {
                            x1: 0,
                            y1: 0,
                            x2: 0,
                            y2: 1
                        },
                        stops: [
                            [0, "#FF0000"],
                            [1, "#000000"]
                        ]
                    },
                    marker: {
                        radius: 2
                    },
                    lineWidth: 1,
                    states: {
                        hover: {
                            lineWidth: 1
                        }
                    },
                    threshold: null
                }
            },

            series: [{
                name: 'Events',
                data: data
            }]
        });
    });
</script>

<?php
