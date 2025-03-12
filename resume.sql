-- Table: login
CREATE TABLE login (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(100) NOT NULL,  
    email VARCHAR(100) NOT NULL UNIQUE,  
    password VARCHAR(255) NOT NULL
);

-- Table: basic
CREATE TABLE basic (
    id INT PRIMARY KEY,
    name VARCHAR(255),
    phone_number VARCHAR(15),
    address TEXT,
    executive_summary TEXT,
    resume_email VARCHAR(100),  
    FOREIGN KEY (id) REFERENCES login(id) ON DELETE CASCADE
);

-- Table: experience
CREATE TABLE experience (
    id INT PRIMARY KEY,
    type VARCHAR(255),
    title VARCHAR(255),
    company_name VARCHAR(255),
    start_date DATE,
    end_date DATE,
    responsibilities TEXT,
    FOREIGN KEY (id) REFERENCES login(id) ON DELETE CASCADE
);

-- Table: education
CREATE TABLE education (
    id INT PRIMARY KEY,
    school VARCHAR(255),
    tenth_date DATE,
    tenth_marks DECIMAL(5, 2),
    twelfth_school VARCHAR(255),
    twelfth_date DATE,
    twelfth_percentage DECIMAL(5, 2),
    college_name VARCHAR(255),
    completion_date DATE,
    gpa DECIMAL(5, 2),
    FOREIGN KEY (id) REFERENCES login(id) ON DELETE CASCADE
);

-- Table: skills
CREATE TABLE skills (
    id INT PRIMARY KEY,
    s1 VARCHAR(255),
    s2 VARCHAR(255),
    s3 VARCHAR(255),
    s4 VARCHAR(255),
    s5 VARCHAR(255),
    s6 VARCHAR(255),
    s7 VARCHAR(255),
    s8 VARCHAR(255),
    s9 VARCHAR(255),
    s10 VARCHAR(255),
    FOREIGN KEY (id) REFERENCES login(id) ON DELETE CASCADE
);

-- Table: achievements
CREATE TABLE achievements (
    id INT PRIMARY KEY,
    achieve TEXT,
    FOREIGN KEY (id) REFERENCES login(id) ON DELETE CASCADE
);
