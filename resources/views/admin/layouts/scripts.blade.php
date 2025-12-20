<script>
    //tinymce
    tinymce.init({
        selector: '.tinymce',
        license_key: 'gpl',
        plugins: [
            "advlist", "anchor", "autolink", "charmap", "code", "fullscreen",
            "help", "image", "insertdatetime", "link", "lists", "media",
            "preview", "searchreplace", "table", "visualblocks",
        ],
        toolbar: "undo redo | styles | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
    });
    //sweetalert init
    $(function() {
        $('.delete-item').on('click', function(e) {
            e.preventDefault();
            const url = $(this).attr('href');

            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        method: "POST",
                        url: url,
                        data: {
                            _token: "{{ csrf_token() }}",
                            _method: "DELETE"
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.status === 'success') {
                                window.location.reload();
                            }
                        },
                        error: function(xhr, status, error) {
                            console.log('Error:', xhr.responseText);
                        }
                    });
                }
            });
        });
    });


    //notyfy init
    var notyf = new Notyf({
        duration:2000
    });
</script>
