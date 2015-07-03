<?php

require_once('db.php');

// function for getting drop down list with regions

function createDBConnection()
{
    $dsn = DB_ENGINE . ':host=' . DB_HOST . ';dbname=' . DB_NAME;
    $db  = new PDO($dsn, DB_USER, DB_PW);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    return $db;
}


// function for getting regions from drop down list
function getRegions($db)
{
    $regions = array();
    
    try {
        //connect as appropriate as above
        $stmt = $db->prepare('select distinct region_name from region;');
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $regions[] = $row["region_name"];
        }
        
        
        
    }
    catch (PDOException $ex) {
        $error = $error + ' ' + ($ex->getMessage());
    }
    
    return $regions;
}

// function for getting grape variety from drop down list
function getVariety($db)
{
    $varieties = array();
    
    try {
        //connect as appropriate as above
        $stmt = $db->prepare('select distinct variety from grape_variety;;');
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $varieties[] = $row["variety"];
        }
        
        
        
    }
    catch (PDOException $ex) {
        $error = $error + ' ' + ($ex->getMessage());
    }
    
    return $varieties;
}


// function for getting the year range

function getYearRange($db)
{
    
    $years = array();
    
    try {
        //connect as appropriate as above
        $stmt = $db->prepare('select min(year) as MinYear, max(year) as MaxYear from wine;');
        $stmt->execute();
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $minyear = $row["MinYear"];
            $maxyear = $row["MaxYear"];
        }
        
        
        for ($i = $minyear; $i <= $maxyear; $i++) {
            $years[] = $i;
        }
        
        foreach ($years as $year) {
            
        }
        
    }
    catch (PDOException $ex) {
        $error = $error + ' ' + ($ex->getMessage());
    }
    
    return $years;
}

?>