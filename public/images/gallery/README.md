Place actual society images (group photos, event pictures) in this directory.
Supported formats: JPG, PNG, WebP

To use in the gallery section, update the img src attributes in:
  resources/views/pages/landing.blade.php

Replace the placeholder picsum.photos URLs with:
  {{ asset('images/gallery/your-image.jpg') }}

For the gallery section placeholders, replace the div.gallery-placeholder with:
  <img src="{{ asset('images/gallery/your-image.jpg') }}" alt="..." loading="lazy">
  <div class="gallery-overlay"><span>Caption text</span></div>
