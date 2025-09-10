<div class="card mb-3">
    <div class="p-4 text-center text-white text-lg bg-dark rounded-top"><span class="text-uppercase">MODEL / SERIAL NUMBER RESEARCH </span></div>
    <div class="card-body">
        <div class="steps d-flex flex-wrap flex-sm-nowrap justify-content-center padding-top-1x padding-bottom-1x">
            <div class="model_serial_number w-100">
                <form method="POST" enctype="multipart/form-data" action="{{ route('model-serial-number-research.store') }}">
                    @csrf

                    <div class="form-row py-2">
                        <div class="col">
                            <input type="text" class="form-control" name="manufacturer_name" placeholder="Manufacturer Name" value="{{ old('manufacturer_name') }}">
                        </div>
                        <div class="col">
                            <input type="text" class="form-control" name="model_number" placeholder="Model Number" value="{{ old('model_number') }}">
                        </div>
                    </div>

                    <div class="form-row py-2">
                        <div class="col">
                            <input type="text" class="form-control" name="serial_number" placeholder="Serial Number" value="{{ old('serial_number') }}">
                        </div>
                        <div class="col">
                            <input type="text" class="form-control" name="part_description" placeholder="Part Description" value="{{ old('part_description') }}">
                        </div>
                    </div>

                    <div class="form-row py-2">
                        <div class="col">
                            <input type="text" class="form-control" name="account_or_business_name" placeholder="Your Account # or Business Name" value="{{ old('account_or_business_name') }}">
                            @error('account_or_business_name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col">
                            <input type="text" class="form-control" name="zip_code" placeholder="Your ZIP Code" value="{{ old('zip_code') }}">
                            @error('zip_code')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row py-2">
                        <div class="col">
                            <input type="text" class="form-control" name="method_of_contact" placeholder="Method of Contact (phone / email)" value="{{ old('method_of_contact') }}">
                            @error('method_of_contact')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group d-flex justify-content-center">
                        <img id="imagePreview" class="upload-preview" width="20%" src="" alt="Image preview">
                    </div>

                    <div class="form-group py-2">
                        <div class="custom-file">
                            <input type="file" name="file" accept="image/*" class="custom-file-input form-control-plaintext" id="fileInput">
                            <label class="custom-file-label">Select image</label>
                            @error('file')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                        </div>
                    </div>
                    <div class="d-flex  justify-content-center ">
                        <button type="submit" name="submit" class="btn btn-primary font-weight-bold">Submit</button>
            </div>

                </form>
            </div>
        </div>
    </div>
</div>

@php
    push_js(function () {
        return <<<HTML
        document.getElementById('fileInput').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('imagePreview').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
        HTML;
    }, 'footer-script');
@endphp
