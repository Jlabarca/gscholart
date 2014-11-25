
CREATE SCHEMA public;

CREATE DOMAIN dom_type VARCHAR(1) CHECK(
	VALUE IN('b','k','p','j','d','n')
);

CREATE TABLE category(
	id INTEGER,
	name VARCHAR(100) NOT NULL,
	CONSTRAINT pkey_category PRIMARY KEY(id),
	CONSTRAINT unq_category_name UNIQUE(name)
);

CREATE TABLE country(
	id SERIAL,
	name VARCHAR(100) NOT NULL,
	CONSTRAINT pkey_country PRIMARY KEY(id),
	CONSTRAINT unq_country_name UNIQUE(name)
);

CREATE TABLE journal(
	issn VARCHAR(8),
	title VARCHAR(400) NOT NULL,
	type dom_type NOT NULL,
	sjr NUMERIC(5,3) NOT NULL,
	h_index NUMERIC(3) NOT NULL,
	total_docs NUMERIC(8) NOT NULL,
	total_docs_three_years NUMERIC(8) NOT NULL,
	total_refs NUMERIC(8) NOT NULL,
	total_cites NUMERIC(8) NOT NULL,
	citable_docs NUMERIC(8) NOT NULL,
	avg_citation_doc_two_years NUMERIC(5,2) NOT NULL,
	avg_amount_refs_doc NUMERIC(5,2) NOT NULL,
	id_country INTEGER,
	CONSTRAINT pkey_journal PRIMARY KEY(issn),
	CONSTRAINT fkey_country FOREIGN KEY(id_country) REFERENCES country(id)
	/*CONSTRAINT unq_journal_title UNIQUE(title)*/
);

CREATE TABLE journalCategory(
	id_journal VARCHAR(8),
	id_category INTEGER,
	CONSTRAINT pkey_journalCategory PRIMARY KEY(id_journal,id_category),
	CONSTRAINT fkey_journal FOREIGN KEY(id_journal) REFERENCES journal(issn),
	CONSTRAINT fkey_category FOREIGN KEY(id_category) REFERENCES category(id)
);

ALTER DATABASE prueba_4 SET client_encoding = 'utf8';