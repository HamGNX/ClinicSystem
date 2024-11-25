DROP DATABASE IF EXISTS Clinic;
CREATE DATABASE IF NOT EXISTS Clinic;
USE Clinic;

CREATE TABLE Patient (
  Patient_ID INT PRIMARY KEY,
  FName VARCHAR(50),
  LName VARCHAR(50),
  Email VARCHAR(50),
  Phone VARCHAR(50),
  DOB DATE,
  Username VARCHAR(50),
  Password VARCHAR(50)
);

CREATE TABLE Doctor(
  Doctor_ID INT PRIMARY KEY,
  FName VARCHAR(50),
  LName VARCHAR(50),
  Specialty_ID INT, -- [ref: > Specialty.Specialty_ID]
  Username VARCHAR(50),
  Password VARCHAR(50)
);

CREATE TABLE Specialty (
  Specialty_ID INT PRIMARY KEY,
  Specialty_Name VARCHAR(50)
);

CREATE TABLE Appointment (
  Appointment_ID INT PRIMARY KEY,
  Patient_ID INT, -- [ref: > Patient.Patient_ID]
  Doctor_ID INT, -- [ref: > Doctor.Doctor_ID]
  Appointment_Date DATETIME,
  is_Confirmed BOOLEAN
);

CREATE TABLE Medical_Record (
  Record_ID INT PRIMARY KEY,
  Patient_ID INT, -- [ref: > Patient.Patient_ID]
  Doctor_ID INT, -- [ref: > Doctor.Doctor_ID]
  Diagnosis TEXT,
  Treatment TEXT,
  Record_Date DATETIME
);

CREATE TABLE Payment (
  Payment_ID INT PRIMARY KEY,
  Appointment_ID INT, -- [ref: > Appointment.Appointment_ID]
  Payment_Date DATETIME,
  Amount DECIMAL(10,2),
  Payment_Status ENUM('Pending', 'Completed', 'Failed')
);

ALTER TABLE Doctor
	ADD CONSTRAINT fk_specialty FOREIGN KEY Doctor(Specialty_ID) REFERENCES Specialty(Specialty_ID);
    
ALTER TABLE Appointment
	ADD CONSTRAINT fk_PatientApp FOREIGN KEY Appointment(Patient_ID) REFERENCES Patient(Patient_ID),
    ADD CONSTRAINT fk_DoctorApp FOREIGN KEY Appointment(Doctor_ID) REFERENCES Doctor(Doctor_ID);

ALTER TABLE Medical_Record
	ADD CONSTRAINT fk_PatientMed FOREIGN KEY Medical_Record(Patient_ID) REFERENCES Patient(Patient_ID),
    ADD CONSTRAINT fk_DoctorMed FOREIGN KEY Medical_Record(Doctor_ID) REFERENCES Doctor(Doctor_ID);

ALTER TABLE Payment
	ADD CONSTRAINT fk_AppointmentPay FOREIGN KEY Payment(Appointment_ID) REFERENCES Appointment(Appointment_ID);