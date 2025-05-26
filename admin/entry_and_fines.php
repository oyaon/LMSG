<?php 
include 'header.php'; 
include 'db-connect.php'; 

// Get selected year from query parameter or default to current year
$selected_year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');

// Fetch distinct years from entry_fee_date and issue_date for filter dropdown
$years = [];
$sql_years = "SELECT DISTINCT YEAR(entry_fee_date) as year FROM users WHERE entry_fee_stat=1
              UNION
              SELECT DISTINCT YEAR(issue_date) as year FROM borrow_history WHERE fine != 0
              ORDER BY year DESC;";
$result_years = $conn->query($sql_years);
while ($row = $result_years->fetch_assoc()) {
    $years[] = $row['year'];
}

?>

<div class="container">
    <h1 class="pt-2">Summary report</h1>

    <!-- Year filter form -->
    <form method="get" class="mb-3">
        <label for="year">Select Year:</label>
        <select name="year" id="year" onchange="this.form.submit()">
            <?php foreach ($years as $year): ?>
                <option value="<?php echo $year; ?>" <?php if ($year == $selected_year) echo 'selected'; ?>>
                    <?php echo $year; ?>
                </option>
            <?php endforeach; ?>
        </select>
        <noscript><input type="submit" value="Filter"></noscript>
    </form>

    <!-- Export CSV button -->
    <a href="export_entry_and_fines.php?year=<?php echo $selected_year; ?>" class="btn btn-primary mb-3">Export CSV</a>

    <div class="row">
        <div class="col">
            <div class="table-responsive">
                <table class="table" id="summaryTable">
                    <thead>
                        <tr>
                            <th onclick="sortTable(0)" style="cursor:pointer"># &#x25B2;&#x25BC;</th>
                            <th onclick="sortTable(1)" style="cursor:pointer">Duration &#x25B2;&#x25BC;</th>
                            <th onclick="sortTable(2)" style="cursor:pointer">Entry fee &#x25B2;&#x25BC;</th>
                            <th onclick="sortTable(3)" style="cursor:pointer">Fine &#x25B2;&#x25BC;</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $entry_n_fines = [];
                            $x = [];

                            // fetching entry fee data filtered by selected year
                            $sql = "SELECT `entry_fee_stat`, `entry_fee_date` FROM `users` WHERE `entry_fee_stat`=1 AND YEAR(`entry_fee_date`) = $selected_year ORDER BY `entry_fee_date` ASC;";
                            $result = $conn->query($sql);
                            while($data = $result->fetch_array()){
                                $date = explode("-", $data["entry_fee_date"]);
                                $time = date("F", mktime(0, 0, 0, (int)$date[1])).", $date[0]";
                                $entry_n_fines[$time] = [
                                    "entry" => 0,
                                    "fine" => 0
                                ];
                                array_push($x, $data);
                            }
                            
                            foreach ($x as $k) {
                                $date = explode("-", $k["entry_fee_date"]);
                                $time = date("F", mktime(0, 0, 0, (int)$date[1])).", $date[0]";
                                $entry_n_fines[$time]["entry"]++;
                            }
                            unset($x);
                            $x = [];

                            // fetching fine data filtered by selected year
                            $sql = "SELECT `issue_date`, `fine` FROM `borrow_history` WHERE NOT `fine`=0 AND YEAR(`issue_date`) = $selected_year ORDER BY `issue_date` ASC;";
                            $result = $conn->query($sql);
                            while($data = $result->fetch_array())
                                array_push($x, $data);

                            foreach ($x as $k) {
                                $date = explode("-", $k["issue_date"]);
                                $time = date("F", mktime(0, 0, 0, (int)$date[1])).", $date[0]";
                                $entry_n_fines[$time]["fine"] += $k["fine"];
                            }
                            ?>

                            <?php $total_entries=0;$total_fine=0;$i=0; ?>
                            <?php foreach($entry_n_fines as $key => $val): ?>
                                <tr>
                                    <td><?php echo ++$i; ?></td>

                                    <td><?php echo $key; ?></td>

                                    <td><?php echo $val["entry"]*100; ?> Tk</td>

                                    <td><?php echo $val["fine"]; ?> Tk</td>

                                    <?php $total_entries+=$val["entry"]*100;$total_fine+=$val["fine"]; ?>
                                </tr>
                            <?php endforeach; ?>
                    </tbody>

                    <tfoot>
                        <tr>
                            <td></td>
                            <td>Total</td>

                            <td><?php echo $total_entries; ?> Tk</td>

                            <td><?php echo $total_fine; ?> Tk</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
// Simple table sorting function
function sortTable(n) {
  var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
  table = document.getElementById("summaryTable");
  switching = true;
  // Set the sorting direction to ascending:
  dir = "asc"; 
  while (switching) {
    switching = false;
    rows = table.rows;
    // Loop through all table rows (except the headers and footers)
    for (i = 1; i < (rows.length - 2); i++) {
      shouldSwitch = false;
      x = rows[i].getElementsByTagName("TD")[n];
      y = rows[i + 1].getElementsByTagName("TD")[n];
      // Check if the two rows should switch place
      if (dir == "asc") {
        if (n == 0 || n == 2 || n == 3) { // numeric columns
          if (parseFloat(x.innerHTML) > parseFloat(y.innerHTML)) {
            shouldSwitch= true;
            break;
          }
        } else { // text columns
          if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
            shouldSwitch= true;
            break;
          }
        }
      } else if (dir == "desc") {
        if (n == 0 || n == 2 || n == 3) { // numeric columns
          if (parseFloat(x.innerHTML) < parseFloat(y.innerHTML)) {
            shouldSwitch= true;
            break;
          }
        } else { // text columns
          if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
            shouldSwitch= true;
            break;
          }
        }
      }
    }
    if (shouldSwitch) {
      // Switch rows and mark that a switch has been done
      rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
      switching = true;
      switchcount ++;      
    } else {
      // If no switching has been done AND the direction is "asc",
      // set the direction to "desc" and run the loop again.
      if (switchcount == 0 && dir == "asc") {
        dir = "desc";
        switching = true;
      }
    }
  }
}
</script>

<?php include 'footer.php'; ?>
