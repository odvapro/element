
DROP TABLE IF EXISTS em_types;

CREATE TABLE em_types (
  id SERIAL PRIMARY KEY,
  table_name VARCHAR(200) NOT NULL DEFAULT '',
  field VARCHAR(200) NOT NULL DEFAULT '',
  type VARCHAR(20) NOT NULL DEFAULT '',
  required INT NOT NULL DEFAULT 0,
  settings TEXT,
  name VARCHAR(255) DEFAULT NULL
);

DROP TABLE IF EXISTS em_users;

CREATE TABLE em_users (
  id SERIAL PRIMARY KEY,
  login VARCHAR(200) NOT NULL DEFAULT '',
  name VARCHAR(200) NOT NULL DEFAULT '',
  password VARCHAR(200) NOT NULL DEFAULT '',
  email VARCHAR(200) NOT NULL DEFAULT '',
  language VARCHAR(200) NOT NULL DEFAULT 'en'
);

INSERT INTO em_users (id, login, name, password, email, language) VALUES
  (1, 'admin', 'Untitled', '25e4ee4e9229397b6b17776bfceaf8e7', 'admin@email.com', 'en');

DROP TABLE IF EXISTS em_views;

CREATE TABLE em_views (
  id SERIAL PRIMARY KEY,
  name VARCHAR(100) DEFAULT NULL,
  table_name VARCHAR(200) NOT NULL DEFAULT '',
  filter TEXT,
  sort TEXT,
  default_view INT DEFAULT 1,
  settings TEXT
);

DROP TABLE IF EXISTS em_groups;

CREATE TABLE em_groups (
  id SERIAL PRIMARY KEY,
  name VARCHAR(255) DEFAULT NULL
);

INSERT INTO em_groups (id, name) VALUES (1, 'Administrators');

DROP TABLE IF EXISTS em_groups_users;

CREATE TABLE em_groups_users (
  id SERIAL PRIMARY KEY,
  group_id INT REFERENCES em_groups(id) ON DELETE SET NULL,
  user_id INT REFERENCES em_users(id) ON DELETE SET NULL
);

INSERT INTO em_groups_users (id, group_id, user_id) VALUES (1, 1, 1);

DROP TABLE IF EXISTS em_tokens;

CREATE TABLE em_tokens (
  id SERIAL PRIMARY KEY,
  group_id INT REFERENCES em_groups(id) ON DELETE SET NULL,
  value VARCHAR(200) DEFAULT NULL,
  date TIMESTAMP DEFAULT NULL
);

DROP TABLE IF EXISTS em_groups_tables;

CREATE TABLE em_groups_tables (
  id SERIAL PRIMARY KEY,
  group_id INT REFERENCES em_groups(id) ON DELETE SET NULL,
  table_name VARCHAR(200) DEFAULT NULL,
  access INT DEFAULT NULL
);

-- Convert Levenshtein function to PostgreSQL
-- PostgreSQL has built-in support for Levenshtein distance in the fuzzystrmatch extension
CREATE EXTENSION IF NOT EXISTS fuzzystrmatch;