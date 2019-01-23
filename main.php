<html>
<head>
<title>Basic Image Site</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
    <header id="row">
        <div>
        <ul class="row">
          <li class="col"><a href="main.php">Home</a></li>
        </ul>
        </div>
    </header>
    
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
           Search by ZIP code <input type="text" pattern="[\d]{5}" name="search" required><br>
           <input type="submit" name="submit" value="Submit Form"><br>
        </form>
    
        <?php     
            function result($url) {
                $curl = curl_init();

                // OPTIONS:
                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
                
                // EXECUTE:
                $result = curl_exec($curl);
                if(!$result){die("Connection Failure. Please try again or come back later.");}
                curl_close($curl);
                
                return $result;
            }
            
            if(isset($_POST['submit'])) 
            { 
                $search = $_POST['search'];
                $url = "http://api.openweathermap.org/data/2.5/forecast?APPID=ab90019a46ee4ed511f87895b9903f08&zip=" . $search .",us&units=Imperial";
            }
            else {
                $url = "http://api.openweathermap.org/data/2.5/forecast?APPID=ab90019a46ee4ed511f87895b9903f08&zip=10001,us&units=Imperial";
            }
           

            $obj = json_decode(result($url));
            
            if($obj -> cod == "404")
            {
                echo "<h3 style='color:red'>Not a valid location.</h3>";
                echo "<p>Default: New York 10001</p>";
                $url = "http://api.openweathermap.org/data/2.5/forecast?APPID=ab90019a46ee4ed511f87895b9903f08&zip=10001,us&units=Imperial";
                $obj = json_decode(result($url));
            }
            
            echo '<div class="scrollmenu">';
            for($i = 0; $i < count($obj->{'list'});$i++) {
                echo "<ul class='menu'>";
                echo "<li>" . date("l j, g:i a", $obj -> list[$i] -> dt)."</li>";
                echo "<li>".$obj -> list[$i] -> weather[0] -> main ."</li>";
                echo "<li>" . $obj -> list[$i] -> weather[0] -> description . "</li>";
                echo "<li>High: " . $obj -> list[$i] -> main -> temp_max . "°</li>";
                echo "<li>Low: " . $obj -> list[$i] -> main -> temp_min . "°</li>";
                echo "<li><img src='http://openweathermap.org/img/w/".$obj -> list[$i] -> weather[0] -> icon.".png'/></li>";
                echo "</ul>";
            }
            echo "</div>";
            
            
        ?>

</body>
</html>