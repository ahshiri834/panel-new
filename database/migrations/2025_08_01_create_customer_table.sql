CREATE TABLE IF NOT EXISTS doctor_info (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    doctor_type  ENUM('Male','Female','Clinic','Hospital','Company','infirmary','Pharmacy','Laboratory') NOT NULL,
    name         VARCHAR(255) NOT NULL,
    mobile       VARCHAR(20)   NOT NULL UNIQUE,
    address      TEXT           NULL,
    specialty    VARCHAR(100)   NULL,
    national_id  VARCHAR(20)    NULL,
    created_by   INT            NULL,
    type_add     TINYINT       NOT NULL DEFAULT 1,
    created_at   DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at   DATETIME      NULL ON UPDATE CURRENT_TIMESTAMP,
    INDEX(mobile),
    FOREIGN KEY (created_by) REFERENCES users(id)
);
