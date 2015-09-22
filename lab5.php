<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
        <?php
            ini_set('display_errors',1);
            ini_set('display_startup_errors',1);
            error_reporting(-1);
        
            $SERVER = 'us-cdbr-azure-central-a.cloudapp.net';
        	$USER = 'bf7f0622e9427e';
        	$PASS = '720ad0bb';
        	$DATABASE = 'cs3380-pah9qd';
        	
            $mylink = mysqli_connect( $SERVER, $USER, $PASS, $DATABASE) or die("<h3>Sorry, could not connect to database.</h3><br/>Please contact your system's admin for more help\n");
            $_SESSION['mylink'] = $mylink;
            
            function query1() {
                $sql = "SELECT District, Population FROM city WHERE Name='Springfield' ORDER BY Population DESC";
                $result = mysqli_query($_SESSION['mylink'], $sql);
                
                while($data = mysqli_fetch_object($result)) {
            	$return[] = array(	'district' => $data->District,
            						'population' => $data->Population,
            						);
                }
                return $return;
            }
            
            function query2() {
                $sql = "select name, district, population from city where CountryCode = 'BRA' order by name";
                $result = mysqli_query($_SESSION['mylink'], $sql);
                
                while($data = mysqli_fetch_object($result)) {
            	$return[] = array(	'name' => $data->name,
            	                    'district' => $data->district,
            						'population' => $data->population
            						);
                }
                return $return;
            }
            
            function query3() {
                $sql = "select name, continent, surfacearea from country order by surfacearea limit 20";
                $result = mysqli_query($_SESSION['mylink'], $sql);
                
                while($data = mysqli_fetch_object($result)) {
            	$return[] = array(	'name' => $data->name,
            	                    'continent' => $data->continent,
            						'surfacearea' => $data->surfacearea
            						);
                }
                return $return;
            }
            
            function query4() {
                $sql = "select name, continent, governmentform, gnp from country where gnp > 200000 order by name;";
                $result = mysqli_query($_SESSION['mylink'], $sql);
                
                while($data = mysqli_fetch_object($result)) {
            	$return[] = array(	'name' => $data->name,
            	                    'continent' => $data->continent,
            						'governmentform' => $data->governmentform,
            						'gnp' => $data->gnp
            						);
                }
                return $return;
            }
            
            function run_sql_query($sql="") {
                if ($sql = "") {
                    return NULL;
                }
                $mysql_result = mysqli_query($_SESSION['mylink'], $sql);
                
                while($data = mysqli_fetch_array($mysql_result)) {
                    
                }
                
            }
        ?>
        
    </head>
    <body>
        <div class="container">
            <!--<br>-->
            <div class="row">
                <form action="<?=$_SERVER['PHP_SELF']?>" method="POST" class="col-md-4">
                    <input type="submit" name="submit" value="Go">
                </form>
            </div>
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                <table class="table table-hove table-striped">
                <?php
                    // Query 2
                    
                    $return = query4();
        		    
        		    foreach($return as $key => $value) {
        		        echo "<tr><td>" . $value[0] . '</td><td>' . $value['continent'] . "</td><td>" . $value['governmentform'] . "</td><td>" . $value['gnp']."</td></tr>";
        		    }
                ?>
                </table>
                </div>
            </div>
        </div>
    </body>
</html>