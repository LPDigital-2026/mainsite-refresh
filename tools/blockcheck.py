# Strict block-comment nesting check: every <!-- wp:x --> closes in order.
import re,sys
bad=0
for f in sys.argv[1:]:
    s=open(f).read(); stack=[]
    for m in re.finditer(r'<!-- (/?)wp:([a-z0-9/-]+)(?: (\{.*?\}))? ?(/?)-->',s):
        close,name,attrs,selfc=m.groups()
        if selfc: continue
        if not close: stack.append(name)
        else:
            if not stack or stack[-1]!=name: print(f,'BAD close',name,'expected',stack[-1:] ,'@',s.count('\n',0,m.start())+1); bad+=1; break
            stack.pop()
    d=len(re.findall(r'<div[ >]',s)); e=s.count('</div>')
    print(f, 'blocks OK' if not stack else 'UNCLOSED %s'%stack, '| divs', 'OK' if d==e else 'MISMATCH %d/%d'%(d,e))
    bad+= bool(stack) or d!=e
sys.exit(1 if bad else 0)
