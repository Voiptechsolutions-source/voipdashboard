<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <!-- NiceAdmin CSS -->
    <link rel="stylesheet" href="{{ asset('niceadmin/vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('niceadmin/vendor/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('niceadmin/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('niceadmin/vendor/bootstrap-icons/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">

    <!-- jQuery & DataTables -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
</head>
<body>

    @include('layouts.header')
    @include('layouts.sidebar')

    <main id="main" class="main">
        @yield('content')
    </main>

    @include('layouts.footer')

    <!-- TinyMCE (Moved before main.js) -->
    <script src="https://cdn.tiny.cloud/1/eecv9gurjuhytimxqezzv5tmpqayd32dlllxat3pl0g023mi/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>

    <!-- NiceAdmin JS -->
    <script src="{{ asset('niceadmin/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('niceadmin/js/main.js') }}"></script>
    <script src="{{ asset('niceadmin/js/customer.js') }}"></script>

    

    <script>
        var leadsIndexUrl = "{{ route('leads.index') }}";
    </script>

    <!-- <script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof tinymce !== 'undefined') {
            tinymce.init({
                selector: '#body',
                plugins: 'advlist autolink lists link image charmap print preview anchor code',
                toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | outdent indent | link image | code',
                height: 400,
                content_style: 'body { font-family: Arial, sans-serif; }',
                valid_elements: '*[*]', // Allow all elements and attributes
                extended_valid_elements: '*[*]', // Accept custom styles and attributes
                setup: function (editor) {
                    editor.on('init', function () {
                        var hiddenBody = document.getElementById('hiddenBody');
                        if (hiddenBody) {
                            var initialContent = hiddenBody.value.trim();
                            console.log('HiddenBody value before init:', initialContent);
                            if (initialContent) {
                                editor.setContent(initialContent); // Set content directly
                                console.log('Editor initialized with:', editor.getContent());
                            } else {
                                console.warn('No content in hiddenBody');
                                editor.setContent('<p>No content available.</p>');
                            }
                        } else {
                            console.error('hiddenBody element not found');
                            editor.setContent('<p>hiddenBody not found.</p>');
                        }
                    });
                    editor.on('change', function () {
                        var content = editor.getContent({ format: 'raw' });
                        document.getElementById('hiddenBody').value = content;
                        console.log('Editor content updated:', content);
                    });
                }
            });

            document.getElementById('insertPlaceholder').addEventListener('click', function () {
                var select = document.getElementById('placeholderSelect');
                var placeholder = select.value;
                if (placeholder && tinymce.activeEditor) {
                    tinymce.activeEditor.execCommand('mceInsertContent', false, placeholder);
                    console.log('Inserted placeholder:', placeholder);
                }
            });
        } else {
            console.error('TinyMCE is not loaded. Check the CDN or API key and ensure the script tag is correct.');
        }
    });
</script> -->
    @yield('scripts')
</body>
</html>