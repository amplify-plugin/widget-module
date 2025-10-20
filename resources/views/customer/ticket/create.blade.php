@pushonce('plugin-script')
    <script src="{{ asset('//cdn.ckeditor.com/4.6.2/standard/ckeditor.js') }}"></script>
@endpushonce
@pushonce('footer-script')
    <script src="{{ asset('CKEDITOR.replace(\'my-editor\');') }}"></script>
@endpushonce
@php
    push_js(
        "CKEDITOR.on('instanceReady', function(event) {
            // Select the CKEditor iframe document
            var editorDocument = event.editor.document.$;

            // Create a style element
            var styleElement = editorDocument.createElement('style');
            styleElement.type = 'text/css';

            // Add your CSS rule
            styleElement.innerHTML = '.cke_editable.cke_display_version_check-absolute:before { display: none !important; }';

            // Append the style to the head of the editor's document
            editorDocument.head.appendChild(styleElement);
        });",
        'plugin-script',
    );
@endphp
<div {!! $htmlAttributes !!}>
    <div class="card">
        <form action="{{ route('frontend.tickets.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group col-12">
                        <label aria-label="Subject">Subject<span class="text-danger font-weight-bold">*</span></label>
                        <input class="form-control" aria-label="Subject" id="subject" name="subject" type="text"
                            value="{{ old('subject') }}" placeholder="Write ticket subject.">
                        @error('subject')
                            <small class="text-danger">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>

                    <div class="form-group col-md-6">
                        <label for="departments_name_id">Department<span
                                class="text-danger font-weight-bold">*</span></label>
                        <select class="form-control" id="departments_name_id" name="departments_name_id">
                            <option value="">Select department</option>
                            @foreach ($ticket_departments ?? collect() as $department)
                                <option value="{{ $department->id }}" @if (old('departments_name_id') == $department->id) selected @endif>
                                    {{ $department->name }}</option>
                            @endforeach
                        </select>
                        @error('departments_name_id')
                            <small class="text-danger">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>

                    <div class="form-group col-md-6">
                        <label for="priority">Priority<span class="text-danger font-weight-bold">*</span></label>
                        <select class="form-control" id="priority" name="priority">
                            <option value="">Select priority</option>
                            @foreach ($priorities ?? collect([]) as $key => $priority)
                                <option value="{{ $priority }}" @if (old('priority') == $priority) selected @endif>
                                    {{ $key }}</option>
                            @endforeach
                        </select>
                        @error('priority')
                            <small class="text-danger">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>

                    <div class="form-group col-md-12 mb-3">
                        <label aria-label="Message">Message<span class="text-danger font-weight-bold">*</span></label>
                        <textarea class="form-control" aria-label="Message" id="my-editor" id="message" name="message">{!! old('message', '') !!}</textarea>
                        @error('message')
                            <small class="text-danger">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <div class="custom-file">
                                <input  aria-label="custom-file-input"
                                    class="form-control custom-file-input @if ($errors->has('attachments') || $errors->has('attachments.*')) is-invalid @endif"
                                    id="attachments" name="attachments[]" type="file" multiple>
                                <label class="custom-file-label" for="upload-file">Choose file</label>
                            </div>
                        </div>
                        <small class="text-danger">
                            {{ $errors->first('attachments') }}
                            {{ $errors->first('attachments.*') }}
                        </small>
                    </div>

                    <div class="col-12">
                        <div class="btn-group" role="group">
                            <button class="btn btn-success" type="submit">
                                <span class="pe-7s-diskette"></span>
                                <span>Save</span>
                            </button>
                        </div>
                        <a class="btn btn-outline-secondary" href="{{ route('frontend.contacts.index') }}">Cancel</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
