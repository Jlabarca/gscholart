#!/Python27/python
import scholar as gs
import json
import pickle
import cgi
import sys
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

sys.stdout.write("Content-Type: application/json")
sys.stdout.write("\n")
sys.stdout.write("\n")

result={}   
result['message']="Exito"
sys.stdout.write(json.dumps(result,indent=1))
sys.stdout.write("\n")

sys.stdout.close()   
#print ''   
#print 'Archivo creado'+data['consulta'].value+'aa'