<?php
// # femcon
// * cancon
// + local
//LP ###-### but if only one LP ##
//Show every format that was added
// LP, 7i, CASS, CD, ....
//
function makeEmailAddsTemplate(){
    $out = "";
    $out .= "<b>Format1 ### , Format2 ###,
    ADDED ".Carbon::now()->format('F jS, Y')."</b>

    _________________________________________________________
    TOP FIVE ADDS!
    ";
    //This is a numbered list
    $out.= "
    {ADD HERE!}

    _________________________________________________________
    DIGITAL ONLY

    ";
    //space-seperated list for all entries
    foreach($dig as $digi){
        $out .= "
        <b>{artist1}</b> - {album} (Label) {*#} {format} {No Catalog cause dig}
        {review}. - {Firstname L.}

        ";
    }
    $out .= "___________________________________________________________________
    LP

    ";
    foreach($lp as $lps){
        $out .= "<b>LP {Catalog#}
        {artist1}</b> - {album} (Label) {*#} {format}
        {review}. - {Firstname L.}

        ";
    }
    $out .= "___________________________________________________________________
    7”

    ";
    foreach($seveni as $sevenis){
        $out .= "<b>7i {Catalog#}
        {artist1}</b> - {album} (Label) {*#} {format}
        {review}. - {Firstname L.}

        ";
    }
    $out .= "___________________________________________________________________
    CASSETTE

    ";
    foreach($cass as $casss){
        $out .= "<b>CASS {Catalog#}
        {artist1}</b> - {album} (Label) {*#} {format}
        {review}. - {Firstname L.}

        ";
    }
    $out.="___________________________________________________________________
    CD
    "
    //sort into category based on local first, thn cancon, then femcon
    ."
    [blue text]___________________________________________________________________
    LOCAL[/blue text]

    <b>CD {Catalog#}
    {artist1}</b> - {album} (Label) {*#} {format}
    {review}. - {Firstname L.}

    [red text]___________________________________________________________________
    CANCON[/red text]

    <b>CD {Catalog#}
    {artist1}</b> - {album} (Label) {*#} {format}
    {review}. - {Firstname L.}

    [green text]___________________________________________________________________
    FEMCON [/green text]

    <b>CD {Catalog#}
    {artist1}</b> - {album} (Label) {*#} {format}
    {review}. - {Firstname L.}
    ___________________________________________________________________

    "
    //below is CDs that fit no category
    ."

    <b>CD {Catalog#}
    {artist1}</b> - {album} (Label) {*#} {format}
    {review}. - {Firstname L.}

    ";
    return $out;
}
