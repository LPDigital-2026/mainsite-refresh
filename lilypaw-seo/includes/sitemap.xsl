<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:s="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
<xsl:output method="html" encoding="UTF-8" indent="yes"/>
<xsl:template match="/">
<html lang="en-GB"><head><meta charset="utf-8"/><meta name="viewport" content="width=device-width,initial-scale=1"/><meta name="robots" content="noindex"/><title>Sitemap - Lily Paw Digital</title>
<style>
body{margin:0;background:#1d0e38;color:#fbf7f2;font:16px/1.6 system-ui,-apple-system,"Segoe UI",sans-serif}
main{max-width:1000px;margin:0 auto;padding:48px 20px}
h1{margin:0 0 8px;font-size:2.2rem;letter-spacing:-.03em}
p{margin:0 0 28px;color:rgba(251,247,242,.75)}
table{width:100%;border-collapse:collapse;background:#2a1650;border-radius:16px;overflow:hidden}
th,td{padding:12px 16px;text-align:left;border-bottom:1px solid rgba(251,247,242,.12);font-size:.95rem}
th{font-weight:600;color:#ff8fc2}
a{color:#fbf7f2;word-break:break-all}
td.d{white-space:nowrap;color:rgba(251,247,242,.75)}
</style></head><body><main>
<h1>Sitemap</h1>
<p>This is the list of pages Lily Paw Digital gives to search engines such as Google and Bing. It is meant for them, but you are welcome to look.</p>
<xsl:choose>
<xsl:when test="s:sitemapindex">
<table><tr><th>Sitemap</th><th>Last changed</th></tr>
<xsl:for-each select="s:sitemapindex/s:sitemap"><tr><td><a href="{s:loc}"><xsl:value-of select="s:loc"/></a></td><td class="d"><xsl:value-of select="substring(s:lastmod,1,10)"/></td></tr></xsl:for-each>
</table>
</xsl:when>
<xsl:otherwise>
<table><tr><th>Address (<xsl:value-of select="count(s:urlset/s:url)"/>)</th><th>Picture</th><th>Last changed</th></tr>
<xsl:for-each select="s:urlset/s:url"><tr><td><a href="{s:loc}"><xsl:value-of select="s:loc"/></a></td><td class="d"><xsl:if test="image:image">Yes</xsl:if></td><td class="d"><xsl:value-of select="substring(s:lastmod,1,10)"/></td></tr></xsl:for-each>
</table>
</xsl:otherwise>
</xsl:choose>
</main></body></html>
</xsl:template>
</xsl:stylesheet>
