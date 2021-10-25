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

$dir    = './data';
$files = scandir($dir);

echo "<div class='container-fluid d-flex flex-row justify-content-center flex-wrap'>";
$prev_month = '';
$first = FALSE;

$decal = intval(date('w')) + 1;
$current_week = date('F W', strtotime('+'. $decal .' day', strtotime('now')));

foreach($files as $f)
{
    if(strpos($f, '.json'))
    {
        
        $file = file_get_contents('data/'. $f);
        $data = json_decode($file, true);

        $date = pathinfo($f, PATHINFO_FILENAME);
        $date_str = date_format(date_create_from_format('Y-m-d', $date),'F W');
        
        if($prev_month != date_format(date_create_from_format('Y-m-d', $date),'m')){
           
            if($first == FALSE){
                $first = TRUE;
            }
            else{
                echo "</ul>";
                echo "</div>";
                echo "</div>";
            }
           
            $prev_month = date_format(date_create_from_format('Y-m-d', $date),'m');
            echo "<div class='card title' style='width: 18rem; margin: 0.3rem'>";
            echo "<div class='card-header text-uppercase fw-bold text-center'>" . date_format(date_create_from_format('Y-m-d', $date),'F') . "</div>";
            echo "<div class='card-body'>";
            echo "<ul class='list-group'>";
          
        }

        $hour = 0;
        if($data){
            foreach($data as $entry){
                $hour += $entry['hour_end'] - $entry['hour_start'];
            }
        }

        if ($current_week == $date_str) {
            echo "<li class='current list-group-item d-flex justify-content-between align-items-center font-monospace'>" .'<a class="text-decoration-none" href=detail?file='. $f .'>' .$date_str.'</a>';
            echo "<span class='badge bg-primary'>". $hour ." h</span>";
            echo "</li>";
        }
        else if(new DateTime() > date_create_from_format('Y-m-d', $date)){
            echo "<li class='done list-group-item d-flex justify-content-between align-items-center font-monospace'>" .'<a class="text-decoration-none" href=detail?file='. $f .'>' .$date_str.'</a>';
            echo "<span class='badge bg-primary'>". $hour ." h</span>";
            echo "</li>";
        }
        else if($data){
            echo "<li class='next list-group-item d-flex justify-content-between align-items-center font-monospace'>" .'<a class="text-decoration-none" href=detail?file='. $f .'>' .$date_str.'</a>';
            echo "<span class='badge bg-primary'>". $hour ." h</span>";
            echo "</li>";
        }
        else {
            echo "<li class='void list-group-item d-flex justify-content-between align-items-center font-monospace'>" . $date_str;
            echo "<span class='badge bg-success'>pas de cours</span>";
            echo "</li>";
        }
    }
}
echo "</div>";
?>
