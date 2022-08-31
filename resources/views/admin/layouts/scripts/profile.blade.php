<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.2/min/dropzone.min.js"
  integrity="sha512-VQQXLthlZQO00P+uEu4mJ4G4OAgqTtKG1hri56kQY1DtdLeIqhKUp9W/lllDDu3uN3SnUNawpW7lBda8+dSi7w=="
  crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.2/dropzone.min.css"
  integrity="sha512-jU/7UFiaW5UBGODEopEqnbIAHOI8fO6T99m7Tsmqs2gkdujByJfkCbbfPSN4Wlqlb9TGnsuC0YgUgWkRBK7B9A=="
  crossorigin="anonymous" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"
  integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script>
  new Dropzone("#dropzone-avatar", { 
    paramName: "file",
    acceptedFiles: ".jpeg,.jpg,.png,.gif,.JPEG,.JPG,.PNG,.GIF",
    maxFiles: 1,      
    thumbnailWidth: 200,
    thumbnailHeight: 200,
    init: function() {		
      var myDropzone = this;

      this.on("maxfilesexceeded", function(file) {
        this.removeAllFiles();
        this.addFile(file);
      });

      if($('#avatar_id').val().length) {
        fetch('/admin/fetch/file?mode=get-avatar', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.head.querySelector('meta[name=csrf-token]').content
          },
        })
        .then((response) => {
          if (!response.ok) {
            return false;
          }
          return response.json();
        })
        .then((response) => {
          response = response.data;
          if (!response) {
            return false;
          }
          var mockFile = { name: response.file_name, size: response['size'] }; 
          myDropzone.emit("addedfile", mockFile);
          myDropzone.emit("thumbnail", mockFile, response['url']);
          myDropzone.emit("complete", mockFile);
          myDropzone.files = [mockFile];
        });
      }
      
      this.on("sending", function(file, response) {
        $('#btn-submit').attr('disabled','disabled');
        if (this.files.length > 1) {
          this.removeFile(this.files[0]);
        }
      });							
      
      this.on("success", function(file, response) {
        $('#btn-submit').removeAttr('disabled');
        $('#avatar_id').val(response.id);
      });		

      this.on('error', function(file, response) {
        $('#btn-submit').removeAttr('disabled');
      });																																																																																									
    },
    url: "/admin/fetch/files?mode=upload-avatar",
    headers: {
      'x-csrf-token': document.head.querySelector('meta[name=csrf-token]').content,
    },
  });
</script>