<?php
include("connection.php");
include("drugFuns.php");
session_start();

if (!isset($_SESSION['loggedIn'])) {
  header("location: login.php");
}

if(isset($_GET["delete"]) && $_GET["delete"] == true && isset($_GET["medicine_id"]))
{
  $medicine_id =intval($_GET["medicine_id"]);
        
  $query = "DELETE FROM medicine WHERE ID = ?";
  if($stmt = $con->prepare($query)){
    $stmt->bind_param("i",$medicine_id);
    if($stmt->execute()){
      echo '<h5 class="display-4">A Record Deleted Successfully</h5>';
      header("location: index.php");
    }else{
      echo '<h5 class="display-4">There is Error in Deleting the Record. Try Again</h5>';
    }
  }

}


?>

<?php set_header("Home") ?>

<div class="container-fluid">
  <div class="row mt-5 px-5">
    <div class="col-md-12">
      
      //link between the index page and the saveDrugs
      <a href="drugSaved.php" class="btn btn-primary mb-3 mr-3">Save New Drug</a>
       //link between the index page and the drugDosages
      <a href="drugDosages.php" class="btn btn-info mb-3">Record New Drug</a>
      <div class="table-responsive">

        <table class="table table-striped table-light">
          <thead>
            <tr>
             
              <th>Name</th>
              <th>Dosage_Qty.</th>
              <th>Dosage_Unit</th>
              <th>Measurement_Qty.</th>
              <th>Measurement_Unit</th>
              <th>Frequency_Qty.</th>
              <th>Frequency_Unit</th>
              <th colspan="2">Actions</th>
            </tr>
          </thead>
          <tbody>
          <?php
                $query = "SELECT * FROM medicine WHERE user_ID = ".$_SESSION['user_id']."";
                $result = mysqli_query($con, $query);

                if($result){
                    $count = 1;
                    while($rows = mysqli_fetch_array($result)){
                      echo '
                      <tr>
                        <td>' . $rows['medicine_name'] . '</td>
                        <td>' . $rows['dosage_quantity'] . '</td>
                        <td>' . $rows['dosage_unit'] . '</td>
                        <td>' . $rows['milligram_quantity'] . '</td>
                        <td>' . $rows['milligram_unit'] . '</td>
                        <td>' . $rows['frequency_quantity'] . '</td>
                        <td>' . $rows['frequency_unit'] . '</td>
                        <td><a class="btn btn-primary btn-block" href="edit_drug.php?id='.$rows['ID'].'">EDIT</a></td>
                        <td><a class="btn btn-danger btn-block" href="index.php?delete=true&medicine_id='.$rows['ID'].'">DELETE</a></td>
                    </tr>
                ';
                        $count += 1;
                    }
                }else{
                    echo "<h2>THE MEDICINES IS NOT FOUND</h2>";
                }
            ?>
          </tbody>
        </table>
      </div>


    </div>

  </div>

</div>
<?php set_footer()?>