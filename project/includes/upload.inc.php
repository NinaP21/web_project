<?php
/* This code is executed when admin wants to upload a file. */


/* Make sure that this code is executed only if user has pressed
  the "Upload" button in the file upload form. */
if ( isset($_POST['submit']) ) {
  $file = $_FILES['userfile'];

/* Save useful information about files */
  $fileName = $_FILES['userfile']['name'];
  $fileTmpName = $_FILES['userfile']['tmp_name'];
  $fileSize = $_FILES['userfile']['size'];
  $fileError = $_FILES['userfile']['error'];

/* Get the file extension by seperating the file name string
   using the dot as the delimiter and then keep the second half substring. */
  $fileExtension = explode('.', $fileName);
  $fileActualExtension = strtolower(end($fileExtension));

/* Only .json files are acceptable,
   otherwise an error message will appear to the user*/
  if ( $fileActualExtension == 'json' ) {
/* Make sure no error has happened during the file upload */
    if ( $fileError === 0 ) {
/* Set a limit in file size. This is equal to 1 GByte. */
      if ($fileSize < 1000000000 ) {
/* At this point all requirements are ok,
   so we implement the file upload. First, we create a new name
   which will be unique and then we change the folder where it will be saved. */
        $fileNameNew = uniqid('', true).".json";
        $fileDestination = '../uploads/'. $fileNameNew;

        if ( !move_uploaded_file($fileTmpName, $fileDestination) ) {
          header("Location: ../index.php?upload=error");
          mysqli_close($conn);
          exit();
        }
/* Call this function that uploads the json data to the database. */
        upload_json( $fileDestination );
        header("Location: ../index.php?upload=success");
        mysqli_close($conn);
        exit();
      }
/* Error concerning file size */
       else {
        header("Location: ../index.php?upload=error&error=size");
        mysqli_close($conn);
        exit();
       }
     }
/* Error concerning $_FILES error code */
     else {
      header("Location: ../index.php?upload=error");
      mysqli_close($conn);
      exit();
     }
  }
/* Error concerning file type */
  else {
    header("Location: ../index.php?upload=error&error=filetype");
    mysqli_close($conn);
    exit();
  }
}
/* If user tries to visit this page without pressing "Upload"
  then the system will redirect them to home page */
else {
  header("Location: ../index.php");
  exit();
}


/* This function parses the data from the .json file
   and then it inserts them to our database.*/
function upload_json( $file )
{
/* First connect to our database */
  require 'dbh.inc.php';
/* Then convert json data into a string */
  $jsondata = file_get_contents($file);

/* Convert $jsondata string into a php associative array */
  $data_array = json_decode($jsondata, true);

/* Then, we parse the JSON array elements
   one by one and store them in local variables.
   Then, insert those data to the appropriate tables of our database.
   In every case we state that if it is about a duplicate key, then
   just update those data without creating duplicate records. */

/* parse whole table of JSON objects */
  foreach ($data_array as $key1) {
    $id = $key1['id'];
    $name = mysqli_real_escape_string($conn, $key1['name']);
    $address = mysqli_real_escape_string($conn, $key1['address']);

    $types = array();
/* parse table of types of POIs */
    foreach ($key1['types'] as $key2) {
      array_push($types, $key2);
      $sql1 = "INSERT INTO POI_type (id, type)
                VALUES ('$id', '$key2')
                ON DUPLICATE KEY UPDATE id = '$id'";
      mysqli_query($conn, $sql1);
    }

/* Save coordinates with 8 decimal digits and a simple point as decimalpoint parameter. */
    $coord_lat = number_format($key1['coordinates']['lat'], 8, '.');
    $coord_lng = number_format($key1['coordinates']['lng'], 8, '.');
    $rating = $key1['rating'];
    $rating_n = $key1['rating_n'];
/* The value current_popularity is not set in all JSON objects,
   so we consider that the default value is zero. */
    if( isset($key1['current_popularity']) ){
      $current_popularity = $key1['current_popularity'];
    } else {
      $current_popularity = 0;
    }

/* parse table of populartimes */
    foreach ($key1['populartimes'] as $key3) {
      $populartimes_data = array();

      $day = $key3['name'];
/* parse table of data in populartimes */
      foreach ($key3['data'] as $key4) {
        array_push($populartimes_data, $key4);
      }
/* Make all hour population numbers into a string with a comma as seperator. */
      $data_str = implode("','",$populartimes_data);
      $sql2 = "INSERT INTO popular_times_of_pois
               VALUES('$id', '$day', '".$data_str."')
               ON DUPLICATE KEY UPDATE h00 = '$populartimes_data[0]', h01 = '$populartimes_data[1]', h02 = '$populartimes_data[2]', h03 = '$populartimes_data[3]', h04 = '$populartimes_data[4]', h05 = '$populartimes_data[5]',
                                       h06 = '$populartimes_data[6]', h07 = '$populartimes_data[7]', h08 = '$populartimes_data[8]', h09 = '$populartimes_data[9]', h10 = '$populartimes_data[10]', h11 = '$populartimes_data[11]',
                                       h12 = '$populartimes_data[12]', h13 = '$populartimes_data[13]', h14 = '$populartimes_data[14]', h15 = '$populartimes_data[15]', h16 = '$populartimes_data[16]', h17 = '$populartimes_data[17]',
                                       h18 = '$populartimes_data[18]', h19 = '$populartimes_data[19]', h20 = '$populartimes_data[20]', h21 = '$populartimes_data[21]', h22 = '$populartimes_data[22]', h23 = '$populartimes_data[23]'";
      mysqli_query($conn, $sql2);
    }
/* Insert data into database */
    $sql3 = "INSERT INTO points_of_interest (id, name, address, coordinates_lat, coordinates_lng, rating, rating_n, current_popularity)
            VALUES ('$id', '$name', '$address', '$coord_lat', '$coord_lng', '$rating', '$rating_n', '$current_popularity')
            ON DUPLICATE KEY UPDATE id='$id', name = '$name', address = '$address', coordinates_lat = '$coord_lat', coordinates_lng = '$coord_lng',
                                    rating = '$rating', rating_n = '$rating_n', current_popularity = '$current_popularity' ";
    mysqli_query($conn, $sql3);

  }
}
