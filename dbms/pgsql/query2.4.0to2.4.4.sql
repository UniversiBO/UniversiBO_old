ALTER TABLE "utente" ALTER COLUMN "password" TYPE CHARACTER VARYING (64);
ALTER TABLE "utente" ADD "algoritmo" CHARACTER VARYING (8) DEFAULT '' NOT NULL;
ALTER TABLE "utente" ADD "salt"  CHARACTER VARYING (8) DEFAULT '' NOT NULL;
CREATE INDEX notifica_eliminata ON notifica USING btree(eliminata);
CREATE INDEX notifica_eliminata ON notifica USING btree(eliminata);
CREATE INDEX notifica_timestamp ON notifica USING btree(timestamp);