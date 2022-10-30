## Laravel Model File Uploader package
Easy way to upload laravel model related file from the requset.


# Install (Via Composer)
```bash
composer require plusemon/uploader
```

# Usages


```php
 $product->uploadRequestFile('image')->resize($width, $height, $callback)->saveInto('thumbnail');
```

# Usages Menuals
use HasUploader trait on the model

```php

<?php

namespace App\Models;

...
use Plusemon\Uploader\HasUploader;

class User extends Authenticatable
{
    use ... HasUploader;
    ...
}
```

Upload files from the request input.

if you need update the model file again
```php
 $user->uploadRequestFile('user_image')->saveInto('profile_picture');

 // You have uploaded you file into:  /public/uploads/users/images/users-1-image.jpg
```

it will
  - delete the old file from the storage
  - upload the new file into the storage
  magically :)


Generate file url:
```html
 <img src="{{ $user->urlOf('image')">
```

Delete a model / file:
```php
// delete only file
$user->deleteFile('image');

// or delete model and file
 $user->deleteWithFile('image');
````
it will delete the model with the model related file also :).


Multiple file upload:
```php
 // multiple files
  $user->uploadRequestFiles('user_image')->getUploadedFiles();
  // it will return you an array of uploaded file path.
```


Tip: if the file not exist on this dir or missing somehow then the noimage will show there.
```html
 <img src="{{ $user->urlOf('image') }}">
```

Awesome right?

If you like my works please star my repo.
Thanks.
