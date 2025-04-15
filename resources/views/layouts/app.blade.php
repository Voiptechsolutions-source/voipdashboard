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

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">


    <!-- jQuery & DataTables -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>


</head>
<body>

    @include('layouts.header') <!-- Include Header -->
    @include('layouts.sidebar') <!-- Include Sidebar -->

    <main id="main" class="main">
        @yield('content') <!-- Dynamic Page Content -->
    </main>

    @include('layouts.footer') <!-- Include footer -->

    <!-- NiceAdmin JS -->
    <script src="{{ asset('niceadmin/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('niceadmin/js/main.js') }}"></script>
    <script>
        var leadsIndexUrl = "{{ route('leads.index') }}"; // Pass the correct route
    </script>
    <script src="{{ asset('niceadmin/js/customer.js') }}"></script>  


    @yield('scripts') <!-- ✅ This allows scripts to be added from child views-->
   <script src="https://cdn.ckeditor.com/ckeditor5/41.0.0/classic/ckeditor.js"></script>
   <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    let editor = null;

    document.addEventListener('DOMContentLoaded', function () {
        
        const textarea = document.querySelector('#body');
        const hiddenBody = document.getElementById('hiddenBody');
        if (!textarea || !hiddenBody) {
            console.error('Textarea or hiddenBody not found in the DOM');
            return;
        }

        ClassicEditor
            .create(textarea, {
                toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'insertTable', 'undo', 'redo'],
            })
            .then(e => {
                console.log('CKEditor loaded successfully:', e);
                editor = e;
                editor.setData(hiddenBody.value); // Initialize with hiddenBody value
                console.log('Initial editor data:', editor.getData());
                console.log('Initial hiddenBody value:', hiddenBody.value);

                // Sync content to hidden input on change
                editor.model.document.on('change:data', () => {
                    
                    syncEditorContent();
                });

                // Initial sync
                syncEditorContent();
            })
            .catch(error => {
                console.error('Error loading CKEditor:', error);
            });

        document.getElementById('insertPlaceholder').addEventListener('click', function() {
            const select = document.getElementById('placeholderSelect');
            const placeholder = select.value;
            if (placeholder && editor) {
                editor.model.change(writer => {
                    const insertPosition = editor.model.document.selection.getFirstPosition();
                    writer.insertText(placeholder, insertPosition);
                });
            } else {
                console.warn('No editor or placeholder selected');
            }
        });
    });

    function syncEditorContent() {
        if (editor) {
            const editorContent = editor.getData();
            const hiddenBody = document.getElementById('hiddenBody');
            hiddenBody.value = editorContent;
            console.log('Synced editor content to hiddenBody:', editorContent);
            console.log('Current hiddenBody value:', hiddenBody.value);
        }
    }

    function validateForm() {
        if (editor) {
            syncEditorContent(); // Force sync before validation
            const hiddenBody = document.getElementById('hiddenBody');
            const bodyValue = hiddenBody.value.trim();
            console.log('Validating with hiddenBody value:', bodyValue);
            if (!bodyValue) {
                alert('Body is required.');
                return false;
            }
        } else {
            console.error('Editor not initialized');
            return false;
        }
        return true;
    }
</script>

</body>
</html>
