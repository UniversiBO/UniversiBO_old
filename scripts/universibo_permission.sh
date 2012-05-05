#!/bin/sh
BASEDIR=/var/www/universibo
USER=apache
GROUP=apache

chown ${USER}.${GROUP} $BASEDIR -R
find $BASEDIR -type f -exec chmod 640 {} \;
find $BASEDIR -type d -exec chmod 750 {} \;

# gli utenti devono poter caricare le immagini per il forum
chmod g+w $BASEDIR/shared/htmls/forum/images/avatars
# gli utenti del sito devono poter inviare le loro foto
chmod g+w $BASEDIR/shared/htmls/img/contacts
# notifiche non ancora inviate
touch $BASEDIR/current/universibo/notifiche.lock
chmod g+w $BASEDIR/current/universibo/notifiche.lock
# log...
chmod g+w $BASEDIR/shared/universibo/log-universibo
# dispense etc etc
chmod g+w $BASEDIR/shared/universibo/file-universibo
# output di smarty, per il template
chmod g+w $BASEDIR/current/universibo/templates_compile/black
chmod g+w $BASEDIR/current/universibo/templates_compile/simple
chmod g+w $BASEDIR/current/universibo/templates_compile/unibo
