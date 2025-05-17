<?php
require_once __DIR__ . '/../includes/init.php';
include (__DIR__ . "/header.php");
include (__DIR__ . "/top-navbar.php");

if (!$user->isLoggedIn() || !$user->isAdmin()) {
    header('Location: login_page.php');
    exit;
}

$borrow = new Borrow();
$user_email = $_GET['u'] ?? '';

if (empty($user_email)) {
    echo '<p>User email not specified.</p>';
    exit;
}

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 10;

$borrowHistory = $borrow->getUserBorrowHistory($user_email);
$total = count($borrowHistory);
$totalPages = ceil($total / $perPage);

// Paginate results
$borrowHistoryPage = array_slice($borrowHistory, ($page - 1) * $perPage, $perPage);

?>

<style type="text/css">
    .table {
        width: 700px;
        margin: 2.5rem auto;
    }

    td, th {
        text-align: center;
    }
</style>

<div class="container my-5" id="tableContent">
    <h1 class="text-center mb-4"><u>Borrow history for "<?php echo htmlspecialchars($user_email); ?>"</u></h1>

    <table class="table table-hover table-stripped">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Book name</th>
                <th scope="col">Issued date</th>
                <th scope="col">Return date</th>
                <th scope="col">Remaining days</th>
                <th scope="col">Fine</th>
                <th scope="col">Status</th>
                <th class="printRmv" scope="col">Actions</th>
            </tr>
        </thead>

        <tbody>
            <?php
            $i = 0;
            foreach ($borrowHistoryPage as $data):
                $rem_days = 0;
                if (!empty($data["issue_date"])) {
                    $date = strtotime($data["issue_date"]);
                    $date = strtotime("+7days", $date);
                    $rem_days = $date - strtotime(date("Y-m-d"));
                    $rem_days = round($rem_days / 86400);
                }
            ?>
                <tr>
                    <th scope="row"><?php echo ++$i; ?></th>

                    <td><?php echo htmlspecialchars($data["book_name"]); ?></td>

                    <td><?php echo htmlspecialchars($data["issue_date"]); ?></td>

                    <td>
                        <?php
                        if (!empty($data["issue_date"])) {
                            echo date("Y-m-d", $date);
                        }
                        ?>
                    </td>

                    <td>
                        <?php
                        echo ($rem_days > 0) ? $rem_days : 0;
                        echo " days";
                        ?>
                    </td>

                    <td>
                        <?php
                        if ($data["fine"] == 0) {
                            $fine = ($rem_days < 0) ? (abs($rem_days) + 1) * 2.5 : 0;
                        } else {
                            $fine = $data["fine"];
                        }
                        echo $fine;
                        ?> Tk
                    </td>

                    <td>
                        <?php echo htmlspecialchars($data["status"]); ?>
                    </td>

                    <td class="printRmv">
                        <?php if ($data["status"] == "Requested"): ?>
                            <a href="admin_actions.php?a=issue&t=<?php echo $data["id"]; ?>" class="btn btn-success">Issue</a>

                            <a href="admin_actions.php?a=decline&t=<?php echo $data["id"]; ?>" class="btn btn-danger">Decline</a>

                        <?php elseif ($data["status"] == "Issued"): ?>
                            <a href="admin_actions.php?a=return&t=<?php echo $data["id"]; ?>&b_id=<?php echo $data["book_id"]; ?>" class="btn btn-primary">Book returned</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <button id="printBtn" class="btn btn-success d-block mx-auto" onclick="downloadPage()">Download</button>
    <script type="text/javascript">
        function downloadPage() {
            document.getElementById("printBtn").remove();

            document.querySelectorAll(".printRmv").forEach(x => x.remove());

            const tableContent = document.getElementById("tableContent").innerHTML;
            document.body.innerHTML = tableContent;
            window.print();
        }
    </script>
</div>

<?php include ("footer.php"); ?>
