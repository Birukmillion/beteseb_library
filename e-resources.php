<?php
$mysqli = new mysqli("localhost", "root", "", "beteseb_library");

// Upload Logic
$upload_success = "";
$delete_success = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['resourceFile'])) {
        $title = $_POST['resourceTitle'];
        $file = $_FILES['resourceFile'];
        $targetDir = "uploads/";
        $fileName = basename($file["name"]);
        $targetPath = $targetDir . $fileName;

        if (move_uploaded_file($file["tmp_name"], $targetPath)) {
            $stmt = $mysqli->prepare("INSERT INTO resources (title, filename) VALUES (?, ?)");
            $stmt->bind_param("ss", $title, $fileName);
            $stmt->execute();
            $upload_success = "Uploaded successfully!";
        }
    }
}

// Delete Logic
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $mysqli->prepare("SELECT filename FROM resources WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($filename);
    if ($stmt->fetch()) {
        unlink("uploads/" . $filename);
        $stmt->close();
        $delStmt = $mysqli->prepare("DELETE FROM resources WHERE id = ?");
        $delStmt->bind_param("i", $id);
        $delStmt->execute();
        $delete_success = "Deleted successfully.";
    }
}
$resources = $mysqli->query("SELECT * FROM resources ORDER BY uploaded_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>E-Resources</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 2rem; background: #f9fafb; }
        .alert { position: fixed; top: 1rem; left: 1rem; background: #22c55e; color: white; padding: 10px 20px; border-radius: 6px; z-index: 999; }
        .container { max-width: 800px; margin: auto; }
        .upload-box, .resource-list { background: white; padding: 20px; margin-bottom: 2rem; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .upload-box input[type="text"], input[type="file"] { width: 100%; padding: 10px; margin: 0.5rem 0; border: 1px solid #ddd; border-radius: 6px; }
        .upload-box button { padding: 10px 20px; background: #3b82f6; color: white; border: none; border-radius: 6px; cursor: pointer; }
        .resource { display: flex; justify-content: space-between; align-items: center; padding: 10px; background: #f1f5f9; border-radius: 8px; margin-bottom: 10px; }
        .resource .actions button { margin-left: 8px; padding: 6px 10px; border: none; border-radius: 5px; cursor: pointer; }
        .btn-view { background: #2563eb; color: white; }
        .btn-download { background: #16a34a; color: white; }
        .btn-delete { background: #ef4444; color: white; }
        .modal { position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(0,0,0,0.4); display: none; justify-content: center; align-items: center; z-index: 1000; }
        .modal-content { background: white; padding: 2rem; border-radius: 10px; text-align: center; }
        .modal-content button { margin: 0 1rem; padding: 8px 16px; border: none; border-radius: 5px; }
        .btn-yes { background: #22c55e; color: white; }
        .btn-no { background: #d1d5db; }
    </style>
</head>
<body>

<?php if ($upload_success): ?>
    <div class="alert"><?= $upload_success ?></div>
<?php endif; ?>
<?php if ($delete_success): ?>
    <div class="alert"><?= $delete_success ?></div>
<?php endif; ?>


<div class="container">

    <div class="upload-box">
        <h2>Upload New Resource</h2>
        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="resourceTitle" placeholder="Enter Resource Title" required>
            <input type="file" name="resourceFile" required>
            <button type="submit">Upload</button>
        </form>
    </div>

    <div class="resource-list">
        <h2>Available Resources</h2>
        <?php while ($row = $resources->fetch_assoc()): ?>
            <div class="resource">
                <div>
                    <strong><?= htmlspecialchars($row['title']) ?></strong> - <?= $row['filename'] ?>
                </div>
                <div class="actions">
                    <a href="uploads/<?= $row['filename'] ?>" target="_blank">
                        <button class="btn-view">View</button>
                    </a>
                    <a href="uploads/<?= $row['filename'] ?>" download>
                        <button class="btn-download">Download</button>
                    </a>
                    <button class="btn-delete" onclick="confirmDelete(<?= $row['id'] ?>)">Delete</button>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="modal">
    <div class="modal-content">
        <h3>Are you sure you want to delete this file?</h3>
        <button class="btn-yes" id="confirmYes">Yes</button>
        <button class="btn-no" onclick="closeModal()">No</button>
    </div>
</div>

<script>
    let deleteId = null;

    function confirmDelete(id) {
        deleteId = id;
        document.getElementById('deleteModal').style.display = 'flex';
    }

    function closeModal() {
        document.getElementById('deleteModal').style.display = 'none';
    }

    document.getElementById('confirmYes').onclick = function () {
        if (deleteId !== null) {
            window.location.href = 'e-resources.php?delete=' + deleteId;
        }
    };

    // Hide alert after 3s
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(el => el.remove());
    }, 3000);
</script>
</body>
</html>
