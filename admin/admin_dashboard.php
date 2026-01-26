<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') { 
    header("Location: ../login.php"); 
    exit; 
}

require_once '../controller/ComplaintController.php';
require_once '../controller/ProductController.php';
require_once '../controller/ComplaintTypeController.php';
require_once '../controller/UserController.php';
require_once '../model/User.php';

// CONTROLLERS
$complaintController = new ComplaintController();
$userController      = new UserController();
$userModel           = new User();

// FETCH DATA
$customers           = $userModel->getCustomers();
$staff               = $userModel->getStaff();
$assignedComplaints  = $complaintController->getAssignedComplaints();
$unassignedComplaints= $complaintController->getUnassignedComplaints();
$techWorkload        = $complaintController->getTechnicianWorkload();

include '../header.php';
?>

<h2>Welcome, Admin!</h2>

<!-- ========================= -->
<!-- 1. CUSTOMERS TABLE        -->
<!-- ========================= -->
<h3>Customers</h3>
<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>User ID</th>
        <th>First Name</th> 
        <th>Last Name</th> 
        <th>Email</th> 
        <th>Phone</th> 
        <th>Street</th> 
        <th>City</th> 
        <th>State</th> 
        <th>Zip</th>
        <th>Actions</th>
    </tr>

    <?php while ($row = $customers->fetch_assoc()): ?>
        <tr>
            <td><?= $row['user_id'] ?></td>
            <td><?= htmlspecialchars($row['first_name']) ?></td> 
            <td><?= htmlspecialchars($row['last_name']) ?></td> 
            <td><?= htmlspecialchars($row['email']) ?></td> 
            <td><?= htmlspecialchars($row['phone']) ?></td> 
            <td><?= htmlspecialchars($row['street']) ?></td> 
            <td><?= htmlspecialchars($row['city']) ?></td> 
            <td><?= htmlspecialchars($row['state']) ?></td> 
            <td><?= htmlspecialchars($row['zip']) ?></td>
            <td>
                <a href="edit_customer.php?id=<?= $row['user_id'] ?>">Edit</a>
            </td>
        </tr>
    <?php endwhile; ?>
</table>

<br><br>

<!-- ========================= -->
<!-- 2. STAFF TABLE            -->
<!-- ========================= -->
<h3>Technicians & Administrators</h3>

<a href="add_employee.php">➕ Add New Employee</a>
<br><br>

<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>User ID</th>
        <th>First Name</th> 
        <th>Last Name</th> 
        <th>Email</th> 
        <th>Phone</th> 
        <th>Role</th> 
        <th>Actions</th>
    </tr>

    <?php while ($row = $staff->fetch_assoc()): ?>
        <tr>
            <td><?= $row['user_id'] ?></td>
            <td><?= htmlspecialchars($row['first_name']) ?></td> 
            <td><?= htmlspecialchars($row['last_name']) ?></td> 
            <td><?= htmlspecialchars($row['email']) ?></td> 
            <td><?= htmlspecialchars($row['phone']) ?></td> 
            <td><?= $row['role'] ?></td>
            <td>
                <a href="edit_employee.php?id=<?= $row['user_id'] ?>">Edit</a>
            </td>
        </tr>
    <?php endwhile; ?>
</table>

<br><br>

<!-- ========================= -->
<!-- 3. OPEN COMPLAINTS (ASSIGNED) -->
<!-- ========================= -->
<h3>Open Complaints (Assigned)</h3>

<?php if ($assignedComplaints->num_rows === 0): ?>
    <p>No assigned open complaints.</p>
<?php else: ?>
<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>ID</th>
        <th>Customer</th>
        <th>Technician</th>
        <th>Description</th>
        <th>Image</th>
        <th>Status</th>
    </tr>

    <?php while ($c = $assignedComplaints->fetch_assoc()): ?>
        <tr>
            <td><?= $c['complaint_id'] ?></td>
            <td><?= $c['user_id'] ?></td>

            <td>
            <?php if ($c['technician_id']): ?>
                <?= htmlspecialchars($c['tech_first_name'] . ' ' . $c['tech_last_name']) ?>
            <?php else: ?>
                    Unassigned
            <?php endif; ?>
            </td>

            <td><?= $c['description'] ?></td>

            <td>
                <?php if (!empty($c['image_path'])): ?> 
                    <img src="../serve_image.php?file=<?= urlencode($c['image_path']) ?>" 
                        width="80" style="border:1px solid #ccc; padding:3px;"> 
                <?php else: ?> 
                    No image 
                <?php endif; ?> 
            </td>

            <td><?= $c['status'] ?></td>
        </tr>
    <?php endwhile; ?>
</table>
<?php endif; ?>

<br><br>

<!-- ========================= -->
<!-- 4. OPEN COMPLAINTS (UNASSIGNED) -->
<!-- ========================= -->
<h3>Open Complaints (Unassigned)</h3>

<?php if ($unassignedComplaints->num_rows === 0): ?>
    <p>No unassigned open complaints.</p>
<?php else: ?>
<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>ID</th>
        <th>Customer</th>
        <th>Description</th>
        <th>Image</th>
        <th>Status</th>
        <th>Assign</th>
    </tr>

    <?php while ($c = $unassignedComplaints->fetch_assoc()): ?> 
        <tr> 
            <td><?= $c['complaint_id'] ?></td> 
            <td><?= $c['user_id'] ?></td> 
            <td><?= $c['description'] ?></td> 

            <td> 
                <?php if (!empty($c['image_path'])): ?> 
                    <img src="../serve_image.php?file=<?= urlencode($c['image_path']) ?>" 
                        width="80" style="border:1px solid #ccc; padding:3px;"> 
                <?php else: ?>
                    No image 
                <?php endif; ?> 
            </td>

            <td><?= $c['status'] ?></td> 
            <td> 
                <a href="assign_tech.php?id=<?= $c['complaint_id'] ?>">Assign</a> 
            </td> 
        </tr> 
        <?php endwhile; ?> 
    </table> 
<?php endif; ?> 

<br><br> 

<!-- ========================= --> 
<!-- 5. TECHNICIAN WORKLOAD --> 
<!-- ========================= --> 
<h3>Technician Workload</h3> 

<table border="1" cellpadding="8" cellspacing="0"> 
    <tr> 
        <th>Technician</th> 
        <th>Open Complaints Assigned</th> 
    </tr> 
    
    <?php while ($t = $techWorkload->fetch_assoc()): ?> 
        <tr> 
            <td><?= $t['first_name'] . ' ' . $t['last_name'] ?></td> 
            <td><?= $t['open_count'] ?></td> 
        </tr> 
    <?php endwhile; ?> 
</table> 

<br><br> 

<?php include '../footer.php'; ?>