<?php
require_once 'includes/init.php';

if(isset($_GET["a"])){
    extract($_GET);

    if($a == "reissue"){
        $query = $db->query("UPDATE `borrow_history` SET `status`='Requested' WHERE `id`=?", "i", [$t]);
        if($query){ ?>
            <script type="text/javascript">
                alert("Re-issued successfully");
                window.history.go(-1);
            </script>
        <?php }
    }
    else if($a == "delete"){
        $query = $db->query("DELETE FROM `borrow_history` WHERE `id`=?", "i", [$t]);
        if($query){ 
            $updateQuery = $db->query("UPDATE `all_books` SET `quantity`=`quantity`+1 WHERE `id`=?", "i", [$book_id]);
            if($updateQuery){ ?>
                <script type="text/javascript">
                    alert("Request deleted successfully");
                    window.history.go(-1);
                </script>
            <?php }
        }
    }
}
else{
    echo "Bad request. Error: 101";
    exit();
}
?>
