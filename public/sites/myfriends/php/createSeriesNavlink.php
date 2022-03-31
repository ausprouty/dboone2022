<?php

function createSeriesNavlink($p){
    $navlink = '../index.html';
    // in English all four of these are at the same level
    if ($p['library_code'] =='family' || $p['library_code'] =='friends'|| $p['library_code'] =='meet'){
         $navlink = 'index.html';
    }
    return $navlink;
}