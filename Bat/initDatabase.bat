mysql -u root -ppassword < ./Sql/DropDatabase.sql
mysql -u root -ppassword < ./Sql/CreateDatabase.sql
mysql -u root -ppassword attendance_management < ./Sql/CreateTable.sql
mysql -u root -ppassword attendance_management < ./Sql/InsertEmployee.sql
mysql -u root -ppassword attendance_management < ./Sql/InsertAttendance.sql
mysql -u root -ppassword attendance_management < ./Sql/InsertTeam.sql