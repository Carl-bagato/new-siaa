create database siaadatabase;

CREATE TABLE user (
    user_id INT AUTO_INCREMENT PRIMARY KEY,          -- user_id is an auto-increment integer
    user_name VARCHAR(255) UNIQUE NOT NULL,          -- user_name cannot be duplicated
    password VARCHAR(255) NOT NULL                   -- password field (store hashed passwords in a real app)
);

-- Create the 'flashcard' table
CREATE TABLE flashcard (
    flashcard_id INT AUTO_INCREMENT PRIMARY KEY,    -- flashcard_id is the primary key for flashcards
    user_id INT,                                    -- user_id is the foreign key to the user table
    date_created TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- automatically set to the current timestamp
    title VARCHAR(255) NOT NULL,                     -- title of the flashcard
    content TEXT NOT NULL,                           -- general content of the flashcard (could be optional)
    FOREIGN KEY (user_id) REFERENCES user(user_id)   -- linking flashcard to user_id in 'user' table
);

-- Create the 'term_answer' table to store multiple terms and answers per flashcard
CREATE TABLE term_answer (
    term_answer_id INT AUTO_INCREMENT PRIMARY KEY,  -- unique ID for each term-answer pair
    flashcard_id INT,                                -- foreign key linking term-answer to a flashcard
    term VARCHAR(255) NOT NULL,                      -- the term (question) for the flashcard entry
    answer TEXT NOT NULL,                            -- the answer to the term
    FOREIGN KEY (flashcard_id) REFERENCES flashcard(flashcard_id) -- linking to flashcard
);
