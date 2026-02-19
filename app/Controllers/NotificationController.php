<?php

/**
 * Notification Controller
 * Displays reminders and notifications
 */

class NotificationController extends Controller {

    public function index() {
        $this->requireAuth();

        $taskModel = new Task();
        $reminders = $taskModel->getReminders(Auth::id());

        $this->view('notifications/index', [
            'reminders' => $reminders,
            'user' => Auth::user()
        ]);
    }
}
