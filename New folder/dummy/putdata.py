#!/usr/bin/python
import urllib.request,urllib.parse, json, logging, requests
import mysql.connector, sys
try: 
	db=None
	logging.basicConfig(filename='syncup.log',level=logging.DEBUG,format='%(asctime)s %(message)s')
	logging.info('Syncup started')
	db=mysql.connector.connect(user="root",passwd="Sania@2003",db="ssvm")
	c=db.cursor()
	c.execute("select updt,dndt from synctb limit 1")
	row=c.fetchone()
	updt=str(row[0])
	dndt=str(row[1])
	f=open("data.sql","w+")
	c.execute("select mr_no,mr_date,studid,noterm,fee_amt,cash,chq_no,drawn_on,branch,late_fee,term,rebflg,rebate,adhoc,lastupd from fee where lastupd>'%s' and mr_no not like '%s'" % (updt,'%w'))
	for r in c:
		sql="""insert into fee(MR_NO,MR_DATE,BILLNO,STUDID,NOTERM,FEE_AMT,CASH,CHQ_NO,DRAWN_ON,CHQ_AMT,BRANCH,LATE_FEE,TERM,STD,SEC,REBFLG,REBATE,REBINFO,adhoc,uid,bnoterm,lastupd) 
			values('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')
			on duplicate key update MR_DATE='%s',BILLNO='%s',STUDID='%s',NOTERM='%s',FEE_AMT='%s',CASH='%s',CHQ_NO='%s',DRAWN_ON='%s',CHQ_AMT='%s',BRANCH='%s',LATE_FEE='%s',TERM='%s',STD='%s',SEC='%s',REBFLG='%s',REBATE='%s',REBINFO='%s',adhoc='%s',uid='%s',bnoterm='%s',lastupd='%s';\n""" % (r[0],r[1],r[2],r[3],r[4],r[5],r[6],r[7],r[8],r[9],r[10],r[11],r[12],r[13],r[14],r[15],r[16],r[17],r[18],r[19],r[20],r[21],r[1],r[2],r[3],r[4],r[5],r[6],r[7],r[8],r[9],r[10],r[11],r[12],r[13],r[14],r[15],r[16],r[17],r[18],r[19],r[20],r[21])
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
	fl="STUDID ,ADMNO,NAME,CLCODE,ROLLNO,SECTION,DOB,SEX,GNAME,FNAME,MNAME,LADDR,PADDR,CATCODE,DOA,AGE,NOTERMS,PHONE,EML,M_PHONE,RELIGION,CASTE,NOFSB,F_OCCP,F_QUAL,M_QUAL,M_OCCP,SCHLAST,BLOODGROUP,HOUSE,MTOUNGE,BYBUS,REMARK,UPGRDYR,ADM_CL,PATH,BSDT,LIBFINE,TRANSMODE,G_OCCP,G_QUAL,FSERVADDR,MSERVADDR,GSERVADDR,FEE_PROF_CODE,GREL,GCOMP,CMP_PNO,CMP_DEPT,handicap,idmark,disease,tc_status,uidno,NSO,M_PHONE1,nso_dt,bus_stop,bnoterms,bmnth,feecomp,buscomp,lastupd" 
	sql="select %s from studmast where lastupd>'%s'" % (fl,updt)
	c.execute(sql)
	for r in c:
		sql="""insert into studmast(%s)
			values('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s') 
			on duplicate key update ADMNO='%s',NAME='%s',CLCODE='%s',ROLLNO='%s',SECTION='%s',DOB='%s',SEX='%s',GNAME='%s',FNAME='%s',MNAME='%s',LADDR='%s',PADDR='%s',CATCODE='%s',DOA='%s',AGE='%s',NOTERMS='%s',PHONE='%s',EML='%s',M_PHONE='%s',RELIGION='%s',CASTE='%s',NOFSB='%s',F_OCCP='%s',F_QUAL='%s',M_QUAL='%s',M_OCCP='%s',SCHLAST='%s',BLOODGROUP='%s',HOUSE='%s',MTOUNGE='%s',BYBUS='%s',REMARK='%s',UPGRDYR='%s',ADM_CL='%s',PATH='%s',BSDT='%s',LIBFINE='%s',TRANSMODE='%s',G_OCCP='%s',G_QUAL='%s',FSERVADDR='%s',MSERVADDR='%s',GSERVADDR='%s',FEE_PROF_CODE='%s',GREL='%s',GCOMP='%s',CMP_PNO='%s',CMP_DEPT='%s',handicap='%s',idmark='%s',disease='%s',tc_status='%s',uidno='%s',NSO='%s',M_PHONE1='%s',nso_dt='%s',bus_stop='%s',bnoterms='%s',bmnth='%s',feecomp='%s',buscomp='%s',lastupd='%s';\n""" % \
			(fl,r[0],r[1],r[2],r[3],r[4],r[5],r[6],r[7],r[8],r[9],r[10],r[11],r[12],r[13],r[14],r[15],r[16],r[17],r[18],r[19],r[20],r[21],r[22],r[23],r[24],r[25],r[26],r[27],r[28],r[29],r[30],r[31],r[32],r[33],r[34],r[35],r[36],r[37],r[38],r[39],r[40],r[41],r[42],r[43],r[44],r[45],r[46],r[47],r[48],r[49],r[50],r[51],r[52],r[53],r[54],r[55],r[56],r[57],r[58],r[59],r[60],r[61],r[62], \
			r[1],r[2],r[3],r[4],r[5],r[6],r[7],r[8],r[9],r[10],r[11],r[12],r[13],r[14],r[15],r[16],r[17],r[18],r[19],r[20],r[21],r[22],r[23],r[24],r[25],r[26],r[27],r[28],r[29],r[30],r[31],r[32],r[33],r[34],r[35],r[36],r[37],r[38],r[39],r[40],r[41],r[42],r[43],r[44],r[45],r[46],r[47],r[48],r[49],r[50],r[51],r[52],r[53],r[54],r[55],r[56],r[57],r[58],r[59],r[60],r[61],r[62],)
		sql=sql.replace("'None'",'NULL').replace('None','NULL')
		f.write(sql)
		#print sql
	#sql="update synctb set updt=now()"
	#f.write(sql)
	f.close()
	url='http://ssvm.symphonytek.com/fee/uploadsql.php'
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
except mysql.connector.Error:
	if not db is None:
		db.rollback()
	logging.exception("DB error")
	#print("DB error ",sys.exc_info()[0])
except:
	print(r.text+"Other error ",sys.exc_info()[0])
	logging.exception("Error")
