#!/usr/bin/env php
<?php

/**
 * CLI Script - Send Email Reminders
 * Run this script via cron to send reminder emails
 * 
 * Usage: php reminder.php
 * Cron example (every hour): 0 * * * * /usr/bin/php /path/to/ticklyst/reminder.php
 */

// Load configuration
require_once __DIR__ . '/config/env.php';
loadEnv(__DIR__ . '/.env');

require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/app/Models/Task.php';
require_once __DIR__ . '/app/Models/User.php';

// Check if running from CLI
if (php_sapi_name() !== 'cli') {
    die('This script must be run from the command line');
}

echo "TickLyst Reminder Script\n";
echo "========================\n\n";

try {
    $pdo = getDbConnection();
    
    // Get all tasks with reminders that need to be sent
    $stmt = $pdo->prepare("
        SELECT t.*, u.email, u.name as user_name, p.name as project_name
        FROM tasks t
        JOIN users u ON t.user_id = u.id
        JOIN projects p ON t.project_id = p.id
        WHERE t.remind_at <= NOW() 
          AND t.remind_at >= DATE_SUB(NOW(), INTERVAL 1 HOUR)
          AND t.status != 'done'
          AND t.deleted_at IS NULL
        ORDER BY t.remind_at ASC
    ");
    
    $stmt->execute();
    $tasks = $stmt->fetchAll();
    
    if (empty($tasks)) {
        echo "No reminders to send.\n";
        exit(0);
    }
    
    echo "Found " . count($tasks) . " reminder(s) to send.\n\n";
    
    foreach ($tasks as $task) {
        echo "Sending reminder for task #{$task['id']}: {$task['title']}\n";
        echo "  To: {$task['user_name']} <{$task['email']}>\n";
        echo "  Project: {$task['project_name']}\n";
        echo "  Due Date: " . ($task['due_date'] ?? 'Not set') . "\n";
        
        // Email stub - in production, use a proper email library like PHPMailer or SwiftMailer
        $sent = sendReminderEmail($task);
        
        if ($sent) {
            echo "  Status: ✓ Sent successfully\n";
        } else {
            echo "  Status: ✗ Failed to send\n";
        }
        
        echo "\n";
    }
    
    echo "Reminder script completed.\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}

/**
 * Send reminder email (stub implementation)
 * 
 * @param array $task Task data
 * @return bool Success status
 */
function sendReminderEmail($task) {
    // This is a stub implementation
    // In production, integrate with a real email service:
    // - PHPMailer: https://github.com/PHPMailer/PHPMailer
    // - SwiftMailer: https://swiftmailer.symfony.com/
    // - SendGrid API: https://sendgrid.com/
    // - Mailgun API: https://www.mailgun.com/
    
    $to = $task['email'];
    $subject = "Reminder: {$task['title']}";
    $message = "Hi {$task['user_name']},\n\n";
    $message .= "This is a reminder about your task:\n\n";
    $message .= "Task: {$task['title']}\n";
    $message .= "Project: {$task['project_name']}\n";
    $message .= "Status: {$task['status']}\n";
    
    if ($task['description']) {
        $message .= "Description: {$task['description']}\n";
    }
    
    if ($task['due_date']) {
        $message .= "Due Date: {$task['due_date']}\n";
    }
    
    $message .= "\nBest regards,\nTickLyst Team";
    
    // Uncomment to use PHP's mail() function (requires mail server configuration)
    // return mail($to, $subject, $message, "From: noreply@ticklyst.local");
    
    // For development/testing, just log the email
    $logFile = __DIR__ . '/storage/logs/reminders.log';
    $logEntry = date('Y-m-d H:i:s') . " - Email to {$to}: {$subject}\n";
    file_put_contents($logFile, $logEntry, FILE_APPEND);
    
    return true; // Simulate success
}
