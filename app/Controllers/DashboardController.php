<?php

/**
 * Dashboard Controller
 * Main dashboard with Today, Overdue, Upcoming sections
 */

class DashboardController extends Controller {

    public function index() {
        $this->requireAuth();

        $taskModel = new Task();
        $projectModel = new Project();
        $userId = Auth::id();

        $todayTasks = $taskModel->getToday($userId);
        $overdueTasks = $taskModel->getOverdue($userId);
        $upcomingTasks = $taskModel->getUpcoming($userId);
        $projects = $projectModel->getAll($userId);

        $this->view('dashboard/index', [
            'todayTasks' => $todayTasks,
            'overdueTasks' => $overdueTasks,
            'upcomingTasks' => $upcomingTasks,
            'projects' => $projects,
            'user' => Auth::user()
        ]);
    }
}
