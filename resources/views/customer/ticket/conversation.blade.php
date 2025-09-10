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
<div class="card chat-app">
    <div class="chat ml-0">
        @if ($threadMsg)
            @php
                $receiver = $threadMsg->participants->where('model', ("Amplify\System\Backend\Models\User"))->first()
            @endphp
            <div class="chat-header clearfix">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="chat-about">
                            <h6 class="mt-2"><span class="font-weight-bold">Subject:</span>
                                {{ $threadMsg->title }}</h6>
                            <h6 class="mt-2"><span class="font-weight-bold">Assignee:</span>
                                {{ $receiver ? optional(optional($receiver)->user)->name : 'Waiting to be assigned' }}
                            </h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="chat-history py-0 pr-0">
                @if (isset($threadMsg->tickets) && $threadMsg->tickets->count() > 0)
                    <ul class="mb-0 pl-0">
                        @foreach ($threadMsg->tickets as $message)
                            <li class="clearfix my-2">
                                <div
                                    class="message @if ($message->sender_id === optional(backpack_user())->id && $message->model == get_class(backpack_user())) my-message @else other-message float-right @endif">
                                    @if ($message->attachments)
                                        @foreach (json_decode($message->attachments) as $attachment)
                                            @if (in_array(pathinfo(($attachment), PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png']))
                                                <a href="{{ ($attachment) }}"
                                                   data-lightbox="{{ ($attachment) }}"
                                                   data-title="{{ $message->message }}">
                                                    <object class="img-fluid"
                                                            data="{{ ($attachment) }}"></object>
                                                </a>
                                            @endif
                                        @endforeach
                                    @endif
                                    <p class="text-left">{!! nl2br($message->message) !!}</p>
                                    @if ($message->attachments)
                                        @php
                                            $attachment_titles = json_decode($message->attachment_title)
                                        @endphp
                                        @foreach (json_decode($message->attachments) as $key => $attachment)
                                            @if (in_array(pathinfo($attachment, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png']))
                                                <a href="{{ ($attachment) }}"
                                                   data-lightbox="{{ ($attachment) }}">
                                                </a>
                                            @else
                                                <a href="{{ ($attachment) }}" target="_blank" download>
                                                    {{ (is_array($attachment_titles) ? $attachment_titles[$key] : $attachment_titles) ?? "" }}
                                                </a><i class="fa fa-file ml-2" aria-hidden="true"></i>
                                                <br/>
                                            @endif
                                        @endforeach
                                    @endif
                                    <small
                                        class="message-data-time text-muted font-italic">{{ $message->created_at->diffForHumans() }}
                                        >
                                    </small>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <ul style="height: 480px"></ul>
                @endif
            </div>
            <div class="chat-message clearfix">
                <form action="{{ route('frontend.tickets.update', $threadMsg->id) }}" method="post"
                      enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <div class="input-group frontend-message-input-group">
                                            <textarea name="message" class="form-control" style="padding-left: 0.75rem;"
                                                      rows="2"
                                                      placeholder="Enter text here...">{{ old('message') }}</textarea>
                            <div class="input-group-append">
                                <button type="submit" id="send-msg"

                                        class="btn btn-info rounded-right my-0 px-2 frontend-message-save-btn input-group-btn"
                                        style="font-size: 1.5rem; height: 75px">
                                    ➤
                                </button>
                            </div>
                        </div>
                    </div>
                    @error('message')
                    <small class="text-danger">
                        {{ $message }}
                    </small>
                    @enderror
                    <div class="form-group mb-0">
                        <div class="input-group d-flex clone-field">
                            <div class="custom-file">
                                <input type="file" class="form-control custom-file-input"
                                       name="attachments[]" id="attachments">
                                <label class="custom-file-label" for="upload-file">Choose file</label>
                            </div>
                        </div>
                        <small class="text-danger">
                            {{ $errors->first('attachments') }}
                            {{ $errors->first('attachments.*') }}
                        </small>
                    </div>
                </form>
            </div>
        @endif
    </div>
</div>
</div>
