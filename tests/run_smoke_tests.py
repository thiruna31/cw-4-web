"""CW04 repeatable checks. Uses fictional data in a temporary copy, not CODD."""
from pathlib import Path
import subprocess, shutil, tempfile, socket, time, urllib.request, urllib.parse, csv, json

source=Path(__file__).resolve().parents[1]
with tempfile.TemporaryDirectory(prefix='cw04-local-test-') as tmp:
    sandbox=Path(tmp)/'site'
    shutil.copytree(source, sandbox, ignore=shutil.ignore_patterns('tests','*.docx'))
    (sandbox/'data'/'submissions.csv').write_text('')
    sock=socket.socket(); sock.bind(('127.0.0.1',0)); port=sock.getsockname()[1]; sock.close()
    proc=subprocess.Popen(['php','-S',f'127.0.0.1:{port}','-t',str(sandbox)],stdout=subprocess.DEVNULL,stderr=subprocess.DEVNULL)
    time.sleep(.5)
    results=[]
    capture=[]
    def send(path, data=None):
        binary=urllib.parse.urlencode(data,doseq=True).encode() if data is not None else None
        if path == 'formtest.php' and data is not None:
            capture.append({'method':'POST', 'path':path, 'content_type':'application/x-www-form-urlencoded', 'encoded_body':binary.decode(), 'parsed_fields':urllib.parse.parse_qs(binary.decode(), keep_blank_values=True)})
        req=urllib.request.Request(f'http://127.0.0.1:{port}/{path}',data=binary)
        return urllib.request.urlopen(req,timeout=10).read().decode()
    def check(name, ok, note): results.append({'test':name,'pass':bool(ok),'observation':note})
    def count():
        with open(sandbox/'data'/'submissions.csv',newline='') as f: return len(list(csv.reader(f)))
    try:
        hello=send('hello.php')
        check('PHP warm-up', 'Hello world!' in hello and 'Quoted text: rand(1, 10)' in hello and 'Random integer (1-10):' in hello, 'Hello world, numeric random paragraph, and literal rand(1, 10) are present')
        home=send('index.php')
        check('Home', 'formtest.html' in home and 'buy_grade.php' in home,'Links to both required activities appear')
        base={'fictional_name':'Demo Student'}
        out=send('formtest.php',base)
        check('T01 no optional features','No optional features were selected.' in out,'No selection produces clear message')
        out=send('formtest.php',{**base,'features[]':'Chat'})
        check('T01 one selection','1 feature(s) selected' in out and '<li>Chat</li>' in out,'Chat appears once')
        out=send('formtest.php',{**base,'features[]':['Chat','Email','Shell']})
        check('T01 three selections',all(f'<li>{x}</li>' in out for x in ['Chat','Email','Shell']) and '3 feature(s)' in out,'Chat, Email and Shell appear')
        out=send('formtest.php',{**base,'features':'Chat'})
        check('T02 malformed scalar','Invalid feature format' in out,'Scalar selection rejected without warning')
        out=send('formtest.php',{**base,'features[]':['Chat','Intruder']})
        check('T02 unknown selection','Unknown or malformed feature' in out,'Unknown option rejected')
        out=send('formtest.php',{**base,'fictional_name':'<script>alert(1)</script>','features[]':['Email']})
        check('Escaped output', '<script>alert(1)</script>' not in out and '&lt;script&gt;alert(1)&lt;/script&gt;' in out, 'Script-looking input displayed as text')
        valid={'student_name':'Demo Student','student_email':'demo@example.test','student_id':'DEMO1234','requested_grade':'B'}
        before=count(); out=send('process_grade.php',valid); after=count()
        check('Valid four-field submission','Practice request saved' in out and after==before+2,'Success confirmation; CSV header and one record written')
        out=send('process_grade.php',{**valid,'student_email':'not-an-email'}); next_count=count()
        check('Invalid email','Please correct the form' in out and next_count==after,'Validation error; row count unchanged')
        out=send('process_grade.php',{**valid,'student_name':''}); next_count2=count()
        check('Missing name','Please correct the form' in out and next_count2==after,'Validation error; row count unchanged')
        out=send('process_grade.php',{**valid,'student_id':'A!'}); next_count3=count()
        check('Invalid student ID','Please correct the form' in out and next_count3==after,'Validation error; row count unchanged')
        out=send('process_grade.php',{**valid,'requested_grade':'Z'}); next_count4=count()
        check('Invalid grade','Please correct the form' in out and next_count4==after,'Validation error; row count unchanged')
        out=send('process_grade.php',{**valid,'student_name':'<b>Demo</b>'}); more=count()
        check('Safe HTML display','&lt;b&gt;Demo&lt;/b&gt;' in out and more==after+1,'HTML markup escaped; valid row written')
        out=send('process_grade.php',valid); last=count()
        check('Second valid append','Practice request saved' in out and last==more+1,'Second valid submission appends exactly one row')
        out=send('process_grade.php')
        check('GET to processor','Submit the form first' in out,'GET without form is not saved')
        check('CSV final row count',last==4, f'{last} CSV rows (1 header and 3 successful records) in isolated temporary test')
        stats=send('mission_records.php')
        check('Extra record-count audit','Total valid practice records:' in stats and '<strong>3</strong>' in stats, 'Read-only audit reports three accepted records without exposing IDs/emails')
        preview=send('mission_preview.php', {'message':'<script>alert(1)</script>'})
        check('Extra escaped preview', '&lt;script&gt;alert(1)&lt;/script&gt;' in preview and '<script>alert(1)</script>' not in preview, 'Unsafe-looking HTML was displayed as plain text without saving')
        preview_bad=send('mission_preview.php', {'message':''})
        check('Extra preview validation', 'Enter 1–300 characters of fictional text.' in preview_bad, 'Missing preview text gets a controlled error')
        sess=send('mission_session.php')
        check('Extra session visit counter', 'Visits from this browser session:' in sess, 'PHP session starts before HTML and displays a counter')
        if (source/'tests'/'POST_CAPTURE_LOCAL.json').parent.exists():
            (source/'tests'/'POST_CAPTURE_LOCAL.json').write_text(json.dumps({'environment':'PHP local HTTP requests (not browser DevTools / not CODD)','requests':capture}, indent=2))

    finally: proc.terminate(); proc.wait(timeout=5)
    print(json.dumps({'runtime':'PHP CLI local web server (not CODD)','results':results,'passed':sum(x['pass'] for x in results),'total':len(results)},indent=2))
    if not all(x['pass'] for x in results): raise SystemExit(1)
