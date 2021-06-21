<?php
//This is the edditing drug page...where it is use to connect the pages 
include("connection.php");
include("drugFuns.php");
session_start();
if (!isset($_SESSION['loggedIn'])) {
    header("location: login.php");
} else {

    $medicine_id;

    if (isset($_GET["id"])) {
        $medicine_id = intval($_GET['id']);
        $query = "SELECT * FROM medicine WHERE ID = ? LIMIT 1";
        $stmt = $con->prepare($query);
        $stmt->bind_param('i', $medicine_id);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
        }
    }

    if (isset($_POST["update_Medicine"])) {

        //Collect all the variables from the updates
        $medicine_Name = trim($_POST["medicine_name"]);
        $dosage_Qty = intval(trim($_POST["dosage_quantity"]));
        $dosage_Unit = trim($_POST["dosage_unit"]);
        $milligram_Qty = intval(trim($_POST["milligram_quantity"]));
        $milligram_Unit = trim($_POST["milligram_unit"]);
        $frequency_Qty = intval(trim($_POST["frequency_quantity"]));
        $frequency_Unit = trim($_POST["frequency_unit"]);


        $query = "UPDATE medicine SET 
        medicine_name = ?,
        dosage_quantity = ?,
        dosage_unit = ?,
        milligram_quantity = ?,
        milligram_unit = ?,
        frequency_quantity = ?,
        frequency_unit = ? WHERE ID = ?";

        if ($statement = $con->prepare($query)) {
            if ($statement->bind_param("sisisisi", $medicine_Name, $dosage_Qty, $dosage_Unit, $milligram_Qty, $milligram_Unit, $frequency_Qty, $frequency_Unit, $medicine_id)) {

                if ($statement->execute()) {
                    echo "It has been Successfully Updated";
                    header("location: index.php");
                } else {
                    echo "It has  Error on the Update!";
                    header("location: edit_drug.php?id=" . $medicine_id. "");
                }
            } else {
                echo "Rectifing the error binding";
            }
        } else {
            echo "Error in query statement";
        }


    
    }
}
?>

<?php set_header("Edit_Medicines") ?>
<div class="container">
    <div class="row mt-4">
        <div class="col-md-8 offset-md-2">
            <h4 class="display-5 my-1">Edit Update Medicine</h4>
            <?php
            if (!empty($row)) {
                echo '
                    <form action="" method="post">
                    <div class="form-group">
                        <label for="">Medicine Name</label>
                        <input type="text" name="medicine_name" id="medicine_name" placeholder="Medicine Name" required class="form-control" value="' . $row["medicine_name"] . '">
                    </div>
                    <div class="form-group">
                        <label for="">Dosage Quantity</label>
                        <input type="number" name="dosage_quantity" id="dosage_quantity" required class="form-control" min="1" max="300" value="' . $row["dosage_quantity"] . '">
                    </div>
                    <div class="form-group">
                        <label for="">Dosage Unit</label>
                        <select name="dosage_unit" id="dosage_unit" class="form-select" required>
                            <option value="' . $row["dosage_unit"] . '" selected>' . $row["dosage_unit"] . '</option>
                            <option value="Tab" >Tab</option>
                            <option value="Bottle">Bottle</option>
                            <option value="Injection">Injection</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Milligrams</label>
                        <input type="text" name="milligram_quantity" id="milligram" placeholder="Milligram" required class="form-control" value="' . $row["milligram_quantity"] . '">
                    </div>
                    <div class="form-group">
                        <label for="">Unit(g/mg)</label>
                        <select name="milligram_unit" id="unit" class="form-select" required>
                            <option value="' . $row["milligram_unit"] . '" selected>' . $row["milligram_unit"] . '</option>
                            <option value="Grams" >Grams</option>
                            <option value="Milli_Grams">MilliGrams</option>
                        </select>
                    </div>
    
                    <div class="form-group">
                        <label for="">Frequency Quantity</label>
                        <input type="number" name="frequency_quantity" id="frequency_quantity" placeholder="Frequency Quantity" required class="form-control" min="1" max="300" value="' . $row["frequency_quantity"] . '">
                    </div>
    
                    <div class="form-group">
                        <label for="">Frequency Unit</label>
                        <select name="frequency_unit" id="frequency_unit" class="form-select">
                            <option value="' . $row["frequency_unit"] . '" selected>' . $row["frequency_unit"] . '</option>
                            <option value="Daily" >Daily</option>
                            <option value="Weekly">Weekly</option>
                            <option value="Montly">Monthly</option>
                        </select>
                    </div>
                    <input type="submit" name="updateMedicine" value="Update Record" class="form-control btn btn-warning btn-block my-3">
                </form>';
            }

            ?>


        </div>
    </div>
</div>
<?php set_footer()?>