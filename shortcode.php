<?php 
$Content = explode("{cwnl}",$ActiveArticle['info']);
foreach($Content as $Segment){
    preg_match_all("/{shortcode.id=.../", $Segment, $matches);
    if($matches['0']['0'] == ""){
        echo $Segment;
        echo "<div class='cwnl'></div>";
    }else{
        foreach($matches['0'] as $value){
            $value2 = $value . "}";
            $ActiveArticle['info'] = str_replace($value2, "", $ActiveArticle['info']);
            $SCFilter = explode(" ",$value);
            $SCFilter = explode("=",$SCFilter['1']);
            $SCId = $SCFilter['1'];
            unset($SCFilter);
            $query = "SELECT * FROM page_function WHERE active='1' AND trash='0' AND shortcode='$SCId'";
            $result = mysql_query($query) or die(mysql_error());
            $row = mysql_fetch_array($result);
            $row = PbUnSerial($row);
            $Function_Type = $row['function'];
            $Function_Array = $row['contents'];
            $Array['function'] = $Function_Array;
            if(function_exists("$Function_Type")){
                $Function_Type($Array);
                echo "<div class='cwnl'></div>";
            }
        }
    }
}
?>
