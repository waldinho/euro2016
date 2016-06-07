<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <title>Hello</title>
    
  </head>
<body>
<div class="content">


    
  <?php

    $url = 'https://api.crowdscores.com/api/v1/matches?competition_id=267&api_key=bfffed6e955441008f49c642a2dcb8d4';
    $content = file_get_contents($url);
    $json = json_decode($content, true);
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $data = curl_exec($ch);
    curl_close($ch);
    $date = time();
    file_put_contents('data/api.json', $data);

    // echo $content;

    
        
        // echo '<img src="'$json[0][homeTeam][flagURL]'">';
        // echo $json[0][start];
        // echo $json[1][homeTeam][name] . "<br/>";

        $epoch = number_format($json[0][start], 0, '.', '');
        // echo date('r', $epoch); // output as RFC 2822 date - returns local time
        // echo gmdate('r', $epoch); // returns GMT/UTC time


        echo $date . " - Current time and date";
        echo "<br/>";
        $number = ($epoch / 1000);
        echo $number . " - Kick off";

        echo "<br/>";
        function compareDate() {
          if($date <= $number)
          {
            echo "Buying and selling is closed for this match.";
          }
          else {
            echo "Buy now!";
          }
        }
        echo "<br/>";
        if($date >= $number)
          {
            echo $json[0][homeGoals] . " - " . $json[0][awayGoals];
          }


        compareDate(); // call the function
        

  ?>

</div>
</body>

