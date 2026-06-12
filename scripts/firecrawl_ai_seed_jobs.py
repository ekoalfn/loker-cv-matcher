#!/usr/bin/env python3
import os, json, re, time, html, urllib.request
from datetime import datetime, timezone
from urllib.parse import urlparse, urldefrag

FC=(os.getenv('FIRECRAWL_API_KEY') or '').strip()
OR=(os.getenv('OPENROUTER_API_KEY') or '').strip()
MODEL=(os.getenv('AI_MODEL_SUMMARIZER','meta-llama/llama-3.1-8b-instruct:free') or '').strip()
MAX_JOBS=int(os.getenv('MAX_JOBS','1000'))
if not FC: raise SystemExit('Missing FIRECRAWL_API_KEY')
if not OR: raise SystemExit('Missing OPENROUTER_API_KEY')
UA={
    'User-Agent':'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36',
    'Accept':'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
    'Accept-Language':'id-ID,id;q=0.9,en-US;q=0.8,en;q=0.7',
    'Connection':'keep-alive',
}
TARGETS=os.getenv('JOB_TARGETS','https://dealls.com/loker/lokasi,https://dealls.com/loker,https://www.kalibrr.com/job-board/te/Indonesia,https://glints.com/id/job-category/software-engineering-jobs,https://weworkremotely.com/remote-jobs,https://arc.dev/remote-jobs,https://getonbrd.com/jobs').split(',')
GREENHOUSE_BOARDS=[x.strip() for x in os.getenv('GREENHOUSE_BOARDS','xendit').split(',') if x.strip()]
LINKEDIN_SEARCHES=[x.strip() for x in os.getenv('LINKEDIN_SEARCHES','https://www.linkedin.com/jobs-guest/jobs/api/seeMoreJobPostings/search?keywords=&location=Indonesia').split(',') if x.strip()]
LINKEDIN_PAGES=int(os.getenv('LINKEDIN_PAGES','5'))
HIMALAYAS_LIMIT=int(os.getenv('HIMALAYAS_LIMIT','50'))
REMOTIVE_LIMIT=int(os.getenv('REMOTIVE_LIMIT','50'))
REMOTIVE_CATEGORIES=os.getenv('REMOTIVE_CATEGORIES','software-dev,devops-sysadmin,product,design').split(',')
EMP={'full-time','part-time','contract','internship','freelance'}


def post_json(url,payload,headers,timeout=120):
    req=urllib.request.Request(url,data=json.dumps(payload).encode(),headers={'Content-Type':'application/json',**headers})
    with urllib.request.urlopen(req,timeout=timeout) as r: return json.loads(r.read().decode())

def scrape(url):
    res=post_json('https://api.firecrawl.dev/v1/scrape',{'url':url,'formats':['markdown','links'],'onlyMainContent':True,'timeout':60000},{'Authorization':'Bearer '+FC})
    d=res.get('data') or res
    return d.get('markdown') or '', d.get('links') or []

def get_text(url, timeout=30):
    req=urllib.request.Request(url,headers=UA)
    with urllib.request.urlopen(req,timeout=timeout) as r: return r.read().decode('utf-8','ignore')

def dealls_location_targets():
    targets=[]
    if not any(t.rstrip('/') == 'https://dealls.com/loker/lokasi' for t in TARGETS):
        return []
    try:
        md_text, links_fc = scrape('https://dealls.com/loker/lokasi')
        for lk in links_fc:
            u = lk if isinstance(lk, str) else lk.get('url', '')
            if '/loker/lokasi/' in u:
                if u.startswith('/'): u = 'https://dealls.com' + u
                if u not in targets: targets.append(u)
        for m in re.finditer(r'https://dealls\.com/loker/lokasi/[\w-]+', md_text):
            u = m.group(0)
            if u not in targets: targets.append(u)
        print('DEALLS_LOCATIONS', len(targets), flush=True)
    except Exception as e:
        print('DEALLS_LOCATIONS_FAIL', str(e)[:120], flush=True)
    return targets

def greenhouse_urls():
    urls=[]
    for board in GREENHOUSE_BOARDS:
        api=f'https://boards-api.greenhouse.io/v1/boards/{board}/jobs?content=true'
        try:
            data=json.loads(get_text(api,30))
            for j in data.get('jobs',[]):
                u=j.get('absolute_url')
                if u and u.startswith('http'): urls.append(u)
            print('GREENHOUSE',board,len(urls),flush=True)
        except Exception as e:
            print('GREENHOUSE_FAIL',board,str(e)[:120],flush=True)
    return urls

