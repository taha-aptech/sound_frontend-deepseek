<!-- contact.php -->
<?php
include 'header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    $interested_in_production = isset($_POST['interested_in_production']) ? 1 : 0;
    $updates_subscription = isset($_POST['updates_subscription']) ? 1 : 0;
    
    // Validation
    $errors = [];
    
    if (empty($name) || empty($email) || empty($message)) {
        $errors[] = "Name, email, and message are required fields";
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address";
    }
    
    // If no errors, insert into database
    if (empty($errors)) {
        $insert_query = "INSERT INTO contact (name, email, subject, message, interested_in_production, updates_subscription) 
                        VALUES ('$name', '$email', '$subject', '$message', $interested_in_production, $updates_subscription)";
        
        if (mysqli_query($conn, $insert_query)) {
            $success = "Thank you for your message! We'll get back to you soon.";
            
            // Clear form fields
            $name = $email = $subject = $message = '';
            $interested_in_production = $updates_subscription = 0;
        } else {
            $errors[] = "Sorry, there was an error sending your message. Please try again.";
        }
    }
}
?>

<div class="container py-5">
    <div class="row">
        <div class="col-12 text-center mb-5" data-aos="fade-up">
            <h1 class="display-5 fw-bold">Contact Us</h1>
            <p class="lead">We'd love to hear from you. Send us a message and we'll respond as soon as possible.</p>
        </div>
    </div>

    <div class="row">
        <!-- Contact Form -->
        <div class="col-lg-8 mb-5" data-aos="fade-right">
            <div class="card shadow">
                <div class="card-body p-5">
                    <h3 class="card-title mb-4">Send us a Message</h3>
                    
                    <?php if(isset($success)): ?>
                        <div class="alert alert-success"><?php echo $success; ?></div>
                    <?php endif; ?>
                    
                    <?php if(!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach($errors as $error): ?>
                                    <li><?php echo $error; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="contact.php">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Full Name *</label>
                                <input type="text" class="form-control" id="name" name="name" 
                                       value="<?php echo isset($_POST['name']) ? $_POST['name'] : ''; ?>" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email Address *</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="subject" class="form-label">Subject</label>
                            <input type="text" class="form-control" id="subject" name="subject" 
                                   value="<?php echo isset($_POST['subject']) ? $_POST['subject'] : ''; ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label for="message" class="form-label">Message *</label>
                            <textarea class="form-control" id="message" name="message" rows="5" required><?php echo isset($_POST['message']) ? $_POST['message'] : ''; ?></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="interested_in_production" name="interested_in_production" 
                                       <?php echo (isset($_POST['interested_in_production']) && $_POST['interested_in_production']) ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="interested_in_production">
                                    I'm interested in music/video production services
                                </label>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="updates_subscription" name="updates_subscription"
                                       <?php echo (isset($_POST['updates_subscription']) && $_POST['updates_subscription']) ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="updates_subscription">
                                    Subscribe to updates and newsletters
                                </label>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-lg">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Contact Information -->
        <div class="col-lg-4" data-aos="fade-left">
            <div class="card shadow">
                <div class="card-body p-4">
                    <h3 class="card-title mb-4">Get in Touch</h3>
                    
                    <div class="mb-4">
                        <div class="d-flex align-items-start mb-3">
                            <div class="flex-shrink-0">
                                <i class="fas fa-map-marker-alt text-primary fa-lg me-3 mt-1"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h5>Address</h5>
                                <p class="mb-0">123 Music Street<br>Entertainment District<br>City, State 12345</p>
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-start mb-3">
                            <div class="flex-shrink-0">
                                <i class="fas fa-phone text-primary fa-lg me-3 mt-1"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h5>Phone</h5>
                                <p class="mb-0">+1 (234) 567-8900</p>
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-start mb-3">
                            <div class="flex-shrink-0">
                                <i class="fas fa-envelope text-primary fa-lg me-3 mt-1"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h5>Email</h5>
                                <p class="mb-0">info@soundentertainment.com</p>
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-start">
                            <div class="flex-shrink-0">
                                <i class="fas fa-clock text-primary fa-lg me-3 mt-1"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h5>Business Hours</h5>
                                <p class="mb-0">Monday - Friday: 9:00 AM - 6:00 PM<br>Saturday: 10:00 AM - 4:00 PM<br>Sunday: Closed</p>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <h5 class="mb-3">Follow Us</h5>
                    <div class="social-links">
                        <a href="#" class="btn btn-outline-primary btn-sm me-2 mb-2">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="btn btn-outline-primary btn-sm me-2 mb-2">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="btn btn-outline-primary btn-sm me-2 mb-2">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="btn btn-outline-primary btn-sm me-2 mb-2">
                            <i class="fab fa-youtube"></i>
                        </a>
                        <a href="#" class="btn btn-outline-primary btn-sm mb-2">
                            <i class="fab fa-spotify"></i>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- FAQ Section -->
            <div class="card shadow mt-4">
                <div class="card-body p-4">
                    <h5 class="card-title mb-3">Quick Help</h5>
                    <div class="accordion" id="faqAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                    How do I create an account?
                                </button>
                            </h2>
                            <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Click on the "Register" button in the top navigation and fill out the registration form with your details.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                    Can I upload my own music?
                                </button>
                            </h2>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Currently, only approved artists and labels can upload content. Contact us if you're interested in featuring your music.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>