<script src="{{ asset ('assets/fe/assets/js/plugins.js')}}"></script>
<script src="{{ asset ('assets/fe/assets/js/theme.js')}}"></script>
<script src="https://unpkg.com/aos@next/dist/aos.js"></script>
<script>
    AOS.init();
</script>
{{--start chatbox--}}
<script>
    const chatbotToggle = document.querySelector('.chatbot__button');
    const sendChatBtn = document.querySelector('#send-btn');
    const chatInput = document.querySelector('.chatbot__textarea');
    const chatBox = document.querySelector('.chatbot__box');
    const chatbotCloseBtns = document.querySelectorAll('.chatbot__close');
    const loginForm = document.getElementById('chatbot-login-form');
    const logoutBtn = document.getElementById('chatbot-logout');
    const chatbotLogin = document.getElementById('chatbot-login');
    const chatbotInterface = document.getElementById('chatbot-interface');
    const welcomeMsg = document.getElementById('welcome-msg');

    let userMessage;
    let guestData = {};
    let isLoggedIn = false;
    const inputInitHeight = chatInput.scrollHeight;

    // Create chat list item
    const createChatLi = (message, className) => {
        const chatLi = document.createElement('li');
        chatLi.classList.add('chatbot__chat', className);
        let chatContent = className === 'outgoing'
            ? `<p></p>`
            : `<span class="material-symbols-outlined">smart_toy</span> <p></p>`;
        chatLi.innerHTML = chatContent;
        chatLi.querySelector('p').textContent = message;
        return chatLi;
    };

    // Simulate bot response
    const generateResponse = (incomingChatLi) => {
        const messageElement = incomingChatLi.querySelector('p');

        setTimeout(() => {
            messageElement.textContent = 'Terima kasih atas pesan Anda. Tim kami sedang meninjau pertanyaan Anda dan akan segera merespons.';
            chatBox.scrollTo(0, chatBox.scrollHeight);
        }, 600);
    };

    // Handle chat
    const handleChat = () => {
        userMessage = chatInput.value.trim();
        if (!userMessage) return;

        chatInput.value = '';
        chatInput.style.height = `${inputInitHeight}px`;

        chatBox.appendChild(createChatLi(userMessage, 'outgoing'));
        chatBox.scrollTo(0, chatBox.scrollHeight);

        setTimeout(() => {
            const incomingChatLi = createChatLi('Thinking...', 'incoming');
            chatBox.appendChild(incomingChatLi);
            chatBox.scrollTo(0, chatBox.scrollHeight);
            generateResponse(incomingChatLi);
        }, 600);
    };

    // Toggle chatbot
    chatbotToggle.addEventListener('click', () => {
        document.body.classList.toggle('show-chatbot');
    });

    // Close chatbot
    chatbotCloseBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            document.body.classList.remove('show-chatbot');
        });
    });

    // Handle login form
    loginForm.addEventListener('submit', (e) => {
        e.preventDefault();

        const username = document.getElementById('guest-username').value.trim();
        const email = document.getElementById('guest-email').value.trim();
        const phone = document.getElementById('guest-phone').value.trim();

        if (!/^[0-9]{10,13}$/.test(phone)) {
            alert('Nomor HP harus berisi 10-13 digit angka');
            return;
        }

        guestData = { username, email, phone };
        isLoggedIn = true;

        welcomeMsg.textContent = `Hi ${username}! Selamat datang di layanan chat BAN PDM Jawa Timur. Ada yang bisa saya bantu?`;

        chatbotLogin.style.display = 'none';
        chatbotInterface.style.display = 'block';

        setTimeout(() => chatInput.focus(), 100);
    });

    // Handle logout
    logoutBtn.addEventListener('click', () => {
        if (confirm('Apakah Anda yakin ingin keluar dari chat?')) {
            isLoggedIn = false;
            guestData = {};

            // Clear chat except first message
            const firstMessage = chatBox.querySelector('.chatbot__chat.incoming');
            chatBox.innerHTML = '';
            if (firstMessage) {
                chatBox.appendChild(firstMessage);
            }

            welcomeMsg.textContent = 'Hi there. How can I help you today?';
            loginForm.reset();

            chatbotInterface.style.display = 'none';
            chatbotLogin.style.display = 'block';
        }
    });

    // Auto-resize textarea
    chatInput.addEventListener('input', () => {
        chatInput.style.height = `${inputInitHeight}px`;
        chatInput.style.height = `${chatInput.scrollHeight}px`;
    });

    // Send on Enter (desktop only)
    chatInput.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' && !e.shiftKey && window.innerWidth > 800) {
            e.preventDefault();
            handleChat();
        }
    });

    // Send button click
    sendChatBtn.addEventListener('click', handleChat);
</script>
{{--start chatbox--}}
@stack('scripts')
