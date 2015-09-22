<?php

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

?>

