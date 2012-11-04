UPDATE fos_user SET password='ac28c2b41697aa58a24449437bade547', salt = '', encoder_name='md5';
UPDATE fos_user SET email='nome.cognome' || id || '@unibo.it';
UPDATE fos_user SET email_canonical = LOWER(email);
UPDATE fos_user SET phone='+393333333333' WHERE phone IS NOT NULL OR phone != '' ;

DELETE FROM contacts;
DELETE FROM questionario;
DELETE FROM notifica;