-- Seed data for TickLyst
-- Demo user: demo@ticklyst.com / password123

USE ticklyst;

-- Insert demo user (password: password123)
INSERT INTO users (email, password, name) VALUES
('demo@ticklyst.com', '$2y$10$BazWKARPJt.y5SIHwePToeI3WCdSiu3SJpt3wCtBNCo9/gW7mQ6Be', 'Demo User');

-- Get demo user ID
SET @demo_user_id = LAST_INSERT_ID();

-- Insert sample projects
INSERT INTO projects (user_id, name, description, color) VALUES
(@demo_user_id, 'Personal', 'Personal tasks and errands', '#3498db'),
(@demo_user_id, 'Work', 'Work-related tasks', '#e74c3c'),
(@demo_user_id, 'Learning', 'Study and skill development', '#2ecc71');

-- Get project IDs
SET @project_personal = (SELECT id FROM projects WHERE user_id = @demo_user_id AND name = 'Personal');
SET @project_work = (SELECT id FROM projects WHERE user_id = @demo_user_id AND name = 'Work');
SET @project_learning = (SELECT id FROM projects WHERE user_id = @demo_user_id AND name = 'Learning');

-- Insert sample tasks
INSERT INTO tasks (user_id, project_id, title, description, status, priority, due_date, remind_at) VALUES
-- Personal tasks
(@demo_user_id, @project_personal, 'Buy groceries', 'Milk, eggs, bread', 'todo', 2, CURDATE(), DATE_ADD(NOW(), INTERVAL -1 HOUR)),
(@demo_user_id, @project_personal, 'Call dentist', 'Schedule annual checkup', 'todo', 1, DATE_ADD(CURDATE(), INTERVAL 2 DAY), NULL),
(@demo_user_id, @project_personal, 'Pay utility bills', NULL, 'done', 3, DATE_ADD(CURDATE(), INTERVAL -1 DAY), NULL),

-- Work tasks
(@demo_user_id, @project_work, 'Prepare presentation', 'Q4 review meeting', 'doing', 1, DATE_ADD(CURDATE(), INTERVAL 1 DAY), DATE_ADD(NOW(), INTERVAL 4 HOUR)),
(@demo_user_id, @project_work, 'Review pull requests', 'Check team PRs', 'todo', 2, CURDATE(), NULL),
(@demo_user_id, @project_work, 'Update documentation', 'API endpoints documentation', 'todo', 2, DATE_ADD(CURDATE(), INTERVAL 3 DAY), NULL),

-- Learning tasks
(@demo_user_id, @project_learning, 'Complete PHP course', 'Advanced OOP concepts', 'doing', 2, DATE_ADD(CURDATE(), INTERVAL 7 DAY), NULL),
(@demo_user_id, @project_learning, 'Read design patterns book', 'Chapter 5-8', 'todo', 3, DATE_ADD(CURDATE(), INTERVAL 14 DAY), NULL);
