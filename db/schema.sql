-- Drop tables if they exist
DROP TABLE IF EXISTS grades;
DROP TABLE IF EXISTS submissions;
DROP TABLE IF EXISTS assignments;
DROP TABLE IF EXISTS enrollments;
DROP TABLE IF EXISTS courses;
DROP TABLE IF EXISTS users;

-- Create Users table
CREATE TABLE users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    role ENUM('student', 'instructor', 'admin') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create Courses table
CREATE TABLE courses (
    course_id INT PRIMARY KEY AUTO_INCREMENT,
    course_code VARCHAR(10) UNIQUE NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    instructor_id INT,
    duration_weeks INT,
    duration_hours INT,
    status ENUM('active', 'inactive', 'archived') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (instructor_id) REFERENCES users(user_id)
);

-- Create Enrollments table
CREATE TABLE enrollments (
    enrollment_id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT,
    course_id INT,
    status ENUM('in_progress', 'completed', 'dropped') DEFAULT 'in_progress',
    progress_percentage DECIMAL(5,2) DEFAULT 0.00,
    enrollment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    completion_date TIMESTAMP NULL,
    FOREIGN KEY (student_id) REFERENCES users(user_id),
    FOREIGN KEY (course_id) REFERENCES courses(course_id),
    UNIQUE KEY unique_enrollment (student_id, course_id)
);

-- Create Assignments table
CREATE TABLE assignments (
    assignment_id INT PRIMARY KEY AUTO_INCREMENT,
    course_id INT,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    type ENUM('quiz', 'project', 'exam', 'homework') NOT NULL,
    due_date TIMESTAMP NOT NULL,
    total_points DECIMAL(5,2) DEFAULT 100.00,
    weight_percentage DECIMAL(5,2) DEFAULT 100.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(course_id)
);

-- Create Submissions table
CREATE TABLE submissions (
    submission_id INT PRIMARY KEY AUTO_INCREMENT,
    assignment_id INT,
    student_id INT,
    file_path VARCHAR(255),
    submission_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('submitted', 'graded', 'pending_review') DEFAULT 'submitted',
    FOREIGN KEY (assignment_id) REFERENCES assignments(assignment_id),
    FOREIGN KEY (student_id) REFERENCES users(user_id),
    UNIQUE KEY unique_submission (assignment_id, student_id)
);

-- Create Grades table
CREATE TABLE grades (
    grade_id INT PRIMARY KEY AUTO_INCREMENT,
    submission_id INT,
    grade_value DECIMAL(3,1) NOT NULL, -- Stores grades like 1.0, 2.1, etc.
    feedback TEXT,
    graded_by INT,
    graded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (submission_id) REFERENCES submissions(submission_id),
    FOREIGN KEY (graded_by) REFERENCES users(user_id)
);

-- Clear existing test data
TRUNCATE TABLE grades;
TRUNCATE TABLE submissions;
TRUNCATE TABLE assignments;
TRUNCATE TABLE enrollments;
TRUNCATE TABLE courses;
TRUNCATE TABLE users;

-- Insert Users (Instructors)
INSERT INTO users (email, password_hash, first_name, last_name, role) VALUES
('mufaro.kaseke@nust.ac.zw', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Mufaro', 'Kaseke', 'instructor'),
('andile.dube@nust.ac.zw', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Andile', 'Dube', 'instructor'),
('chilumani@nust.ac.zw', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'K', 'Chilumani', 'instructor'),
('mutengeni@nust.ac.zw', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Mutengeni', 'M', 'instructor');

-- Insert Users (Students)
INSERT INTO users (email, password_hash, first_name, last_name, role) VALUES
('student1@students.nust.ac.zw', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'John', 'Doe', 'student'),
('student2@students.nust.ac.zw', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Jane', 'Smith', 'student'),
('student3@students.nust.ac.zw', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Bob', 'Johnson', 'student');

-- Insert Courses
INSERT INTO courses (course_code, title, description, instructor_id, duration_weeks, duration_hours) VALUES
('CSC4101', 'Advanced Mathematical Structures for Computing', 'Advanced mathematical concepts essential for computer science', 1, 14, 56),
('CSC4102', 'Computational Modelling', 'Fundamentals of computational modeling and simulation', 1, 14, 56),
('CSC4103', 'Mobile Application Development', 'Development of mobile applications for various platforms', 2, 14, 56),
('CSC4104', 'Software Project Management', 'Project management principles for software development', 3, 14, 56),
('CSC4105', 'Design and Analysis of Algorithms', 'Study of algorithm design techniques and analysis', 2, 14, 56),
('CSC4106', 'Group Project', 'Collaborative software development project', 4, 14, 56);

-- Insert Enrollments
INSERT INTO enrollments (student_id, course_id, status, progress_percentage) VALUES
(5, 1, 'in_progress', 65.00),
(5, 2, 'in_progress', 45.00),
(5, 3, 'in_progress', 80.00),
(5, 4, 'in_progress', 90.00),
(5, 5, 'in_progress', 75.00),
(5, 6, 'in_progress', 60.00),
(6, 1, 'in_progress', 70.00),
(6, 2, 'in_progress', 55.00),
(6, 3, 'in_progress', 85.00);

-- Insert Assignments
INSERT INTO assignments (course_id, title, description, type, due_date, total_points, weight_percentage) VALUES
(4, 'E-Learning Platform', 'Develop an e-learning platform for NUST students', 'project', DATE_ADD(CURRENT_TIMESTAMP, INTERVAL 7 DAY), 100.00, 40.00),
(4, 'Project Management Plan', 'Create a comprehensive project management plan', 'homework', DATE_ADD(CURRENT_TIMESTAMP, INTERVAL 14 DAY), 100.00, 20.00),
(6, 'Group Project Phase 1', 'Initial project proposal and planning', 'project', DATE_ADD(CURRENT_TIMESTAMP, INTERVAL 5 DAY), 100.00, 30.00),
(1, 'Mathematical Proofs', 'Complex mathematical proofs assignment', 'homework', DATE_ADD(CURRENT_TIMESTAMP, INTERVAL 3 DAY), 100.00, 15.00),
(2, 'Simulation Model', 'Create a simulation model for a real-world problem', 'project', DATE_ADD(CURRENT_TIMESTAMP, INTERVAL 10 DAY), 100.00, 35.00),
(3, 'Mobile App Prototype', 'Develop a prototype mobile application', 'project', DATE_ADD(CURRENT_TIMESTAMP, INTERVAL 8 DAY), 100.00, 45.00);

-- Insert Sample Submissions
INSERT INTO submissions (assignment_id, student_id, file_path, status, submission_date) VALUES
(1, 5, '/uploads/student5/assignment1.pdf', 'graded', DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 2 DAY)),
(2, 5, '/uploads/student5/assignment2.pdf', 'submitted', DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 1 DAY)),
(3, 5, '/uploads/student5/group_project.zip', 'graded', DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 5 DAY));

-- Insert Grades
INSERT INTO grades (submission_id, grade_value, feedback, graded_by) VALUES
(1, 2.1, 'Good work on the platform design. Consider improving the user interface.', 3),
(3, 1.8, 'Project proposal needs more detail on implementation strategy.', 4);

-- Password for all users is 'password123'

-- Add indexes for better performance
CREATE INDEX idx_enrollment_student ON enrollments(student_id);
CREATE INDEX idx_enrollment_course ON enrollments(course_id);
CREATE INDEX idx_assignment_course ON assignments(course_id);
CREATE INDEX idx_submission_student ON submissions(student_id);
CREATE INDEX idx_submission_status ON submissions(status);
