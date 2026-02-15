@php $meta_data = (isset($meta_data)) ? $meta_data : [] @endphp
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<x-site.meta-tags :tags="$meta_data"/>
<x-site.favicon/>
@stack('plugin-style')
@stack('template-style')
@stack('custom-style')
@stack('internal-style')
@stack('head-script')
<x-google-analytic/>
<x-site.script-manager position="header"/>
{{--
<style>
    :root {
        --blue: {{ theme_option('primary_color', null, '#007bff') }};
        --red: {{ theme_option('danger_color', null, '#dc3545') }};
        --yellow: {{ theme_option('warning_color', null, '#ffc107') }};
        --green: {{ theme_option('success_color', null, '#28a745') }};
        --cyan: {{ theme_option('info_color', null, '#17a2b8') }};
        --primary: {{ theme_option('primary_color', null, '#007bff') }};
        --secondary: {{ theme_option('secondary_color', null, '#6c757d') }};
        --success: {{ theme_option('success_color', null, '#28a745') }};
        --info: {{ theme_option('info_color', null, '#17a2b8') }};
        --warning: {{ theme_option('warning_color', null, '#ffc107') }};
        --danger: {{ theme_option('danger_color', null, '#dc3545') }};
        --light: {{ theme_option('light_color', null, '#f8f9fa') }};
        --dark: {{ theme_option('dark_color', null, '#343a40') }};
    }
</style>--}}
