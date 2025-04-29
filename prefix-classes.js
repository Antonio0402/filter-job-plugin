// prefix-classes.js
const fs = require('fs');
const path = require('path');

const PREFIX = 'job-filter-';
const IGNORE_PATTERNS = [/^is-/, /^has-/];
const FILE_EXTENSIONS = ['.php', '.html'];
const TARGET_DIR = './'; // Default directory to start searching


function shouldIgnore(className) {
  return IGNORE_PATTERNS.some(pattern => pattern.test(className));
}

function prefixClassesInContent(content) {
  return content.replace(/class\s*=\s*["']([^"']+)["']/g, (match, classList) => {
    const newClassList = classList
      .split(/\s+/)
      .map(cls => (cls === '|' || shouldIgnore(cls) || cls.startsWith(PREFIX) ? cls : PREFIX + cls))
      .join(' ');
    return `class="${newClassList}"`;
  });
}

function processFile(filePath) {
  const content = fs.readFileSync(filePath, 'utf8');
  const newContent = prefixClassesInContent(content);
  if (content !== newContent) {
    fs.writeFileSync(filePath, newContent, 'utf8');
    console.log(`Prefixed classes in: ${filePath}`);
  }
}

function walkDir(dir) {
  fs.readdirSync(dir).forEach(file => {
    const fullPath = path.join(dir, file);
    if (fs.statSync(fullPath).isDirectory()) {
      walkDir(fullPath);
    } else if (FILE_EXTENSIONS.includes(path.extname(fullPath))) {
      processFile(fullPath);
    }
  });
}

walkDir(TARGET_DIR);