<?php
/**
 * INDEX / HOME PAGE
 * Public landing page
 */

require_once(__DIR__ . '/includes/init.php');

// If user is logged in, redirect to dashboard
if (isLoggedIn()) {
    header('Location: ' . BASE_URL . 'dashboard.php');
    exit();
}

?>
<?php require_once('includes/header.php'); ?>

<div class="home-container">
    <div class="home-hero">
        <h1>♻ Waste Management System</h1>
        <p>Efficient waste collection and management for a cleaner community</p>
        <div class="hero-actions">
            <a href="<?php echo BASE_URL; ?>login.php" class="btn btn-primary btn-large">Get Started</a>
            <a href="<?php echo BASE_URL; ?>contact.php" class="btn btn-secondary btn-large">Contact Us</a>
        </div>
    </div>
    
    <div class="features-grid">
        <div class="feature-card">
            <h3>📍 Bin Tracking</h3>
            <p>Track waste bins across multiple locations with real-time fill level monitoring.</p>
        </div>
        
        <div class="feature-card">
            <h3>🚚 Pickup Scheduling</h3>
            <p>Request and schedule waste pickups with our efficient management system.</p>
        </div>
        
        <div class="feature-card">
            <h3>⚠ Complaint Management</h3>
            <p>File and track complaints about waste management services.</p>
        </div>
        
        <div class="feature-card">
            <h3>👷 Staff Assignment</h3>
            <p>Efficiently assign routes and tasks to collection staff.</p>
        </div>
        
        <div class="feature-card">
            <h3>💰 Billing System</h3>
            <p>Manage service fees and payment history.</p>
        </div>
        
        <div class="feature-card">
            <h3>📊 Reports</h3>
            <p>Generate detailed reports on pickups, complaints, and payments.</p>
        </div>
    </div>
</div>

<?php require_once('includes/footer.php'); ?>
