<div class="row">
    <div class="col-md-6">
        <div class="box box-solid">
        	<?php /*?>
            <div class="box-header">
            	<h3 class="box-title">Trend</h3>
        	</div>
            <?php */?>
        	<div class="box-body">
        		<div id='chartContainer1' style="width: 100%; height:300px"></div>
        	</div>
        </div>
    </div><!-- ./col -->

    <div class="col-md-6">
        <div class="box box-solid">
        	<?php /*?>
            <div class="box-header">
                <h3 class="box-title">Trend</h3>
            </div>
            <?php */?>
        	<div class="box-body">
        		<div id='chartContainer2' style="width: 100%; height:300px"></div>
        	</div>
        </div>
    </div><!-- ./col -->
</div>


<div class="row">
    <div class="col-md-6">
        <div class="box box-solid">
        	<?php /*?>
            <div class="box-header">
                <h3 class="box-title">Trend</h3>
            </div>
            <?php */?>
        	<div class="box-body">
        		<div id='chartContainer3' style="width: 100%; height:300px"></div>
        	</div>
        </div>
    </div><!-- ./col -->

    <div class="col-md-6">
        <div class="box box-solid">
        	<?php /*?>
            <div class="box-header">
                <h3 class="box-title">Trend</h3>
            </div>
            <?php */?>
        	<div class="box-body">
        		<div id='chartContainer4' style="width: 100%; height:300px"></div>
        	</div>
        </div>
    </div><!-- ./col -->
</div>


 <script type="text/javascript">
    $(document).ready(function () {
        // prepare chart data as an array
        var source =
        {
            datatype: "json",
            datafields: [
                { name: 'status' },
                { name: 'count' }
            ],
            url: "<?php echo site_url('smartpit/admin/temp'); ?>"
        };

        var colorsArray = new Array('#FF0000', '#00FF00');
        $.jqx._jqxChart.prototype.colorSchemes.push({ name: 'myScheme', colors: colorsArray });


        var dataAdapter = new $.jqx.dataAdapter(source, { async: false, autoBind: true, loadError: function (xhr, status, error) { alert('Error loading "' + source.url + '" : ' + error); } });
        // prepare jqxChart settings
        var settings = {
            title: "Smartpit Number Status",
            description: "Facebook",
            enableAnimations: false,
            showLegend: true,
            showBorderLine: false,
            legendPosition: { left: 520, top: 140, width: 100, height: 100 },
            padding: { left: 5, top: 5, right: 5, bottom: 5 },
            titlePadding: { left: 0, top: 0, right: 0, bottom: 10 },
            source: dataAdapter,
            colorScheme: 'myScheme',
            seriesGroups:
                [
                    {
                        type: 'pie',
                        showLabels: true,
                        series:
                            [
                                { 
                                    dataField: 'count',
                                    displayText: 'status',
                                    labelRadius: 120,
                                    initialAngle: 0,
                                    radius: 100,
                                    centerOffset: 0,
                                    
                                }
                            ]
                    }
                ]
        };
        // setup the chart
        $('#chartContainer1').jqxChart(settings);

        // prepare chart data as an array
        var source =
        {
            datatype: "json",
            datafields: [
                { name: 'status' },
                { name: 'count' }
            ],
            url: "<?php echo site_url('smartpit/admin/temp2'); ?>"
        };

        var colorsArray = new Array('#FF0000', '#00FF00');
        $.jqx._jqxChart.prototype.colorSchemes.push({ name: 'myScheme', colors: colorsArray });


        var dataAdapter = new $.jqx.dataAdapter(source, { async: false, autoBind: true, loadError: function (xhr, status, error) { alert('Error loading "' + source.url + '" : ' + error); } });
        // prepare jqxChart settings
        var settings = {
            title: "Smartpit Number Status",
            description: "Printed Card",
            enableAnimations: false,
            showLegend: true,
            showBorderLine: false,
            legendPosition: { left: 520, top: 140, width: 100, height: 100 },
            padding: { left: 5, top: 5, right: 5, bottom: 5 },
            titlePadding: { left: 0, top: 0, right: 0, bottom: 10 },
            source: dataAdapter,
            colorScheme: 'myScheme',
            seriesGroups:
                [
                    {
                        type: 'pie',
                        showLabels: true,
                        series:
                            [
                                { 
                                    dataField: 'count',
                                    displayText: 'status',
                                    labelRadius: 120,
                                    initialAngle: 0,
                                    radius: 100,
                                    centerOffset: 0,
                                }
                            ]
                    }
                ]
        };
        // setup the chart
        $('#chartContainer2').jqxChart(settings);
    });
</script>

