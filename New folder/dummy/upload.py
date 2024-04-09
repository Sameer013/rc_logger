#!/usr/bin/python2.7
import urllib, json, logging, requests
url='http://jps.symphonytek.com/fee/uploadsql.php'
files = {'sql_file': open('data.sql','rb')}
values = {'DB': 'photcat', 'OUT': 'csv', 'SHORT': 'short'}
r = requests.post(url, files=files, data=values)
print r.text
#data=json.loads(r.text)
#(status,msg,dtm)=(data['status'],data['msg'],data['dtm'])