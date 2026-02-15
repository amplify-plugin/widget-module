@pushonce('plugin-style')
    <link rel="stylesheet" type="text/css" href="{{ asset('packages/lightbox2/css/lightbox.min.css') }}">
@endpushonce
@pushonce('internal-style')
    <style>
        .min-h-200 {
            min-height: 200px;
        }

        .min-h-100 {
            min-height: 100px;
        }
    </style>
@endpushonce
@pushonce('plugin-script')
    <script src="{{ asset('packages/lightbox2/js/lightbox.min.js') }}"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('packages/lightbox2/css/lightbox.min.css') }}">
@endpushonce
<div {!! $htmlAttributes !!}>
<div class="card border-0 shadow h-100">
    <div class="d-flex flex-column h-100 bg-white rounded overflow-hidden">
        @if ($threadMsg)
            @php
                $receiver = $threadMsg->participants->where('model', "Amplify\System\Backend\Models\User")->first();
            @endphp

            <!-- Chat Header -->
            <header class="px-4 py-3 border-bottom bg-white sticky-top">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="avatar-circle bg-primary text-white mr-3 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px; border-radius: 50%;">
                            <i class="fa fa-headset"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 font-weight-bold text-dark text-truncate" style="max-width: 250px;">{{ $threadMsg->title }}</h6>
                            <small class="text-muted text-bold">
                                <span class="d-inline-block bg-success rounded-circle mr-1" style="width: 8px; height: 8px;"></span>
                                {{ $receiver ? optional(optional($receiver)->user)->name : 'Waiting for support agent' }}
                            </small>
                        </div>
                    </div>
                    <div class="text-right d-none d-md-block ">
                        <span class="badge badge-light px-3 py-2 text-black">
                            <i class="fa fa-ticket-alt mr-1"></i> Ticket #{{ $threadMsg->id }}
                        </span>
                    </div>
                </div>
            </header>

            <!-- Chat Messages Area -->
            <section class="flex-grow-1 overflow-auto p-4" style="background: linear-gradient(180deg, #f8f9fa 0%, #e9ecef 100%);">
                @if ($threadMsg->tickets?->count())
                    <div class="chat-messages">
                        @foreach ($threadMsg->tickets as $message)
                            @php
                                $isMine = $message->sender_id === optional(backpack_user())->id && $message->model == get_class(backpack_user());
                                $attachments = collect(json_decode($message->attachments, true) ?? []);
                                $attachmentTitles = collect(json_decode($message->attachment_title, true) ?? []);
                            @endphp

                            <div class="d-flex mb-4 {{ $isMine ? 'justify-content-end' : 'justify-content-start' }}">
                                @if (!$isMine)
                                    <div class="avatar-sm bg-secondary mr-2 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 36px; height: 36px; border-radius: 50%; margin-top: 4px;">
                                        <i class="fa fa-user-tie" style="font-size: 14px;"></i>
                                    </div>
                                @endif

                                <div class="message-content {{ $isMine ? 'text-right' : '' }}" style="max-width: 75%;">
                                    <div class="message-bubble p-3 {{ $isMine ? 'bg-primary text-white' : 'bg-white border' }}" style="border-radius: {{ $isMine ? '18px 18px 4px 18px' : '18px 18px 18px 4px' }}; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                                        <p class="mb-0" style="white-space: pre-wrap; word-break: break-word;">{!! nl2br(e($message->message)) !!}</p>

                                        @if ($attachments->isNotEmpty())
                                            <!-- Image Attachments -->
                                            @php
                                                $imageAttachments = $attachments->filter(fn($a) => preg_match('/\.(jpe?g|png|gif|webp)$/i', $a));
                                                $fileAttachments = $attachments->filter(fn($a) => !preg_match('/\.(jpe?g|png|gif|webp)$/i', $a));
                                            @endphp

                                            @if ($imageAttachments->isNotEmpty())
                                                <div class="mt-3 d-flex flex-wrap" style="gap: 8px;">
                                                    @foreach ($imageAttachments as $index => $attachment)
                                                        <a href="{{ $attachment }}"
                                                           data-lightbox="message-{{ $message->id }}"
                                                           data-title="{{ $message->message }}"
                                                           class="d-inline-block rounded overflow-hidden border"
                                                           style="line-height: 0;">
                                                            <img src="{{ $attachment }}" alt="Attachment" class="img-fluid" style="max-height: 140px; max-width: 200px; object-fit: cover;">
                                                        </a>
                                                    @endforeach
                                                </div>
                                            @endif

                                            <!-- File Attachments -->
                                            @if ($fileAttachments->isNotEmpty())
                                                <div class="mt-3">
                                                    @foreach ($fileAttachments as $index => $attachment)
                                                        <a href="{{ $attachment }}"
                                                           target="_blank"
                                                           download
                                                           class="d-flex align-items-center p-2 mb-2 rounded {{ $isMine ? 'bg-white bg-opacity-20' : 'bg-light' }} text-decoration-none"
                                                           style="{{ $isMine ? 'background: rgba(255,255,255,0.15);' : '' }}">
                                                            <div class="file-icon mr-2 d-flex align-items-center justify-content-center bg-secondary text-white" style="width: 32px; height: 32px; border-radius: 6px;">
                                                                <i class="fa fa-file-alt" style="font-size: 14px;"></i>
                                                            </div>
                                                            <span class="text-truncate small {{ $isMine ? 'text-white' : 'text-dark' }}">
                                                                {{ $attachmentTitles->get($index, basename($attachment)) }}
                                                            </span>
                                                        </a>
                                                    @endforeach
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                    <small class="text-muted d-block mt-1 px-2" style="font-size: 11px;">
                                        {{ $message->created_at->format('M d, Y · h:i A') }}
                                    </small>
                                </div>

                                @if ($isMine)
                                    <div class="avatar-sm bg-info text-white ml-2 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 36px; height: 36px; border-radius: 50%; margin-top: 4px;">
                                        <i class="fa fa-user" style="font-size: 14px;"></i>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="min-h-200 d-flex flex-column align-items-center justify-content-center text-muted py-5">
                        <div class="mb-3" style="width: 80px; height: 80px; border-radius: 50%; background: rgba(0,0,0,0.05);">
                            <div class="d-flex align-items-center justify-content-center h-100">
                                <i class="fa fa-comments" style="font-size: 32px; opacity: 0.5;"></i>
                            </div>
                        </div>
                        <p class="mb-0">No messages yet</p>
                        <small>Start the conversation by sending a message below</small>
                    </div>
                @endif
            </section>

            <!-- Chat Input Footer -->
            <footer class="border-top bg-white p-3">
                <form action="{{ route('frontend.tickets.update', $threadMsg->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="d-flex align-items-end" style="gap: 12px;">
                        <!-- Attachment Button -->
                        <div class="position-relative">
                            <label for="attachments" class="btn btn-light rounded-circle d-flex align-items-center justify-content-center mb-0" style="width: 44px; height: 44px; cursor: pointer;">
                                <i class="fa fa-paperclip text-muted"></i>
                            </label>
                            <input type="file" class="d-none" name="attachments[]" id="attachments">
                        </div>

                        <!-- Message Input -->
                        <div class="flex-grow-1">
                            <div class="position-relative">
                                <textarea name="message"
                                          class="form-control border rounded-pill py-2 px-4 pr-5"
                                          rows="1"
                                          style="resize: none; min-height: 44px; max-height: 120px;"
                                          placeholder="Type your message...">{{ old('message') }}</textarea>
                            </div>
                            @error('message')
                                <small class="text-danger d-block mt-1 px-3">{{ $message }}</small>
                            @enderror
                            <small class="text-danger d-block mt-1 px-3">
                                {{ $errors->first('attachments') }}
                                {{ $errors->first('attachments.*') }}
                            </small>
                        </div>

                        <!-- Send Button -->
                        <button type="submit"
                                id="send-msg"
                                class="btn btn-primary rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 44px; height: 44px;">
                            <i class="fa fa-paper-plane"></i>
                        </button>
                    </div>
                </form>
            </footer>
        @endif
    </div>
</div>
</div>
