-- roles
CREATE TABLE roles (
    id   INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) UNIQUE NOT NULL
);

-- permissions
CREATE TABLE permissions (
    id   INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) UNIQUE NOT NULL
);

-- pivot
CREATE TABLE role_permission (
    role_id INT,
    permission_id INT,
    PRIMARY KEY (role_id, permission_id)
);

-- users add role
ALTER TABLE users
  ADD COLUMN role_id INT NOT NULL,
  ADD FOREIGN KEY (role_id) REFERENCES roles(id);
