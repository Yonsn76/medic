/*
* BookMedik Database - SQLite Version
* @author Evilnapsis
* Modified for SQLite compatibility
*/

CREATE TABLE user (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT,
    name TEXT,
    lastname TEXT,
    email TEXT,
    password TEXT,
    is_active INTEGER NOT NULL DEFAULT 1,
    is_admin INTEGER NOT NULL DEFAULT 0,
    created_at DATETIME
);

-- Initial admin user (password: admin)
-- La contraseña es el resultado de sha1(md5('admin'))
INSERT INTO user (username, password, is_admin, is_active, created_at)
VALUES ("admin", "d033e22ae348aeb5660fc2140aec35850c4da997", 1, 1, datetime('now'));

CREATE TABLE pacient (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    no TEXT,
    name TEXT,
    lastname TEXT,
    gender TEXT,
    day_of_birth DATE,
    email TEXT,
    address TEXT,
    phone TEXT,
    image TEXT,
    sick TEXT,
    medicaments TEXT,
    alergy TEXT,
    is_favorite INTEGER NOT NULL DEFAULT 1,
    is_active INTEGER NOT NULL DEFAULT 1,
    created_at DATETIME
);

CREATE TABLE category (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT
);

-- Initial category
INSERT INTO category (name) VALUES ("Modulo 1");

CREATE TABLE medic (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    no TEXT,
    name TEXT,
    lastname TEXT,
    gender TEXT,
    day_of_birth DATE,
    email TEXT,
    address TEXT,
    phone TEXT,
    image TEXT,
    is_active INTEGER NOT NULL DEFAULT 1,
    created_at DATETIME,
    category_id INTEGER,
    FOREIGN KEY (category_id) REFERENCES category(id)
);

CREATE TABLE status (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT
);

-- Initial statuses
INSERT INTO status (id, name) VALUES
    (1, "Pendiente"),
    (2, "Aplicada"),
    (3, "No asistio"),
    (4, "Cancelada");

CREATE TABLE payment (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT
);

-- Initial payment types
INSERT INTO payment (id, name) VALUES
    (1, "Pendiente"),
    (2, "Pagado"),
    (3, "Anulado");

CREATE TABLE reservation (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT,
    note TEXT,
    message TEXT,
    date_at TEXT,
    time_at TEXT,
    created_at DATETIME,
    pacient_id INTEGER,
    symtoms TEXT,
    sick TEXT,
    medicaments TEXT,
    user_id INTEGER,
    medic_id INTEGER,
    price REAL,
    is_web INTEGER NOT NULL DEFAULT 0,
    payment_id INTEGER NOT NULL DEFAULT 1,
    status_id INTEGER NOT NULL DEFAULT 1,
    FOREIGN KEY (payment_id) REFERENCES payment(id),
    FOREIGN KEY (status_id) REFERENCES status(id),
    FOREIGN KEY (user_id) REFERENCES user(id),
    FOREIGN KEY (pacient_id) REFERENCES pacient(id),
    FOREIGN KEY (medic_id) REFERENCES medic(id)
);
