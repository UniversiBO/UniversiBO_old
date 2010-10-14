# !/bin/sh
cd "/d/Universita/UNI linguaggi e modelli computazionale ls/progetto-esteso-nsbody-as-token/"
sh rebuild.sh
cd -
cp "/d/Universita/UNI linguaggi e modelli computazionale ls/progetto-esteso-nsbody-as-token/src"/* . -r
cp "/d/Universita/UNI linguaggi e modelli computazionale ls/progetto-esteso-nsbody-as-token"/input* .
rm */*class
/opt/jdk1.5.0/bin/javac */*java
