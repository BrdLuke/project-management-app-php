CREATE DATABASE Azienda;
USE Azienda;

-- Tabella Amministratore
CREATE TABLE Amministratore (
    CF VARCHAR(16),
    Cognome VARCHAR(30),
    Nome VARCHAR(30),
    NumeroTelefono VARCHAR(10),
    Email VARCHAR(50),
    Password VARCHAR(255),
    PRIMARY KEY (CF)
);

-- Tabella Dipendente
CREATE TABLE Dipendente (
    CodD VARCHAR(16),
    Cognome VARCHAR(30), 
    Nome VARCHAR(30),
    NumeroTelefono VARCHAR(10),
    Email VARCHAR(50),
    Skills VARCHAR(255),
    Password VARCHAR(255),
    PRIMARY KEY (CodD)
);

-- Tabella Cliente
CREATE TABLE Cliente (
    Email VARCHAR(50),
    Cognome VARCHAR(30),
    Nome VARCHAR(30),
    Password VARCHAR(255),
    PRIMARY KEY (Email)
);

-- Tabella Stato
CREATE TABLE Stato (
    Tipo VARCHAR(20),
    Descrizione VARCHAR(100),
    PRIMARY KEY (Tipo)
);

-- Tabella Progetto
CREATE TABLE Progetto (
    ID INT AUTO_INCREMENT,
    Nome VARCHAR(50),
    Descrizione VARCHAR(255),
    Amministratore VARCHAR(16),
    Cliente VARCHAR(50),
    Stato VARCHAR(20),
    PRIMARY KEY (ID),
    FOREIGN KEY (Amministratore) REFERENCES Amministratore(CF),
    FOREIGN KEY (Cliente) REFERENCES Cliente(Email),
    FOREIGN KEY (Stato) REFERENCES Stato(Tipo)
);

-- Tabella Assegnazione
CREATE TABLE Assegnazione (
    ID INT AUTO_INCREMENT,
    Dipendente VARCHAR(16),
    Progetto INT,
    DataOraAssegnazione TIMESTAMP,
    PRIMARY KEY (ID),
    FOREIGN KEY (Dipendente) REFERENCES Dipendente(CodD),
    FOREIGN KEY (Progetto) REFERENCES Progetto(ID)
);

-- Tabella Tasks
CREATE TABLE Tasks (
    ID INT AUTO_INCREMENT,
    Nome VARCHAR(50),
    Descrizione VARCHAR(255),
    PRIMARY KEY (ID)
);

-- Tabella Assegnazione_Tasks
CREATE TABLE Assegnazione_Tasks (
    ID INT AUTO_INCREMENT,
    Assegnazione INT,
    Task INT,
    DataOraAssegnazione TIMESTAMP,
    Completato INT, -- Se completato = 1, altrimenti non completato = 0
    PRIMARY KEY (ID),
    FOREIGN KEY (Assegnazione) REFERENCES Assegnazione(ID),
    FOREIGN KEY (Task) REFERENCES Tasks(ID)
);


INSERT INTO Stato (Tipo, Descrizione) VALUES ('In corso', 'Il progetto è in esecuzione'), ('Avviato', 'Il progetto è stato avviato'), ('Completato', 'Il progetto è stato completato');