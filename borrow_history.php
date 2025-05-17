<?php
require_once 'includes/init.php';

if (!$user->isLoggedIn()) {
    header('Location: login_page.php');
    exit;
}

include ("header.php");

$borrow = new Borrow();
$user_email = $user->getEmail();

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

<div class="container my-5">
    <h1 class="text-center mb-4"><u>Borrow history</u></h1>

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
                <th scope="col">Actions</th>
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
                        if ($data["fine"] == 0)
                            echo ($rem_days < 0) ? (abs($rem_days) + 1) * 2.5 : 0;
                        else
                            echo $data["fine"];
                        ?> Tk
                    </td>

                    <td>
                        <?php echo htmlspecialchars($data["status"]); ?>
                    </td>

                    <td>
                        <?php if ($data["status"] == "Declined"): ?>
                            <a href="actions.php?a=reissue&t=<?php echo $data["id"]; ?>" class="btn btn-success">Re-issue</a>
                        <?php endif; ?>

                        <?php if ($data["status"] == "Declined" || $data["status"] == "Requested"): ?>
                            <a href="actions.php?a=delete&t=<?php echo $data["id"]; ?>&book_id=<?php echo $data["book_id"]; ?>" class="btn btn-outline-danger">Delete request</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

<nav aria-label="Page navigation">
    <ul class="pagination justify-content-center">
        <?php if ($page > 1): ?>
            <li class="page-item">
                <a href="?page=<?php echo $page - 1; ?>" class="page-link">Previous</a>
            </li>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?php echo ($i === $page) ? 'active' : ''; ?>">
                <a href="?page=<?php echo $i; ?>" class="page-link"><?php echo $i; ?></a>
            </li>
        <?php endfor; ?>

        <?php if ($page < $totalPages): ?>
            <li class="page-item">
                <a href="?page=<?php echo $page + 1; ?>" class="page-link">Next</a>
            </li>
        <?php endif; ?>
    </ul>
</nav>
</div>

<?php include ("footer.php"); ?>
