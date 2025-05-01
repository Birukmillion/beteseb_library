<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['id']);

    // Get file name
    $get = $conn->prepare("SELECT filename FROM resources WHERE id=?");
    $get->bind_param("i", $id);
    $get->execute();
    $result = $get->get_result();
    $row = $result->fetch_assoc();
    if ($row) {
        unlink($row['filename']);
    }

    // Delete DB row
    $stmt = $conn->prepare("DELETE FROM resources WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    echo "success";
}
?>
