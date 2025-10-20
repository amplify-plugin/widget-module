<div class="form-group {{ $field['fieldInitChecklist'] }}">
    <div class="d-flex justify-content-between">
        <label>{!! $field['label'] !!}</label>
        <a href="javascript:void(0);" class="my-2 font-weight-bold text-decoration-none" type="button"
           data-toggle="collapse"
           data-target=".collapse" aria-expanded="false">
        <span data-toggle="tooltip" data-placement="top" title="Expand/Collapse">
            <i class="pe-7s-angle-up" style="font-weight: 600; font-size: 1.2rem"></i>
            <i class="pe-7s-angle-down" style="font-weight: 600; font-size: 1.2rem"></i>
        </span>
        </a>
    </div>

    <input type="hidden" value='@json($field['value'])' name="{{ $field['name'] }}">

    <div class="row mr-1" id="checklist" style="max-height: 100vh; overflow-y: scroll">
        @foreach ($permissionOptions as $key => $option_group)
            @continue($key == "login-management")
            <div class="col-12">
                <div class="card mb-2">
                    <div class="card-header py-2 px-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input all-permission-check pmsn-title"
                                       value="{{ str_replace(".", "-", $key) }}"
                                       id="permission-group-{{ $key }}">
                                <label class="form-check-label font-weight-bold mb-0"
                                       for="permission-group-{{ $key }}">
                                    {{ ucwords(str_replace("-", " ", $key)) }}
                                </label>
                            </div>
                            <a href="javascript:void(0);" class="text-dark text-decoration-none mt-1"
                               data-toggle="collapse"
                               data-target="#collapse-{{ str_replace(".", "-", $key) }}"
                               aria-expanded="true"
                               aria-controls="collapse-{{ str_replace(".", "-", $key) }}">
                                <i class="pe-7s-angle-down" style="font-weight: 600; font-size: 1.2rem"></i>
                            </a>
                        </div>

                        <div id="collapse-{{ str_replace(".", "-", $key) }}" class="collapse">
                            <div class="card-body row px-0 py-2">
                                @foreach($option_group as $value => $label)
                                    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 my-1">
                                        <div class="form-check">
                                            <input type="checkbox" value="{{ $value }}"
                                                   data-title="{{ str_replace(".", "-", $key) }}"
                                                   class="pmsn form-check-input {{$key}}" id="permission-{{$label}}">
                                            <label class="form-check-label" for="permission-{{$label}}">
                                                {{ ucwords(str_replace(['.', '-', 'show'], [' ', ' ', 'detail'], $label)) }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

@pushonce('footer-script')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            $('.collapse').on('hide.bs.collapse', function () {
                var id = $(this).attr('id');

                var element = $("a[data-target='#" + id + "']").find('i');

                element.removeClass('pe-7s-angle-up');
                element.addClass('pe-7s-angle-down');
            });

            $('.collapse').on('show.bs.collapse', function () {
                var id = $(this).attr('id');

                var element = $("a[data-target='#" + id + "']").find('i');

                element.removeClass('pe-7s-angle-down');
                element.addClass('pe-7s-angle-up');
            });

            let element = $('.{{ $field['fieldInitChecklist'] }}');
            bpFieldInitChecklist(element);
        });

        function initCheckboxes(checkboxes, hidden_input, selected_options) {

            checkboxes.each(function (key, option) {

                var id = $(option).val();

                if (selected_options.map(String).includes(id)) {
                    $(option).prop('checked', 'checked');
                } else {
                    $(option).prop('checked', false);
                }
            });

            changeCheckBoxes(checkboxes, hidden_input);
        }

        function changeCheckBoxes(checkboxes, hidden_input) {
            var newValue = [];
            let all_permission_titles = [];
            let active_permission_titles = [];
            let label;
            checkboxes.each(function (key, element) {
                const item = $(element);
                if (label != item.data('title')){
                    label = item.data('title');
                    all_permission_titles.push(label);
                    active_permission_titles.push(label);
                }
                if (item.is(':checked')) {
                    var id = item.val();
                    newValue.push(id);
                }
                if (active_permission_titles.includes(label) && !item.is(':checked')){
                    active_permission_titles.pop(label);
                }
            });
            hidden_input.val(JSON.stringify(newValue));
            all_permission_titles.forEach(function(title) {
                if (active_permission_titles.includes(title)) {
                    $('input[value=' + title+'].pmsn-title').prop('checked', 'checked');
                } else {
                    $('input[value=' + title +'].pmsn-title').prop('checked', false);
                }
            });
        }

        function bpFieldInitChecklist(element) {
            let hidden_input = element.find('input[type=hidden]');
            let selected_options = JSON.parse(hidden_input.val() || '[]');
            let checkboxes = element.find('input[type=checkbox].pmsn');
            let title_checkboxes = element.find('input[type=checkbox].pmsn-title');
            initCheckboxes(checkboxes, hidden_input, selected_options);
            checkboxes.click(function () {
                changeCheckBoxes(checkboxes, hidden_input);
            });
            title_checkboxes.click(function () {
                let child_class_name = $(this).val();
                $('input[data-title=' + child_class_name +'].pmsn').prop('checked', $(this).is(':checked'));
                changeCheckBoxes(checkboxes, hidden_input);
            });
        }
    </script>
@endpushonce
