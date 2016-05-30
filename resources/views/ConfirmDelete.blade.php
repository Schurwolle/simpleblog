<script>
  	function confirmDelete(){
    var btnDelete = $(this);
    var form = btnDelete.parents('form');
    swal({
        title: "Are you sure?",
        text: "Deleted files cannot be recovered!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, delete it!",
        closeOnConfirm: false
        }, function(isConfirm){
            if (isConfirm) 
            {
                form.submit();
            } else {
                setTimeout(function() {
                    btnDelete.blur();
                }, 0);
            }
        });
    }
    $('button#delete').on('click', confirmDelete);
</script>