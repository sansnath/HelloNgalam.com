<?php
include "data.php";

if (isset($_GET['id']) && isset($_GET['status'])) {
    $id = intval($_GET['id']);
    $status = intval($_GET['status']);

    if ($status < 1 || $status > 3) {
        http_response_code(400);
        echo "Invalid status value.";
        exit;
    }

    $query = "UPDATE laporan_masalah SET status_laporan = ? WHERE ID_Laporan = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("ii", $status, $id);

    if ($stmt->execute()) {
        echo "Success";
    } else {
        error_log("Error: " . $stmt->error);
        http_response_code(500);
        echo "Failed to update status.";
    }
    exit;
}

$query = "SELECT ID_Laporan, Jenis_Masalah, status_laporan FROM laporan_masalah";
$result = $db->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrator - Ubah Status Laporan</title>
    <link rel="stylesheet" href="css/styles14.css">
    <script>
        function updateStatus(id, increment) {
            const statusElem = document.getElementById('status-' + id);
            let currentStatus = parseInt(statusElem.dataset.status);

            let newStatus = currentStatus + increment;
            if (newStatus < 1) newStatus = 1;
            if (newStatus > 3) newStatus = 3;

            const xhr = new XMLHttpRequest();
            xhr.open('GET', window.location.href + '?id=' + id + '&status=' + newStatus, true);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    statusElem.dataset.status = newStatus;
                    statusElem.innerText = getStatusText(newStatus);
                } else {
                    alert('Gagal memperbarui status.');
                }
            };
            xhr.send();
        }

        function getStatusText(status) {
            switch (status) {
                case 1: return "Not Yet";
                case 2: return "Proceed";
                case 3: return "Done";
                default: return "Unknown";
            }
        }
    </script>
</head>
<body>
    <h1>Halaman Administrator - Ubah Status Laporan</h1>
    <table border="1">
        <thead>
            <tr>
                <th>ID Laporan</th>
                <th>Jenis Masalah</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['ID_Laporan']; ?></td>
                    <td><?php echo htmlspecialchars($row['Jenis_Masalah']); ?></td>
                    <td id="status-<?php echo $row['ID_Laporan']; ?>" data-status="<?php echo $row['status_laporan']; ?>">
                        <?php
                            switch ($row['status_laporan']) {
                                case 1: echo "Not Yet"; break;
                                case 2: echo "Proceed"; break;
                                case 3: echo "Done"; break;
                                default: echo "Unknown";
                            }
                        ?>
                    </td>
                    <td>
                        <button onclick="updateStatus(<?php echo $row['ID_Laporan']; ?>, -1)">-</button>
                        <button onclick="updateStatus(<?php echo $row['ID_Laporan']; ?>, 1)">+</button>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>