def linkedin_urls():
    urls=[]
    for base in LINKEDIN_SEARCHES:
        for i in range(LINKEDIN_PAGES):
            sep='&' if '?' in base else '?'
            u=base if 'start=' in base else base+sep+'start='+str(i*10)
            if 'start=' in base: u=re.sub(r'start=\d+', 'start='+str(i*10), u)
            try:
                text=get_text(u,30)
                found=re.findall(r'href="(https://[^"?]+/jobs/view/[^"?]+)', text)
                for f in found:
                    f=html.unescape(f)
                    if f not in urls: urls.append(f)
                print('LINKEDIN_PAGE',i,len(found),flush=True)
                time.sleep(0.5)
            except Exception as e:
                print('LINKEDIN_FAIL',str(e)[:120],flush=True); break
    return urls

def himalayas_jobs():
    """Himalayas API publik - structured data, logo CDN tersedia."""
    jobs_out=[]
    try:
        url=f'https://himalayas.app/jobs/api?limit={HIMALAYAS_LIMIT}'
        data=json.loads(get_text(url,30))
        raw=data.get('jobs',[])
        print('HIMALAYAS_API', len(raw), 'jobs', flush=True)
        for j in raw:
            try:
                source_url=j.get('applicationLink') or j.get('guid','')
                if not source_url or not source_url.startswith('http'): continue
                logo=j.get('companyLogo','')
                if not logo or not logo.startswith('http'): continue
                desc_html=j.get('description','')
                desc=re.sub(r'<[^>]+>','',desc_html).strip()
                desc=re.sub(r'\n{3,}','\n\n',desc)
                if len(desc)<300: continue
                emp_raw=(j.get('employmentType') or 'full_time').lower().replace('_','-')
                emp='full-time'
                if 'part' in emp_raw: emp='part-time'
                elif 'contract' in emp_raw or 'contractor' in emp_raw: emp='contract'
                elif 'intern' in emp_raw: emp='internship'
                elif 'freelance' in emp_raw: emp='freelance'
                sal_min=j.get('minSalary'); sal_max=j.get('maxSalary')
                currency=j.get('currency','USD')
                loc=', '.join(j.get('locationRestrictions',[]) or ['Remote'])[:255]
                tags=[c.lower().replace('-',' ')[:40] for c in (j.get('categories') or [])[:5]]
                excerpt=j.get('excerpt','') or ''
                job={
                    'title': str(j.get('title',''))[:255],
                    'company': str(j.get('companyName',''))[:255],
                    'company_logo': logo,
                    'location': loc,
                    'employment_type': emp,
                    'salary_min': int(sal_min) if sal_min else None,
                    'salary_max': int(sal_max) if sal_max else None,
                    'salary_currency': currency,
                    'description_raw': desc,
                    'summary_ai': re.sub(r'\s+',' ',excerpt or desc[:280])[:500],
                    'tags': tags,
                    'source_url': source_url,
                    'expires_at': None,
                }
                jobs_out.append(job)
            except Exception as e:
                print('HIMALAYAS_JOB_ERR', str(e)[:80], flush=True)
        print('HIMALAYAS_VALID', len(jobs_out), flush=True)
    except Exception as e:
        print('HIMALAYAS_FAIL', str(e)[:120], flush=True)
    return jobs_out

