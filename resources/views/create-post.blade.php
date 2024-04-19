<x-layout> 
	<style>
		
	</style>
	<div class="container py-md-5 container--narrow">
	<div class="mb-3">
			<a href="javascript:history.back()" class="btn btn-dark btn-sm">
				<i class="fas fa-arrow-left"></i> Back
			</a>
		</div>
	<form action="/create-post" method="POST" enctype="multipart/form-data">
		@csrf
		<div class="form-group">
			<label class="text-muted mb-1"><small>Category</small></label><br>
			<div class="category-container">
				<span class="category" data-category="cars">Cars</span>
				<span class="category" data-category="movies">Movies</span>
				<span class="category" data-category="music">Music</span>
				<span class="category" data-category="hike">Hike</span>
				<span class="category" data-category="restaurant">Cafe</span>
				<span class="category" data-category="restaurant">Country</span>
				<span class="category" data-category="restaurant">Accessories</span>
				<span class="category" data-category="restaurant">Clothing</span>
				<span class="category" data-category="restaurant">Dance</span>
				<span class="category" data-category="restaurant">Food</span>
				<span class="category" data-category="restaurant">Footwear</span>
				<span class="category" data-category="restaurant">Headwear</span>
				<span class="category" data-category="restaurant">Electronics</span>

				<!-- Add more spans for other categories -->
			</div>
			<input type="hidden" name="selected_category" id="selected-category">
    	</div>

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
		<br>
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

	document.addEventListener('DOMContentLoaded', function() {
        const categories = document.querySelectorAll('.category');
        const selectedCategoryInput = document.getElementById('selected-category');

        categories.forEach(category => {
            category.addEventListener('click', function() {
                if (this.classList.contains('selected')) {
                    this.classList.remove('selected');
                    selectedCategoryInput.value = '';
                } else {
                    categories.forEach(c => c.classList.remove('selected'));
                    this.classList.add('selected');
                    selectedCategoryInput.value = this.dataset.category;
                }
            });
        });
    });
	</script>
</x-layout>
