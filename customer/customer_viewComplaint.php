<?php
session_start();
require_once '../db_connect.php';
require_once '../core/auth_middleware.php'; 
require_role(['customer']);

// Redirect if not logged in or not a customer
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: ../login.php");
    exit;
}

$user_id = (int) $_SESSION['user_id'];

// Get all complaints for this customer
$sql = "
    SELECT c.complaint_id, c.description, c.status, c.image_path, 
           c.created_at, p.name AS product_name, 
           t.type_name AS complaint_type
    FROM complaints c
    JOIN products p ON c.product_id = p.product_id
    JOIN complaint_types t ON c.complaint_type_id = t.complaint_type_id
    WHERE c.user_id = $user_id
    ORDER BY c.created_at DESC
";

$complaints = mysqli_query($conn, $sql);

// Get technician notes for each complaint
function getNotes($conn, $complaint_id) {
    $notes_sql = "
        SELECT n.note_text, n.created_at, u.first_name, u.last_name
        FROM technician_notes n
        JOIN users u ON n.technician_id = u.user_id
        WHERE n.complaint_id = $complaint_id
        ORDER BY n.created_at ASC
    ";
    return mysqli_query($conn, $notes_sql);
}
?>

<?php include '../header.php'; ?>

<h2>Your Complaints</h2>

<?php if (mysqli_num_rows($complaints) === 0): ?>
    <p>You have not submitted any complaints yet.</p>
<?php else: ?>

<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>Product/Service</th>
        <th>Type</th>
        <th>Description</th>
        <th>Image</th>
        <th>Status</th>
        <th>Technician Notes</th>
        <th>Submitted</th>
    </tr>

    <?php while ($row = mysqli_fetch_assoc($complaints)): ?>
        <tr>
            <td><?= htmlspecialchars($row['product_name']) ?></td>
            <td><?= htmlspecialchars($row['complaint_type']) ?></td>
            <td><?= nl2br(htmlspecialchars($row['description'])) ?></td>

            <td>
                <?php if ($row['image_path']): ?>
                    <img src="../<?= $row['image_path'] ?>" width="120">
                <?php else: ?>
                    No image
                <?php endif; ?>
            </td>

            <td style="color: <?= $row['status'] === 'open' ? 'red' : 'green' ?>">
                <?= $row['status'] === 'open' ? 'Open' : 'Closed' ?>
            </td>


            <td>
                <?php
                $notes = getNotes($conn, $row['complaint_id']);
                if (mysqli_num_rows($notes) === 0) {
                    echo "<span style='color:#777;'>No notes yet</span>";
                } else {
                    while ($n = mysqli_fetch_assoc($notes)) {
                        echo "<strong>" . htmlspecialchars($n['first_name']) . " " . htmlspecialchars($n['last_name']) . ":</strong><br>";
                        echo nl2br(htmlspecialchars($n['note_text'])) . "<br>";
                        echo "<em>(" . $n['created_at'] . ")</em><br><br>";
                    }
                }
                ?>
            </td>

            <td><?= $row['created_at'] ?></td>
        </tr>
    <?php endwhile; ?>

</table>

<?php endif; ?>

<?php include '../footer.php'; ?>
