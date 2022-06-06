## Laravel Model File Uploader package
Easy way to upload laravel model related file from the requset.


# Install (Via Composer)
```bash
composer require plusemon/uploader
```

# Usages
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

```php
// single file
 $user->uploadRequestFile('user_avater')->saveInto('profile_picture');
```

You have uploaded you file into: 
```
/public/uploads/users/images/users-1-avater.jpg
```

if you need update the model file again
```php
 $user->uploadRequestFile('user_avater')->saveInto('profile_picture');
```

it will
  - delete the old file from the storage
  - upload the new file into the storage
  magically :)

Generate file url:
```html
 <img src="{{ $user->urlOf('avater')">
```

Delete a file:
```php
 $user->deleteWith('avater');
````
it will delete the model with the model related file also :).


Multiple file upload:
```php
 // multiple files
  $user->uploadRequestFiles('user_avater')->getUploadedFiles();
  // it will return you an array of uploaded file path.
```


Tip: if the file not exist on this dir or missing somehow then the noimage will show there.
```html
 <img src="{{ $user->urlOf('avater') ?? asset('assets/images/no-image-placeholder.png') }}">
```

Awesome right?

If you like my works please star my repo.
Thanks.
