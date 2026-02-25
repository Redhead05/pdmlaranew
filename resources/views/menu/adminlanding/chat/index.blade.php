@extends('app.layout')
@section('title', 'Chat')

@section('content')
    <div id="adminChatRoot" class="container-fluid" data-adminlanding-chat
         data-conversations-url="{{ route('adminlanding.chat.conversations') }}"
         data-messages-base-url="{{ url('/adminlanding/chat/conversations') }}"
         data-reply-base-url="{{ url('/adminlanding/chat/conversations') }}"
         data-read-base-url="{{ url('/adminlanding/chat/conversations') }}">
        <div class="main-content d-flex flex-column">
            <div class="card bg-white border-0 rounded-3 mb-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <h4 class="mb-1">Inbox Chat (Guest)</h4>
                            <div class="text-muted">Realtime via Reverb</div>
                        </div>
                        <button class="btn btn-outline-secondary btn-sm" id="refreshConversations">Refresh</button>
                    </div>

                    <div class="row g-3" style="min-height: 520px;">
                        <div class="col-12 col-lg-4">
                            <div class="border rounded-3 overflow-hidden">
                                <div class="p-2 border-bottom bg-light">
                                    <input class="form-control form-control-sm" id="conversationSearch" placeholder="Cari nama/email..." />
                                </div>
                                <div id="conversationList" class="list-group list-group-flush" style="max-height: 460px; overflow:auto;"></div>
                            </div>
                        </div>

                        <div class="col-12 col-lg-8">
                            <div class="border rounded-3 d-flex flex-column" style="height: 520px;">
                                <div class="p-3 border-bottom d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="fw-semibold" id="activeGuestName">Pilih percakapan</div>
                                        <div class="small text-muted" id="activeGuestMeta"></div>
                                    </div>
                                    <div class="small text-muted" id="activeConversationStatus"></div>
                                </div>

                                <div id="messageList" class="p-3" style="flex:1; overflow:auto; background:#f7f8fa;">
                                    <div class="text-muted">Pilih percakapan di sebelah kiri untuk melihat pesan.</div>
                                </div>

                                <form id="replyForm" class="p-3 border-top d-flex gap-2" style="background:white;">
                                    <input class="form-control" id="replyBody" placeholder="Tulis balasan..." autocomplete="off" disabled />
                                    <button class="btn btn-primary" type="submit" disabled>Kirim</button>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

