<?php
session_start();
?>
<html>
<head>
    <title>Build CSVs for Saturation Mapping</title>
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
</head>
<body>
<br>
<h1>Build CSVs For Saturation Mapping (for development only)</h1>

</br>
<form class="form-horizontal"action="" method="post">
    <div class="form-group">
        <label for="mysql" class="control-label col-xs-2">Mysql Server address (or)<br>Host name</label>
        <div class="col-xs-3">
            <input type="text" class="form-control" name="mysql" id="mysql" placeholder="" <?php isset( $_SESSION['mysql'] ) ? print 'value="' . $_SESSION['mysql'] . '"' : print ''; ?>>
        </div>
    </div>
    <div class="form-group">
        <label for="username" class="control-label col-xs-2">Username</label>
        <div class="col-xs-3">
            <input type="text" class="form-control" name="username" id="username" placeholder="" <?php isset( $_SESSION['username'] ) ? print 'value="' . $_SESSION['username'] . '"' : print ''; ?>>
        </div>
    </div>
    <div class="form-group">
        <label for="password" class="control-label col-xs-2">Password</label>
        <div class="col-xs-3">
            <input type="text" class="form-control" name="password" id="password" placeholder="" <?php isset( $_SESSION['password'] ) ? print 'value="' . $_SESSION['password'] . '"' : print ''; ?>>
        </div>
    </div>
    <div class="form-group">
        <label for="db" class="control-label col-xs-2">Database name</label>
        <div class="col-xs-3">
            <input type="text" class="form-control" name="db" id="db" placeholder="" <?php isset( $_SESSION['db'] ) ? print 'value="' . $_SESSION['db'] . '"' : print ''; ?> >
        </div>
    </div>

    <div class="form-group">
        <label for="table" class="control-label col-xs-2">table name</label>
        <div class="col-xs-3">
            <input type="name" class="form-control" name="table" id="table" <?php isset( $_SESSION['table'] ) ? print 'value="' . $_SESSION['table'] . '"' : print ''; ?>>
        </div>
    </div>
    <div class="form-group">
        <label for="login" class="control-label col-xs-2"></label>
        <div class="col-xs-3">
            <button type="submit" class="btn btn-primary">Build</button>
        </div>
    </div>
</form>
</div>

</body>

<?php

if(isset($_POST['username'])&&isset($_POST['mysql'])&&isset($_POST['db'])&&isset($_POST['username']))
{
    $sqlname=$_POST['mysql'];
    $_SESSION['mysql'] = $sqlname;

    $username=$_POST['username'];
    $_SESSION['username'] = $username;

    $table=$_POST['table'];
    $_SESSION['table'] = $table;

    if(isset($_POST['password']))
    {
        $password=$_POST['password'];
    }
    else
    {
        $password= '';
    }
    $_SESSION['password'] = $password;

    $db=$_POST['db'];
    $_SESSION['db'] = $db;

    $html = '';

    $file = file_get_contents('../csv/countries.json' );
    $file_array = json_decode( $file, true );

    if ( ! empty( $file_array ) ) {
        foreach ( $file_array as $key => $item ) {

            $key = strtolower( $key );

            $file_location = getcwd() . '/gn_'.$key.'_p.csv';

            if ( ! file_exists( $file_location ) ) {

//                $header_rows = ["geonameid","name","asciiname","alternatenames","latitude","longitude","feature_class","feature_code","country_code","cc2","admin1_code","admin2_code","admin3_code","admin4_code","population","elevation","dem","timezone","modification_date"];
//                $fp = fopen($file_location, 'w');
//                fputcsv($fp, $header_rows, ",", '"', "\\");
//                fclose($fp);

                $cons= mysqli_connect("$sqlname", "$username","$password","$db") or die(mysql_error());
                $query_results = mysqli_query($cons, "
                    
                    SELECT * FROM $table
                        WHERE feature_code != 'PPLF'
                            AND feature_code != 'PPLW'
                            AND feature_code != 'PPLCH'
                            AND feature_code != 'PPLQ'
                            AND feature_code != 'PPLR'
                            AND feature_code != 'PPLCH'
                            AND feature_class = 'P'
                            AND country_code = '$key'
                            ORDER BY name
                            INTO OUTFILE '$file_location'
                            FIELDS TERMINATED BY ','
                            ENCLOSED BY '\"'
                            LINES TERMINATED BY '\n';
                        ");
                mysqli_close( $cons );
                $html .= $item . '<br>';
            }

        }
        echo $html;
    }

}
else{
    echo "Mysql Server Address/Host name, Username, Database Name, Table name, and File name are the Mandatory Fields";
}

?>
<h3> Instructions </h3>
1.  Keep this php file and Your csv file in one folder <br>
2.  Create a table in your mysql database to which you want to import <br>
3.  Open the php file from your localhost server <br>
4.  Enter all the fields  <br>
5.  click on upload button  </p>

<h3> Facing Problems ? Some of the reasons can be the ones shown below </h3>
1) Check if the table to which you want to import is created and the datatype of each column matches with the data in csv<br>
2) If fields in your csv are not separated by commas go to Line 117 of php file and change the query<br>
3) If each tuple in your csv are not one below other(i.e not seperated by a new line) got line 117 of php file and change the query<br>

</html>
