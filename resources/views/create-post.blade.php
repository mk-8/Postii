<x-layout>
    <div class="container py-md-5 container--narrow">
      <form action="/create-post" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
          <label for="post-title" class="text-muted mb-1"><small>Title</small></label>
          <input value="{{ old('title') }}" required name="title" id="post-title" class="form-control form-control-lg form-control-title" type="text" placeholder="" autocomplete="off" />
            @error('title')   
                <p class="m-0 small alert alert-danger shadow-sm">{{ $message }}</p>            
            @enderror
        </div>

        <div class="form-group">
          <label for="post-body" class="text-muted mb-1"><small>Body Content</small></label>
          <textarea value="{{ old('body') }}" required name="body" id="post-body" class="body-content tall-textarea form-control" type="text"></textarea>
            @error('body')   
                <p class="m-0 small alert alert-danger shadow-sm">{{ $message }}</p>            
            @enderror
        </div>

        <div class="form-group">
          <label for="post-image" class="text-muted mb-1"><small>Upload an Image</small></label>
          <div class="custom-file">
              <input type="file" class="custom-file-input" id="post-image" name="post-image" onchange="displayImage(this)">
              <label class="custom-file-label" for="post-image">Choose file</label>
          </div>
        </div>
        <div id="image-preview"></div>

        <button class="btn btn-primary">Save New Post</button>
      </form>
    </div>

    <script>
      function displayImage(input) {
          var file = input.files[0];
          var reader = new FileReader();

          reader.onload = function(e) {
              var imagePreview = document.getElementById('image-preview');
              imagePreview.innerHTML = '<div class="position-relative"><img src="' + e.target.result + '" class="img-fluid">' + 
                                       '<button type="button" class="btn-close position-absolute top-0 end-0" style="color: red; border: none;" aria-label="Close" onclick="removeImage()"><i class="fas fa-times"></i></button></div>';
          };

          reader.readAsDataURL(file);
      }

      function removeImage() {
          var imagePreview = document.getElementById('image-preview');
          imagePreview.innerHTML = '';
          document.getElementById('post-image').value = '';
      }
    </script>
</x-layout>
