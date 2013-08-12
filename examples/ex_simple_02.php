<?php
// load bootstrap
require __DIR__ . '/bootstrap.php';

/*
 * data source for tests
 * This is just mock data to simulate if it were coming from a database,
 * file, or any other datasource. 
 */

$filename = __DIR__ . '/data.json';
$mock_data = (array) json_decode(file_get_contents($filename));

/** 
 * Instance of Mgrid: Full example
 * set your datasource and render it
 */
$grid = new \Demo\Grid\Simple02();
// set datasource
$grid->setSource(new \Mgrid\Source\ArraySource($mock_data));
?>

<html>
    
    <head>
        <meta http-equiv="Content-type" content="text/html;charset=UTF-8">
        <title>Mgrid Example: Simple Version</title>
        <style>
            body { font-family: 'PT Sans', sans-serif, "Trebuchet MS"; }
        </style>
    </head>
    
    <body>
        <div class="my_application_grid">
            <?php echo $grid->render(); ?>
        </div>        
    </body>
    
</html>

        