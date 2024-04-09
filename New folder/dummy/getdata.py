#!/usr/bin/python
import urllib.request,urllib.parse, json, logging
import mysql.connector
import sys,os
try: 
	db=None
	logging.basicConfig(filename='sync.log',level=logging.DEBUG,format='%(asctime)s %(message)s')
	logging.info('Sync started')
	db=mysql.connector.connect(user="root",passwd="Sania@2003",db="ssvm")
	c=db.cursor()
	c.execute("select updt,dndt from synctb limit 1")
	row=c.fetchone()
	updt=str(row[0])
	dndt=str(row[1])
	url="http://ssvm.symphonytek.com/fee/getfeedata.php"
	urlparams={ 'lastdtm':dndt }
	urlval=urllib.parse.urlencode(urlparams)
	response=urllib.request.urlopen(url+'?'+urlval)
	# print (dndt) 
	data=json.loads(response.read())
	
	for r in data['fee']:
		sql="""insert into fee(MR_NO,MR_DATE,BILLNO,STUDID,NOTERM,FEE_AMT,CASH,CHQ_NO,DRAWN_ON,CHQ_AMT,BRANCH,LATE_FEE,TERM,STD,SEC,REBFLG,REBATE,REBINFO,adhoc,uid,bnoterm,lastupd) 
			values('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')
				""" % (r['MR_NO'],r['MR_DATE'],r['BILLNO'],r['STUDID'],r['NOTERM'],r['FEE_AMT'],r['CASH'],r['CHQ_NO'],r['DRAWN_ON'],r['CHQ_AMT'],r['BRANCH'],r['LATE_FEE'],r['TERM'],r['STD'],r['SEC'],r['REBFLG'],r['REBATE'],r['adhoc'],r['uid'],r['bnoterm'],r['lastupd'])
		c.execute(sql)
		c.execute("update studmast set noterms=noterms+%s where studid=%s" % (r['NOTERM'],r['STUDID']))
	for r in data['fee_detail']:
		sql="insert into fee_detail(mr_no,ac_no,ac_amt,ac_amtpaid,lastupd) values('%s',%s,%s,%s,'%s')"%(r['MR_NO'],r['AC_NO'],r['AC_AMT'],r['AC_AMTPAID'],r['lastupd'])
		c.execute(sql)
	for r in data['adhocfee']:
		sql="update adhocfee set studid=%s,ac_no=%s,amount=%s,dt='%s',paid=%s,mr_no='%s',lastupd='%s' where slno=%s"%(r['studid'],r['ac_no'],r['amount'],r['dt'],r['paid'],r['mr_no'],r['lastupd'],r['slno'])
		print(sql)
		c.execute(sql)
	sql="update synctb set dndt='%s'" % data['dtm']
	c.execute(sql)
	db.commit()
	logging.info('Sync finished')
except mysql.connector.Error:
	if not db is None:
		db.rollback()
	logging.exception("DB error")
	logging.exception(response)
	logging.exception(sql)
	#print("DB error ",sys.exc_info()[0])
except:
	#print("Other error ",sys.exc_info()[0])
	logging.exception("Error")
