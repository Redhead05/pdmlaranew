import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

function qs(sel, root = document) {
  return root.querySelector(sel);
}

function escapeHtml(str) {
  return String(str)
    .replaceAll('&', '&amp;')
    .replaceAll('<', '&lt;')
    .replaceAll('>', '&gt;')
    .replaceAll('"', '&quot;')
    .replaceAll("'", '&#039;');
}

function getCsrf() {
  return qs('meta[name="csrf-token"]')?.getAttribute('content');
}

function ensureCsrfMeta() {
  if (qs('meta[name="csrf-token"]')) return;
  const meta = document.createElement('meta');
  meta.name = 'csrf-token';
  meta.content = window.Laravel?.csrfToken || '';
  document.head.appendChild(meta);
}

function appendMessage(listEl, message, side) {
  const li = document.createElement('li');
  li.className = `chatbot__chat ${side}`;

  if (side === 'incoming') {
    li.innerHTML = `<span class="material-symbols-outlined">smart_toy</span><p>${escapeHtml(message.body)}</p>`;
  } else {
    li.innerHTML = `<p>${escapeHtml(message.body)}</p>`;
  }

  listEl.appendChild(li);
  listEl.scrollTop = listEl.scrollHeight;
}

async function jsonFetch(url, opts = {}) {
  ensureCsrfMeta();
  const headers = {
    'Accept': 'application/json',
    'Content-Type': 'application/json',
    ...(opts.headers || {}),
  };

  const csrf = getCsrf();
  if (csrf) headers['X-CSRF-TOKEN'] = csrf;

  const res = await fetch(url, {
    credentials: 'same-origin',
    ...opts,
    headers,
  });

  const data = await res.json().catch(() => ({}));
  if (!res.ok) {
    const msg = data?.message || `Request failed (${res.status})`;
    throw new Error(msg);
  }
  return data;
}

