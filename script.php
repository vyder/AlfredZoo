<?php

    $DEBUG = true;

    if($DEBUG) { print "Started ZooTool Script\n"; }
    
    // Setup ----------------------------
    // ----------------------------------
    include 'class.zoo.php';
    include 'encoder.php';
    
    // API Key. Ref: zootool.com/api
    $key = 'b02bbb3888d5ed09c1ce785e72591ac2';
    $secret = 'uup7d';

    $zoo = new ZooPHP($key, $secret);

    // Set output format
    $zoo->setFormat('json');
    
    // Auth

    // $settings = simplexml_load_file("settings.xml");
    // $key = $settings->$key;
    // $zoo->setAuth(decode($settings->$username,$key),decode($settings->$password,$key));
    // print "Username = $settings->$username\n";
    // $zoo->setAuth($settings->$username,$settings->$password);
    
    $username = 'alfredzoo_tester';
    $password = 'epicsecret';
    
    $zoo->setAuth($username, $password);
    
    // ----------------------------------
    // End of Setup ---------------------


    // Parse query from AlfredApp -------
    // ----------------------------------
    $query = $argv[1];
    $query = explode(" ", $query);

    $href = $query[0];
    $title = $query[1];

    if($DEBUG) { 
        print "URL: $href\n";
        print "Title: $title\n";
    }
    // ----------------------------------
    // End of Parsing -------------------
    
    
    $response = $zoo->addItem($href, $title);
    if($DEBUG) { print "Got this far...\n"; }

    if( $DEBUG && $response == false) {
        print "Request failed...\n";
    }
    else {
        print "Success!";
    }

    return $response;
    
?>