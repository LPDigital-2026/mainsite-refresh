#!/usr/bin/env python3
"""Builds page-content/blog-articles.html from lilypawdigital/inc/drafts/:
every starter article, readable, with its title, excerpt, category and tags,
and a copy button for the block content. Run from the repository root."""
import html, re

php = open('lilypawdigital/inc/drafts.php').read()
articles = []
for m in re.finditer(r"'([a-z0-9-]+)'\s*=>\s*array\(\s*'title'\s*=>\s*'(.*?)',\s*'file'\s*=>\s*'(.*?)',\s*'category'\s*=>\s*'(.*?)',\s*'tags'\s*=>\s*array\((.*?)\),\s*'excerpt'\s*=>\s*'(.*?)',", php, re.S):
    slug, title, file, cat, tags, excerpt = m.groups()
    tags = re.findall(r"'(.*?)'", tags)
    body = open(f'lilypawdigital/inc/drafts/{file}').read()
    words = len(re.sub(r'<[^>]+>', ' ', body).split())
    articles.append((slug, title, cat, tags, excerpt, body, words))

def card(i, a):
    slug, title, cat, tags, excerpt, body, words = a
    shown = re.sub(r'<!--.*?-->', '', body, flags=re.S)
    return f'''
<details class="post" {'open' if i == 0 else ''}>
  <summary><span class="n">{i + 1}</span> <span class="t">{html.escape(title)}</span> <span class="w">{words} words, about {max(1, round(words / 220))} min read</span></summary>
  <dl>
    <dt>Title</dt><dd>{html.escape(title)}</dd>
    <dt>Slug</dt><dd>{slug} <small>(the end of the article's web address; set automatically when added from Page Setup)</small></dd>
    <dt>Category</dt><dd>{html.escape(cat)}</dd>
    <dt>Tags</dt><dd>{html.escape(', '.join(tags))}</dd>
    <dt>Excerpt</dt><dd>{html.escape(excerpt)} <small>({len(excerpt)} characters: shown in Google results)</small></dd>
  </dl>
  <article>{shown}</article>
  <textarea id="a{i}" readonly>{html.escape(body)}</textarea>
  <button data-t="a{i}">Copy article content (for the code editor)</button>
</details>'''

page = f'''<!doctype html><html lang="en-GB"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Lily Paw Digital - starter blog articles</title>
<style>
body{{margin:0;background:#f5f1fb;color:#1d0e38;font:17px/1.65 system-ui,-apple-system,"Segoe UI",sans-serif}}
main{{max-width:820px;margin:0 auto;padding:40px 20px 80px}}
h1{{font-size:2rem;letter-spacing:-.03em;margin:0 0 8px}} .lead{{color:#564a6c;margin:0 0 28px}}
.post{{background:#fff;border-radius:18px;margin:0 0 14px;padding:0 24px}} summary{{cursor:pointer;padding:18px 0;font-weight:600;display:flex;gap:10px;align-items:baseline;flex-wrap:wrap}}
.n{{color:#b3276b}} .w{{color:#564a6c;font-weight:400;font-size:.9rem}}
dl{{display:grid;grid-template-columns:110px 1fr;gap:6px 14px;margin:0 0 20px;padding:16px;background:#f5f1fb;border-radius:12px;font-size:.95rem}} dt{{font-weight:600}} dd{{margin:0}} small{{color:#564a6c}}
article{{border-top:1px solid #e5def0;padding-top:12px}} article h2{{font-size:1.4rem;margin:1.6em 0 .4em}} article h3{{font-size:1.15rem}} article a{{color:#b3276b}}
textarea{{width:100%;height:110px;margin:16px 0 8px;font:12px/1.4 ui-monospace,monospace;border:1px solid #d8d0e6;border-radius:8px;padding:8px}}
button{{margin:0 0 22px;padding:10px 18px;border:0;border-radius:999px;background:#f0529c;color:#1d0e38;font-weight:600;cursor:pointer}}
</style></head><body><main>
<h1>Starter blog articles</h1>
<p class="lead">{len(articles)} articles about your services. The easiest way to use them: upload the theme, go to <strong>Appearance → Page Setup</strong> and press <strong>Add draft articles</strong>. They arrive as drafts with their titles, excerpts, categories and tags filled in. Read each one, change anything that is not right, add a featured image, then publish. This page is the same articles to read first, or to paste in by hand.</p>
{''.join(card(i, a) for i, a in enumerate(articles))}
</main><script>document.querySelectorAll('button[data-t]').forEach(b=>b.onclick=()=>{{const t=document.getElementById(b.dataset.t);t.select();navigator.clipboard.writeText(t.value);b.textContent='Copied';setTimeout(()=>b.textContent='Copy article content (for the code editor)',1500)}})</script>
</body></html>'''
open('page-content/blog-articles.html', 'w').write(page)
print(len(articles), 'articles,', sum(a[6] for a in articles), 'words')
