<?php

/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the LGPL. For more information, see
 * <http://mgrid.mdnsolutions.com/license>.
 */

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
 * Instance of Mgrid: Simple01 example
 * set your datasource and render it
 */
$grid = new \Demo\Grid\Simple01();
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

        