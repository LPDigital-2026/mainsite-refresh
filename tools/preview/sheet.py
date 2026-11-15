import sys,glob
from PIL import Image
pre,cols,scale,out=sys.argv[1],int(sys.argv[2]),float(sys.argv[3]),sys.argv[4]
fs=sorted(glob.glob(pre+'-*.png'))
start=int(sys.argv[5]) if len(sys.argv)>5 else 0; n=int(sys.argv[6]) if len(sys.argv)>6 else len(fs)
fs=fs[start:start+n]
ims=[Image.open(f) for f in fs]
w,h=int(ims[0].width*scale),int(ims[0].height*scale)
rows=(len(ims)+cols-1)//cols
sh=Image.new('RGB',(cols*w+(cols-1)*8,rows*h+(rows-1)*8),'#ff00ff')
for i,im in enumerate(ims): sh.paste(im.resize((w,h)).convert('RGB'),((i%cols)*(w+8),(i//cols)*(h+8)))
sh.save(out)
