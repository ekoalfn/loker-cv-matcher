#!/usr/bin/env node
import Firecrawl from 'firecrawl';
import { readFile, writeFile, mkdir } from 'node:fs/promises';
import { resolve } from 'node:path';

async function loadEnvValue(name) {
  if (process.env[name]) return process.env[name];

  try {
    const env = await readFile('.env', 'utf8');
    const line = env.split(/\r?\n/).find((entry) => entry.startsWith(`${name}=`));
    if (!line) return null;

    return line.slice(name.length + 1).trim().replace(/^['\"]|['\"]$/g, '');
  } catch {
    return null;
  }
}

const apiKey = await loadEnvValue('FIRECRAWL_API_KEY');
if (!apiKey) {
  console.error('Missing FIRECRAWL_API_KEY. Add it to .env or export it before running this script.');
  process.exit(1);
}

const rawTargets = process.argv.slice(2).filter((arg) => !arg.startsWith('-'));
const targets = rawTargets;
const urls = targets.length ? targets : [
  'https://lamaraja.web.id',
  'https://lamaraja.web.id/jobs',
  'https://lamaraja.web.id/cv-matcher',
  'https://www.jobstreet.co.id',
  'https://www.kalibrr.com',
  'https://glints.com/id',
  'https://www.kitalulus.com',
];

const app = new Firecrawl({ apiKey });
const outDir = resolve('storage/app/audits/firecrawl');
await mkdir(outDir, { recursive: true });

const stamp = new Date().toISOString().replace(/[:.]/g, '-');
const report = [];

for (const url of urls) {
  try {
    const result = await app.scrape(url, {
      formats: ['markdown'],
      onlyMainContent: true,
      timeout: 60000,
    });

    const markdown = result.markdown || result.data?.markdown || '';
    const metadata = result.metadata || result.data?.metadata || {};
    report.push({
      url,
      ok: true,
      title: metadata.title || null,
      description: metadata.description || null,
      statusCode: metadata.statusCode || null,
      markdownChars: markdown.length,
      sample: markdown.slice(0, 1200),
    });
  } catch (error) {
    report.push({
      url,
      ok: false,
      error: error?.message || String(error),
    });
  }
}

const path = resolve(outDir, `${stamp}.json`);
await writeFile(path, JSON.stringify({ generatedAt: new Date().toISOString(), report }, null, 2));

console.log(`Firecrawl audit saved: ${path}`);
for (const item of report) {
  console.log(`${item.ok ? 'OK' : 'FAIL'} ${item.url}${item.title ? ` - ${item.title}` : ''}`);
}
