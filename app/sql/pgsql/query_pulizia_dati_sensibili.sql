UPDATE utente SET password='ac28c2b41697aa58a24449437bade547', salt = '', algoritmo='md5';
UPDATE utente SET email=user_email='fake.email'||id_utente||'@example.com';
UPDATE utente SET ad_username='nome.cognome' || id_utente || '@studio.unibo.it' WHERE ad_username IS NOT NULL OR ad_username != '' ;
UPDATE utente SET phone='0000000000' WHERE phone IS NOT NULL OR phone != '' ;


UPDATE phpbb_users SET user_password='ac28c2b41697aa58a24449437bade547';
UPDATE phpbb_users SET user_email='fake.email'||user_id||'@example.com';


UPDATE phpbb_posts_text SET post_text='finto testo finto testo finto testo finto testo finto testo finto testo finto testo finto testo finto testo finto testo finto testo finto testo 

finto testo finto testo finto testo finto testo finto testo finto testo finto testo finto testo 

finto testo finto testo finto testo finto testo finto testo finto testo 
finto testo finto testo' ;

DELETE FROM phpbb_privmsgs;
DELETE FROM phpbb_privmsgs_text;

DELETE FROM questionario;

DELETE FROM notifica;

