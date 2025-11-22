-- SQL script to create the 'users' table for the Fingyaan_sathi database

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` INT(11) NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `username` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  
  -- Optional field based on your reference, useful for displaying profile pictures
  `photo` VARCHAR(255) NOT NULL DEFAULT 'default.svg' 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  
  -- It's a good practice to also ensure email addresses are unique
  ADD UNIQUE KEY `email` (`email`); 

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1; 

--
-- Optional: Insert a test user (Password is 'testpass')
--
INSERT INTO `users` (`id`, `name`, `username`, `email`, `password`, `photo`) VALUES
(NULL, 'Test User', 'testuser', 'test@example.com', '$2y$10$tW03.iXG6u7k9bX6.Y73g.E3R1o0T4.L0B.v3A5D4A.E7Q4Q3A2', 'default.svg');
