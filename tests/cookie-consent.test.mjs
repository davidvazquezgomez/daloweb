import { test } from 'node:test';
import assert from 'node:assert/strict';
import { readFileSync } from 'node:fs';
import { runInNewContext } from 'node:vm';

const source = readFileSync(new URL('../public/js/landing/cookies.js', import.meta.url), 'utf8');
const key = 'daloweb_cookie_consent';
const saved = choice => JSON.stringify({ version: 1, choice, expires: Date.now() + 86400000 });

function browser(value = null, blocked = false) {
  const listeners = {};
  const scripts = [];
  const deletions = [];
  const timers = new Map();
  const store = new Map(value === null ? [] : [[key, value]]);
  const element = dataset => ({ hidden: true, dataset, listeners: {},
    addEventListener(name, callback) { this.listeners[name] = callback; },
    focus() { this.focused = true; } });
  const banner = element({});
  const title = element({});
  const settings = element({});
  const accept = element({ cookieChoice: 'accepted' });
  const reject = element({ cookieChoice: 'rejected' });
  const window = {
    location: { hostname: 'www.daloweb.es', href: 'https://www.daloweb.es/?email=private#contacto',
      reload() { window.reloads++; } },
    reloads: 0,
    addEventListener(name, callback) { listeners[name] = callback; },
    clearTimeout(id) { timers.delete(id); },
    setTimeout(callback) { const id = Symbol(); timers.set(id, callback); return id; }
  };
  const document = {
    getElementById(id) { return id === 'cookieBanner' ? banner : title; },
    querySelectorAll(selector) { return selector === '[data-cookie-settings]' ? [settings] : [accept, reject]; },
    querySelector() { return { href: 'https://www.daloweb.es/' }; },
    referrer: 'https://example.com/path?email=private#fragment',
    createElement() { return {}; },
    head: { appendChild(script) { scripts.push(script); } }
  };
  Object.defineProperty(document, 'cookie', {
    get() { return '_ga=123; _ga_TT318T6SZZ=456; XSRF-TOKEN=keep'; },
    set(value) { deletions.push(value); }
  });
  const localStorage = {
    getItem(name) { if (blocked) throw new Error('blocked'); return store.get(name) ?? null; },
    setItem(name, data) { if (blocked) throw new Error('blocked'); store.set(name, data); }
  };
  runInNewContext(source, { window, document, localStorage, URL });
  return { window, banner, settings, accept, reject, scripts, deletions, store, listeners, title,
    click(button) { button.listeners.click(); },
    emit(detail) { listeners['daloweb:analytics']({ detail }); },
    commands() { return Array.from(window.dataLayer || [], args => Array.from(args)); }
  };
}

test('no choice or rejection never loads Google or forwards events', () => {
  for (const value of [null, saved('rejected')]) {
    const b = browser(value);
    b.emit({ event: 'cta_click' });
    assert.equal(b.scripts.length, 0);
    assert.equal(b.commands().length, 0);
    assert.equal(b.window['ga-disable-G-TT318T6SZZ'], true);
    assert.equal(b.banner.hidden, value !== null);
    assert.ok(b.deletions.some(cookie => cookie.startsWith('_ga=')));
    assert.ok(b.deletions.every(cookie => !cookie.startsWith('XSRF-TOKEN=')));
  }
});

test('acceptance loads once, denies ads, sanitizes URLs and forwards only later safe events', () => {
  const b = browser();
  b.emit({ event: 'before_consent' });
  b.click(b.accept);
  b.click(b.accept);
  assert.equal(b.scripts.length, 1);
  assert.equal(b.scripts[0].src, 'https://www.googletagmanager.com/gtag/js?id=G-TT318T6SZZ');
  assert.equal(b.banner.hidden, true);
  const commands = b.commands();
  assert.equal(commands[0][0], 'consent');
  assert.equal(commands[0][2].ad_user_data, 'denied');
  assert.equal(commands[1][2].analytics_storage, 'granted');
  const config = commands.find(command => command[0] === 'config')[2];
  assert.equal(config.page_location, 'https://www.daloweb.es/');
  assert.equal(config.page_referrer, 'https://example.com/path');
  assert.equal(config.allow_google_signals, false);
  b.emit({ event: 'lead_submit_success', service: 'web', email: 'private', id: 'private-id' });
  const events = b.commands().filter(command => command[0] === 'event');
  assert.equal(events.length, 1);
  assert.equal(events[0][1], 'lead_submit_success');
  assert.deepEqual(Object.keys(events[0][2]), ['service']);
});

test('saved acceptance starts Analytics on subsequent pages', () => {
  const b = browser(saved('accepted'));
  assert.equal(b.scripts.length, 1);
  assert.equal(b.banner.hidden, true);
});

test('withdrawal disables measurement, deletes cookies and reloads', () => {
  const b = browser(saved('accepted'));
  b.click(b.settings);
  assert.equal(b.banner.hidden, false);
  assert.equal(b.title.focused, true);
  b.click(b.reject);
  assert.equal(JSON.parse(b.store.get(key)).choice, 'rejected');
  assert.equal(b.window['ga-disable-G-TT318T6SZZ'], true);
  assert.equal(b.window.reloads, 1);
  b.emit({ event: 'after_withdrawal' });
  assert.equal(b.commands().filter(command => command[0] === 'event').length, 0);
  assert.ok(b.deletions.some(cookie => cookie.includes('domain=daloweb.es')));
});

test('malformed, expired or old consent requires a new decision', () => {
  for (const value of ['broken', '{}', JSON.stringify({ version: 1, choice: 'accepted', expires: Date.now() - 1 }),
    JSON.stringify({ version: 0, choice: 'accepted', expires: Date.now() + 10000 })]) {
    const b = browser(value);
    assert.equal(b.scripts.length, 0);
    assert.equal(b.banner.hidden, false);
  }
});

test('blocked storage keeps consent only on the current page', () => {
  const b = browser(null, true);
  b.click(b.accept);
  assert.equal(b.scripts.length, 1);
  assert.equal(b.store.size, 0);
  b.click(b.reject);
  assert.equal(b.window.reloads, 1);
});

test('withdrawal in another tab or a restored page disables Analytics', () => {
  for (const name of ['storage', 'pageshow']) {
    const b = browser(saved('accepted'));
    b.store.set(key, saved('rejected'));
    b.listeners[name]({ key, persisted: true });
    assert.equal(b.window['ga-disable-G-TT318T6SZZ'], true);
    assert.equal(b.window.reloads, 1);
  }
});
