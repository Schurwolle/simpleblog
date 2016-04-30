<script type="text/javascript">
    $('#searchButton').on('click', function(e) {
        e.preventDefault();
        $(this).blur();
        if($('#search').val().trim() == "")
        {
            swal({ title: "Error!", text: "Please enter something first.", timer: 1100, showConfirmButton: false, type:"error" });
            $('#search').focus();
        } else {
            $(this).closest('form').submit();
        }
    });
</script>