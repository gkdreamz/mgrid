<?php 
// load the vendor autoload
require __DIR__ . '/../../vendor/autoload.php';

class MyTestGrid extends \Mgrid\Grid
{
    // this class is the one called by the render
    public function init()
    {
        $this->addColumn(array(
                    'label' => 'Id',
                    'index' => 'id',
                ))
            ->addColumn(array(
                    'label' => 'Customer',
                    'index' => 'name',
                ));
    }
}

// some data to output
$mockResultSet = array(
    0 => array(
        'id' => 1001,
        'name' => 'John Due'
    ),
    1 => array(
        'id' => 1003,
        'name' => 'Mary Due'
    ),
);
        
// instance of my grid
$myTestGridObj = new MyTestGrid();

// set datasource
$myTestGridObj->setSource(new \Mgrid\Source\ArraySource($mockResultSet));

?>

<html>
    
    <head>
        <meta http-equiv="Content-type" content="text/html;charset=UTF-8">
        <title>Mgrid Example</title>
        <style>
            body { font-family: 'PT Sans', sans-serif, "Trebuchet MS"; }
        </style>
    </head>
    
    <body>
        <!-- Rendering the grid  -->
        <?php echo $myTestGridObj->render(); ?>
    </body>
    
</html>
