<script>
  	function confirmDelete(){
    var form = $(this).parents('form');
    swal({
        title: "Are you sure?",
        text: "Deleted files cannot be recovered!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, delete it!",
        closeOnConfirm: false
        }, function(isConfirm){
            if (isConfirm) form.submit();
        });
    }
    $('button#delete').on('click', confirmDelete);
</script>