<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <style>
      select[name="sqlDropDown"] {
        height: 34px;
      }
      
      body {
        padding-top: 70px;
        padding-bottom: 70px;
      }
      .nav-bottom-cust {
        border-top: 1px solid lightslategray;
      }
      
      .nav-top-cust {
        border-bottom: 1px solid lightslategray;
      }
      
      /*.fa-globe {*/
      /*  color: #6E8D6E;*/
      /*}*/
      
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
      
      $query_list = array(    
                  "SELECT District, Population FROM city WHERE Name='Springfield' ORDER BY Population DESC",
                  "SELECT name, district, population FROM city WHERE CountryCode = 'BRA' ORDER BY name",
                  "SELECT name, continent, surfacearea AS `Surface Area` FROM country ORDER BY surfacearea LIMIT 20",
                  "SELECT name, continent, governmentform AS `Form of Government`, gnp AS `GNP` FROM country WHERE gnp > 200000 ORDER BY name",
                  "SELECT name FROM country WHERE lifeexpectancy is not null ORDER BY lifeexpectancy LIMIT 10 OFFSET 9",
                  "SELECT name FROM city WHERE name like 'B%s' ORDER BY population DESC",
                  "SELECT city.name AS `City Name`, country.name AS `Country Name`, city.population AS `City Population` FROM city inner join country on city.countrycode = country.code WHERE city.population > 6000000 ORDER BY city.population DESC",
                  "SELECT country.name AS `Country Name`, country.indepyear AS `Year of Independence`, country.region FROM country inner join countrylanguage on country.code = countrylanguage.countrycode WHERE countrylanguage.language = 'English' and countrylanguage.isofficial='T' ORDER BY country.region, country.name",
                  "SELECT countryName AS `Country Name`, cityName AS `City Name`, (cityPop / countryPop) AS `Percent of Population In Capital` FROM (SELECT country.capital, country.name AS 'countryName', city.name AS 'cityName', city.population AS 'cityPop', country.population AS 'countryPop' FROM city inner join country on country.capital = city.id) AS table1 ORDER BY `Percent of Population In Capital` DESC",
                  "SELECT language, name, ((percentage * population) / 100) AS `Percentage of Speakers` FROM country inner join (SELECT countrycode, language, percentage FROM countrylanguage WHERE isOfficial = 'T') AS languageTable on country.code = languageTable.countrycode ORDER BY `Percentage of Speakers` DESC",
                  "SELECT name, region, gnp AS `GNP`, gnpold AS `Old GNP`, ((gnp - gnpold) / gnpold) AS `Real GNP Change` FROM country WHERE gnp is not null and gnpold is not null ORDER BY `Real GNP Change` DESC");
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
  
  <body onload="onPageLoad()">
    <nav class="navbar navbar-default navbar-fixed-top nav-top-cust">
      <div class="container">
          <form action="<?=$_SERVER['PHP_SELF']?>" method="POST" class="navbar-form navbar-left">
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
        <span class="navbar-brand navbar-right">World Database Queries <i class="fa fa-globe fa-lg"></i></span>
      </div>
    </nav>
      
    <br>
    <div class="container">
      <div class="row">
        
        <?php
          if(isset($_POST['sqlDropDown'])) {
            echo "<table class='table table-hover table-striped'>";
            // Runs the sql query, the query is stored in return, 
            // The column names are stored in columns
            list($columns, $return) = run_sql_query($query_list[3]);
            
            // Creates the table headers based off of $columns
            echo "<tr>";
            foreach($columns as $value) {
              echo "<th class='text-center'>" . ucwords($value->name) . "</th>";
            }
            echo "</tr>";
            
            //Creates the table data
            foreach($return as $value) {
              echo "<tr>\n";
              for($i = 0; $i < count($columns); $i++) {
                is_numeric($value[$i]) ? $textAlign = " class='text-right'" : $textAlign = "";
                echo "<td$textAlign>" . $value[$i] . "</td>";
              }
              echo "\n</tr>\n";
            }
          } else {
            echo "<div class='jumbotron text-center'><h2>Please Select a Query to Begin</h2></div>";
          }
          
        ?>
        </table>
      </div>
    </div>
    <nav class = "navbar navbar-default navbar-fixed-bottom nav-bottom-cust">
      <div class="container">
        <span class="navbar-brand">Pearse Hutson - pah9qd</span>
        <p></p>
        <button class='btn btn-primary navbar-right ' id='queryResults'>
          <?php
          if(isset($_POST['sqlDropDown'])) {
            echo "Number of query results: <span class='badge'>" . count($return) . "</span>";
          } else {
            echo "No Query Selected";
          }
          ?>
        </button>
      </dvi>
    </nav>
  </body>
</html>