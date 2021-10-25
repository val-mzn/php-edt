<?php

include_once 'simple_html_dom.php';

function getWeek($date)
{
    $week = array();

    $url = 'https://edtmobiliteng.wigorservices.net//WebPsDyn.aspx?action=posEDTBEECOME&serverid=C&Tel=valentino.manzon&date=' . $date;
    $html = file_get_html($url);

    $cases = $html->find('div[class=Case]');
    foreach($cases as $case){
        if ($case->id == "Apres") {break;}
        if(!$case->find('td[class=TCase]'))
        {
            array_push($week, array());
            break;
        }

        $style = $case->style;
        $start = stripos($style, 'left:');
        $end = stripos($style, '%;', $offset = $start);
        $length = $end - $start;
        $left = substr($style, $start + 5, $length - 5);
        $d_index = round((floatval($left) - 103.12) / 19.4);

        $name = $case->find('td[class=TCase]')[0]->plaintext;


        $prof = str_replace("\r\n", "", $case->find('td[class=TCProf]')[0]->plaintext);
        $end = stripos($prof, 'Selon');
        $prof = substr($prof, 0, $end);

        $hour = $case->find('td[class=TChdeb]')[0]->plaintext;
        $hours = explode(" - ", $hour);

        $end = stripos($hours[0], ':');
        $hour_start = intval(substr($hours[0], 0, $end));

        $end = stripos($hours[1], ':');
        $hour_end = intval(substr($hours[1], 0, $end));

        $room = $case->find('td[class=TCSalle]')[0]->plaintext;
        $start = stripos($room, 'Salle:');
        $room = substr($room, $start + 6);

        $teams = "";
        $entry = $case->find('div[class=Teams] a');
        if($entry) {$teams = $entry[0]->href;}

        array_push($week, array(
            'name'=> $name,
            'prof'=> $prof,
            'room' => $room, 
            'd_index' => $d_index,
            'hour_start' => $hour_start, 
            'hour_end' => $hour_end, 
            'teams' => $teams
        ));
    }

    return $week;
}

for($week_count = 0; $week_count < 43; $week_count++) {

    $decal = $week_count * 7 + intval(date('w' , strtotime(date('2021/09/01')))) + 2;
    $date = date('Y-m-d', strtotime('+'. $decal .' day', strtotime('2021/09/01')));
    echo "scraping: " . $date . "\n";
    $response = getWeek($date);

    if (!file_exists('data')) {
        mkdir('data', 0777, true);
    }

    $fp = fopen('data/' . $date . '.json', 'w');
    if(count($response) - 1 != 0){
        fwrite($fp, json_encode($response));
    }
    fclose($fp);
}

