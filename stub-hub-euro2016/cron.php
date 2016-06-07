<?php
    // Actual data feed
    $url = 'https://api.crowdscores.com/api/v1/matches?competition_id=267&api_key=bfffed6e955441008f49c642a2dcb8d4';
    // Use below url for test data  
    // $url = './testdata';
    $content = file_get_contents($url);
    $matchContent = file_get_contents('./data/matches-static.json');
    $json = json_decode($content, true);
    $matchJson = json_decode($matchContent);
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $data = curl_exec($ch);
    curl_close($ch);
    $date = (time() * 1000);
    function getDetails($json,$dbid) {

        $details =  new stdClass();

        foreach ($json as $val) {

            if(intval($dbid) == $val['dbid']) {

                $details->aggregateScore = $val['aggregateScore'];
                $details->awayGoals = $val['awayGoals'];
                $details->start = $val['start'];
                $details->homeGoals = $val['homeGoals'];
                $details->start = $val['start'];
                break;
            }
        }

        return $details;
    }

    foreach ($matchJson->group as $val) {

        foreach ($val->games as $match) {

            $uid = $match->number;

            $details = getDetails($json,$uid);

            $match->aggregateScore = $details->aggregateScore;
            $match->awayGoals = $details->awayGoals;
            $match->start = $details->start;
            $match->homeGoals = $details->homeGoals;
            $match->expired = (intval($details->start-172800000)<$date);
            $match->gameOver = (intval($details->start+6300000)<$date);
        };
    };
    // var_dump($matchJson);
    file_put_contents('data/matches.json', json_encode($matchJson));

?>
