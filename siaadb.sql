USE siaadatabase;

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

-- Insert a sample user
INSERT INTO user (user_name, password) 
VALUES ('john_doe', 'hashedpassword123'); -- In a real app, store the hashed password

-- Insert a sample flashcard for user 'john_doe' (user_id = 1)
INSERT INTO flashcard (user_id, title, content) 
VALUES (1, 'HTML Basics', 'HTML is the standard markup language for creating web pages.');

-- Insert terms and answers for 'HTML Basics' flashcard (flashcard_id = 1)
INSERT INTO term_answer (flashcard_id, term, answer) 
VALUES (1, 'What is HTML?', 'HTML stands for HyperText Markup Language and is used to create web pages.');

INSERT INTO term_answer (flashcard_id, term, answer) 
VALUES (1, 'What is an HTML tag?', 'An HTML tag is a special word enclosed in angle brackets, like <div> or <p>.');

-- Insert another flashcard for user 'john_doe' (user_id = 1)
INSERT INTO flashcard (user_id, title, content) 
VALUES (1, 'CSS Basics', 'CSS is used to style HTML elements and create visually attractive web pages.');

-- Insert terms and answers for 'CSS Basics' flashcard (flashcard_id = 2)
INSERT INTO term_answer (flashcard_id, term, answer) 
VALUES (2, 'What is CSS?', 'CSS stands for Cascading Style Sheets and is used to style web pages.');

INSERT INTO term_answer (flashcard_id, term, answer) 
VALUES (2, 'What is a CSS selector?', 'A CSS selector is used to target HTML elements for styling, like .class or #id.');

-- Insert a flashcard for user 'jane_smith' (user_id = 2)
INSERT INTO flashcard (user_id, title, content) 
VALUES (2, 'JavaScript Basics', 'JavaScript is a programming language used for web development.');

-- Insert terms and answers for 'JavaScript Basics' flashcard (flashcard_id = 3)
INSERT INTO term_answer (flashcard_id, term, answer) 
VALUES (3, 'What is JavaScript?', 'JavaScript is a programming language used to add interactivity to websites.');

INSERT INTO term_answer (flashcard_id, term, answer) 
VALUES (3, 'What is a JavaScript variable?', 'A variable in JavaScript is used to store data values.');

-- Insert a flashcard for user 'jane_smith' (user_id = 2)
INSERT INTO flashcard (user_id, title, content) 
VALUES (2, 'React Basics', 'React is a JavaScript library for building user interfaces.');

-- Insert terms and answers for 'React Basics' flashcard (flashcard_id = 4)
INSERT INTO term_answer (flashcard_id, term, answer) 
VALUES (4, 'What is React?', 'React is a JavaScript library used for building user interfaces.');

INSERT INTO term_answer (flashcard_id, term, answer) 
VALUES (4, 'What is a React component?', 'A React component is a reusable building block of a React application.');
