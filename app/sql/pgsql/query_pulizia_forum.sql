UPDATE phpbb_users SET user_password='ac28c2b41697aa58a24449437bade547';
UPDATE phpbb_users SET user_email='fake.email'||user_id||'@example.com';

UPDATE phpbb_posts_text SET post_text='finto testo finto testo finto testo finto testo finto testo finto testo finto testo finto testo finto testo finto testo finto testo finto testo 

finto testo finto testo finto testo finto testo finto testo finto testo finto testo finto testo 

finto testo finto testo finto testo finto testo finto testo finto testo 
finto testo finto testo' ;

DELETE FROM phpbb_privmsgs;
DELETE FROM phpbb_privmsgs_text;
