#!/Python27/python
import cgi
data=cgi.FieldStorage()
print "Content-type: text/html"
print ""
print "Hello, Woriild"+data['buscar'].value+"a"