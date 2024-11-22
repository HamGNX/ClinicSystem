CREATE DATABASE ClinicAppointmentSystem;

USE ClinicAppointmentSystem;

-- Patient Table
CREATE TABLE Patient (
    Patient_ID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(100),
    Email VARCHAR(100),
    Phone VARCHAR(15),
    DOB DATE,
    Username VARCHAR(20) UNIQUE,
    Password VARCHAR(100)
);

-- Doctor Table
CREATE TABLE Doctor (
    Doctor_ID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(100),
    Specialty_ID INT,
    Username VARCHAR(20) UNIQUE,
    Password VARCHAR(100),
    FOREIGN KEY (Specialty_ID) REFERENCES Specialty(Specialty_ID)
);

-- Specialty Table
CREATE TABLE Specialty (
    Specialty_ID INT AUTO_INCREMENT PRIMARY KEY,
    Specialty_Name VARCHAR(100)
);

-- Appointment Table
CREATE TABLE Appointment (
    Appointment_ID INT AUTO_INCREMENT PRIMARY KEY,
    Patient_ID INT,
    Doctor_ID INT,
    Appointment_Date DATETIME,
    is_Confirmed BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (Patient_ID) REFERENCES Patient(Patient_ID),
    FOREIGN KEY (Doctor_ID) REFERENCES Doctor(Doctor_ID)
);

-- Medical Record Table
CREATE TABLE Medical_Record (
    Record_ID INT AUTO_INCREMENT PRIMARY KEY,
    Patient_ID INT,
    Doctor_ID INT,
    Diagnosis TEXT,
    Treatment TEXT,
    Record_Date DATETIME,
    FOREIGN KEY (Patient_ID) REFERENCES Patient(Patient_ID),
    FOREIGN KEY (Doctor_ID) REFERENCES Doctor(Doctor_ID)
);

-- Payment Table
CREATE TABLE Payment (
    Payment_ID INT AUTO_INCREMENT PRIMARY KEY,
    Appointment_ID INT,
    Payment_Date DATETIME,
    Amount DECIMAL(10, 2),
    Payment_Status ENUM('Pending', 'Completed', 'Failed'),
    FOREIGN KEY (Appointment_ID) REFERENCES Appointment(Appointment_ID)
);