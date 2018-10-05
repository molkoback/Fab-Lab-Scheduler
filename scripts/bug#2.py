# This script must be utilized in order to update the database if you download v1.0 beta or before. 
# Change the database password, user and db in the line 16-19
# 
# REQUIREMENTS:
# Python 2.7
# mysqlclient is needed: pip install mysqlclient
# for that might be needed sudo apt-get install libmysqlclient-dev

import MySQLdb

SELECT_ALL_USERS = "SELECT id FROM aauth_users"
SELECT_ALL_MACHINES ="SELECT MachineID FROM Machine"
USER_LEVEL = "SELECT * FROM UserLevel WHERE MachineId=%s AND aauth_usersID=%s"
ADD_MACHINE_LEVEL ="INSERT INTO UserLevel(MachineID, aauth_usersID, Level) VALUES (%s,%s,%s)"

connection = MySQLdb.connect (host = "127.0.0.1",
                              user = "root",
                              passwd = "pass",
                              db = "fablab_scheduler")

#cursor = connection.cursor(MySQLdb.cursors.DictCursor)
try:
	cursor1 = connection.cursor()
	cursor2 = connection.cursor()
	cursor3 = connection.cursor()
	cursor1.execute (SELECT_ALL_USERS)
	#result = cursor.fetchall()
	for (id_user,) in cursor1:
		cursor2.execute(SELECT_ALL_MACHINES)
		for (id_machine,) in cursor2:
			cursor3.execute(USER_LEVEL, (id_machine, id_user))
			if cursor3.rowcount<=0:
				print(id_user, id_machine, "out")
				cursor=connection.cursor()
				try:
					#pass
					cursor.execute(ADD_MACHINE_LEVEL,(id_machine, id_user,0))
					connection.commit()
				finally:
					cursor.close()
finally:
	if cursor1:			
		cursor1.close()
	if cursor2:
		cursor2.close()
	if cursor3:
		cursor3.close()
	connection.close()