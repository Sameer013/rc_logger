#!/usr/bin/python2.7
import urllib, json, logging, requests
import MySQLdb, sys
try: 
	db=None
	logging.basicConfig(filename='syncup.log',level=logging.DEBUG,format='%(asctime)s %(message)s')
	logging.info('Syncup started')
	db=MySQLdb.connect(user="root",passwd="Sania@2003",db="ssvmt")
	db.autocommit(False)
	c=db.cursor()
	c.execute("select updt,dndt from synctb limit 1")
	row=c.fetchone()
	updt=str(row[0])
	dndt=str(row[1])
	f=open("data.sql","w+")
	c.execute("select mr_no,mr_date,studid,noterm,fee_amt,cash,chq_no,drawn_on,branch,late_fee,term,rebflg,rebate,adhoc,lastupd from fee where lastupd>'%s' and mr_no not like '%s'" % (updt,'%w'))
	for r in c:
		sql="""insert into fee(mr_no,mr_date,studid,noterm,fee_amt,cash,chq_no,drawn_on,
			branch,late_fee,term,rebflg,rebate,adhoc,lastupd) 
			values('%s','%s',%s,%s,%s,%s,'%s',%s,%s,%s,%s,%s,%s,%s,'%s')
			on duplicate key update fee_amt=%s,lastupd='%s'
			;\n""" % (r[0],r[1],r[2],r[3],r[4],r[5],r[6],r[7],r[8],r[9],r[10],r[11],r[12],r[13],r[14],r[4],r[14])
		sql=sql.replace('None','NULL')
		f.write(sql)
		#print sql
	c.execute("select mr_no,ac_no,ac_amt,ac_amtpaid,lastupd from fee_detail where lastupd>'%s' and mr_no not like '%s'" % (updt,'%w'))	
	for r in c:
		sql="replace into fee_detail(mr_no,ac_no,ac_amt,ac_amtpaid,lastupd) values('%s',%s,%s,%s,'%s');\n" % (r[0],r[1],r[2],r[3],r[4])
		sql=sql.replace('None','NULL')
		f.write(sql)
		#print sql
	c.execute("select slno,studid,ac_no,amount,dt,paid,mr_no,lastupd from adhocfee where lastupd>'%s'" % updt)	
	for r in c:
		sql="""insert into adhocfee(slno,studid,ac_no,amount,dt,paid,mr_no,lastupd) 
				values(%s,%s,%s,%s,'%s',%s,'%s','%s') 
				on duplicate key update 
				studid=%s,ac_no=%s,amount=%s,dt='%s',paid=%s,mr_no='%s',lastupd='%s';
				\n""" % (r[0],r[1],r[2],r[3],r[4],r[5],r[6],r[7],r[1],r[2],r[3],r[4],r[5],r[6],r[7])
		sql=sql.replace('None','NULL')
		f.write(sql)
		#print sql
	fl="STUDID,ADMNO,NAME,CLCODE,ROLLNO,SECTION,DOB,SEX,FNAME,MNAME,CATCODE,DOA,NOTERMS,EML,M_PHONE,FEE_PROF_CODE,tc_status,NSO,M_PHONE1,lastupd" 
	sql="select %s from studmast where lastupd>'%s'" % (fl,updt)
	c.execute(sql)
	for r in c:
		sql="""insert into studmast(%s)
			values(%s,'%s','%s',%s,%s,'%s','%s','%s','%s','%s',%s,'%s',%s,'%s','%s',%s,%s,%s,'%s','%s') 
			on duplicate key update admno='%s',name='%s',clcode=%s,rollno=%s,section='%s',dob='%s',sex='%s',fname='%s',mname='%s',catcode=%s,
			doa='%s',noterms=%s,EML='%s',m_phone='%s',fee_prof_code=%s,tc_status=%s,nso=%s,m_phone1='%s',lastupd='%s';\n""" % \
			(fl,r[0],r[1],r[2],r[3],r[4],r[5],r[6],r[7],r[8],r[9],r[10],r[11],r[12],r[13],r[14],r[15],r[16],r[17],r[18],r[19], \
			r[1],r[2],r[3],r[4],r[5],r[6],r[7],r[8],r[9],r[10],r[11],r[12],r[13],r[14],r[15],r[16],r[17],r[18],r[19])
		sql=sql.replace("'None'",'NULL').replace('None','NULL')
		f.write(sql)
		#print sql
	#sql="update synctb set updt=now()"
	#f.write(sql)
	f.close()
	url='http://jps.symphonytek.com/fee/uploadsql.php'
	files = {'sql_file': open('data.sql','rb')}
	values = {'DB': 'photcat', 'OUT': 'csv', 'SHORT': 'short'}
	r = requests.post(url, files=files, data=values)
	data=json.loads(r.text)
	(status,msg,dtm)=(data['status'],data['msg'],data['dtm'])
	if status=="ok":
		sql="update synctb set updt=now()" 
		c.execute(sql)
		db.commit()
		logging.info('Syncup finished')
	else:
		logging.info(msg)
except MySQLdb.Error:
	if not db is None:
		db.rollback()
	logging.exception("DB error")
	#print("DB error ",sys.exc_info()[0])
except:
	#print("Other error ",sys.exc_info()[0])
	logging.exception("Error")
