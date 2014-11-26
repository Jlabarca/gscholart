#!/Python27/python
import gscholart as gs
import time
import sys,json
import pickle
import cgi
data=cgi.FieldStorage()
fetcher = gs.Fetcher();
for x in range(0, 1):
	articles = gs.query("",""+data['journal'].value,0+20*x,20,fetcher)
	# format the articles
	merged = [article.dumps() for article in articles]
	formatted = {
		'dict':   lambda: merged,
		'json':   lambda: json.dumps(merged, indent=2, ensure_ascii=False),
		'pickle': lambda: pickle.dumps(merged),
	}.get('json', lambda: None)()
	#time.sleep(1)
	
#time.sleep(3)
print ''   
print formatted.encode('utf8')