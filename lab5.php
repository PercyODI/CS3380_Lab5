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
            
            function run_sql_query($sql="") {
                $result = mysqli_query($_SESSION['mylink'], $sql);
                
                $columns = mysqli_fetch_fields($result);
                
                $j = 0;
                while($data = mysqli_fetch_row($result)) {
                    
                    for($i = 0; $i < count($columns); $i++) {
                        $return[$j][] = $data[$i - 0];
                    }
                    $j++;
                }
                
                return array($columns, $return);
            }
            
            $query_list = array(    "SELECT District, Population FROM city WHERE Name='Springfield' ORDER BY Population DESC",
                                    "select name, district, population from city where CountryCode = 'BRA' order by name",
                                    "select name, continent, surfacearea from country order by surfacearea limit 20",
                                    "select name, continent, governmentform, gnp from country where gnp > 200000 order by name"
                                    )
        ?>
        
    </head>
    <body>
        <div class="container">
            <!--<br>-->
            <div class="row">
                <form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
                    <select name="sqlDropDown">
                        <?php
                            $i = 0;
                            foreach($query_list as $value) {
                                echo "<option value='Query" . $i++ . "'>" . $value ."</option>";
                            }
                        ?>
                    </select>
                    <input type="submit" name="submit" value="Go">
                </form>
            </div>
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                <table class="table table-hove table-striped">
                <?php
                    
                    list($columns, $return) = run_sql_query($query_list[3]);
                    
                    echo "<tr>";
                    foreach($columns as $value) {
                        echo "<th class='text-center'>" . ucwords($value->name) . "</th>";
                    }
                    echo "</tr>";
        		    
        		    foreach($return as $value) {
        		        echo "<tr>";
        		        for($i = 0; $i < count($columns); $i++) {
        		            echo "<td>" . $value[$i] . "</td>";
        		        }
        		        echo "</tr>";
        		    }
                ?>
                </table>
                </div>
            </div>
        </div>
    </body>
</html>