@echo off
set name_db=shop
echo ��������! �᫨ �� � ������ %name_db% 㦥 �������, ��� �㤥� 㤠���� � ᮧ���� ������!
echo ���⮬�, �᫨ � ��� ���� ����� ����� � �� %name_db%, ��� ����室��� ��ࢠ�� ࠡ��� 
echo ������� �ਯ� (Ctrl+C) � ᤥ���� ������ १�ࢭ�� ����� ��.
echo ====================================================================================
echo �⠯ 0. �������� ���� �� %name_db%.
c:\xampp\mysql\bin\mysqldump.exe -uroot -hlocalhost %name_db% >old_db.sql -vvv
echo ====================================================================================
echo �⠯ 1. �������� �������饩 �� %name_db%.
c:\xampp\mysql\bin\mysql -uroot -hlocalhost -vvv -e "DROP DATABASE IF EXISTS %name_db%"
echo ====================================================================================
echo �⠯ 2. �������� ����� �� %name_db%.
c:\xampp\mysql\bin\mysql -uroot -hlocalhost -vvv -e "SET NAMES 'utf8'"
c:\xampp\mysql\bin\mysql -uroot -hlocalhost -vvv -e "CREATE DATABASE %name_db%  CHARACTER SET utf8 COLLATE utf8_general_ci;"
echo ====================================================================================
echo �⠯ 3. ������ ������ � �� %name_db% �� 䠩�� db.sql.
c:\xampp\mysql\bin\mysql -uroot -hlocalhost -vvv %name_db% <db.sql
echo ====================================================================================
echo ������ �ᯥ譮 �����襭.
pause