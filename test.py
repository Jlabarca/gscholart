#!/Python27/python
import scholar as gs
import time
import sys,json
import pickle
import cgi
data=cgi.FieldStorage()
articles = gs.query(search=data['consulta'].value)
# format the articles
merged = [article.dumps() for article in articles]
formatted = {
    'dict':   lambda: merged,
    'json':   lambda: json.dumps(merged, indent=2, ensure_ascii=False),
    'pickle': lambda: pickle.dumps(merged),
}.get('json', lambda: None)()
# write the articles to file, or display them
with open('output.json', 'w') as f:
   f.write(formatted.encode('utf8'))

#time.sleep(3)
print ''   
print formatted.encode('utf8')