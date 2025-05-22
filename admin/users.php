<?php include 'header.php'; ?>
<?php require_once '../includes/init.php'; // Use init.php for consistency

// Ensure user is admin
if (!$user->isLoggedIn() || !$user->isAdmin()) {
    Helper::redirect('../login.php'); // Redirect to login if not admin
    exit;
}
?>

<div class="container">
    <h1 class="pt-2">All users</h1>

    <div class="row">
        <div class="col-12 mt-5 mb-3">
            <form method="get" action="" class="d-flex">
                <input type="text" name="s" required class="form-control" placeholder="Search user by email or name">

                <button type="submit" class="btn btn-success">Search</button>
            </form>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12">
            <?php if(isset($_GET['s'])): ?>
                <div class="my-3">
                    <h3>Search result for: "<?php echo $_GET['s']; ?>"</h3>
                    <a href="users.php" class="btn btn-info">Clear filter</a>
                </div>
            <?php endif; ?>

            <div class="d-flex flex-column wrap">
                <?php
                $conn = $db->getConnection(); // Get connection from Database class
                $sql = "SELECT * FROM `users` WHERE `user_type` IN (0,1) ";
                $params = [];
                $types = "";
                if(isset($_GET['s'])){
                    $searchTerm = "%" . $conn->real_escape_string($_GET['s']) . "%"; // Basic sanitization, but prepared statement is better
                    $sql .= "AND (`first_name` LIKE ? OR `last_name` LIKE ? OR `email` LIKE ? OR `user_name` LIKE ?) ";
                    $params = [$searchTerm, $searchTerm, $searchTerm, $searchTerm];
                    $types = "ssss";
                }
                $sql .=";";

                $stmt = $db->query($sql, $types, $params);
                $result = $stmt->get_result();
                $i = 0;                
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_array()){
                        ?>
                        <div class="container h-100">
                            <div class="row d-flex justify-content-center align-items-center h-100">
                                <div class="col col-lg-6 mb-4 mb-lg-0">
                                    <div class="card mb-3" style="border-radius: .5rem;">
                                        <div class="row g-0">
                                            <div class="col-md-4 gradient-custom text-center text-white"
                                            style="border-top-left-radius: .5rem; border-bottom-left-radius: .5rem;">
                                                <img src="../images/Avatar.png"
                                                alt="Avatar" class="img-fluid my-5" style="width: 80px;" />

                                                <h5><?php echo $row['first_name']." ".$row['last_name']; ?></h5>

                                                <i class="far fa-edit mb-5"></i>
                                        </div>

                                        <div class="col-md-8">
                                            <div class="card-body p-4">
                                                <h6>Information</h6>

                                                <hr class="mt-0 mb-4">

                                                <div class="row pt-1">
                                                    <div class="col-6 mb-3">
                                                        <h6>Email</h6>

                                                        <a href="mailto:<?php echo $row["email"]; ?>"><?php echo $row["email"]; ?></a>
                                                    </div>

<div class="col-6 mb-3">
    <h6>Entry fee</h6>

    <?php if (isset($row["entry_fee_stat"]) && $row["entry_fee_stat"]): ?>
        <span class="badge bg-success">Paid</span>
    <?php else: ?>
        <span class="badge bg-danger">Not Paid</span>
    <?php endif; ?>
</div>
                                                </div>
                                            </div>

                                            <div class="d-flex p-4">
                                                <?php if(!isset($row["entry_fee_stat"]) || !$row["entry_fee_stat"]): ?>
                                                    <form method="POST" action="actions.php?a=entry_paid&t=<?php echo urlencode($row["email"]); ?>" style="display:inline;">
                                                        <?php echo Helper::csrfTokenField('entry_paid_form_' . $row['id']); ?>
                                                        <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Mark entry fee as paid for this user?');">Entry paid</button>
                                                    </form>
                                                <?php endif; ?>

                                                <a class="btn btn-primary" href="user_issue_history.php?u=<?php echo $row["email"]; ?>">View book list</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo "<tr><td colspan='5'>No users found</td></tr>";
            }
            $stmt->close();
            ?>
        </div>
    </div>
</div>
</div>

<?php include 'footer.php'; ?>