def remotive_jobs():
    """Remotive API publik - structured, ada logo URL."""
    jobs_out=[]
    for cat in REMOTIVE_CATEGORIES:
        cat=cat.strip()
        if not cat: continue
        try:
            url=f'https://remotive.com/api/remote-jobs?category={cat}&limit={REMOTIVE_LIMIT}'
            data=json.loads(get_text(url,30))
            raw=data.get('jobs',[])
            print(f'REMOTIVE_API {cat}', len(raw), 'jobs', flush=True)
            for j in raw:
                try:
                    source_url=j.get('url','')
                    if not source_url or not source_url.startswith('http'): continue
                    logo=j.get('company_logo','')
                    if not logo or not logo.startswith('http'): continue
                    desc_html=j.get('description','')
                    desc=re.sub(r'<[^>]+>','',desc_html).strip()
                    desc=re.sub(r'\n{3,}','\n\n',desc)
                    if len(desc)<300: continue
                    emp_raw=(j.get('job_type') or 'full_time').lower().replace('_','-')
                    emp='full-time'
                    if 'part' in emp_raw: emp='part-time'
                    elif 'contract' in emp_raw: emp='contract'
                    elif 'intern' in emp_raw: emp='internship'
                    elif 'freelance' in emp_raw: emp='freelance'
                    loc=(j.get('candidate_required_location') or 'Remote')[:255]
                    tags=[t.lower()[:40] for t in (j.get('tags') or [])[:5]]
                    job={
                        'title': str(j.get('title',''))[:255],
                        'company': str(j.get('company_name',''))[:255],
                        'company_logo': logo,
                        'location': loc,
                        'employment_type': emp,
                        'salary_min': None,
                        'salary_max': None,
                        'salary_currency': 'USD',
                        'description_raw': desc,
                        'summary_ai': re.sub(r'\s+',' ',desc[:280])[:500],
                        'tags': tags,
                        'source_url': source_url,
                        'expires_at': None,
                    }
                    jobs_out.append(job)
                except Exception as e:
                    print('REMOTIVE_JOB_ERR', str(e)[:80], flush=True)
        except Exception as e:
            print(f'REMOTIVE_FAIL {cat}', str(e)[:120], flush=True)
        time.sleep(0.5)
    print('REMOTIVE_VALID', len(jobs_out), flush=True)
    return jobs_out

def links_from(md,links):
    raw=set([x if isinstance(x,str) else x.get('url','') for x in links])
    raw |= set(re.findall(r'https?://[^\s)\]"\']+',md))
    out=[]
    for u in raw:
        u=urldefrag(u.rstrip('.,#'))[0]
        l=u.lower()
        if '/loker/lokasi/' in l: continue
        if any(s in l for s in ['/loker/','/job/','/jobs/','/career','/careers']) and not any(x in l for x in ['login','register','apply?','/search','/category']): out.append(u)
    return sorted(set(out))[:120]

def ai_extract(url, md):
    text=md[:12000]
    prompt=f"""
Ekstrak 1 lowongan kerja dari markdown Firecrawl berikut. Output JSON object murni saja, tanpa markdown.
Schema WAJIB persis:
{{"title":"","company":"","company_logo":"https://...","location":"","employment_type":"full-time|part-time|contract|internship|freelance","salary_min":null,"salary_max":null,"salary_currency":"IDR","description_raw":"","summary_ai":"","tags":[""],"source_url":"{url}","expires_at":null}}
Rules:
- Hanya valid job post aktif dari URL ini.
- Semua field DB Lamaraja harus cocok: title/company/source_url wajib; location/description/company_logo wajib agar tampil bagus.
- company_logo harus URL image/logo perusahaan dari markdown. Jangan pakai icon/social/avatar jika bukan logo.
- description_raw pakai Bahasa Indonesia, rapi markdown, mencakup tugas+kualifikasi jika ada.
- employment_type hanya salah satu enum. Jika tidak jelas full-time.
- salary integer IDR atau null.
- tags max 5, lowercase.
- Jika bukan halaman detail lowongan valid, return {{}}.
URL: {url}
MARKDOWN:
{text}
"""
    base=(os.getenv('OPENROUTER_API_ENDPOINT') or os.getenv('OPENROUTER_BASE_URL') or 'https://openrouter.ai/api/v1').strip()
    res=post_json(base.rstrip('/')+'/chat/completions',{
        'model':MODEL,'temperature':0.1,'max_tokens':1800,'stream':False,
        'messages':[{'role':'user','content':prompt}]
    },{'Authorization':'Bearer '+OR,'HTTP-Referer':'https://lamaraja.web.id','X-Title':'Lamaraja'},180)
    c=res['choices'][0]['message']['content'].strip()
    m=re.search(r'```(?:json)?\s*([\s\S]*?)```',c)
    if m: c=m.group(1).strip()
    m=re.search(r'\{[\s\S]*\}',c)
    if m: c=m.group(0)
    return json.loads(c)

def head(url):
    try:
        req=urllib.request.Request(url,method='HEAD',headers=UA)
        with urllib.request.urlopen(req,timeout=15) as r: return r.status, r.headers.get('Content-Type','')
    except Exception: return 0,''

