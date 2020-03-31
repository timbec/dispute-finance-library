<?php

/***
 * Debugging Functions
 */
//var_dump
function vd($el) {
    echo '<pre>'; 
    var_dump($el);
    echo '</pre>'; 
}

//print_r
function pr($el) {
    echo '<pre>'; 
    print_r($el);
    echo '</pre>'; 
}

//var_dump and die
function dd($el) {
    echo '<pre>'; 
    die(var_dump($el));
    echo '</pre>'; 
}