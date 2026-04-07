<?php
/**
 * CONTACT / FEEDBACK PAGE
 * Public contact form
 */

require_once(__DIR__ . '/includes/init.php');

$page_title = 'Contact Us';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $phone = sanitize($_POST['phone'] ?? '');
    $subject = sanitize($_POST['subject'] ?? '');
    $message = sanitize($_POST['message'] ?? '');
    
    // Validate input
    $errors = [];
    if (empty($name)) $errors[] = 'Name is required.';
    if (empty($email)) $errors[] = 'Email is required.';
    if (!isValidEmail($email)) $errors[] = 'Invalid email format.';
    if (empty($subject)) $errors[] = 'Subject is required.';
    if (empty($message)) $errors[] = 'Message is required.';
    
    // If no errors, store inquiry (in real system, send email)
    if (empty($errors)) {
        // For now, just display success message
        // In production, you would send email or store in database
        addMessage('Thank you for your inquiry. We will contact you shortly.', 'success');
    } else {
        foreach ($errors as $error) {
            addMessage($error, 'danger');
        }
    }
}

?>
<?php require_once('includes/header.php'); ?>
<?php require_once('includes/sidebar.php'); ?>

<div class="page-header">
    <h2>Contact Us</h2>
    <p>Have a question or suggestion? We'd love to hear from you.</p>
</div>

<?php displayMessages(); ?>

<div class="contact-container">
    <div class="contact-info">
        <h3>Get In Touch</h3>
        <div class="info-item">
            <strong>Email:</strong><br>
            <a href="mailto:support@waste.local">support@waste.local</a>
        </div>
        <div class="info-item">
            <strong>Phone:</strong><br>
            +1 (555) 123-4567
        </div>
        <div class="info-item">
            <strong>Address:</strong><br>
            123 Waste Management Street<br>
            Environmental City, EC 12345
        </div>
        <div class="info-item">
            <strong>Business Hours:</strong><br>
            Monday - Friday: 9:00 AM - 5:00 PM<br>
            Saturday: 10:00 AM - 2:00 PM<br>
            Sunday: Closed
        </div>
    </div>
    
    <div class="contact-form-container">
        <h3>Send us a Message</h3>
        <form method="POST" class="form">
            <div class="form-row">
                <div class="form-group">
                    <label for="name">Name *</label>
                    <input type="text" id="name" name="name" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="phone">Phone (Optional)</label>
                <input type="tel" id="phone" name="phone">
            </div>
            
            <div class="form-group">
                <label for="subject">Subject *</label>
                <input type="text" id="subject" name="subject" placeholder="What is this about?" required>
            </div>
            
            <div class="form-group">
                <label for="message">Message *</label>
                <textarea id="message" name="message" rows="6" placeholder="Your message here..." required></textarea>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Send Message</button>
            </div>
        </form>
    </div>
</div>

        </main>
    </div>
<?php require_once('includes/footer.php'); ?>
