<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
        <style>
            #queryResults {
                /*position: fixed; */
                /*bottom: 10px; */
                /*right: 10px; */
                /*border: 0;*/
            }
        </style>
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
            
            $query_list = array(    "SELECT District, Population FROM city WHERE Name='Springfield' ORDER BY Population DESC",
                                    "select name, district, population from city where CountryCode = 'BRA' order by name",
                                    "select name, continent, surfacearea from country order by surfacearea limit 20",
                                    "select name, continent, governmentform, gnp from country where gnp > 200000 order by name",
                                    "select name from country where lifeexpectancy is not null order by lifeexpectancy limit 10 offset 9",
                                    "select name from city where name like 'B%s' order by population desc",
                                    "select city.name, country.name, city.population from city inner join country on city.countrycode = country.code where city.population > 6000000 order by city.population desc",
                                    "select country.name, country.indepyear, country.region from country inner join countrylanguage on country.code = countrylanguage.countrycode where countrylanguage.language = 'English' and countrylanguage.isofficial='T' order by country.region, country.name",
                                    "select countryName, cityName, (cityPop / countryPop) as PercentOfPopulationInCapital from (select country.capital, country.name as 'countryName', city.name as 'cityName', city.population as 'cityPop', country.population as 'countryPop' from city inner join country on country.capital = city.id) as table1 order by PercentOfPopulationInCapital desc",
                                    "select language, name, ((percentage * population) / 100) as `Percentage of Speakers` from country inner join (select countrycode, language, percentage from countrylanguage where isOfficial = 'T') as languageTable on country.code = languageTable.countrycode order by `Percentage of Speakers` desc",
                                    "select name, region, gnp, gnpold, ((gnp - gnpold) / gnpold) as `Real GNP Change` from country where gnp is not null and gnpold is not null order by `Real GNP Change` desc");
                                    // 10 11
                                    // Cross off when completed.
                                    // Come back to 5...seems off.
            
            function run_sql_query() {
                global $query_list;
                if (!isset($_POST['sqlDropDown'])) {
                    $_POST['sqlDropDown'] = 0;
                }
                $result = mysqli_query($_SESSION['mylink'], $query_list[$_POST['sqlDropDown']]);
                
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
            
            
        ?>
        
    </head>
    <body>
        <div class="container">
            <br>
            <div class="row">
                <form action="<?=$_SERVER['PHP_SELF']?>" method="POST" class="form-inline">
                    <div class="form-group">
                        <select name="sqlDropDown">
                            <?php
                                $i = 0;
                                foreach($query_list as $value) {
                                    $i == $_POST['sqlDropDown'] ? $selectedTag = "selected" : $selectedTag = "";
                                    
                                    echo "<option value='" . $i++ . "' $selectedTag >Query $i</option>";
                                }
                            ?>
                        </select>
                    </div>
                    <button type="submit" type="submit" name="submit" value="Go" class="btn btn-primary">Submit</button>
                </form>
            </div>
            <br>
            <div class="row">
                <!--<div class="col-md-6 col-md-offset-3">-->
                <table class="table table-hover table-striped">
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
                <button class='btn btn-primary pull-right' id='queryResults'>
                <?php
                    echo "Number of query results: <span class='badge'>" . count($return) . "</span>";
                ?>
                </button>
            </div>
        </div>
    </body>
</html>