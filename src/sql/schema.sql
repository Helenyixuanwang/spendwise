-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Expenses table
CREATE TABLE IF NOT EXISTS expenses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(100) NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    category ENUM('Food', 'Transport', 'Housing', 'Health', 'Entertainment', 'Other') NOT NULL,
    expense_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Stored procedure: monthly summary per category
DELIMITER $$

CREATE PROCEDURE GetMonthlySummary(
    IN p_user_id INT,
    IN p_year INT,
    IN p_month INT
)
BEGIN
    SELECT
        category,
        SUM(amount) AS total,
        COUNT(*) AS num_expenses
    FROM expenses
    WHERE
        user_id = p_user_id
        AND YEAR(expense_date) = p_year
        AND MONTH(expense_date) = p_month
    GROUP BY category
    ORDER BY total DESC;
END$$

DELIMITER ;