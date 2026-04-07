<?php
/**
 * DASHBOARD PAGE
 * Shows summary statistics and recent activities
 */

require_once(__DIR__ . '/includes/init.php');

// Require login
requireLogin();

$page_title = 'Dashboard';

// Get dashboard statistics
$stats = [];

// Get total users
$result = $conn->query("SELECT COUNT(*) as count FROM users");
$stats['total_users'] = $result->fetch_assoc()['count'];

// Get total bins
$result = $conn->query("SELECT COUNT(*) as count FROM waste_bins");
$stats['total_bins'] = $result->fetch_assoc()['count'];

// Get total pickups
$result = $conn->query("SELECT COUNT(*) as count FROM pickup_requests");
$stats['total_pickups'] = $result->fetch_assoc()['count'];

// Get total complaints
$result = $conn->query("SELECT COUNT(*) as count FROM complaints");
$stats['total_complaints'] = $result->fetch_assoc()['count'];

// Get pending payments
$result = $conn->query("SELECT SUM(amount) as total FROM payments WHERE payment_status = 'unpaid'");
$row = $result->fetch_assoc();
$stats['pending_payments'] = $row['total'] ?? 0;

// Get staff count
$result = $conn->query("SELECT COUNT(*) as count FROM users WHERE role = 'staff'");
$stats['total_staff'] = $result->fetch_assoc()['count'];

// Get recent pickups
$pickups_sql = "SELECT pr.pickup_id, pr.pickup_date, pr.status, pr.urgency, u.first_name, u.last_name, wb.location_name 
                FROM pickup_requests pr 
                JOIN users u ON pr.request_by_user_id = u.user_id 
                LEFT JOIN waste_bins wb ON pr.bin_id = wb.bin_id 
                ORDER BY pr.created_at DESC LIMIT 5";
$recent_pickups = $conn->query($pickups_sql);

// Get recent complaints
$complaints_sql = "SELECT c.complaint_id, c.subject, c.status, c.priority, u.first_name, u.last_name 
                   FROM complaints c 
                   JOIN users u ON c.filed_by_user_id = u.user_id 
                   ORDER BY c.created_at DESC LIMIT 5";
$recent_complaints = $conn->query($complaints_sql);

?>
<?php require_once('includes/header.php'); ?>
<?php require_once('includes/sidebar.php'); ?>

<div class="page-header">
    <h2>Dashboard</h2>
    <p>Overview of your waste management operations</p>
</div>

<?php displayMessages(); ?>

<!-- Statistics Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon">👥</div>
        <h3>Total Users</h3>
        <p class="stat-number"><?php echo $stats['total_users']; ?></p>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">🗑</div>
        <h3>Waste Bins</h3>
        <p class="stat-number"><?php echo $stats['total_bins']; ?></p>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">🚚</div>
        <h3>Total Pickups</h3>
        <p class="stat-number"><?php echo $stats['total_pickups']; ?></p>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">⚠</div>
        <h3>Complaints</h3>
        <p class="stat-number"><?php echo $stats['total_complaints']; ?></p>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">💰</div>
        <h3>Pending Payments</h3>
        <p class="stat-number">$<?php echo number_format($stats['pending_payments'], 2); ?></p>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">👷</div>
        <h3>Staff Members</h3>
        <p class="stat-number"><?php echo $stats['total_staff']; ?></p>
    </div>
</div>

<!-- Recent Activities -->
<div class="dashboard-grid">
    <!-- Recent Pickups -->
    <div class="dashboard-panel">
        <h3>Recent Pickup Requests</h3>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Requested By</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th>Urgency</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($pickup = $recent_pickups->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo escape(date('M d, Y', strtotime($pickup['pickup_date']))); ?></td>
                        <td><?php echo escape($pickup['first_name'] . ' ' . $pickup['last_name']); ?></td>
                        <td><?php echo escape($pickup['location_name'] ?? 'N/A'); ?></td>
                        <td>
                            <span class="badge badge-<?php echo $pickup['status']; ?>">
                                <?php echo ucfirst(str_replace('_', ' ', $pickup['status'])); ?>
                            </span>
                        </td>
                        <td>
                            <span class="badge badge-<?php echo $pickup['urgency']; ?>">
                                <?php echo ucfirst($pickup['urgency']); ?>
                            </span>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Recent Complaints -->
    <div class="dashboard-panel">
        <h3>Recent Complaints</h3>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Filed By</th>
                        <th>Subject</th>
                        <th>Status</th>
                        <th>Priority</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($complaint = $recent_complaints->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo escape($complaint['first_name'] . ' ' . $complaint['last_name']); ?></td>
                        <td><?php echo escape(substr($complaint['subject'], 0, 40)); ?></td>
                        <td>
                            <span class="badge badge-<?php echo $complaint['status']; ?>">
                                <?php echo ucfirst(str_replace('_', ' ', $complaint['status'])); ?>
                            </span>
                        </td>
                        <td>
                            <span class="badge badge-<?php echo $complaint['priority']; ?>">
                                <?php echo ucfirst($complaint['priority']); ?>
                            </span>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

        </main>
    </div>
<?php require_once('includes/footer.php'); ?>
