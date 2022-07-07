<?php 
    /*
     * Please put this file in the same folder with KoolControls folder
     * or you may modify path of require and scriptFolder to refer correctly
     * to koolchart.php and its folder.
     */
    require "KoolControls/KoolChart/koolchart.php";
    $chart = new KoolChart("chart");
    $chart->scriptFolder="KoolControls/KoolChart";
    $series = new PieSeries();
    $series->AddItem(new PieItem(20,"Quarter 1",null,false));
    $series->AddItem(new PieItem(30,"Quarter 2",null,false));
    $series->AddItem(new PieItem(35,"Quarter 3",null,true));
    $series->AddItem(new PieItem(15,"Quarter 4",null,false));
    $chart->PlotArea->AddSeries($series);
?>
<html>
    <head>
        <title>KoolChart</title>
    </head>
    <body>
        <?php echo $chart->Render(); ?>
    </body>
</html>