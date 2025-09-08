@php $meta_data = (isset($meta_data)) ? $meta_data : [] @endphp
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- Meta Tags -->
<x-site.meta-tags :tags="$meta_data"/>
<!-- Favicon -->
<x-site.favicon/>
<!-- CSS Variable Overwrites Start-->
<style>
    :root {
        --blue: {{ template_option('primary_color', null, '#007bff') }};
        --red: {{ template_option('danger_color', null, '#dc3545') }};
        --yellow: {{ template_option('warning_color', null, '#ffc107') }};
        --green: {{ template_option('success_color', null, '#28a745') }};
        --cyan: {{ template_option('info_color', null, '#17a2b8') }};
        --primary: {{ template_option('primary_color', null, '#007bff') }};
        --secondary: {{ template_option('secondary_color', null, '#6c757d') }};
        --success: {{ template_option('success_color', null, '#28a745') }};
        --info: {{ template_option('info_color', null, '#17a2b8') }};
        --warning: {{ template_option('warning_color', null, '#ffc107') }};
        --danger: {{ template_option('danger_color', null, '#dc3545') }};
        --light: {{ template_option('light_color', null, '#f8f9fa') }};
        --dark: {{ template_option('dark_color', null, '#343a40') }};
    }
</style>
<!-- CSS Variable Overwrites End-->
@stack('plugin-style')
@stack('template-style')
@stack('custom-style')
@stack('internal-style')
@stack('head-script')
<!--Google Analytics -->
<x-google-analytic/>
<!--Custom Head Scripts -->
<x-site.script-manager position="header"/>
