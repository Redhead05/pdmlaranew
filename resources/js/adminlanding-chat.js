import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

const qs = (s, r = document) => r.querySelector(s);

function escapeHtml(str) {
  return String(str)
    .replaceAll('&', '&amp;')
    .replaceAll('<', '&lt;')
    .replaceAll('>', '&gt;')
    .replaceAll('"', '&quot;')
    .replaceAll("'", '&#039;');
}

async function jsonFetch(url, opts = {}) {
  const headers = {
    'Accept': 'application/json',
    'Content-Type': 'application/json',
    ...(opts.headers || {}),
  };
  const csrf = qs('meta[name="csrf-token"]')?.getAttribute('content');
  if (csrf) headers['X-CSRF-TOKEN'] = csrf;

  const res = await fetch(url, { credentials: 'same-origin', ...opts, headers });
  const data = await res.json().catch(() => ({}));
  if (!res.ok) throw new Error(data?.message || `Request failed (${res.status})`);
  return data;
}

function initAdminLandingChat() {
  const root = qs('[data-adminlanding-chat]');
  if (!root) return;

  // prevent double init (layout loads the script globally)
  if (root.dataset.bound === '1') return;
  root.dataset.bound = '1';

  const elList = qs('#conversationList', root);
  const elSearch = qs('#conversationSearch', root);
  const elMsg = qs('#messageList', root);
  const elRefresh = qs('#refreshConversations', root);

  const elGuestName = qs('#activeGuestName', root);
  const elGuestMeta = qs('#activeGuestMeta', root);
  const elStatus = qs('#activeConversationStatus', root);

  const replyForm = qs('#replyForm', root);
  const replyBody = qs('#replyBody', root);
  const replyBtn = replyForm?.querySelector('button[type="submit"]') || null;

  let conversations = [];
  let activeId = null;
  let echo = null;
  let activeConversationChannel = null;

  const routes = {
    conversations: root.getAttribute('data-conversations-url'),
    messagesBase: root.getAttribute('data-messages-base-url'),
    replyBase: root.getAttribute('data-reply-base-url'),
    readBase: root.getAttribute('data-read-base-url'),
  };

  function setReplyEnabled(enabled) {
    if (replyBody) replyBody.disabled = !enabled;
    if (replyBtn) replyBtn.disabled = !enabled;
    if (enabled) replyBody?.focus();
  }

  // default: disabled until selection
  setReplyEnabled(false);

  function renderConversations() {
    const q = (elSearch.value || '').toLowerCase().trim();

    const filtered = conversations.filter((c) => {
      if (!q) return true;
      return (
        c.guest.username.toLowerCase().includes(q) ||
        c.guest.email.toLowerCase().includes(q) ||
        c.guest.phone.toLowerCase().includes(q)
      );
    });

    elList.innerHTML = '';

    if (!filtered.length) {
      elList.innerHTML = `<div class="p-3 text-muted">Belum ada percakapan</div>`;
      return;
    }

    for (const c of filtered) {
      const a = document.createElement('button');
      a.type = 'button';
      a.className = 'list-group-item list-group-item-action';
      a.dataset.conversationId = String(c.id);
      if (Number(c.id) === Number(activeId)) a.classList.add('active');

      const unread = Number(c.unread_count || 0);

      a.innerHTML = `
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <div class="fw-semibold">${escapeHtml(c.guest.username)}</div>
            <div class="small text-muted">${escapeHtml(c.guest.email)} • ${escapeHtml(c.guest.phone)}</div>
          </div>
          <div class="d-flex align-items-center gap-2">
            ${unread > 0 ? `<span class="badge text-bg-danger">${unread}</span>` : ''}
            <span class="badge text-bg-${c.status === 'open' ? 'success' : 'secondary'}">${escapeHtml(c.status)}</span>
          </div>
        </div>
        <div class="small text-muted mt-1">#${c.id} • ${c.last_message_at ? new Date(c.last_message_at).toLocaleString() : '-'}</div>
      `;

      a.addEventListener('click', (ev) => {
        ev.preventDefault();
        openConversation(c.id);
      });

      elList.appendChild(a);
    }
  }

  function renderMessages(messages) {
    elMsg.innerHTML = '';

    if (!messages.length) {
      elMsg.innerHTML = `<div class="text-muted">Belum ada pesan</div>`;
      return;
    }

    for (const m of messages) {
      const wrap = document.createElement('div');
      wrap.className = 'd-flex mb-2 ' + (m.sender_type === 'admin' ? 'justify-content-end' : 'justify-content-start');

      const bubble = document.createElement('div');
      bubble.className = 'px-3 py-2 rounded-3 ' + (m.sender_type === 'admin' ? 'bg-primary text-white' : 'bg-white border');
      bubble.style.maxWidth = '80%';
      bubble.innerHTML = `<div style="white-space:pre-wrap">${escapeHtml(m.body)}</div>
        <div class="small opacity-75 mt-1">${m.sent_at ? new Date(m.sent_at).toLocaleString() : ''}${m.sender_type === 'admin' && m.read_at ? ' • dibaca' : ''}</div>`;

      wrap.appendChild(bubble);
      elMsg.appendChild(wrap);
    }

    elMsg.scrollTop = elMsg.scrollHeight;
  }

  function initEcho() {
    if (echo) return echo;

    try {
      window.Pusher = Pusher;

      // pusher-js requires `cluster` option even when using a custom host.
      // Reverb speaks the Pusher protocol, so we provide a dummy cluster.
      const reverbHost = import.meta.env.VITE_REVERB_HOST;
      const reverbPort = Number(import.meta.env.VITE_REVERB_PORT || 8080);
      const reverbScheme = import.meta.env.VITE_REVERB_SCHEME || 'http';

      echo = new Echo({
        broadcaster: 'pusher',
        key: import.meta.env.VITE_REVERB_APP_KEY,
        cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER || 'mt1',
        wsHost: reverbHost,
        wsPort: reverbPort,
        wssPort: reverbPort,
        forceTLS: reverbScheme === 'https',
        enabledTransports: ['ws', 'wss'],
        disableStats: true,
      });

      echo.private('chat.admin.inbox')
        .listen('.chat.message.sent', () => loadConversations(false))
        .listen('.chat.messages.read', () => loadConversations(false));

      return echo;
    } catch (e) {
      console.error('Echo init failed, falling back to polling only:', e);
      echo = null;
      return null;
    }
  }

  async function loadConversations(scroll = true) {
    try {
      const data = await jsonFetch(routes.conversations);
      conversations = data.conversations || [];
      renderConversations();
      if (scroll) elList.scrollTop = 0;
    } catch (e) {
      console.error('Failed to load conversations:', e);
      elList.innerHTML = `<div class="p-3 text-danger">Gagal memuat percakapan: ${escapeHtml(e?.message || 'Unknown error')}</div>`;
    }
  }

  function renderActiveAppend(e) {
    const msg = {
      sender_type: e.sender_type,
      body: e.body,
      sent_at: e.sent_at,
      read_at: e.read_at,
    };

    const wrap = document.createElement('div');
    wrap.className = 'd-flex mb-2 ' + (msg.sender_type === 'admin' ? 'justify-content-end' : 'justify-content-start');

    const bubble = document.createElement('div');
    bubble.className = 'px-3 py-2 rounded-3 ' + (msg.sender_type === 'admin' ? 'bg-primary text-white' : 'bg-white border');
    bubble.style.maxWidth = '80%';
    bubble.innerHTML = `<div style="white-space:pre-wrap">${escapeHtml(msg.body)}</div>
      <div class="small opacity-75 mt-1">${msg.sent_at ? new Date(msg.sent_at).toLocaleString() : ''}</div>`;

    wrap.appendChild(bubble);
    elMsg.appendChild(wrap);
    elMsg.scrollTop = elMsg.scrollHeight;
    loadConversations(false);
  }

  async function loadActiveMessages(updateHeader = true) {
    if (!activeId) return;
    try {
      const url = `${routes.messagesBase}/${activeId}/messages`;
      const data = await jsonFetch(url);

      const conv = data.conversation;
      if (updateHeader) {
        elGuestName.textContent = conv.guest.username;
        elGuestMeta.textContent = `${conv.guest.email} • ${conv.guest.phone}`;
        elStatus.textContent = conv.status;
      }

      renderMessages(data.messages || []);
      setReplyEnabled(true);
      loadConversations(false);
    } catch (e) {
      console.error('Failed to load messages:', e);
      elMsg.innerHTML = `<div class="text-danger">Gagal memuat pesan: ${escapeHtml(e?.message || 'Unknown error')}</div>`;
      setReplyEnabled(false);
    }
  }

  async function openConversation(id) {
    activeId = Number(id);
    setReplyEnabled(false);

    // show loading state
    elMsg.innerHTML = `<div class="text-muted">Memuat pesan...</div>`;

    renderConversations();

    const echoClient = initEcho();

    try {
      if (echoClient && activeConversationChannel) {
        echoClient.leave(activeConversationChannel);
        activeConversationChannel = null;
      }

      activeConversationChannel = `chat.conversation.${activeId}`;

      if (echoClient) {
        echoClient.private(activeConversationChannel)
          .listen('.chat.message.sent', (e) => {
            if (Number(e.conversation_id) !== Number(activeId)) return;
            renderActiveAppend(e);
          })
          .listen('.chat.messages.read', () => {
            loadActiveMessages(false);
          });
      }

      await loadActiveMessages(true);

      // mark read
      try {
        await jsonFetch(`${routes.readBase}/${activeId}/read`, { method: 'POST', body: JSON.stringify({}) });
        await loadConversations(false);
      } catch (_) {}

    } catch (e) {
      console.error('openConversation failed:', e);
      elMsg.innerHTML = `<div class="text-danger">Gagal membuka percakapan: ${escapeHtml(e?.message || 'Unknown error')}</div>`;
      setReplyEnabled(false);
    }
  }

  // polling fallback (in case reverb is not connected yet)
  let pollTimer = null;
  function startPolling() {
    if (pollTimer) return;
    pollTimer = window.setInterval(() => {
      // don't scroll list while user reading
      loadConversations(false);
    }, 8000);
  }

  function stopPolling() {
    if (!pollTimer) return;
    window.clearInterval(pollTimer);
    pollTimer = null;
  }

  document.addEventListener('visibilitychange', () => {
    if (document.visibilityState === 'visible') {
      loadConversations(false);
      startPolling();
    } else {
      stopPolling();
    }
  });

  replyForm?.addEventListener('submit', async (e) => {
    e.preventDefault();
    if (!activeId) return;

    const body = (replyBody.value || '').trim();
    if (!body) return;

    replyBody.value = '';

    renderActiveAppend({ conversation_id: activeId, sender_type: 'admin', body, sent_at: new Date().toISOString() });

    try {
      await jsonFetch(`${routes.replyBase}/${activeId}/reply`, {
        method: 'POST',
        body: JSON.stringify({ body }),
      });
    } catch (err) {
      alert(err.message);
    }
  });

  elSearch?.addEventListener('input', renderConversations);
  elRefresh?.addEventListener('click', () => loadConversations());

  // init echo is optional; polling will always run
  initEcho();
  loadConversations();
  startPolling();
}

document.addEventListener('DOMContentLoaded', initAdminLandingChat);
document.addEventListener('turbo:load', initAdminLandingChat);

