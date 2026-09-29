/* ==========================================================================
   ElitePanel — Simple Markdown Editor
   Wraps selected text with markdown syntax + live preview + counter.
   ========================================================================== */
(function () {
  'use strict';

  function getTextarea(target) {
    if (!target) return null;
    return document.querySelector(target);
  }

  /* Wrap or insert markdown around the textarea selection */
  function applyMd(textarea, action) {
    if (!textarea) return;
    var start = textarea.selectionStart;
    var end = textarea.selectionEnd;
    var value = textarea.value;
    var selected = value.substring(start, end);
    var before = value.substring(0, start);
    var after = value.substring(end);
    var inserted = '';
    var caretOffset = 0;

    switch (action) {
      case 'bold':
        inserted = '**' + (selected || 'bold text') + '**';
        caretOffset = selected ? inserted.length : 2;
        break;
      case 'italic':
        inserted = '*' + (selected || 'italic text') + '*';
        caretOffset = selected ? inserted.length : 1;
        break;
      case 'strike':
        inserted = '~~' + (selected || 'strikethrough') + '~~';
        caretOffset = selected ? inserted.length : 2;
        break;
      case 'h1':
        inserted = (before.endsWith('\n') || before === '' ? '' : '\n') + '# ' + (selected || 'Heading 1');
        caretOffset = inserted.length;
        break;
      case 'h2':
        inserted = (before.endsWith('\n') || before === '' ? '' : '\n') + '## ' + (selected || 'Heading 2');
        caretOffset = inserted.length;
        break;
      case 'h3':
        inserted = (before.endsWith('\n') || before === '' ? '' : '\n') + '### ' + (selected || 'Heading 3');
        caretOffset = inserted.length;
        break;
      case 'ul':
        inserted = (selected ? selected.split('\n').map(function (l) { return '- ' + l; }).join('\n') : '- list item');
        if (!before.endsWith('\n') && before !== '') inserted = '\n' + inserted;
        caretOffset = inserted.length;
        break;
      case 'ol':
        inserted = (selected ? selected.split('\n').map(function (l, i) { return (i + 1) + '. ' + l; }).join('\n') : '1. list item');
        if (!before.endsWith('\n') && before !== '') inserted = '\n' + inserted;
        caretOffset = inserted.length;
        break;
      case 'quote':
        inserted = (selected ? selected.split('\n').map(function (l) { return '> ' + l; }).join('\n') : '> quoted text');
        if (!before.endsWith('\n') && before !== '') inserted = '\n' + inserted;
        caretOffset = inserted.length;
        break;
      case 'link':
        var url = window.prompt('Link URL', 'https:///');
        if (url === null) return;
        inserted = '[' + (selected || 'link text') + '](' + url + ')';
        caretOffset = inserted.length;
        break;
      case 'image':
        var img = window.prompt('Image URL', 'https:///');
        if (img === null) return;
        inserted = '![' + (selected || 'alt text') + '](' + img + ')';
        caretOffset = inserted.length;
        break;
      case 'code':
        inserted = '`' + (selected || 'code') + '`';
        caretOffset = selected ? inserted.length : 1;
        break;
      case 'codeblock':
        inserted = '\n```\n' + (selected || 'code block') + '\n```\n';
        caretOffset = inserted.length;
        break;
      case 'hr':
        inserted = (before.endsWith('\n') || before === '' ? '' : '\n') + '\n---\n';
        caretOffset = inserted.length;
        break;
      case 'table':
        inserted = '\n| Column 1 | Column 2 | Column 3 |\n| --- | --- | --- |\n| Cell | Cell | Cell |\n';
        caretOffset = inserted.length;
        break;
      default:
        return;
    }

    textarea.value = before + inserted + after;
    var pos = start + (selected ? inserted.length : caretOffset);
    textarea.focus();
    textarea.setSelectionRange(pos, pos);
    /* Trigger input so counter/preview updates */
    textarea.dispatchEvent(new Event('input', { bubbles: true }));
  }

  /* Tiny markdown → HTML renderer (handles common syntax) */
  function renderMarkdown(md) {
    var html = (md || '')
      .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');

    /* Code blocks first */
    html = html.replace(/```([\s\S]*?)```/g, function (m, code) {
      return '<pre><code>' + code.trim() + '</code></pre>';
    });

    /* Headings */
    html = html.replace(/^### (.*)$/gm, '<h3>$1</h3>');
    html = html.replace(/^## (.*)$/gm, '<h2>$1</h2>');
    html = html.replace(/^# (.*)$/gm, '<h1>$1</h1>');

    /* HR */
    html = html.replace(/^---$/gm, '<hr>');

    /* Blockquote */
    html = html.replace(/^&gt; (.*)$/gm, '<blockquote>$1</blockquote>');
    html = html.replace(/(<\/blockquote>\n<blockquote>)/g, '<br>');

    /* Bold + italic + strike + inline code */
    html = html.replace(/\*\*([^\*]+)\*\*/g, '<strong>$1</strong>');
    html = html.replace(/\*([^\*]+)\*/g, '<em>$1</em>');
    html = html.replace(/~~([^~]+)~~/g, '<del>$1</del>');
    html = html.replace(/`([^`]+)`/g, '<code>$1</code>');

    /* Images */
    html = html.replace(/!\[([^\]]*)\]\(([^)]+)\)/g, '<img alt="$1" src="$2">');
    /* Links */
    html = html.replace(/\[([^\]]+)\]\(([^)]+)\)/g, '<a href="$2" target="_blank" rel="noopener">$1</a>');

    /* Lists — group consecutive list lines */
    html = html.replace(/(?:^|\n)((?:- .*(?:\n|$))+)/g, function (m, block) {
      var items = block.trim().split('\n').map(function (l) { return '<li>' + l.replace(/^- /, '') + '</li>'; }).join('');
      return '\n<ul>' + items + '</ul>';
    });
    html = html.replace(/(?:^|\n)((?:\d+\. .*(?:\n|$))+)/g, function (m, block) {
      var items = block.trim().split('\n').map(function (l) { return '<li>' + l.replace(/^\d+\. /, '') + '</li>'; }).join('');
      return '\n<ol>' + items + '</ol>';
    });

    /* Paragraphs */
    html = html.split(/\n{2,}/).map(function (p) {
      var t = p.trim();
      if (!t) return '';
      if (/^<(h\d|ul|ol|pre|blockquote|hr|table|img)/.test(t)) return t;
      return '<p>' + t.replace(/\n/g, '<br>') + '</p>';
    }).join('\n');

    return html;
  }

  function bind() {
    /* Toolbar button clicks */
    document.querySelectorAll('[data-md-toolbar]').forEach(function (toolbar) {
      var target = toolbar.getAttribute('data-md-toolbar');
      var textarea = getTextarea(target);
      if (!textarea) return;
      toolbar.querySelectorAll('[data-md]').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
          e.preventDefault();
          applyMd(textarea, btn.getAttribute('data-md'));
        });
      });
    });

    /* Keyboard shortcuts */
    document.querySelectorAll('[data-md-source]').forEach(function (textarea) {
      textarea.addEventListener('keydown', function (e) {
        if (!(e.ctrlKey || e.metaKey)) return;
        var action = null;
        if (e.key === 'b') action = 'bold';
        else if (e.key === 'i') action = 'italic';
        else if (e.key === 'k') action = 'link';
        if (action) {
          e.preventDefault();
          applyMd(textarea, action);
        }
      });
    });

    /* Counter */
    document.querySelectorAll('[data-md-counter]').forEach(function (el) {
      var textarea = getTextarea(el.getAttribute('data-md-counter'));
      if (!textarea) return;
      var update = function () {
        var c = textarea.value.length;
        var w = textarea.value.trim().split(/\s+/).filter(Boolean).length;
        el.textContent = c + ' chars · ' + w + ' words';
      };
      textarea.addEventListener('input', update);
      update();
    });

    /* Preview */
    document.querySelectorAll('[data-md-preview]').forEach(function (preview) {
      var textarea = getTextarea(preview.getAttribute('data-md-preview'));
      if (!textarea) return;
      var refresh = function () { preview.innerHTML = renderMarkdown(textarea.value); };
      textarea.addEventListener('input', refresh);
      refresh();
    });

    /* View toggle (Edit / Preview) */
    document.querySelectorAll('[data-md-view]').forEach(function (btn) {
      btn.addEventListener('click', function () {
        var target = btn.getAttribute('data-md-target');
        var view = btn.getAttribute('data-md-view');
        var textarea = getTextarea(target);
        var preview = document.querySelector('[data-md-preview="' + target + '"]');
        if (!textarea || !preview) return;
        if (view === 'preview') {
          preview.innerHTML = renderMarkdown(textarea.value);
          preview.removeAttribute('hidden');
          textarea.setAttribute('hidden', '');
        } else {
          textarea.removeAttribute('hidden');
          preview.setAttribute('hidden', '');
        }
        /* Toggle button active state */
        var grp = btn.parentElement;
        grp.querySelectorAll('[data-md-view]').forEach(function (b) {
          b.classList.remove('active', 'btn-soft-primary');
          b.classList.add('btn-light-bordered');
        });
        btn.classList.add('active', 'btn-soft-primary');
        btn.classList.remove('btn-light-bordered');
      });
    });
  }

  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', bind);
  else bind();
  document.addEventListener('partials:ready', bind);
})();
