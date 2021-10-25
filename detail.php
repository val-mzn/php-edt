<link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">

<style>
    body{
        background-color: #1b2631;
    }
    .card{
        background-color: #34495e;
        color: #ecf0f1;
        border: #ecf0f1 0.1rem solid;        
    }
    .card-header {
        border-bottom: #ecf0f1 0.1rem solid;
    }
    .list-group-item, a{
        color: #ecf0f1;
    }

    .list-group-item:hover, a:hover{
        color:#fdfefe;
        font-weight: bold;
    }

    .done{
        background-color: #5d6d7e;
    }

    .current{
        background-color: #a569bd;
    }

    .next{
        background-color: #21618c;
    }

    .void{
        background-color: #148f77;
    }
</style>

<?php
setlocale(LC_ALL,'French');
date_default_timezone_set('Europe/Paris');

    $file = $_GET["file"];
    
    $file = file_get_contents('data/'. $file);
    $data = json_decode($file, true);

    $date = pathinfo($_GET["file"], PATHINFO_FILENAME);
    $date = date_create_from_format('Y-m-d', $date);

    $old_index = -1;

    $first = FALSE;
    $next_pick = FALSE;
    echo "<div class='container-fluid d-flex flex-row justify-content-center flex-wrap'>";
    if(isset($data)){
    foreach($data as $entry){
        if($old_index != $entry['d_index'])
        {
            if($first == FALSE){
                $first = TRUE;
            }
            else{
                echo "</ul>";
                echo "</div>";
                echo "</div>";
            }
            echo "<div class='card' style='width: 18rem; margin: 0.3rem'>";
            echo "<h5 class='card-header text-center fw-bold'>" . date_format($date, 'l d F ') . "</h5>";
            echo "<div class='card-body'>";
            echo "<ul class='list-group'>";
            $date->add(new DateInterval('P1D'));
            $old_index = $entry['d_index'];
        }

        /*echo "<a href='#' class='list-group-item list-group-item-action flex-column align-items-start'>";
        echo "<div class='d-flex w-100 justify-content-between'>";
        echo "<h5 class='mb-1'>" . $entry['name'] . "</h5>";
        echo "<small>" . $entry['prof'] . "</small>";
        echo "</div>";
        echo "<p class='mb-1'>" . date_format($date, 'd/m/Y') . " ". "</p>";
        echo "<small>Donec id elit non mi porta.</small>";
        echo "</a>";*/

        if (new DateTime() > $date) {
            echo "<li class='done list-group-item flex-column align-items-start'>" ."<p class='mb-1 fw-bold'>" . $entry['name'] . "</p>";
            echo "<p class='mb-1 fw-light text-capitalize'>" . $entry['prof']. "</p>";
            if($entry['room'] != "Aucune"){
                echo "<span class='badge bg-info'>". $entry['room'] ."</span>";
            }
            echo "<span class='badge bg-primary'>". $entry['hour_start'] .":00 - " .$entry['hour_end'] .":00</span>";
            if($entry['teams'] != ""){
                echo "<span class='badge' style='background-color: purple'><a class='text-decoration-none text-white' href='" . $entry['teams'] . "'>TEAMS</a></span>";
            }
            echo "</li>";
            $next_pick = TRUE;
        }
        else{
            if($next_pick == FALSE && date("W") == date_format($date,"W")){
                echo "<li class='current list-group-item flex-column align-items-start'>" ."<p class='mb-1 fw-bold'>" . $entry['name'] . "</p>";
                echo "<p class='mb-1 fw-light text-capitalize'>" . $entry['prof']. "</p>";
                if($entry['room'] != "Aucune"){
                    echo "<span class='badge bg-info'>". $entry['room'] ."</span>";
                }
                echo "<span class='badge bg-primary'>". $entry['hour_start'] .":00 - " .$entry['hour_end'] .":00</span>";
                if($entry['teams'] != ""){
                    echo "<span class='badge' style='background-color: purple'><a class='text-decoration-none text-white' href='" . $entry['teams'] . "'>TEAMS</a></span>";
                }
                echo "</li>";
                $next_pick = TRUE;
            }
            else{
                echo "<li class='next list-group-item flex-column align-items-start'>" ."<p class='mb-1 fw-bold'>" . $entry['name'] . "</p>";
                echo "<p class='mb-1 fw-light text-capitalize'>" . $entry['prof']. "</p>";
                if($entry['room'] != "Aucune"){
                    echo "<span class='badge bg-info'>". $entry['room'] ."</span>";
                }
                echo "<span class='badge bg-primary'>". $entry['hour_start'] .":00 - " .$entry['hour_end'] .":00</span>";
                if($entry['teams'] != ""){
                    echo "<span class='badge' style='background-color: purple'><a class='text-decoration-none text-white' href='" . $entry['teams'] . "'>TEAMS</a></span>";
                }
                echo "</li>";
            }
        }
        

        
    }}
    else{
        echo "<p>Pas de cours</p>";
    }
    echo "</div>";
?>