function initChatWidget() {
  const widget = qs('.chatbot');
  const btn = qs('.chatbot__button');
  if (!widget || !btn) return;

  // Prevent duplicate event listeners when Turbo fires multiple times
  if (btn.dataset.chatBound === '1') return;
  btn.dataset.chatBound = '1';

  const loginEl = qs('#chatbot-login');
  const chatEl = qs('#chatbot-interface');
  const loginForm = qs('#chatbot-login-form');
  const usernameEl = qs('#guest-username');
  const emailEl = qs('#guest-email');
  const phoneEl = qs('#guest-phone');
  const startBtn = loginForm?.querySelector('button[type="submit"]') || null;

  const loginErrorEl = qs('#chatbot-login-error') || (() => {
    if (!loginForm) return null;
    const div = document.createElement('div');
    div.id = 'chatbot-login-error';
    div.className = 'chatbot__error';
    div.style.display = 'none';
    loginForm.prepend(div);
    return div;
  })();

  // NOTE: message list exists inside chat interface
  const chatList = qs('#chatbot-interface .chatbot__box') || qs('.chatbot__box');
  const textarea = qs('#chatbot-interface .chatbot__textarea') || qs('.chatbot__textarea');
  const sendBtn = qs('#send-btn');
  const logoutBtn = qs('#chatbot-logout');
  const closeBtns = widget.querySelectorAll('.chatbot__close');

  let conversationId = null;
  let echo = null;
  let subscribed = false;

  function setOpen(open) {
    widget.classList.toggle('open', !!open);
  }

  function showLoginError(msg) {
    if (!loginErrorEl) return;
    loginErrorEl.textContent = msg;
    loginErrorEl.style.display = msg ? '' : 'none';
  }

  function setStartLoading(loading) {
    if (!startBtn) return;
    startBtn.disabled = !!loading;
    startBtn.dataset.loading = loading ? '1' : '0';
    startBtn.textContent = loading ? 'Memproses...' : 'Mulai Chat';
  }

  btn.addEventListener('click', (e) => {
    e.preventDefault();
    e.stopPropagation();
    setOpen(!widget.classList.contains('open'));
  });

  closeBtns.forEach((el) => {
    el.addEventListener('click', (e) => {
      e.preventDefault();
      e.stopPropagation();
      setOpen(false);
    });
  });

  function showLogin() {
    if (loginEl) loginEl.style.display = '';
    if (chatEl) chatEl.style.display = 'none';
  }

  function showChat() {
    if (loginEl) loginEl.style.display = 'none';
    if (chatEl) chatEl.style.display = '';
  }

  async function loadMessages() {
    const data = await jsonFetch(`/chat/conversations/${conversationId}/messages`);

    const keep = chatList?.querySelector('#welcome-msg')?.closest('li');
    if (chatList) chatList.innerHTML = '';
    if (keep && chatList) chatList.appendChild(keep);

    for (const msg of data.messages || []) {
      appendMessage(chatList, msg, msg.sender_type === 'guest' ? 'outgoing' : 'incoming');
    }
  }

  function initEcho() {
    if (echo) return echo;

    window.Pusher = Pusher;

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
      authEndpoint: '/chat/broadcasting/auth',
    });

    return echo;
  }

  async function subscribeConversation() {
    if (subscribed || !conversationId) return;

    initEcho();

    echo.private(`chat.conversation.${conversationId}`)
      .listen('.chat.message.sent', (e) => {
        if (Number(e.conversation_id) !== Number(conversationId)) return;
        const side = e.sender_type === 'guest' ? 'outgoing' : 'incoming';
        appendMessage(chatList, { body: e.body ?? '' }, side);
      });

    subscribed = true;
  }

  function validateStartPayload(payload) {
    if (!payload.username || !String(payload.username).trim()) return 'Username wajib diisi.';
    if (!payload.email || !String(payload.email).trim()) return 'Email wajib diisi.';
    if (!payload.phone || !String(payload.phone).trim()) return 'No. HP wajib diisi.';
    return null;
  }

  async function startChat() {
    const payload = {
      username: usernameEl?.value,
      email: emailEl?.value,
      phone: phoneEl?.value,
    };

    const err = validateStartPayload(payload);
    if (err) throw new Error(err);

    const data = await jsonFetch('/chat/guest/start', {
      method: 'POST',
      body: JSON.stringify(payload),
    });

    if (!data?.conversation?.id) {
      throw new Error('Gagal memulai chat. Coba refresh halaman.');
    }

    conversationId = data.conversation.id;
    localStorage.setItem('chat_conversation_id', String(conversationId));

    showChat();
    await loadMessages();
    await subscribeConversation();
  }

  async function resumeIfPossible() {
    const stored = localStorage.getItem('chat_conversation_id');
    if (!stored) {
      showLogin();
      return;
    }

    conversationId = Number(stored);
    try {
      showChat();
      await loadMessages();
      await subscribeConversation();
    } catch (e) {
      localStorage.removeItem('chat_conversation_id');
      conversationId = null;
      subscribed = false;
      showLogin();
    }
  }

  // ---- Anti-spam (frontend) ----
  const MIN_SEND_INTERVAL_MS = 1500; // 1.5s cooldown
  const MAX_BODY_LEN = 500; // keep widget lightweight
  const DUPLICATE_BLOCK_WINDOW_MS = 15_000; // 15s
  let lastSendAt = 0;
  let lastBody = '';
  let lastBodyAt = 0;
  let sending = false;

  function setSendDisabled(disabled) {
    if (!sendBtn) return;
    sendBtn.disabled = !!disabled;
    sendBtn.style.opacity = disabled ? '0.6' : '';
    sendBtn.style.pointerEvents = disabled ? 'none' : '';
  }

  async function sendMessage() {
    const bodyRaw = (textarea?.value || '');
    const body = bodyRaw.trim();
    if (!body || !conversationId) return;

    // length limit
    if (body.length > MAX_BODY_LEN) {
      appendMessage(chatList, { body: `Pesan terlalu panjang. Maks ${MAX_BODY_LEN} karakter.` }, 'incoming');
      return;
    }

    const now = Date.now();

    // cooldown
    if (now - lastSendAt < MIN_SEND_INTERVAL_MS) {
      appendMessage(chatList, { body: 'Tunggu sebentar sebelum kirim pesan lagi.' }, 'incoming');
      return;
    }

    // duplicate block
    if (body === lastBody && (now - lastBodyAt) < DUPLICATE_BLOCK_WINDOW_MS) {
      appendMessage(chatList, { body: 'Pesan yang sama sudah terkirim. Mohon tunggu.' }, 'incoming');
      return;
    }

    if (sending) return;
    sending = true;
    setSendDisabled(true);

    lastSendAt = now;
    lastBody = body;
    lastBodyAt = now;

    if (textarea) textarea.value = '';
    appendMessage(chatList, { body }, 'outgoing');

    try {
      await jsonFetch(`/chat/conversations/${conversationId}/messages`, {
        method: 'POST',
        body: JSON.stringify({ body }),
      });
    } catch (e) {
      appendMessage(chatList, { body: `Gagal mengirim: ${e?.message || 'Unknown error'}` }, 'incoming');
    } finally {
      sending = false;
      // keep the cooldown UX even if request is fast
      const wait = Math.max(0, MIN_SEND_INTERVAL_MS - (Date.now() - lastSendAt));
      window.setTimeout(() => setSendDisabled(false), wait);
    }
  }

  loginForm?.addEventListener('submit', async (ev) => {
    ev.preventDefault();
    showLoginError('');
    setStartLoading(true);

    try {
      await startChat();
      setOpen(true);
    } catch (e) {
      showLoginError(e?.message || 'Terjadi kesalahan.');
    } finally {
      setStartLoading(false);
    }
  });

  sendBtn?.addEventListener('click', sendMessage);
  textarea?.addEventListener('keydown', (e) => {
    if (e.key === 'Enter' && !e.shiftKey) {
      e.preventDefault();
      sendMessage();
    }
  });

  logoutBtn?.addEventListener('click', () => {
    localStorage.removeItem('chat_conversation_id');
    conversationId = null;
    subscribed = false;
    showLoginError('');
    showLogin();
  });

  // default closed
  setOpen(false);
  resumeIfPossible();
}

document.addEventListener('DOMContentLoaded', initChatWidget);
document.addEventListener('turbo:load', initChatWidget);

