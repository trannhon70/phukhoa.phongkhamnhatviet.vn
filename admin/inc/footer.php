

</div>
</div>
<script src="../js/toastr.min.js"></script>
<script>
CKEDITOR.replace('content', {
    filebrowserUploadMethod: 'form',
    filebrowserUploadUrl: '<?php echo $local ?>/admin/ckeditor/ck_upload.php',
    filebrowserWindowWidth: '640',
    filebrowserWindowHeight: '480',
    filebrowserBrowseUrl: '<?php echo $local ?>/admin/ckfinder/ckfinder.html',
    filebrowserImageBrowseUrl: '<?php echo $local ?>/admin/ckfinder/ckfinder.html?type=Images',
    filebrowserUploadUrl: '<?php echo $local ?>/admin/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
    filebrowserImageUploadUrl: '<?php echo $local ?>/admin/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images'

});

 </script>
<!-- Datepicker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script>
    $(function() {
        $('.datepicker').datepicker({
            language: "es",
            autoclose: true,
            format: "dd/mm/yyyy"
        });
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="js/script.js"></script>
</body>

</html>