def valid(job,url):
    if not isinstance(job,dict) or not job: return None
    for k in ['title','company','location','description_raw','source_url','company_logo']:
        if not isinstance(job.get(k),str) or not job[k].strip(): return None
    if job['source_url'] != url: job['source_url']=url
    if not job['source_url'].startswith('http'): return None
    if job.get('employment_type') not in EMP: job['employment_type']='full-time'
    if len(job['description_raw']) < 300: return None
    st,ct=head(job['source_url'])
    if st and not (200 <= st < 400): return None
    st,ct=head(job['company_logo'])
    if not (ct.startswith('image/') or re.search(r'\.(png|jpe?g|webp|svg)(\?.*)?$',job['company_logo'],re.I)): return None
    job['title']=job['title'][:255]; job['company']=job['company'][:255]; job['location']=job['location'][:255]
    job['salary_currency']=job.get('salary_currency') or 'IDR'
    job['summary_ai']=(job.get('summary_ai') or re.sub(r'[#*`>-]','',job['description_raw'])[:280]).strip()[:500]
    tags=job.get('tags') if isinstance(job.get('tags'),list) else []
    job['tags']=[str(t).lower()[:40] for t in tags if str(t).strip()][:5]
    job['expires_at']=None
    return job

def valid_direct(job):
    """Validasi job dari API langsung (Himalayas/Remotive) - sudah structured."""
    if not isinstance(job,dict) or not job: return None
    for k in ['title','company','location','description_raw','source_url','company_logo']:
        if not isinstance(job.get(k),str) or not job[k].strip(): return None
    if not job['source_url'].startswith('http'): return None
    if job.get('employment_type') not in EMP: job['employment_type']='full-time'
    if len(job['description_raw']) < 300: return None
    # Logo: coba HEAD check, fallback ke pattern match (beberapa CDN reject HEAD)
    st,ct=head(job['company_logo'])
    logo_ok=ct.startswith('image/') or re.search(r'\.(png|jpe?g|webp|svg)(\?.*)?$',job['company_logo'],re.I)
    if not logo_ok and not re.search(r'cdn|logo|image|img',job['company_logo'],re.I):
        return None
    job['title']=job['title'][:255]; job['company']=job['company'][:255]; job['location']=job['location'][:255]
    job['salary_currency']=job.get('salary_currency') or 'USD'
    job['summary_ai']=(job.get('summary_ai') or re.sub(r'[#*`>-]','',job['description_raw'])[:280]).strip()[:500]
    tags=job.get('tags') if isinstance(job.get('tags'),list) else []
    job['tags']=[str(t).lower()[:40] for t in tags if str(t).strip()][:5]
    job['expires_at']=None
    return job

jobs=[]; seen=set()
expanded_targets=[]
for t in TARGETS:
    t=t.strip()
    if not t: continue
    if t.rstrip('/') == 'https://dealls.com/loker/lokasi': expanded_targets.extend(dealls_location_targets())
    else: expanded_targets.append(t)

for url in greenhouse_urls()+linkedin_urls():
    if url and url not in seen: expanded_targets.append(url)

# Sumber API langsung - Himalayas & Remotive (structured, tanpa Firecrawl per-job)
print('=== HIMALAYAS + REMOTIVE API ===', flush=True)
for job in himalayas_jobs()+remotive_jobs():
    if len(jobs)>=MAX_JOBS: break
    url=job.get('source_url','')
    if not url or url in seen: continue
    seen.add(url)
    j=valid_direct(job)
    if j:
        jobs.append(j); print('OK_API',len(jobs),j['title'],flush=True)
    else:
        print('REJECT_API',url,flush=True)

# Sumber Firecrawl scrape
print('=== FIRECRAWL SCRAPE TARGETS ===', flush=True)
for target in expanded_targets:
    if len(jobs)>=MAX_JOBS: break
    print('INDEX',target,flush=True)
    try: md,links=scrape(target)
    except Exception as e: print('INDEX_FAIL',e,flush=True); continue
    candidate_urls = [target] if re.search(r'(greenhouse|linkedin\.com/jobs/view/|linkedin\.com/.+/jobs/view/)', target, re.I) else links_from(md,links)
    for url in candidate_urls:
        url=urldefrag(url)[0]
        if url in seen or len(jobs)>=MAX_JOBS: continue
        seen.add(url)
        try:
            print('DETAIL',url,flush=True)
            dmd,_=scrape(url)
            job=valid(ai_extract(url,dmd),url)
            if job:
                jobs.append(job); print('OK',len(jobs),job['title'],flush=True)
            else: print('REJECT',url,flush=True)
            time.sleep(1)
        except Exception as e: print('FAIL',url,str(e)[:160],flush=True)
    if len(jobs)>=MAX_JOBS: break

payload={'source':'firecrawl-ai-trusted-portals','scraped_at':datetime.now(timezone.utc).isoformat(),'jobs':jobs}
print(json.dumps(payload,ensure_ascii=False,indent=2))
