<?php
// Email configuration
$admin_email = "ybinod857@gmail.com"; // Change this to your email
$website_name = "Binod Ray - Portfolio";

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data and sanitize it
    $name = htmlspecialchars(trim($_POST['name'] ?? ''));
    $email = htmlspecialchars(trim($_POST['email'] ?? ''));
    $phone = htmlspecialchars(trim($_POST['number'] ?? ''));
    $subject = htmlspecialchars(trim($_POST['subject'] ?? ''));
    $message = htmlspecialchars(trim($_POST['message'] ?? ''));

    // Validate form data
    $errors = [];
    
    if (empty($name)) {
        $errors[] = "Name is required";
    }
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid email is required";
    }
    
    if (empty($phone)) {
        $errors[] = "Phone number is required";
    }
    
    if (empty($subject)) {
        $errors[] = "Subject is required";
    }
    
    if (empty($message)) {
        $errors[] = "Message is required";
    }

    // If no errors, send email
    if (empty($errors)) {
        // Email headers
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8" . "\r\n";
        $headers .= "From: " . $email . "\r\n";
        $headers .= "Reply-To: " . $email . "\r\n";

        // Email body
        $email_subject = "New Contact Form Submission: " . $subject;
        
        $email_body = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; background-color: #f5f5f5; }
                .header { background-color: #0a0e27; color: #ffa500; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }
                .content { background-color: white; padding: 20px; }
                .field { margin-bottom: 15px; }
                .label { font-weight: bold; color: #0a0e27; }
                .value { color: #333; margin-top: 5px; }
                .footer { background-color: #f5f5f5; padding: 10px; text-align: center; font-size: 12px; color: #666; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>New Contact Form Submission</h2>
                </div>
                <div class='content'>
                    <div class='field'>
                        <div class='label'>Name:</div>
                        <div class='value'>" . $name . "</div>
                    </div>
                    <div class='field'>
                        <div class='label'>Email:</div>
                        <div class='value'><a href='mailto:" . $email . "'>" . $email . "</a></div>
                    </div>
                    <div class='field'>
                        <div class='label'>Phone:</div>
                        <div class='value'>" . $phone . "</div>
                    </div>
                    <div class='field'>
                        <div class='label'>Subject:</div>
                        <div class='value'>" . $subject . "</div>
                    </div>
                    <div class='field'>
                        <div class='label'>Message:</div>
                        <div class='value' style='white-space: pre-wrap; background-color: #f9f9f9; padding: 10px; border-left: 3px solid #ffa500;'>" . $message . "</div>
                    </div>
                </div>
                <div class='footer'>
                    <p>This is an automated email from " . $website_name . ". Please do not reply to this email.</p>
                </div>
            </div>
        </body>
        </html>
        ";

        // Send email to admin
        $admin_mail_sent = mail($admin_email, $email_subject, $email_body, $headers);

        // Optional: Send confirmation email to user
        $user_subject = "We received your message - " . $website_name;
        $user_body = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; background-color: #f5f5f5; }
                .header { background-color: #0a0e27; color: #ffa500; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }
                .content { background-color: white; padding: 20px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>Thank You for Contacting Me!</h2>
                </div>
                <div class='content'>
                    <p>Hello " . $name . ",</p>
                    <p>I have received your message and will get back to you as soon as possible.</p>
                    <p><strong>Subject:</strong> " . $subject . "</p>
                    <p>Thank you for reaching out!</p>
                    <p>Best regards,<br><strong>Binod Ray</strong></p>
                </div>
            </div>
        </body>
        </html>
        ";

        $user_headers = "MIME-Version: 1.0" . "\r\n";
        $user_headers .= "Content-type: text/html; charset=UTF-8" . "\r\n";
        $user_headers .= "From: " . $admin_email . "\r\n";

        mail($email, $user_subject, $user_body, $user_headers);

        // Return success response
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'message' => 'Your message has been sent successfully! I will get back to you soon.'
        ]);
        exit;
    } else {
        // Return error response
        header('Content-Type: application/json');
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => implode(", ", $errors)
        ]);
        exit;
    }
} else {
    // Not a POST request
    header('Content-Type: application/json');
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
    exit;
}
?>
